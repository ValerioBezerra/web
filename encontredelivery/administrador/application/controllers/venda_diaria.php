<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Venda_Diaria extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_id_emp') == 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Pedido_Model', 'PedidoModel');
	}
	
	public function index($data_inicial = NULL, $data_final = NULL) {
//		date_default_timezone_set("Brazil/East");
		date_default_timezone_set("America/Araguaina");

		$dados               = array();
		$dados['ACAO_FORM']  = site_url('venda_diaria/pesquisar');		
		
		if (($data_inicial == NULL) || ($data_final == NULL)) { 
			$data_inicial = date('Y-m-d', strtotime("-7 day"));
			$data_final   = date('Y-m-d');
		} 
		
		$dados['DATA_INICIAL'] = date('d/m/Y', strtotime($data_inicial));
		$dados['DATA_FINAL']   = date('d/m/Y', strtotime($data_final));
		
		$this->carregarDados($data_inicial, $data_final, $dados);
		
		$this->parser->parse('venda_diaria_view', $dados);
	}
	
	public function pesquisar() {
		$data_inicial = $this->input->post('data_inicial');
		$data_final   = $this->input->post('data_final');
		
		$data_inicial_formatada = explode("/", $data_inicial)[2]."-".explode("/", $data_inicial)[1]."-".explode("/", $data_inicial)[0];
		$data_final_formatada   = explode("/", $data_final)[2]."-".explode("/", $data_final)[1]."-".explode("/", $data_final)[0];
		
		
		redirect('venda_diaria/index/'.$data_inicial_formatada.'/'.$data_final_formatada);
	}
	
	private function carregarDados($data_incial, $data_final, &$dados) {
		$dados['BLC_DADOS'] = array();
		
		$resultado = $this->db->query(" SELECT DATE(dlv_datahora_ped) as dlv_datahora_ped, ".
					   				  "        SUM(dlv_quantidade_ppe) as dlv_quantidade_ppe, ".
				                      "        SUM(dlv_preco_ppe) as dlv_preco_ppe, ".
				                      "        SUM(dlv_valor_ppa) as dlv_valor_ppa ".
				                      " FROM dlv_ped ".
									  " LEFT OUTER JOIN dlv_ppe on dlv_dlvped_ppe = dlv_id_ped ".
									  " LEFT OUTER JOIN dlv_ppa on dlv_dlvppe_ppa = dlv_id_ppe ".
									  " WHERE dlv_dlvemp_ped = ".$this->session->userdata("dlv_id_emp").
				                      "   AND dlv_recebido_ped = 1 ".
									  "   AND dlv_cancelado_ped = 0 ".
				                      "   AND DATE(dlv_datahora_ped) BETWEEN '".$data_incial."' AND '".$data_final."' ".
									  " GROUP BY DATE(dlv_datahora_ped), dlv_id_ped, dlv_id_ppe ".
									  " ORDER BY dlv_datahora_ped DESC ")->result();
		
		if (!empty($resultado)) {
			$data               = '';
			$quantidade 		= 1;
			$valor      		= 0;
			$primeiro   		= true;
				
			foreach ($resultado as $registro) {
				if ($data != $registro->dlv_datahora_ped) {
					if (!$primeiro) {
						$dados['BLC_DADOS'][] = array(
								"DATA"            => date('d/m/Y', strtotime($data)),
								"VALOR_PEDIDOS"   => 'R$ '.number_format($valor, 2, ",", "."),
								"IMPRIMIR_DIARIA" => site_url('venda_diaria/imprimir/'.$data)
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
				
			$dados['BLC_DADOS'][] = array(
					"DATA"            => date('d/m/Y', strtotime($data)),
					"VALOR_PEDIDOS"   => 'R$ '.number_format($valor, 2, ",", "."),
					"IMPRIMIR_DIARIA" => site_url('venda_diaria/imprimir/'.$data)
			);
		}
	}
	
	public function imprimir($data) {
		$resultado = $this->db->query(" SELECT dlv_id_ped, ".
									  "        dlv_nome_cli ".
									  " FROM dlv_ped ".
									  " LEFT OUTER JOIN dlv_cli on dlv_id_cli = dlv_dlvcli_ped ".
									  " WHERE dlv_dlvemp_ped = ".$this->session->userdata("dlv_id_emp").
									  "   AND dlv_recebido_ped = 1 ".
									  "   AND dlv_cancelado_ped = 0 ".
									  "   AND DATE(dlv_datahora_ped) = '".$data."'".
									  " ORDER BY dlv_id_ped ")->result();
		
		$altura = 48 + (3.2 * count($resultado));
		if ($altura < 85) {
			$altura = 85;
		}
				
		$params = array('orientation' => 'P', 'unit' => 'mm', 'size' => array(85, $altura));
		$this->load->library('pdf', $params);
		$this->pdf->fontpath = 'font/';
		$this->pdf->AddPage();
		$this->pdf->SetFont('courier','',8);
		$this->pdf->Text(0, 3, $this->gerarCaracteresDireita(40, 'Encontre Delivery', ''));
		$this->pdf->Text(0, 6, $this->gerarCaracteresDireita(40, '', ''));
		$this->pdf->Text(0, 9, $this->gerarCaracteresDireita(40, 'Empresa: '.$this->session->userdata("dlv_nome_emp"), ''));
		$this->pdf->Text(0, 12, $this->gerarCaracteresDireita(40, 'Data: '.date('d/m/Y', strtotime($data)), ' '));
		$this->pdf->Text(0, 15, $this->gerarCaracteresDireita(40, '', ''));
		$this->pdf->Text(0, 18, $this->gerarCaracteresDireita(40, '', '-'));
		$this->pdf->Text(23, 21, $this->gerarCaracteresDireita(15, 'Venda Diária', ''));
		$this->pdf->Text(0, 24, $this->gerarCaracteresDireita(40, '', '-'));
		$this->pdf->Text(0, 27, $this->gerarCaracteresEsquerda(8, 'Pedido', ' ').' '.
				                $this->gerarCaracteresDireita(20, 'Cliente', ' ').' '.
				                $this->gerarCaracteresEsquerda(10, 'Valor', ' '));
		$this->pdf->Text(0, 30, $this->gerarCaracteresDireita(40, '', '-'));
		
		$posicaoY  = 30; 
		$total_produtos     = 0;
		$total_taxa_entrega = 0;
		$total_desconto     = 0;
		$total_dia          = 0;
		
		if (!empty($resultado)) {
			foreach ($resultado as $registro) {
				$this->db->query("CALL sp_dlv_ped_calcular_totais(".$registro->dlv_id_ped.",  @taxa_entrega, @desconto, @quantidade_produtos, @valor_produtos, @valor_total)")->result();
				
				$total_produtos     += $this->db->query("SELECT @valor_produtos as valor_produtos")->row()->valor_produtos;
				$total_taxa_entrega += $this->db->query("SELECT @taxa_entrega as taxa_entrega")->row()->taxa_entrega;
				$total_desconto     += $this->db->query("SELECT @desconto as desconto")->row()->desconto;
				$valor_pedido        = $this->db->query("SELECT @valor_total as valor_total")->row()->valor_total;
				$posicaoY           += 3;
				$total_dia          += $valor_pedido;
				$this->pdf->Text(0, $posicaoY, $this->gerarCaracteresEsquerda(8, $registro->dlv_id_ped, ' ').' '.
											   $this->gerarCaracteresDireita(20, $registro->dlv_nome_cli, ' ').' '.
											   $this->gerarCaracteresEsquerda(10, number_format($valor_pedido,  2, ',', '.'), ' '));
			}
		}
		
		$this->pdf->Text(0, $posicaoY + 3, $this->gerarCaracteresDireita(40, '', '-'));
		$this->pdf->Text(0, $posicaoY + 6, $this->gerarCaracteresDireita(25, '   Produtos:', ' ').
				                           $this->gerarCaracteresEsquerda(15, number_format($total_produtos,  2, ',', '.'), ' '));
		$this->pdf->Text(0, $posicaoY + 9, $this->gerarCaracteresDireita(25, '(+)Taxa de entrega:', ' ').
				                           $this->gerarCaracteresEsquerda(15, number_format($total_taxa_entrega,  2, ',', '.'), ' '));
		$this->pdf->Text(0, $posicaoY + 12, $this->gerarCaracteresDireita(25, '(-)Desconto:', ' ').
				                           $this->gerarCaracteresEsquerda(15, number_format($total_desconto,  2, ',', '.'), ' '));
		$this->pdf->Text(0, $posicaoY + 15, $this->gerarCaracteresDireita(40, '', '-'));
		$this->pdf->Text(0, $posicaoY + 18, $this->gerarCaracteresDireita(25, '   Total:', ' ').
				                           $this->gerarCaracteresEsquerda(15, number_format($total_dia,  2, ',', '.'), ' '));
		
		
		$this->pdf->Output();
		
	}

	private function gerarCaracteresDireita($quantidade, $texto, $char) {
		$characteres = array(
				'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
				'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
				'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
				'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
				'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
				'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
				'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f'
		);
		
		$texto   = strtr($texto, $characteres);
		$texto   = substr($texto, 0, $quantidade - 1);
		$texto   = strtoupper($texto);
		$retorno = $texto;
		for ($i = 1; $i <= ($quantidade - strlen($texto)); $i++) {
			$retorno .= $char;
		}
		
		return $retorno;
	}
	
	private function gerarCaracteresEsquerda($quantidade, $texto, $char) {
		$characteres = array(
				'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
				'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
				'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
				'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
				'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
				'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
				'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f'
		);
		
		$texto   = strtr($texto, $characteres);
		$texto   = substr($texto, 0, $quantidade - 1);
		$texto   = strtoupper($texto);
		$retorno = '';
		for ($i = 1; $i <= ($quantidade - strlen($texto)); $i++) {
			$retorno .= $char;
		}
	
		return $retorno .= $texto;
	}
}