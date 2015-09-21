<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Empresa_Model', 'EmpresaModel');
		$this->load->model('Usuario_Model', 'UsuarioModel');
		$this->load->model('Perfil_Model', 'PerfilModel');
		
		if (!isset($_SESSION)) {
			session_start();
		}		
		
		if ($this->session->userdata('logado')) {$this->session->destroy();}		
	}
	
	public function index() {
		$dados = array();
		
		$dados['MASCARA_CPF']       = MASCARA_CPF;
		$dados['MASCARA_CNPJ']      = MASCARA_CNPJ;
		
		$dados['dlv_cpfcnpj_emp']      = '';
		$dados['dlv_login_usu']        = '';
		$dados['dlv_senha_usu']        = '';
		
		$dados['dlv_tipopessoa_emp_f'] = '';
		$dados['dlv_tipopessoa_emp_j'] = 'selected';
		
		$dados['CLASS_DIV_DLV_CPFCNPJ_EMP_F'] = 'transp';
		$dados['CLASS_DIV_DLV_CPFCNPJ_EMP_J'] = '';
		$dados['DIV_DLV_CPFCNPJ_EMP_F']       = 'disabled';
		$dados['DIV_DLV_CPFCNPJ_EMP_J']       = '';
		
		$dados['ACAO_FORM'] = site_url('login/entrar');
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('login_view', $dados);
	}
	
	public function entrar() {
		global $dlv_tipopessoa_emp;
		global $dlv_cpfcnpj_emp;
		global $dlv_login_usu;
		global $dlv_senha_usu;
		
		global $empresa;
		global $usuario;
		global $perfil;		
		
		$dlv_tipopessoa_emp = $this->input->post('dlv_tipopessoa_emp');
		$dlv_cpfcnpj_emp 	= $this->input->post('dlv_cpfcnpj_emp');
		$dlv_login_usu  	= $this->input->post('dlv_login_usu');	
		$dlv_senha_usu  	= $this->input->post('dlv_senha_usu');

		$dlv_cpfcnpj_emp  = str_replace(".", null, $dlv_cpfcnpj_emp);
		$dlv_cpfcnpj_emp  = str_replace("-", null, $dlv_cpfcnpj_emp);
		$dlv_cpfcnpj_emp  = str_replace("/", null, $dlv_cpfcnpj_emp);
		
		
		if ($this->testarDados()) {
			$this->session->set_userdata(array('logado' => TRUE));
			$this->session->set_userdata($empresa);
			$this->session->set_userdata($usuario);
			$this->session->set_userdata($perfil);
			redirect('');
		} else {
			redirect('login/');
		}
	}
	
	private function testarDados() {
		global $dlv_tipopessoa_emp;
		global $dlv_cpfcnpj_emp;
		global $dlv_login_usu;
		global $dlv_senha_usu;
		
		global $empresa;
		global $usuario;
		global $perfil;
		
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($dlv_cpfcnpj_emp)) {
			$erros    = TRUE;
				
			if ($dlv_tipopessoa_emp == 'f') {
				$mensagem .= "- CPF não informado.\n";
			} else {
				$mensagem .= "- CNPJ não informado.\n";
			}
			
			$this->session->set_flashdata('ERRO_DLV_CPFCNPJ_EMP', 'has-error');
		} else {		
			$empresa = $this->EmpresaModel->getCpfCnpj($dlv_tipopessoa_emp, $dlv_cpfcnpj_emp);
		
		
			if (!$empresa) {
				$erros    = TRUE;
				
				if ($dlv_tipopessoa_emp == 'f') {
					$mensagem .= "- CPF não cadastrado.\n";
				} else {
					$mensagem .= "- CNPJ não cadastrado.\n";
				}
				
				$this->session->set_flashdata('ERRO_DLV_CPFCNPJ_EMP', 'has-error');
			} else {
				if (!$empresa->dlv_ativo_emp) {
					$erros    = TRUE;
						
					if ($dlv_tipopessoa_emp == 'f') {
						$mensagem .= "- CPF inativo.\n";
					} else {
						$mensagem .= "- CNPJ inativo.\n";
					}
						
					$this->session->set_flashdata('ERRO_DLV_CPFCNPJ_EMP', 'has-error');
				} else {				
					if ((!empty($dlv_login_usu)) and (!empty($dlv_senha_usu))) {
						$usuario = $this->UsuarioModel->getUsuario($empresa->dlv_id_emp, $dlv_login_usu, md5($dlv_senha_usu));
						if (!$usuario) {
							$erros    = TRUE;
							$mensagem .= "- Login ou senha incorretos.\n";
							
							$this->session->set_flashdata('ERRO_DLV_LOGIN_USU', 'has-error');
							$this->session->set_flashdata('ERRO_DLV_SENHA_USU', 'has-error');
						} else {
							if (!$usuario->dlv_ativo_usu) {
								$erros    = TRUE;
								$mensagem .= "- Login inativo.\n";
								
								$this->session->set_flashdata('ERRO_DLV_LOGIN_USU', 'has-error');
								$this->session->set_flashdata('ERRO_DLV_SENHA_USU', 'has-error');
							} else {
								$perfil = $this->PerfilModel->getPerfil($usuario->dlv_dlvper_usu);
							} 						
						}
					}
				}
			}
		}
		
		if (empty($dlv_login_usu)) {
			$erros    = TRUE;
			$mensagem .= "- Login não informado.\n";
				
			$this->session->set_flashdata('ERRO_DLV_LOGIN_USU', 'has-error');
		}
		
		if (empty($dlv_senha_usu)) {
			$erros    = TRUE;
			$mensagem .= "- Senha não informada.\n";
		
			$this->session->set_flashdata('ERRO_DLV_SENHA_USU', 'has-error');
		}
		
			
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para entrar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
				
			$this->session->set_flashdata('ERRO_LOGIN', TRUE);
			$this->session->set_flashdata('dlv_tipopessoa_emp', $dlv_tipopessoa_emp);
			$this->session->set_flashdata('dlv_cpfcnpj_emp', $dlv_cpfcnpj_emp);
			$this->session->set_flashdata('dlv_login_usu', $dlv_login_usu);
		}
	
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_LOGIN           = $this->session->flashdata('ERRO_LOGIN');
		$ERRO_DLV_CPFCNPJ_EMP = $this->session->flashdata('ERRO_DLV_CPFCNPJ_EMP');
		$ERRO_DLV_LOGIN_USU   = $this->session->flashdata('ERRO_DLV_LOGIN_USU');
		$ERRO_DLV_SENHA_USU   = $this->session->flashdata('ERRO_DLV_SENHA_USU');
		
		$dlv_tipopessoa_emp = $this->session->flashdata('dlv_tipopessoa_emp');
		$dlv_cpfcnpj_emp    = $this->session->flashdata('dlv_cpfcnpj_emp');
		$dlv_login_usu      = $this->session->flashdata('dlv_login_usu');
		
		$titulo_erro = $this->session->flashdata('titulo_erro');
		$erro        = $this->session->flashdata('erro');
		
		if ($ERRO_LOGIN) {
			$dados['dlv_tipopessoa_emp']   = $dlv_tipopessoa_emp;
			$dados['dlv_tipopessoa_emp_f'] = ($dlv_tipopessoa_emp == 'f')?'selected':'';
			$dados['dlv_tipopessoa_emp_j'] = ($dlv_tipopessoa_emp == 'j')?'selected':'';
			$dados['dlv_login_usu']        = $dlv_login_usu;
				
			if (empty($dlv_cpfcnpj_emp)) {
				$dados['dlv_cpfcnpj_emp']  = '';
			} else {
				$dados['dlv_cpfcnpj_emp']  = mascara($dlv_cpfcnpj_emp, ($dlv_tipopessoa_emp == 'f')?MASCARA_CPF:MASCARA_CNPJ);
			}
			
			$dados['ERRO_DLV_CPFCNPJ_EMP'] = $ERRO_DLV_CPFCNPJ_EMP;
			$dados['ERRO_DLV_LOGIN_USU']   = $ERRO_DLV_LOGIN_USU;
			$dados['ERRO_DLV_SENHA_USU']   = $ERRO_DLV_SENHA_USU;
				
			$dados['CLASS_DIV_DLV_CPFCNPJ_EMP_F'] = ($dlv_tipopessoa_emp == 'f')?'':'transp';
			$dados['CLASS_DIV_DLV_CPFCNPJ_EMP_J'] = ($dlv_tipopessoa_emp == 'j')?'':'transp';
			$dados['DIV_DLV_CPFCNPJ_EMP_F']       = ($dlv_tipopessoa_emp == 'f')?'':'disabled';
			$dados['DIV_DLV_CPFCNPJ_EMP_J']       = ($dlv_tipopessoa_emp == 'j')?'':'disabled';
			
			$dados['MENSAGEM_LOGIN_ERRO'] = $this->criarAlterta($titulo_erro, $erro);
		} else {
			$dados['MENSAGEM_LOGIN_ERRO'] = '';
		} 
	}
	
	
	private function criarAlterta($titulo, $mensagem) {
		$html = " <br/>
		<div class='alert alert-danger' role='alert' align='center' >
		<button type='button' class='close' data-dismiss='alert'>
		<span aria-hidden='true'>&times;</span>
		</button>
		<h4>
		<strong>{$titulo}</strong>
		</h4>";
	
		if (!empty($mensagem)) {
		$html .= "<div align='left'>
		<strong>{$mensagem}</strong>
		</div>";
		}
			
			
		$html .= "</div>";
	
		return $html;
	}
	
}