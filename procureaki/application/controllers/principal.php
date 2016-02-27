<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Principal extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if (!$this->session->userdata('logado')) {redirect('login/');}
	
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
	
		$this->load->model('Empresa_Model', 'EmpresaModel');
//		$this->load->model('Telefone_Model', 'TelefoneModel');
	}

    /**
     *
     */
    public function index() {
		$dados = array();
		
//		$dados['DIV_ABERTO']  = 'transp';
//		$dados['DIV_FECHADO'] = 'transp';
//		$dados['DIV_PEDIDOS'] = 'transp';
		
//		if ($this->session->userdata('dlv_alttaxa_per') == 1) {
//			$resultado = $this->EmpresaModel->getEmpresa($this->session->userdata('dlv_id_emp'));
//
//			if ($resultado) {
//				if ($resultado->dlv_aberto_emp == 1) {
//					$dados['DIV_ABERTO']  = '';
//				} else {
//					$dados['DIV_FECHADO'] = '';
//				}
//			}
//
//		}
		
//		if ($this->session->userdata('dlv_altstatusped_per') == 1) {
//			$dados['DIV_PEDIDOS'] = '';
//		}
		
//		$this->carregarGraficoProdutosVendidos($dados);
//		$this->carregarGraficoVendasSemanais($dados);
	
		$this->parser->parse('principal_view', $dados);
	}
	
//	public function fechar() {
//		$empresa = array(
//			"dlv_aberto_emp"      => 0,
//			"dlv_dlvusumod_emp"   => $this->session->userdata('dlv_id_usu'),
//			"dlv_datahoramod_emp" => date('Y-m-d H:i:s')
//		);
//
//		$this->EmpresaModel->update($empresa, $this->session->userdata('dlv_id_emp'));
//
//		redirect('');
//	}
//
//	public function abrir() {
//		if ($this->testarAbrir()) {
//			$empresa = array(
//				"dlv_aberto_emp"      => 1,
//				"dlv_dlvusumod_emp"   => $this->session->userdata('dlv_id_usu'),
//				"dlv_datahoramod_emp" => date('Y-m-d H:i:s')
//			);
//
//			$this->EmpresaModel->update($empresa, $this->session->userdata('dlv_id_emp'));
//		}
//
//		redirect('');
//	}
	
