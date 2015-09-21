<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuracoes extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_id_emp') == 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Usuario_Model', 'UsuarioModel');		
	}
	
	public function index() {
		$dados = array();
		
		$dlv_nome_usu  = $this->session->userdata('dlv_nome_usu');
		
		$dados['dlv_nome_usu']            = $this->session->userdata('dlv_nome_usu');
		$dados['dlv_senha_usu_atual']     = '';
		$dados['dlv_senha_usu_nova']      = '';
		$dados['dlv_senha_usu_confirmar'] = '';
		
		$dados['ACAO_SENHA'] = site_url('configuracoes/modificar');
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('configuracoes_view', $dados);
	}
	
	public function modificar() {
		global $dlv_id_usu;
		global $dlv_senha_usu;
		
		$dlv_id_usu    = $this->session->userdata('dlv_id_usu');
		$dlv_senha_usu = $this->session->userdata('dlv_senha_usu');
		
		global $dlv_senha_usu_atual;
		global $dlv_senha_usu_nova;
		global $dlv_senha_usu_confirmar;
		
		$dlv_senha_usu_atual     = $this->input->post('dlv_senha_usu_atual');
		$dlv_senha_usu_nova      = $this->input->post('dlv_senha_usu_nova');
		$dlv_senha_usu_confirmar = $this->input->post('dlv_senha_usu_confirmar');
		
		$dlv_senha_usu_atual     = $this->input->post('dlv_senha_usu_atual');
		$dlv_senha_usu_nova      = $this->input->post('dlv_senha_usu_nova');
		$dlv_senha_usu_confirmar = $this->input->post('dlv_senha_usu_confirmar');
		
		if ($this->testarDados()) {
			$usuario = array(
				"dlv_senha_usu" => md5($dlv_senha_usu_nova),
				"dlv_dlvusumod_usu"    => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_usu"  => date('Y-m-d H:i:s')						
			);	

			$dlv_id_usu = $this->UsuarioModel->update($usuario, $dlv_id_usu);
			
			if (is_numeric($dlv_id_usu)) {
				$this->session->set_userdata('dlv_senha_usu', md5($dlv_senha_usu_nova));
				$this->session->set_flashdata('sucesso', 'Senha modificada com sucesso.');
			}
					
		}
		
		redirect('configuracoes/');
	}
	
	private function testarDados() {
		global $dlv_senha_usu;
		
		global $dlv_senha_usu_atual;
		global $dlv_senha_usu_nova;
		global $dlv_senha_usu_confirmar;
		
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($dlv_senha_usu_atual)) {
			$erros    = TRUE;
			$mensagem .= "- Senha atual não informada.\n";
			
			$this->session->set_flashdata('ERRO_DLV_SENHA_USU_ATUAL', 'has-error');
		} else {
			if (md5($dlv_senha_usu_atual) != $dlv_senha_usu) {
				$erros    = TRUE;
				$mensagem .= "- Senha atual incorreta.\n";
					
				$this->session->set_flashdata('ERRO_DLV_SENHA_USU_ATUAL', 'has-error');
			}
		}
		
		if (empty($dlv_senha_usu_nova)) {
			$erros    = TRUE;
			$mensagem .= "- Nova senha não informada.\n";
				
			$this->session->set_flashdata('ERRO_DLV_SENHA_USU_NOVA', 'has-error');
		}
		
		if (empty($dlv_senha_usu_confirmar)) {
			$erros    = TRUE;
			$mensagem .= "- Senha de confirmação não informada.\n";
		
			$this->session->set_flashdata('ERRO_DLV_SENHA_USU_CONFIRMAR', 'has-error');
		}
		
		if (!empty($dlv_senha_usu_nova) and !empty($dlv_senha_usu_confirmar)) {
			if ($dlv_senha_usu_nova != $dlv_senha_usu_confirmar) {
				$erros    = TRUE;
				$mensagem .= "- Nova senha diferente da senha de confirmação.\n";
				
				$this->session->set_flashdata('ERRO_DLV_SENHA_USU_NOVA', 'has-error');
				$this->session->set_flashdata('ERRO_DLV_SENHA_USU_CONFIRMAR', 'has-error');
			} else {
				if (md5($dlv_senha_usu_nova) == $dlv_senha_usu) {
					$erros    = TRUE;
					$mensagem .= "- Nova senha igual a senha atual.\n";
						
					$this->session->set_flashdata('ERRO_DLV_SENHA_USU_NOVA', 'has-error');
				}				
			}
		}
		
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para modificar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		
			$this->session->set_flashdata('ERRO_SENHA', TRUE);
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_SENHA                   = $this->session->flashdata('ERRO_SENHA');
		$ERRO_DLV_SENHA_USU_ATUAL     = $this->session->flashdata('ERRO_DLV_SENHA_USU_ATUAL');
		$ERRO_DLV_SENHA_USU_NOVA      = $this->session->flashdata('ERRO_DLV_SENHA_USU_NOVA');
		$ERRO_DLV_SENHA_USU_CONFIRMAR = $this->session->flashdata('ERRO_DLV_SENHA_USU_CONFIRMAR');
			
// 		if ($ERRO_SENHA) {
// 			$dados['ERRO_DLV_SENHA_USU_ATUAL']     = $ERRO_DLV_SENHA_USU_ATUAL;
// 			$dados['ERRO_DLV_SENHA_USU_NOVA']      = $ERRO_DLV_SENHA_USU_NOVA;
// 			$dados['ERRO_DLV_SENHA_USU_CONFIRMAR'] = $ERRO_DLV_SENHA_USU_CONFIRMAR;
// 		}
	}	
	
}