<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipo extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('vei_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Tipo_Model', 'TipoModel');
//		$this->load->model('Empresa_Segmento_Model', 'EmpresaSegmentoModel');
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
		
		$dados['vei_id_tip']        = 0;
		$dados['vei_descricao_tip'] = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('tipo_cadastro', $dados);
	}
	
	public function editar($vei_id_tip) {
		$vei_id_tip = base64_decode($vei_id_tip);
		$dados = array();
		
		$this->carregarTipo($vei_id_tip, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('tipo_cadastro', $dados);
	}
	
	public function salvar() {
		global $vei_id_tip;
		global $vei_descricao_tip;
		
		$vei_id_tip        = $this->input->post('vei_id_tip');
		$vei_descricao_tip = $this->input->post('vei_descricao_tip');
		
		
		if ($this->testarDados()) {
			$tipo = array(
				"vei_descricao_tip" => $vei_descricao_tip
			);
			
			if (!$vei_id_tip) {
				$vei_id_tip = $this->TipoModel->insert($tipo);
			} else {
				$vei_id_tip = $this->TipoModel->update($tipo, $vei_id_tip);
			}

			if (is_numeric($vei_id_tip)) {
				$this->session->set_flashdata('sucesso', 'Tipo salvo com sucesso.');
				redirect('tipo');
			} else {
				$this->session->set_flashdata('erro', $vei_id_tip);
				redirect('tipo');
			}
		} else {
			if (!$vei_id_tip) {
				redirect('tipo/novo/');
			} else {
				redirect('tipo/editar/'.base64_encode($vei_id_tip));
			}			
		}
	}
	
	public function apagar($vei_id_tip) {
		if ($this->testarApagar(base64_decode($vei_id_tip))) {
			$res = $this->TipoModel->delete(base64_decode($vei_id_tip));
	
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
				"VEI_ID_TIP"        => $registro->vei_id_tip,
				"VEI_DESCRICAO_TIP" => $registro->vei_descricao_tip,
				"EDITAR_TIPO"   => site_url('tipo/editar/'.base64_encode($registro->vei_id_tip)),
				"APAGAR_TIPO"   => "abrirConfirmacao('".base64_encode($registro->vei_id_tip)."')"
			);
		}
	}
	
	private function carregarTipo($vei_id_tip, &$dados) {
		$resultado = $this->TipoModel->getTipo($vei_id_tip);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
					
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $vei_descricao_tip;
		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($vei_descricao_tip)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_VEI_DESCRICAO_TIP', 'has-error');
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_VEI_TIP', TRUE);
			$this->session->set_flashdata('vei_descricao_tip', $vei_descricao_tip);
		}
		
		return !$erros;
	}
	
	private function testarApagar($vei_id_tip) {
		$erros    = FALSE;
		$mensagem = null;
	
//		$resultado = $this->EmpresaSegmentoModel->getEmpresas($dlv_id_seg);
//
//		if ($resultado) {
//			$erros    = TRUE;
//			$mensagem .= "- Um ou mais empresas tip este segmento.\n";
//		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o segmento:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_VEI_TIP           = $this->session->flashdata('ERRO_VEI_TIP');
		$ERRO_VEI_DESCRICAO_TIP = $this->session->flashdata('ERRO_VEI_DESCRICAO_TIP');
		$vei_descricao_tip      = $this->session->flashdata('vei_descricao_tip');
		
		if ($ERRO_VEI_TIP) {
			$dados['vei_descricao_tip']      = $vei_descricao_tip;
			
			$dados['ERRO_VEI_DESCRICAO_TIP'] = $ERRO_VEI_DESCRICAO_TIP;
		}
	}
}