//	private function testarAbrir() {
//		$erros    = FALSE;
//		$mensagem = null;
//
//		$resultado = $this->TelefoneModel->getTelefonesEmpresa($this->session->userdata('dlv_id_emp'));
//
//		if (empty($resultado)) {
//			$erros    = TRUE;
//			$mensagem .= "- Informe pelo menos um fone.\n";
//		}
//
//		if ($erros) {
//			$this->session->set_flashdata('titulo_erro', 'Para abrir corrija os seguintes erros:');
//			$this->session->set_flashdata('erro', nl2br($mensagem));
//		}
//
//		return !$erros;
//	}
	
	private function  carregarGraficoProdutosVendidos(&$dados) {
		$dados['GRAFICO_PRODUTOS_VENDIDOS'] = array();
		
		$resultado = $this->db->query(' SELECT dlv_id_pro, '.
       								  ' 		  MAX(dlv_descricao_pro) as dlv_descricao_pro, '.
       								  ' 		  SUM(dlv_quantidade_ppe) as dlv_quantidade_ppe '.
									  ' FROM dlv_ped '.
									  ' LEFT OUTER JOIN dlv_ppe on dlv_dlvped_ppe = dlv_id_ped '.
									  ' LEFT OUTER JOIN dlv_pro on dlv_id_pro = dlv_dlvpro_ppe '.
									  ' WHERE dlv_dlvemp_ped = '.$this->session->userdata('dlv_id_emp').
				                      '   AND dlv_recebido_ped = 1 '.
									  '   AND dlv_cancelado_ped = 0 '.
									  '   AND DATE(dlv_datahora_ped) BETWEEN DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH) AND CURRENT_DATE '.
									  ' GROUP BY dlv_id_pro '.
									  ' ORDER BY dlv_quantidade_ppe DESC ')->result();
		
		foreach ($resultado as $registro) {
			$dados['GRAFICO_PRODUTOS_VENDIDOS'][] = array(
				"PRODUTO"    => $this->retirar_caracteres($registro->dlv_descricao_pro),
				"QUANTIDADE" => $registro->dlv_quantidade_ppe
			);
		}
	}
	
	private function  carregarGraficoVendasSemanais(&$dados) {
		$dados['GRAFICO_VENDAS_SEMANAIS'] = array();
	
		$resultado = $this->db->query(' SELECT DATE(dlv_datahora_ped) as dlv_datahora_ped, '.
									  '        SUM(dlv_quantidade_ppe) as dlv_quantidade_ppe, '.
									  '        SUM(dlv_preco_ppe) as dlv_preco_ppe, '.
									  '        SUM(dlv_valor_ppa) as dlv_valor_ppa '.
									  ' FROM dlv_ped '.
									  ' LEFT OUTER JOIN dlv_ppe on dlv_dlvped_ppe = dlv_id_ped '.
									  ' LEFT OUTER JOIN dlv_ppa on dlv_dlvppe_ppa = dlv_id_ppe '.
									  ' WHERE dlv_dlvemp_ped = '.$this->session->userdata('dlv_id_emp').
				                      '   AND dlv_recebido_ped = 1 '.
									  '   AND dlv_cancelado_ped = 0 '.
									  '   AND DATE(dlv_datahora_ped) BETWEEN DATE_SUB(CURRENT_DATE, INTERVAL 1 WEEK) AND CURRENT_DATE '.
									  ' GROUP BY dlv_datahora_ped, dlv_id_ped, dlv_id_ppe '.
									  ' ORDER BY dlv_datahora_ped ')->result();
		
		if (!empty($resultado)) {
			$data       = '';
			$quantidade = 0;
			$valor      = 0;
			$primeiro   = true;
			
			foreach ($resultado as $registro) {
				if ($data != $registro->dlv_datahora_ped) {
					if (!$primeiro) {
						$dados['GRAFICO_VENDAS_SEMANAIS'][] = array(
								"DATA"       => date('d/m', strtotime($data)),
								"QUANTIDADE" => $quantidade,
								"VALOR"      => number_format($valor, 2, ".", "")
						);
					}
					
					$primeiro   = FALSE;
					$data       = $registro->dlv_datahora_ped;
					$quantidade = $registro->dlv_quantidade_ppe;
					
					if (is_null($registro->dlv_valor_ppa)) {
						$valor = ($registro->dlv_quantidade_ppe * $registro->dlv_preco_ppe);
					} else {
						$valor = ($registro->dlv_quantidade_ppe * ($registro->dlv_preco_ppe + $registro->dlv_valor_ppa));
					}
				} else {
					$quantidade += $registro->dlv_quantidade_ppe;
					
					if (is_null($registro->dlv_valor_ppa)) {
						$valor += ($registro->dlv_quantidade_ppe * $registro->dlv_preco_ppe);
					} else {
						$valor += ($registro->dlv_quantidade_ppe * ($registro->dlv_preco_ppe + $registro->dlv_valor_ppa));
					}						
				}
			}
			
			$dados['GRAFICO_VENDAS_SEMANAIS'][] = array(
					"DATA"       => date('d/m', strtotime($data)),
					"QUANTIDADE" => $quantidade,
					"VALOR"      => number_format($valor, 2, ".", "")
			);			
		}
	}
	
	private function retirar_caracteres($texto) {
		$characteres = array(
				'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
				'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
				'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
				'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
				'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
				'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
				'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', "'" => '' 
		);
	
		return strtr($texto, $characteres);
	}
}
