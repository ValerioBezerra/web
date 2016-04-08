<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipo extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('bus_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Tipo_Model', 'TipoModel');
		$this->load->model('Segmento_Model', 'SegmentoModel');
//		$this->load->model('Empresa_Tipo_Model', 'EmpresaTipoModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']     = site_url('tipo');
		$dados['NOVO_TIPO'] = site_url('tipo/novo');
		$dados['BLC_DADOS']     = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('tipo_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['bus_id_tip']        = 0;
		$dados['bus_descricao_tip'] = '';
		$dados['bus_busseg_tip'] = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);

		$this->carregarSegmentos($dados);
		
		$this->parser->parse('tipo_cadastro', $dados);
	}
	
	public function editar($bus_id_tip) {
		$bus_id_tip = base64_decode($bus_id_tip);
		$dados = array();
		
		$this->carregarTipo($bus_id_tip, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);

		$this->carregarSegmentos($dados);
		
		$this->parser->parse('tipo_cadastro', $dados);
	}
	
	public function salvar() {
		global $bus_id_tip;
		global $bus_descricao_tip;
		global $bus_busseg_tip;
		
		$bus_id_tip        = $this->input->post('bus_id_tip');
		$bus_descricao_tip = $this->input->post('bus_descricao_tip');
		$bus_busseg_tip    = $this->input->post('bus_busseg_tip');
		
		
		if ($this->testarDados()) {
			$tipo = array(
				"bus_descricao_tip" => $bus_descricao_tip,
				"bus_busseg_tip"    => $bus_busseg_tip
			);
			
			if (!$bus_id_tip) {
				$bus_id_tip = $this->TipoModel->insert($tipo);
			} else {
				$bus_id_tip = $this->TipoModel->update($tipo, $bus_id_tip);
			}

			if (is_numeric($bus_id_tip)) {
				$this->session->set_flashdata('sucesso', 'tipbustível salvo tip sucesso.');
				redirect('tipo');
			} else {
				$this->session->set_flashdata('erro', $bus_id_tip);
				redirect('tipo');
			}
		} else {
			if (!$bus_id_tip) {
				redirect('tipo/novo/');
			} else {
				redirect('tipo/editar/'.base64_encode($bus_id_tip));
			}			
		}
	}
	
	public function apagar($bus_id_tip) {
		if ($this->testarApagar(base64_decode($bus_id_tip))) {
			$res = $this->TipoModel->delete(base64_decode($bus_id_tip));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Tipo apagado com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar tipo.');
			}
		}
		
		redirect('tipo');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_TIPO'] = site_url('tipo');
		$dados['ACAO_FORM']         = site_url('tipo/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->TipoModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"BUS_ID_TIP"        => $registro->bus_id_tip,
				"BUS_DESCRICAO_TIP" => $registro->bus_descricao_tip,
				"EDITAR_TIPO"   => site_url('tipo/editar/'.base64_encode($registro->bus_id_tip)),
				"APAGAR_TIPO"   => "abrirConfirmacao('".base64_encode($registro->bus_id_tip)."')"
			);
		}
	}
	
	private function carregarTipo($bus_id_tip, &$dados) {
		$resultado = $this->TipoModel->getTipo($bus_id_tip);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
					
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}

	private function carregarSegmentos(&$dados) {
		$resultado = $this->SegmentoModel->get();

		$dados['BLC_SEGMENTO'] = array();

		foreach ($resultado as $registro) {
			$dados['BLC_SEGMENTO'][] = array(
				"BUS_ID_SEG"          => $registro->bus_id_seg,
				"BUS_DESCRICAO_SEG"   => $registro->bus_descricao_seg,
				"SEL_BUS_ID_SEG"      => ($dados['bus_busseg_tip'] == $registro->bus_id_seg)?'selected':''
			);
		}
	}

	private function testarDados() {
		global $bus_descricao_tip;
		global $bus_busseg_tip;
		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($bus_descricao_tip)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_BUS_DESCRICAO_TIP', 'has-error');
		}

		if (empty($bus_busseg_tip)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione um  segmento.\n";
			$this->session->set_flashdata('ERRO_BUS_BUSSEG_TIP', 'has-error');
		} else {
			$resultado = $this->SegmentoModel->getSegmento($bus_busseg_tip);
			if (!$resultado) {
				$erros = TRUE;
				$mensagem .= "- Segmento não cadastrado.\n";
				$this->session->set_flashdata('ERRO_BUS_BUSSEG_TIP', 'has-error');
			}
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os tipuintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_BUS_TIP', TRUE);
			$this->session->set_flashdata('bus_descricao_tip', $bus_descricao_tip);
			$this->session->set_flashdata('bus_busseg_tip', $bus_busseg_tip);
		}
		
		return !$erros;
	}
	
	private function testarApagar($bus_id_tip) {
		$erros    = FALSE;
		$mensagem = null;
	
//		$resultado = $this->EmpresaTipoModel->getEmpresas($dlv_id_tip);
//
//		if ($resultado) {
//			$erros    = TRUE;
//			$mensagem .= "- Um ou mais empresas tip este tipo.\n";
//		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o tipo:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_BUS_TIP           = $this->session->flashdata('ERRO_BUS_TIP');
		$ERRO_BUS_DESCRICAO_TIP = $this->session->flashdata('ERRO_BUS_DESCRICAO_TIP');
		$ERRO_BUS_BUSSEG_TIP = $this->session->flashdata('ERRO_BUS_BUSSSEG_TIP');
		$bus_descricao_tip      = $this->session->flashdata('bus_descricao_tip');
		$bus_busseg_tip         = $this->session->flashdata('bus_busseg_tip');
		
		if ($ERRO_BUS_TIP) {
			$dados['bus_descricao_tip']      = $bus_descricao_tip;
			$dados['bus_busseg_tip']      = $bus_busseg_tip;
			
			$dados['ERRO_BUS_DESCRICAO_TIP'] = $ERRO_BUS_DESCRICAO_TIP;
			$dados['ERRO_BUSSEG_TIP'] = $ERRO_BUS_BUSSEG_TIP;
		}
	}
}