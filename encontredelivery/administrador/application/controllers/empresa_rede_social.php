<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa_Rede_Social extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_altfpg_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Rede_Social_Model', 'RedeSocialModel');
		$this->load->model('Empresa_Rede_Social_Model', 'EmpresaRedeSocialModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']               = site_url('empresa_rede_social');
		$dados['BLC_DADOS']               = array();
		$dados['ACAO_FORM']               = site_url('empresa_rede_social/adicionar');
		$dados['URL_REDE_SOCIAL_EMPRESA'] = site_url('json/json/retornar_rede_social_empresa/');
		
		$dados['dlv_id_exr']     = '';
		$dados['dlv_dlvred_exr'] = '';
		$dados['dlv_link_red'] = '';
		
		$this->carregarRedeSocialEmpresa($dados);
		$this->carregarDadosFlash($dados);
		$this->carregarRedeSocial($dados);
		
		$this->parser->parse('empresa_rede_social_cadastro_consulta', $dados);
	}
	
	public function adicionar() {
		global $dlv_id_exr;
		global $dlv_dlvred_exr;
		global $dlv_link_red;
		
		$dlv_id_exr     = $this->input->post('dlv_id_exr');			
		$dlv_dlvred_exr = $this->input->post('dlv_dlvred_exr');		
		$dlv_link_red   = $this->input->post('dlv_link_red');
		
		if ($this->testarDados()) {
			$rede_social = array(
				"dlv_dlvemp_exr"      => $this->session->userdata('dlv_id_emp'),
				"dlv_dlvred_exr"      => $dlv_dlvred_exr,
				"dlv_link_red"        => $dlv_link_red,
				"dlv_dlvusumod_exr"   => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_exr" => date('Y-m-d H:i:s')							
			);
			
			
			if (!$dlv_id_exr) {	
				$dlv_id_exr = $this->EmpresaRedeSocialModel->insert($rede_social);
			} else {
				$dlv_id_exr = $this->EmpresaRedeSocialModel->update($rede_social, $dlv_id_exr);
			}

			if (is_numeric($dlv_id_exr)) {
				$this->session->set_flashdata('sucesso', 'Rede Social adicionada com sucesso.');
				redirect('empresa_rede_social');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_exr);	
				redirect('empresa_rede_social');
			}
		} else {
			redirect('empresa_rede_social');			
		}
	}
	
	public function remover($dlv_id_exr) {
		$res = $this->EmpresaRedeSocialModel->delete(base64_decode($dlv_id_exr));

		if ($res) {
			$this->session->set_flashdata('sucesso', 'Rede social removida com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível remover rede social.');				
		}
		
		redirect('empresa_rede_social');
	}
	
	private function carregarRedeSocialEmpresa(&$dados) {
		$resultado = $this->EmpresaRedeSocialModel->getRedeSocialEmpresa($this->session->userdata('dlv_id_emp'));
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_DESCRICAO_RED"              => $registro->dlv_descricao_red,
				"DLV_CLASSE_RED"                 => $registro->dlv_classe_red,
				"DLV_LINK_RED"                   => $registro->dlv_link_red,
				"APAGAR_REDE_SOCIAL_EMPRESA" => "abrirConfirmacao('".base64_encode($registro->dlv_id_exr)."')"
			);
		}
	}
	
	private function carregarRedeSocial(&$dados) {
		$resultado = $this->RedeSocialModel->get();
		
		$dados['BLC_REDE_SOCIAL'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_REDE_SOCIAL'][]  = array(
					"DLV_ID_RED"         => $registro->dlv_id_red,
					"DLV_DESCRICAO_RED"  => $registro->dlv_descricao_red,
					"DLV_CLASSE_RED"     => $registro->dlv_classe_red,
					"SEL_DLV_ID_RED"     => ($dados['dlv_dlvred_exr'] == $registro->dlv_id_red)?'selected':''
			);
		}
	}
	
	private function testarDados() {
		global $dlv_id_exr;
		global $dlv_dlvred_exr;
		global $dlv_link_red;
						
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($dlv_link_red)) {
			$erros    = TRUE;
			$mensagem .= "- Informe o link da rede social.\n";
			$this->session->set_flashdata('ERRO_DLV_LINK_RED', 'has-error');
		}
		if (empty($dlv_dlvred_exr)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione uma rede social.\n";
			$this->session->set_flashdata('ERRO_DLV_DLVRED_EXR', 'has-error');
		} else {
			$resultado = $this->RedeSocialModel->getRedeSocial($dlv_dlvred_exr);
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Rede social não cadastrada.\n";
				$this->session->set_flashdata('ERRO_DLV_DLVRED_EXR', 'has-error');
// 			} else {
// 				$resultado = $this->EmpresaRedeSocialModel->getEmpresaRedeSocial($dlv_dlvred_exr, $this->session->userdata('dlv_id_emp'));
		
// 				if ($resultado) {
// 					$erros    = TRUE;
// 					$mensagem .= "- Rede Social já adicionada.\n";
// 					$this->session->set_flashdata('ERRO_DLV_DLVRED_EXR', 'has-error');
// 				}
			}
		}
		
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_EXR', TRUE);				
			$this->session->set_flashdata('dlv_dlvred_exr', $dlv_dlvred_exr);				
			$this->session->set_flashdata('dlv_link_red', $dlv_link_red);
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_EXR         = $this->session->flashdata('ERRO_DLV_EXR');
		$ERRO_DLV_DLVRED_EXR  = $this->session->flashdata('ERRO_DLV_DLVRED_EXR');
		$ERRO_DLV_LINK_RED    = $this->session->flashdata('ERRO_DLV_LINK_RED');
		$dlv_dlvred_exr       = $this->session->flashdata('dlv_dlvred_exr');
		$dlv_link_red         = $this->session->flashdata('dlv_link_red');
	
		if ($ERRO_DLV_EXR) {
			$dados['dlv_dlvred_exr']      = $dlv_dlvred_exr;
			$dados['dlv_link_red']        = $dlv_link_red;
			$dados['ERRO_DLV_DLVRED_EXR'] = $ERRO_DLV_DLVRED_EXR;
			$dados['ERRO_DLV_LINK_RED']    = $ERRO_DLV_LINK_RED;
		}
	}
}