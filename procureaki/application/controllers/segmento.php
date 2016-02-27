<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Segmento extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('bus_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Segmento_Model', 'SegmentoModel');
//		$this->load->model('Empresa_Segmento_Model', 'EmpresaSegmentoModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']     = site_url('segmento');
		$dados['NOVO_SEGMENTO'] = site_url('segmento/novo');
		$dados['BLC_DADOS']     = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('segmento_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['bus_id_seg']        = 0;
		$dados['bus_descricao_seg'] = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('segmento_cadastro', $dados);
	}
	
	public function editar($bus_id_seg) {
		$bus_id_seg = base64_decode($bus_id_seg);
		$dados = array();
		
		$this->carregarSegmento($bus_id_seg, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('segmento_cadastro', $dados);
	}
	
	public function salvar() {
		global $bus_id_seg;
		global $bus_descricao_seg;
		
		$bus_id_seg        = $this->input->post('bus_id_seg');
		$bus_descricao_seg = $this->input->post('bus_descricao_seg');
		
		
		if ($this->testarDados()) {
			$segmento = array(
				"bus_descricao_seg" => $bus_descricao_seg
			);
			
			if (!$bus_id_seg) {
				$bus_id_seg = $this->SegmentoModel->insert($segmento);
			} else {
				$bus_id_seg = $this->SegmentoModel->update($segmento, $bus_id_seg);
			}

			if (is_numeric($bus_id_seg)) {
				$this->session->set_flashdata('sucesso', 'segbustível salvo seg sucesso.');
				redirect('segmento');
			} else {
				$this->session->set_flashdata('erro', $bus_id_seg);
				redirect('segmento');
			}
		} else {
			if (!$bus_id_seg) {
				redirect('segmento/novo/');
			} else {
				redirect('segmento/editar/'.base64_encode($bus_id_seg));
			}			
		}
	}
	
	public function apagar($bus_id_seg) {
		if ($this->testarApagar(base64_decode($bus_id_seg))) {
			$res = $this->SegmentoModel->delete(base64_decode($bus_id_seg));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Segmento apagado com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar segmento.');
			}
		}
		
		redirect('segmento');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_SEGMENTO'] = site_url('segmento');
		$dados['ACAO_FORM']         = site_url('segmento/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->SegmentoModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"BUS_ID_SEG"        => $registro->bus_id_seg,
				"BUS_DESCRICAO_SEG" => $registro->bus_descricao_seg,
				"EDITAR_SEGMENTO"   => site_url('segmento/editar/'.base64_encode($registro->bus_id_seg)),
				"APAGAR_SEGMENTO"   => "abrirConfirmacao('".base64_encode($registro->bus_id_seg)."')"
			);
		}
	}
	
	private function carregarSegmento($bus_id_seg, &$dados) {
		$resultado = $this->SegmentoModel->getSegmento($bus_id_seg);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
					
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $bus_descricao_seg;
		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($bus_descricao_seg)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_BUS_DESCRICAO_SEG', 'has-error');
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_BUS_SEG', TRUE);
			$this->session->set_flashdata('bus_descricao_seg', $bus_descricao_seg);
		}
		
		return !$erros;
	}
	
	private function testarApagar($bus_id_seg) {
		$erros    = FALSE;
		$mensagem = null;
	
//		$resultado = $this->EmpresaSegmentoModel->getEmpresas($dlv_id_seg);
//
//		if ($resultado) {
//			$erros    = TRUE;
//			$mensagem .= "- Um ou mais empresas seg este segmento.\n";
//		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o segmento:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_BUS_SEG           = $this->session->flashdata('ERRO_BUS_SEG');
		$ERRO_BUS_DESCRICAO_SEG = $this->session->flashdata('ERRO_BUS_DESCRICAO_SEG');
		$bus_descricao_seg      = $this->session->flashdata('bus_descricao_seg');
		
		if ($ERRO_BUS_SEG) {
			$dados['bus_descricao_seg']      = $bus_descricao_seg;
			
			$dados['ERRO_BUS_DESCRICAO_SEG'] = $ERRO_BUS_DESCRICAO_SEG;
		}
	}
}