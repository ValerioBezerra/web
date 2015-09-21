<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rede_Social extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Rede_Social_Model', 'RedeSocialModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']        = site_url('rede_social');
		$dados['NOVA_REDE_SOCIAL'] = site_url('rede_social/novo');
		$dados['BLC_DADOS']        = array();
		 
		$this->carregarDados($dados);
		
		$this->parser->parse('rede_social_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['dlv_id_red']        = 0;		
		$dados['dlv_descricao_red'] = '';
		$dados['dlv_classe_red']    = '';
		
		$dados['ACAO'] = 'Nova';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('rede_social_cadastro', $dados);
	}
	
	public function editar($dlv_id_red) {
		$dlv_id_red = base64_decode($dlv_id_red);
		$dados = array();
		
		$this->carregarRedeSocial($dlv_id_red, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('rede_social_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_red;
		global $dlv_descricao_red;
		global $dlv_classe_red;
		
		$dlv_id_red        = $this->input->post('dlv_id_red');			
		$dlv_descricao_red = $this->input->post('dlv_descricao_red');
		$dlv_classe_red    = $this->input->post('dlv_classe_red');
		
		
		if ($this->testarDados()) {
			$rede_social = array(
				"dlv_descricao_red" => $dlv_descricao_red,
				"dlv_classe_red"	=> $dlv_classe_red
			);
			
			if (!$dlv_id_red) {
				$dlv_id_red = $this->RedeSocialModel->insert($rede_social);
			} else {
				$dlv_id_red = $this->RedeSocialModel->update($rede_social, $dlv_id_red);
			}

			if (is_numeric($dlv_id_red)) {
				$this->session->set_flashdata('sucesso', 'Rede social salva com sucesso.');
				redirect('rede_social');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_red);	
				redirect('rede_social');
			}
		} else {
			if (!$dlv_id_red) {
				redirect('rede_social/novo/');
			} else {
				redirect('rede_social/editar/'.base64_encode($dlv_id_red));
			}			
		}
	}
	
	public function apagar($dlv_id_red) {
		$res = $this->RedeSocialModel->delete(base64_decode($dlv_id_red));

		if ($res) {
			$this->session->set_flashdata('sucesso', 'Rede social apagada com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível apagar rede social.');				
		}
		
		redirect('rede_social');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_REDE_SOCIAL'] = site_url('rede_social');
		$dados['ACAO_FORM']            = site_url('rede_social/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->RedeSocialModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_ID_RED"           => $registro->dlv_id_red,
				"DLV_DESCRICAO_RED"    => $registro->dlv_descricao_red,
				"DLV_CLASSE_RED"       => $registro->dlv_classe_red,
				"EDITAR_REDE_SOCIAL"   => site_url('rede_social/editar/'.base64_encode($registro->dlv_id_red)),
				"APAGAR_REDE_SOCIAL"   => "abrirConfirmacao('".base64_encode($registro->dlv_id_red)."')"
			);
		}
	}
	
	private function carregarRedeSocial($dlv_id_red, &$dados) {
		$resultado = $this->RedeSocialModel->getRedeSocial($dlv_id_red);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
					
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $dlv_descricao_red;
		global $dlv_classe_red;
		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($dlv_descricao_red)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_DLV_DESCRICAO_RED', 'has-error');				
		}
		
		if (empty($dlv_classe_red)) {
			$erros    = TRUE;
			$mensagem .= "- Classe não preenchida.\n";
			$this->session->set_flashdata('ERRO_DLV_CLASSE_RED', 'has-error');
		}		
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_RED', TRUE);				
			$this->session->set_flashdata('dlv_descricao_red', $dlv_descricao_red);				
			$this->session->set_flashdata('dlv_classe_red', $dlv_classe_red);				
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_RED           = $this->session->flashdata('ERRO_DLV_RED');
		$ERRO_DLV_DESCRICAO_RED = $this->session->flashdata('ERRO_DLV_DESCRICAO_RED');
		$ERRO_DLV_CLASSE_RED    = $this->session->flashdata('ERRO_DLV_CLASSE_RED');
		
		$dlv_descricao_red      = $this->session->flashdata('dlv_descricao_red');
		$dlv_classe_red         = $this->session->flashdata('dlv_classe_red');
		
		if ($ERRO_DLV_RED) {
			$dados['dlv_descricao_red'] = $dlv_descricao_red;
			$dados['dlv_classe_red']    = $dlv_classe_red;
				
			$dados['ERRO_DLV_DESCRICAO_RED'] = $ERRO_DLV_DESCRICAO_RED;
			$dados['ERRO_DLV_CLASSE_RED']    = $ERRO_DLV_CLASSE_RED;
		}

		

		
	}
}