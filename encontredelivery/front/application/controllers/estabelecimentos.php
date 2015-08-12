<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Estabelecimentos extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		if (!$this->session->userdata('endereco_escolhido')) { redirect(''); }
	
		$this->layout = LAYOUT_PADRAO;
		$this->load->model('Endereco_Model', 'EnderecoModel');	
		$this->load->model('Segmento_Model', 'SegmentoModel');
		$this->load->model('Forma_Pagamento_Model', 'FormaPagamentoModel');
		$this->load->model('Empresa_Model', 'EmpresaModel');
		$this->load->model('Empresa_Segmento_Model', 'EmpresaSegmentoModel');		
		$this->load->model('Taxa_Bairro_Model', 'TaxaBairroModel');		
	}	
	
	public function index() {
		$dados = array();
		
		$this->carregarEndereco($dados);
		$this->carregarSegmentos($dados);
		$this->carregarFormasPagamento($dados);
		$this->carregarEmpresasAbertas($dados);
		$this->carregarEmpresasFechadas($dados);
		
		$this->parser->parse('estabelecimentos_view', $dados);
	}
	
	private function carregarEndereco(&$dados) {
		$resultado = $this->EnderecoModel->getEnderecoCompletoId($this->session->userdata('dlv_gloend_ecl'));	

		if ($resultado) {
			$dados['ENDERECO'] = "(".mascara($resultado->glo_cep_end, MASCARA_CEP).") ".$resultado->glo_logradouro_end;
			if (!empty($this->session->userdata('dlv_numero_ecl'))) {
				$dados['ENDERECO'] = $dados['ENDERECO'].', '.$this->session->userdata('dlv_numero_ecl');
			}
			
			$dados['BAIRRO_CIDADE'] = $resultado->glo_nome_bai.". ";
			$dados['BAIRRO_CIDADE'] = $dados['BAIRRO_CIDADE'].$resultado->glo_nome_cid."-".$resultado->glo_uf_est;
		}
	}
	
	private function carregarSegmentos(&$dados) {
		$dados['BLC_SEGMENTOS'] = array();
		$resultado              = $this->SegmentoModel->get();
	
		foreach ($resultado as $registro) {
			$dados['BLC_SEGMENTOS'][] = array(
				"DLV_ID_SEG"         => $registro->dlv_id_seg,
				"DLV_DESCRICAO_SEG"  => $registro->dlv_descricao_seg
			);
		}
	}
	
	private function carregarFormasPagamento(&$dados) {
		$dados['BLC_FORMAS_PAGAMENTO'] = array();
		$resultado                     = $this->FormaPagamentoModel->get();
	
		foreach ($resultado as $registro) {
			$dados['BLC_FORMAS_PAGAMENTO'][] = array(
				"DLV_ID_FPG"         => $registro->dlv_id_fpg,
				"DLV_DESCRICAO_FPG"  => $registro->dlv_descricao_fpg
			);
		}
	}
	
	private function carregarEmpresasAbertas(&$dados) {
		$dados['QUANTIDADE_ABERTAS']   = 0;
		$dados['BLC_EMPRESAS_ABERTAS'] = array();
		$endereco                      = $this->EnderecoModel->getEnderecoCompletoId($this->session->userdata('dlv_gloend_ecl'));
		
		if ($endereco) {
			$resultado = $this->EmpresaModel->getEmpresaAbertas('', $endereco->glo_id_cid, $endereco->glo_id_bai, '');
		}
		
		$dados['QUANTIDADE_ABERTAS'] = count($resultado);
		foreach ($resultado as $registro) {		
			$lista_segmentos = $this->EmpresaSegmentoModel->get($registro->dlv_id_emp);
			$segmentos       = "";
			$separador       = "";
			foreach ($lista_segmentos as $segmento) {
				$segmentos = $segmentos.$separador.$segmento->dlv_descricao_seg;
				$separador = " /";
			}

			$taxa_entrega   = $registro->dlv_taxaentrega_emp;
			$resultado_taxa = $this->TaxaBairroModel->getEmpresaTaxaBairro($endereco->glo_id_bai, $registro->dlv_id_emp);
			if (!empty($resultado_taxa)) {
				$taxa_entrega = $resultado_taxa->dlv_taxaentrega_txb;
			}			
			
			$hora        = date("H", strtotime($registro->dlv_tempomedio_emp));
			$minuto      = date("i", strtotime($registro->dlv_tempomedio_emp));
			$tempo_medio = "";
			
			if ($hora != 0) {
				$tempo_medio = $tempo_medio.$hora.'h';
			}
			
			if ($minuto != 0) {
				$tempo_medio = $tempo_medio.$minuto.'min.';
			}
				
			$dados['BLC_EMPRESAS_ABERTAS'][] = array(
				"IMAGEM"        => base_url('administrador/assets/images/empresas/'.$registro->dlv_id_emp.".png"),
				"NOME"          => $registro->dlv_nome_emp,
				"SEGMENTOS"     => $segmentos,
				"VALOR_ENTREGA" => number_format($taxa_entrega,  2, ',', '.'),
				"TEMPO_ENTREGA" => $tempo_medio,	
				"URL"           => site_url('cardapio/'.$registro->dlv_id_emp),
			);
		}
	}
	
	private function carregarEmpresasFechadas(&$dados) {
		$dados['QUANTIDADE_FECHADAS']   = 0;
		$dados['BLC_EMPRESAS_FECHADAS'] = array();
		$endereco                      = $this->EnderecoModel->getEnderecoCompletoId($this->session->userdata('dlv_gloend_ecl'));
	
		if ($endereco) {
			$resultado = $this->EmpresaModel->getEmpresaFechadas('', $endereco->glo_id_cid, $endereco->glo_id_bai, '');
		}
	
		$dados['QUANTIDADE_FECHADAS'] = count($resultado);
		foreach ($resultado as $registro) {
			$lista_segmentos = $this->EmpresaSegmentoModel->get($registro->dlv_id_emp);
			$segmentos       = "";
			$separador       = "";
			foreach ($lista_segmentos as $segmento) {
				$segmentos = $segmentos.$separador.$segmento->dlv_descricao_seg;
				$separador = " /";
			}
	
			$taxa_entrega   = $registro->dlv_taxaentrega_emp;
			$resultado_taxa = $this->TaxaBairroModel->getEmpresaTaxaBairro($endereco->glo_id_bai, $registro->dlv_id_emp);
			if (!empty($resultado_taxa)) {
				$taxa_entrega = $resultado_taxa->dlv_taxaentrega_txb;
			}
				
			$hora        = date("H", strtotime($registro->dlv_tempomedio_emp));
			$minuto      = date("i", strtotime($registro->dlv_tempomedio_emp));
			$tempo_medio = "";
				
			if ($hora != 0) {
				$tempo_medio = $tempo_medio.$hora.'h';
			}
				
			if ($minuto != 0) {
				$tempo_medio = $tempo_medio.$minuto.'min.';
			}
	
			$dados['BLC_EMPRESAS_FECHADAS'][] = array(
					"IMAGEM"        => base_url('administrador/assets/images/empresas/'.$registro->dlv_id_emp.".png"),
					"NOME"          => $registro->dlv_nome_emp,
					"SEGMENTOS"     => $segmentos,
					"VALOR_ENTREGA" => number_format($taxa_entrega,  2, ',', '.'),
					"TEMPO_ENTREGA" => $tempo_medio,
					"URL"           => site_url('cardapio/'.$registro->dlv_id_emp),
			);
		}
	}
	
	
}
