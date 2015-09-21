<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_cadusuario_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Usuario_Model', 'UsuarioModel');
		$this->load->model('Perfil_Model', 'PerfilModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']  = site_url('usuario');
		$dados['NOVO_USUARIO'] = site_url('usuario/novo');
		$dados['BLC_DADOS']  = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('usuario_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['dlv_id_usu']              = 0;		
		$dados['dlv_nome_usu']            = '';
		$dados['dlv_dlvper_usu']          = '';
		$dados['dlv_login_usu']           = '';
		$dados['dlv_senha_usu']           = '';
		$dados['dlv_senha_usu_confirmar'] = '';
		$dados['dlv_ativo_usu']           = 'checked';
		
		$dados['DLV_LOGIN_USU_DISABLED']  = '';
		$dados['DIV_SENHAS']              = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);

		$this->carregarDadosFlash($dados);

		$this->carregarPerfis($dados);
		
		$this->parser->parse('usuario_cadastro', $dados);
	}
	
	public function editar($dlv_id_usu) {
		$dlv_id_usu = base64_decode($dlv_id_usu);
		$dados = array();
		
		$dados['DLV_LOGIN_USU_READONLY'] = 'readonly';
		$dados['DIV_SENHAS']             = 'transp';
		
		$this->carregarUsuario($dlv_id_usu, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->carregarPerfis($dados);
		
		$this->parser->parse('usuario_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_usu;
		global $dlv_nome_usu;
		global $dlv_dlvper_usu;
		global $dlv_login_usu;
		global $dlv_senha_usu;
		global $dlv_senha_usu_confirmar;
		global $dlv_ativo_usu;
		
		$dlv_id_usu     	     = $this->input->post('dlv_id_usu');			
		$dlv_nome_usu   		 = $this->input->post('dlv_nome_usu');
		$dlv_dlvper_usu 		 = $this->input->post('dlv_dlvper_usu');
		$dlv_login_usu 			 = $this->input->post('dlv_login_usu');
		$dlv_senha_usu 			 = $this->input->post('dlv_senha_usu');
		$dlv_senha_usu_confirmar = $this->input->post('dlv_senha_usu_confirmar');
		$dlv_ativo_usu           = $this->input->post('dlv_ativo_usu');
		
		
		if ($this->testarDados()) {
			$usuario = array(
				"dlv_dlvemp_usu"      => $this->session->userdata('dlv_id_emp'),
				"dlv_nome_usu"    	  => $dlv_nome_usu,
				"dlv_dlvper_usu"      => $dlv_dlvper_usu,
				"dlv_ativo_usu"       => ($dlv_ativo_usu)?'1':'0',
				"dlv_dlvusumod_usu"   => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_usu" => date('Y-m-d H:i:s'),
				"dlv_kingsoft_usu"    => 0						
			);
			
			if (!$dlv_id_usu) {	
				$usuario['dlv_login_usu'] = $dlv_login_usu;
				$usuario['dlv_senha_usu'] = md5($dlv_senha_usu);
				
				$dlv_id_usu = $this->UsuarioModel->insert($usuario);
			} else {
				$dlv_id_usu = $this->UsuarioModel->update($usuario, $dlv_id_usu);
			}

			if (is_numeric($dlv_id_usu)) {
				$this->session->set_flashdata('sucesso', 'Usuario salvo com sucesso.');
				redirect('usuario');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_usu);	
				redirect('usuario');
			}
		} else {
			if (!$dlv_id_usu) {
				redirect('usuario/novo/');
			} else {
				redirect('usuario/editar/'.base64_encode($dlv_id_usu));
			}			
		}
	}
	
	public function apagar($dlv_id_usu) {
		if ($this->testarApagar(base64_decode($dlv_id_usu))) {
			$res = $this->UsuarioModel->delete(base64_decode($dlv_id_usu));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Usuário apagado com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar usuário.');				
			}
		}
		
		redirect('usuario');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_USUARIO'] = site_url('usuario');
		$dados['ACAO_FORM']        = site_url('usuario/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->UsuarioModel->getUsuariosPerfisEmpresaNaoLogado($this->session->userdata('dlv_id_emp'), $this->session->userdata('dlv_id_usu'));
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_NOME_USU"         => $registro->dlv_nome_usu,
				"DLV_DESCRICAO_PER"    => $registro->dlv_descricao_per,
				"DLV_ATIVO_USU"        => ($registro->dlv_ativo_usu == 1)?'checked':'',
				"EDITAR_USUARIO"       => site_url('usuario/editar/'.base64_encode($registro->dlv_id_usu)),
				"APAGAR_USUARIO"       => "abrirConfirmacao('".base64_encode($registro->dlv_id_usu)."')"
			);
		}
	}
	
	private function carregarPerfis(&$dados) {
		$resultado = $this->PerfilModel->getPerfisEmpresa($this->session->userdata('dlv_id_emp'));
	
		$dados['BLC_PERFIS'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_PERFIS'][] = array(
					"DLV_ID_PER"          => $registro->dlv_id_per,
					"DLV_DESCRICAO_PER"   => $registro->dlv_descricao_per,
					"SEL_DLV_ID_PER"      => ($dados['dlv_dlvper_usu'] == $registro->dlv_id_per)?'selected':''
			);
		}
	}
	
	
	private function carregarUsuario($dlv_id_usu, &$dados) {
		$resultado = $this->UsuarioModel->getUsuarioChave($dlv_id_usu);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
			
			$dados['dlv_senha_usu']           = '';
			$dados['dlv_senha_usu_confirmar'] = '';
			$dados['dlv_ativo_usu']           = ($resultado->dlv_ativo_usu == 1)?'checked':'';
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $dlv_id_usu;
		global $dlv_nome_usu;
		global $dlv_dlvper_usu;
		global $dlv_login_usu;
		global $dlv_senha_usu;
		global $dlv_senha_usu_confirmar;
		global $dlv_ativo_usu;
				
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($dlv_nome_usu)) {
			$erros    = TRUE;
			$mensagem .= "- Nome não preenchido.\n";
			$this->session->set_flashdata('ERRO_DLV_NOME_USU', 'has-error');				
		}
		
		if (empty($dlv_dlvper_usu)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione um perfil.\n";
			$this->session->set_flashdata('ERRO_DLV_DLVPER_USU', 'has-error');
		} else {
			$resultado = $this->PerfilModel->getPerfil($dlv_dlvper_usu);
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Perfil não cadastrado.\n";
				$this->session->set_flashdata('ERRO_DLV_DLVPER_USU', 'has-error');
			}
		}
		
		if (!$dlv_id_usu) {
			if (empty($dlv_login_usu)) {
				$erros    = TRUE;
				$mensagem .= "- Login não preenchido.\n";
				$this->session->set_flashdata('ERRO_DLV_LOGIN_USU', 'has-error');
			} else {
				$resultado = $this->UsuarioModel->getUsuarioLogin($this->session->userdata('dlv_id_emp'), $dlv_login_usu);
					
				if ($resultado) {
					$erros    = TRUE;
					$mensagem .= "- Login já cadastrado em outro usuário.\n";
					$this->session->set_flashdata('ERRO_DLV_LOGIN_USU', 'has-error');
				}
			}
			
			if (empty($dlv_senha_usu)) {
				$erros    = TRUE;
				$mensagem .= "- Senha não preenchida.\n";
				$this->session->set_flashdata('ERRO_DLV_SENHA_USU', 'has-error');			
			}
		
			if (empty($dlv_senha_usu_confirmar)) {
				$erros    = TRUE;
				$mensagem .= "- Senha de confirmação não preenchida.\n";
				$this->session->set_flashdata('ERRO_DLV_SENHA_USU_CONFIRMAR', 'has-error');
			}
			
			if ((!empty($dlv_senha_usu)) and (!empty($dlv_senha_usu_confirmar))) {
				if ($dlv_senha_usu != $dlv_senha_usu_confirmar) {
					$erros    = TRUE;
					$mensagem .= "- Senha diferente da senha de confirmação.\n";
					$this->session->set_flashdata('ERRO_DLV_SENHA_USU_CONFIRMAR', 'has-error');
					$this->session->set_flashdata('ERRO_DLV_SENHA_USU', 'has-error');			
				}
			}
		}		
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_USU', TRUE);				
			$this->session->set_flashdata('dlv_nome_usu', $dlv_nome_usu);				
			$this->session->set_flashdata('dlv_dlvper_usu', $dlv_dlvper_usu);				
			$this->session->set_flashdata('dlv_login_usu', $dlv_login_usu);				
			$this->session->set_flashdata('dlv_ativo_usu', $dlv_ativo_usu);				
		}
		
		return !$erros;
	}
	
	private function testarApagar($dlv_id_usu) {
		$erros    = FALSE;
		$mensagem = null;
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o usuário:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
		
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_USU                  = $this->session->flashdata('ERRO_DLV_USU');
		$ERRO_DLV_NOME_USU             = $this->session->flashdata('ERRO_DLV_NOME_USU');
		$ERRO_DLV_DLVPER_USU           = $this->session->flashdata('ERRO_DLV_DLVPER_USU');
		$ERRO_DLV_LOGIN_USU            = $this->session->flashdata('ERRO_DLV_LOGIN_USU');
		$ERRO_DLV_SENHA_USU            = $this->session->flashdata('ERRO_DLV_SENHA_USU');
		$ERRO_DLV_SENHA_USU_CONFIRMAR  = $this->session->flashdata('ERRO_DLV_SENHA_USU_CONFIRMAR');
		
		$dlv_nome_usu        = $this->session->flashdata('dlv_nome_usu');
		$dlv_dlvper_usu      = $this->session->flashdata('dlv_dlvper_usu');
		$dlv_login_usu       = $this->session->flashdata('dlv_login_usu');
		$dlv_ativo_usu       = $this->session->flashdata('dlv_ativo_usu');
		
		if ($ERRO_DLV_USU) {
			$dados['dlv_nome_usu']   = $dlv_nome_usu;
			$dados['dlv_dlvper_usu'] = $dlv_dlvper_usu;
			$dados['dlv_login_usu']  = $dlv_login_usu;
			$dados['dlv_ativo_usu']  = ($dlv_ativo_usu == 1)?'checked':'';
				
			$dados['ERRO_DLV_NOME_USU']             = $ERRO_DLV_NOME_USU;
			$dados['ERRO_DLV_DLVPER_USU']           = $ERRO_DLV_DLVPER_USU;
			$dados['ERRO_DLV_LOGIN_USU']            = $ERRO_DLV_LOGIN_USU;
			$dados['ERRO_DLV_SENHA_USU']            = $ERRO_DLV_SENHA_USU;
			$dados['ERRO_DLV_SENHA_USU_CONFIRMAR']  = $ERRO_DLV_SENHA_USU_CONFIRMAR;
		}
	}
}