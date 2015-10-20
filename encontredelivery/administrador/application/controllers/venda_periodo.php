<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Venda_Periodo extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_id_emp') == 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Pedido_Model', 'PedidoModel');

	}
	
	public function index() {
//		date_default_timezone_set("Brazil/East");
		date_default_timezone_set("America/Araguaina");

		$dados               = array();
		$dados['ACAO_FORM']  = site_url('venda_periodo/imprimir');		
		
		$dados['DATA_INICIAL'] = date('d/m/Y', strtotime("-1 month"));
		$dados['DATA_FINAL']   = date('d/m/Y');
		
		$this->parser->parse('venda_periodo_view', $dados);
	}
	
	public function imprimir() {
		$data_inicial = $this->input->post('data_inicial');
		$data_final   = $this->input->post('data_final');
		
		$data_inicial_formatada = explode("/", $data_inicial)[2]."-".explode("/", $data_inicial)[1]."-".explode("/", $data_inicial)[0];
		$data_final_formatada   = explode("/", $data_final)[2]."-".explode("/", $data_final)[1]."-".explode("/", $data_final)[0];
		
		$resultado = $this->db->query(" SELECT DATE(dlv_datahora_ped) as dlv_datahora_ped ".
				" FROM dlv_ped ".
				" WHERE dlv_dlvemp_ped = ".$this->session->userdata("dlv_id_emp").
				"   AND dlv_recebido_ped = 1 ".
				"   AND dlv_cancelado_ped = 0 ".
				"   AND DATE(dlv_datahora_ped) BETWEEN '".$data_inicial_formatada."' AND '".$data_final_formatada."' ".
				" GROUP BY DATE(dlv_datahora_ped) ".
				" ORDER BY dlv_datahora_ped ")->result();
		
		if (empty($resultado)) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br("- Nenhum pedido realizado no período.\n"));
			redirect('venda_periodo');
		} else {
			$altura = 48 + (3.2 * count($resultado));
			if ($altura < 85) {
				$altura = 85;
			}
			
			$params = array('orientation' => 'P', 'unit' => 'mm', 'size' => array(85, $altura));
			$this->load->library('pdf', $params);
			$this->pdf->fontpath = 'font/';
			$this->pdf->AddPage();
			$this->pdf->SetFont('courier','',8);
			$this->pdf->Text(0, 3, $this->gerarCaracteresDireita(40, 'Encontre Delivery', ' '));
			$this->pdf->Text(0, 6, $this->gerarCaracteresDireita(40, '', ' '));
			$this->pdf->Text(0, 9, $this->gerarCaracteresDireita(40, 'Empresa: '.$this->session->userdata("dlv_nome_emp"), ' '));
			$this->pdf->Text(0, 12, $this->gerarCaracteresDireita(40, 'Período: '.$data_inicial.' a '.$data_final, ' '));
			$this->pdf->Text(0, 15, $this->gerarCaracteresDireita(40, '', ' '));
			$this->pdf->Text(0, 18, $this->gerarCaracteresDireita(40, '', '-'));
			$this->pdf->Text(0, 21, $this->gerarCaracteresDireita(14, '', ' ').$this->gerarCaracteresDireita(15, 'Venda Mensal', ' '));
			$this->pdf->Text(0, 24, $this->gerarCaracteresDireita(40, '', '-'));
			$this->pdf->Text(0, 27, $this->gerarCaracteresDireita(19, 'Data', ' ').' '.
									$this->gerarCaracteresEsquerda(20, 'Total', ' '));
			$this->pdf->Text(0, 30, $this->gerarCaracteresDireita(40, '', '-'));
			
			$posicaoY  = 30;
			$total_produtos     = 0;
			$total_taxa_entrega = 0;
			$total_desconto     = 0;
			$total_dia          = 0;
			
			foreach ($resultado as $registro) {
				$this->db->query("CALL sp_dlv_ped_calcular_totais_data(".$this->session->userdata("dlv_id_emp").",'".$registro->dlv_datahora_ped."',  @taxa_entrega, @desconto, @quantidade_produtos, @valor_produtos, @valor_total)")->result();
		
				$total_produtos     += $this->db->query("SELECT @valor_produtos as valor_produtos")->row()->valor_produtos;
				$total_taxa_entrega += $this->db->query("SELECT @taxa_entrega as taxa_entrega")->row()->taxa_entrega;
				$total_desconto     += $this->db->query("SELECT @desconto as desconto")->row()->desconto;
				$valor_pedido        = $this->db->query("SELECT @valor_total as valor_total")->row()->valor_total;
				$posicaoY           += 3;
				$total_dia          += $valor_pedido;
				$this->pdf->Text(0, $posicaoY, $this->gerarCaracteresDireita(19, date('d/m/Y', strtotime($registro->dlv_datahora_ped)), ' ').' '.
					     					   $this->gerarCaracteresEsquerda(20, number_format($valor_pedido,  2, ',', '.'), ' '));
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