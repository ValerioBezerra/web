<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('bus_cadusuario_per') != 1) {redirect('');}
		
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
		
		$dados['bus_id_usu']              = 0;		
		$dados['bus_nome_usu']            = '';
		$dados['bus_busper_usu']          = '';
		$dados['bus_login_usu']           = '';
		$dados['bus_senha_usu']           = '';
		$dados['bus_senha_usu_confirmar'] = '';
		$dados['bus_ativo_usu']           = 'checked';
		
		$dados['BUS_LOGIN_USU_DISABLED']  = '';
		$dados['DIV_SENHAS']              = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);

		$this->carregarDadosFlash($dados);

		$this->carregarPerfis($dados);
		
		$this->parser->parse('usuario_cadastro', $dados);
	}
	
	public function editar($bus_id_usu) {
		$bus_id_usu = base64_decode($bus_id_usu);
		$dados = array();
		
		$dados['BUS_LOGIN_USU_READONLY'] = 'readonly';
		$dados['DIV_SENHAS']             = 'transp';
		
		$this->carregarUsuario($bus_id_usu, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->carregarPerfis($dados);
		
		$this->parser->parse('usuario_cadastro', $dados);	
	}
	
	public function salvar() {
		global $bus_id_usu;
		global $bus_nome_usu;
		global $bus_busper_usu;
		global $bus_login_usu;
		global $bus_senha_usu;
		global $bus_senha_usu_confirmar;
		global $bus_ativo_usu;
		
		$bus_id_usu     	     = $this->input->post('bus_id_usu');			
		$bus_nome_usu   		 = $this->input->post('bus_nome_usu');
		$bus_busper_usu 		 = $this->input->post('bus_busper_usu');
		$bus_login_usu 			 = $this->input->post('bus_login_usu');
		$bus_senha_usu 			 = $this->input->post('bus_senha_usu');
		$bus_senha_usu_confirmar = $this->input->post('bus_senha_usu_confirmar');
		$bus_ativo_usu           = $this->input->post('bus_ativo_usu');
		
		
		if ($this->testarDados()) {
			$usuario = array(
				"bus_busemp_usu"      => $this->session->userdata('bus_id_emp'),
				"bus_nome_usu"    	  => $bus_nome_usu,
				"bus_busper_usu"      => $bus_busper_usu,
				"bus_ativo_usu"       => ($bus_ativo_usu)?'1':'0',
				"bus_bususumod_usu"   => $this->session->userdata('bus_id_usu'),
				"bus_datahoramod_usu" => date('Y-m-d H:i:s'),
				"bus_kingsoft_usu"    => 0						
			);
			
			if (!$bus_id_usu) {	
				$usuario['bus_login_usu'] = $bus_login_usu;
				$usuario['bus_senha_usu'] = md5($bus_senha_usu);
				
				$bus_id_usu = $this->UsuarioModel->insert($usuario);
			} else {
				$bus_id_usu = $this->UsuarioModel->update($usuario, $bus_id_usu);
			}

			if (is_numeric($bus_id_usu)) {
				$this->session->set_flashdata('sucesso', 'Usuario salvo com sucesso.');
				redirect('usuario');
			} else {
				$this->session->set_flashdata('erro', $bus_id_usu);	
				redirect('usuario');
			}
		} else {
			if (!$bus_id_usu) {
				redirect('usuario/novo/');
			} else {
				redirect('usuario/editar/'.base64_encode($bus_id_usu));
			}			
		}
	}
	
	public function apagar($bus_id_usu) {
		if ($this->testarApagar(base64_decode($bus_id_usu))) {
			$res = $this->UsuarioModel->delete(base64_decode($bus_id_usu));
	
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
		$resultado = $this->UsuarioModel->getUsuariosPerfisEmpresaNaoLogado($this->session->userdata('bus_id_emp'), $this->session->userdata('bus_id_usu'));
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"BUS_NOME_USU"         => $registro->bus_nome_usu,
				"BUS_DESCRICAO_PER"    => $registro->bus_descricao_per,
				"BUS_ATIVO_USU"        => ($registro->bus_ativo_usu == 1)?'checked':'',
				"EDITAR_USUARIO"       => site_url('usuario/editar/'.base64_encode($registro->bus_id_usu)),
				"APAGAR_USUARIO"       => "abrirConfirmacao('".base64_encode($registro->bus_id_usu)."')"
			);
		}
	}
	
	private function carregarPerfis(&$dados) {
		$resultado = $this->PerfilModel->getPerfisEmpresa($this->session->userdata('bus_id_emp'));
	
		$dados['BLC_PERFIS'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_PERFIS'][] = array(
					"BUS_ID_PER"          => $registro->bus_id_per,
					"BUS_DESCRICAO_PER"   => $registro->bus_descricao_per,
					"SEL_BUS_ID_PER"      => ($dados['bus_busper_usu'] == $registro->bus_id_per)?'selected':''
			);
		}
	}
	
	
	private function carregarUsuario($bus_id_usu, &$dados) {
		$resultado = $this->UsuarioModel->getUsuarioChave($bus_id_usu);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
			
			$dados['bus_senha_usu']           = '';
			$dados['bus_senha_usu_confirmar'] = '';
			$dados['bus_ativo_usu']           = ($resultado->bus_ativo_usu == 1)?'checked':'';
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $bus_id_usu;
		global $bus_nome_usu;
		global $bus_busper_usu;
		global $bus_login_usu;
		global $bus_senha_usu;
		global $bus_senha_usu_confirmar;
		global $bus_ativo_usu;
				
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($bus_nome_usu)) {
			$erros    = TRUE;
			$mensagem .= "- Nome não preenchido.\n";
			$this->session->set_flashdata('ERRO_BUS_NOME_USU', 'has-error');				
		}
		
		if (empty($bus_busper_usu)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione um perfil.\n";
			$this->session->set_flashdata('ERRO_BUS_BUSPER_USU', 'has-error');
		} else {
			$resultado = $this->PerfilModel->getPerfil($bus_busper_usu);
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Perfil não cadastrado.\n";
				$this->session->set_flashdata('ERRO_BUS_BUSPER_USU', 'has-error');
			}
		}
		
		if (!$bus_id_usu) {
			if (empty($bus_login_usu)) {
				$erros    = TRUE;
				$mensagem .= "- Login não preenchido.\n";
				$this->session->set_flashdata('ERRO_BUS_LOGIN_USU', 'has-error');
			} else {
				$resultado = $this->UsuarioModel->getUsuarioLogin($this->session->userdata('bus_id_emp'), $bus_login_usu);
					
				if ($resultado) {
					$erros    = TRUE;
					$mensagem .= "- Login já cadastrado em outro usuário.\n";
					$this->session->set_flashdata('ERRO_BUS_LOGIN_USU', 'has-error');
				}
			}
			
			if (empty($bus_senha_usu)) {
				$erros    = TRUE;
				$mensagem .= "- Senha não preenchida.\n";
				$this->session->set_flashdata('ERRO_BUS_SENHA_USU', 'has-error');			
			}
		
			if (empty($bus_senha_usu_confirmar)) {
				$erros    = TRUE;
				$mensagem .= "- Senha de confirmação não preenchida.\n";
				$this->session->set_flashdata('ERRO_BUS_SENHA_USU_CONFIRMAR', 'has-error');
			}
			
			if ((!empty($bus_senha_usu)) and (!empty($bus_senha_usu_confirmar))) {
				if ($bus_senha_usu != $bus_senha_usu_confirmar) {
					$erros    = TRUE;
					$mensagem .= "- Senha diferente da senha de confirmação.\n";
					$this->session->set_flashdata('ERRO_BUS_SENHA_USU_CONFIRMAR', 'has-error');
					$this->session->set_flashdata('ERRO_BUS_SENHA_USU', 'has-error');			
				}
			}
		}		
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_BUS_USU', TRUE);				
			$this->session->set_flashdata('bus_nome_usu', $bus_nome_usu);				
			$this->session->set_flashdata('bus_busper_usu', $bus_busper_usu);				
			$this->session->set_flashdata('bus_login_usu', $bus_login_usu);				
			$this->session->set_flashdata('bus_ativo_usu', $bus_ativo_usu);				
		}
		
		return !$erros;
	}
	
	private function testarApagar($bus_id_usu) {
		$erros    = FALSE;
		$mensagem = null;
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o usuário:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
		
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_BUS_USU                  = $this->session->flashdata('ERRO_BUS_USU');
		$ERRO_BUS_NOME_USU             = $this->session->flashdata('ERRO_BUS_NOME_USU');
		$ERRO_BUS_BUSPER_USU           = $this->session->flashdata('ERRO_BUS_BUSPER_USU');
		$ERRO_BUS_LOGIN_USU            = $this->session->flashdata('ERRO_BUS_LOGIN_USU');
		$ERRO_BUS_SENHA_USU            = $this->session->flashdata('ERRO_BUS_SENHA_USU');
		$ERRO_BUS_SENHA_USU_CONFIRMAR  = $this->session->flashdata('ERRO_BUS_SENHA_USU_CONFIRMAR');
		
		$bus_nome_usu        = $this->session->flashdata('bus_nome_usu');
		$bus_busper_usu      = $this->session->flashdata('bus_busper_usu');
		$bus_login_usu       = $this->session->flashdata('bus_login_usu');
		$bus_ativo_usu       = $this->session->flashdata('bus_ativo_usu');
		
		if ($ERRO_BUS_USU) {
			$dados['bus_nome_usu']   = $bus_nome_usu;
			$dados['bus_busper_usu'] = $bus_busper_usu;
			$dados['bus_login_usu']  = $bus_login_usu;
			$dados['bus_ativo_usu']  = ($bus_ativo_usu == 1)?'checked':'';
				
			$dados['ERRO_BUS_NOME_USU']             = $ERRO_BUS_NOME_USU;
			$dados['ERRO_BUS_BUSPER_USU']           = $ERRO_BUS_BUSPER_USU;
			$dados['ERRO_BUS_LOGIN_USU']            = $ERRO_BUS_LOGIN_USU;
			$dados['ERRO_BUS_SENHA_USU']            = $ERRO_BUS_SENHA_USU;
			$dados['ERRO_BUS_SENHA_USU_CONFIRMAR']  = $ERRO_BUS_SENHA_USU_CONFIRMAR;
		}
	}
}