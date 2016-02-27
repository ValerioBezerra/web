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
		
		$dados['bus_cpfcnpj_emp']      = '';
		$dados['bus_login_usu']        = '';
		$dados['bus_senha_usu']        = '';
		
		$dados['bus_tipopessoa_emp_f'] = '';
		$dados['bus_tipopessoa_emp_j'] = 'selected';
		
		$dados['CLASS_DIV_BUS_CPFCNPJ_EMP_F'] = 'transp';
		$dados['CLASS_DIV_BUS_CPFCNPJ_EMP_J'] = '';
		$dados['DIV_BUS_CPFCNPJ_EMP_F']       = 'disabled';
		$dados['DIV_BUS_CPFCNPJ_EMP_J']       = '';
		
		$dados['ACAO_FORM'] = site_url('login/entrar');
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('login_view', $dados);
	}
	
	public function entrar() {
		global $bus_tipopessoa_emp;
		global $bus_cpfcnpj_emp;
		global $bus_login_usu;
		global $bus_senha_usu;
		
		global $empresa;
		global $usuario;
		global $perfil;		
		
		$bus_tipopessoa_emp = $this->input->post('bus_tipopessoa_emp');
		$bus_cpfcnpj_emp 	= $this->input->post('bus_cpfcnpj_emp');
		$bus_login_usu  	= $this->input->post('bus_login_usu');	
		$bus_senha_usu  	= $this->input->post('bus_senha_usu');

		$bus_cpfcnpj_emp  = str_replace(".", null, $bus_cpfcnpj_emp);
		$bus_cpfcnpj_emp  = str_replace("-", null, $bus_cpfcnpj_emp);
		$bus_cpfcnpj_emp  = str_replace("/", null, $bus_cpfcnpj_emp);
		
		
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
		global $bus_tipopessoa_emp;
		global $bus_cpfcnpj_emp;
		global $bus_login_usu;
		global $bus_senha_usu;
		
		global $empresa;
		global $usuario;
		global $perfil;
		
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($bus_cpfcnpj_emp)) {
			$erros    = TRUE;

			if ($bus_tipopessoa_emp == 'f') {
				$mensagem .= "- CPF não informado.\n";
			} else {
				$mensagem .= "- CNPJ não informado.\n";
			}

			$this->session->set_flashdata('ERRO_BUS_CPFCNPJ_EMP', 'has-error');
		} else {
			$empresa = $this->EmpresaModel->getCpfCnpj($bus_tipopessoa_emp, $bus_cpfcnpj_emp);


			if (!$empresa) {
				$erros    = TRUE;

				if ($bus_tipopessoa_emp == 'f') {
					$mensagem .= "- CPF não cadastrado.\n";
				} else {
					$mensagem .= "- CNPJ não cadastrado.\n";
				}

				$this->session->set_flashdata('ERRO_BUS_CPFCNPJ_EMP', 'has-error');
			} else {
				if (!$empresa->bus_ativo_emp) {
					$erros    = TRUE;

					if ($bus_tipopessoa_emp == 'f') {
						$mensagem .= "- CPF inativo.\n";
					} else {
						$mensagem .= "- CNPJ inativo.\n";
					}

					$this->session->set_flashdata('ERRO_BUS_CPFCNPJ_EMP', 'has-error');
				} else {
					if ((!empty($bus_login_usu)) and (!empty($bus_senha_usu))) {
						$usuario = $this->UsuarioModel->getUsuario($empresa->bus_id_emp, $bus_login_usu, md5($bus_senha_usu));
						if (!$usuario) {
							$erros    = TRUE;
							$mensagem .= "- Login ou senha incorretos.\n";

							$this->session->set_flashdata('ERRO_BUS_LOGIN_USU', 'has-error');
							$this->session->set_flashdata('ERRO_BUS_SENHA_USU', 'has-error');
						} else {
							if (!$usuario->bus_ativo_usu) {
								$erros    = TRUE;
								$mensagem .= "- Login inativo.\n";

								$this->session->set_flashdata('ERRO_BUS_LOGIN_USU', 'has-error');
								$this->session->set_flashdata('ERRO_BUS_SENHA_USU', 'has-error');
							} else {
								$perfil = $this->PerfilModel->getPerfil($usuario->bus_busper_usu);
							}
						}
					}
				}
			}
		}

		if (empty($bus_login_usu)) {
			$erros    = TRUE;
			$mensagem .= "- Login não informado.\n";

			$this->session->set_flashdata('ERRO_BUS_LOGIN_USU', 'has-error');
		}

		if (empty($bus_senha_usu)) {
			$erros    = TRUE;
			$mensagem .= "- Senha não informada.\n";

			$this->session->set_flashdata('ERRO_BUS_SENHA_USU', 'has-error');
		}

			
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para entrar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
				
			$this->session->set_flashdata('ERRO_LOGIN', TRUE);
			$this->session->set_flashdata('bus_tipopessoa_emp', $bus_tipopessoa_emp);
			$this->session->set_flashdata('bus_cpfcnpj_emp', $bus_cpfcnpj_emp);
			$this->session->set_flashdata('bus_login_usu', $bus_login_usu);
		}
	
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_LOGIN           = $this->session->flashdata('ERRO_LOGIN');
		$ERRO_BUS_CPFCNPJ_EMP = $this->session->flashdata('ERRO_BUS_CPFCNPJ_EMP');
		$ERRO_BUS_LOGIN_USU   = $this->session->flashdata('ERRO_BUS_LOGIN_USU');
		$ERRO_BUS_SENHA_USU   = $this->session->flashdata('ERRO_BUS_SENHA_USU');
		
		$bus_tipopessoa_emp = $this->session->flashdata('bus_tipopessoa_emp');
		$bus_cpfcnpj_emp    = $this->session->flashdata('bus_cpfcnpj_emp');
		$bus_login_usu      = $this->session->flashdata('bus_login_usu');
		
		$titulo_erro = $this->session->flashdata('titulo_erro');
		$erro        = $this->session->flashdata('erro');
		
		if ($ERRO_LOGIN) {
			$dados['bus_tipopessoa_emp']   = $bus_tipopessoa_emp;
			$dados['bus_tipopessoa_emp_f'] = ($bus_tipopessoa_emp == 'f')?'selected':'';
			$dados['bus_tipopessoa_emp_j'] = ($bus_tipopessoa_emp == 'j')?'selected':'';
			$dados['bus_login_usu']        = $bus_login_usu;
				
			if (empty($bus_cpfcnpj_emp)) {
				$dados['bus_cpfcnpj_emp']  = '';
			} else {
				$dados['bus_cpfcnpj_emp']  = mascara($bus_cpfcnpj_emp, ($bus_tipopessoa_emp == 'f')?MASCARA_CPF:MASCARA_CNPJ);
			}
			
			$dados['ERRO_BUS_CPFCNPJ_EMP'] = $ERRO_BUS_CPFCNPJ_EMP;
			$dados['ERRO_BUS_LOGIN_USU']   = $ERRO_BUS_LOGIN_USU;
			$dados['ERRO_BUS_SENHA_USU']   = $ERRO_BUS_SENHA_USU;
				
			$dados['CLASS_DIV_BUS_CPFCNPJ_EMP_F'] = ($bus_tipopessoa_emp == 'f')?'':'transp';
			$dados['CLASS_DIV_BUS_CPFCNPJ_EMP_J'] = ($bus_tipopessoa_emp == 'j')?'':'transp';
			$dados['DIV_BUS_CPFCNPJ_EMP_F']       = ($bus_tipopessoa_emp == 'f')?'':'disabled';
			$dados['DIV_BUS_CPFCNPJ_EMP_J']       = ($bus_tipopessoa_emp == 'j')?'':'disabled';
			
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