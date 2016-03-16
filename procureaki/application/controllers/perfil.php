<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('bus_cadperfil_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Perfil_Model', 'PerfilModel');
		$this->load->model('Usuario_Model', 'UsuarioModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']  = site_url('perfil');
		$dados['NOVO_PERFIL'] = site_url('perfil/novo');
		$dados['BLC_DADOS']  = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('perfil_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['bus_id_per']           = 0;		
		$dados['bus_descricao_per']    = '';
		$dados['bus_alttelefone_per']  = '';
		$dados['bus_althorario_per']   = '';
		$dados['bus_cadcategoria_per'] = '';
		$dados['bus_cadproduto_per']   = '';

		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('perfil_cadastro', $dados);
	}
	
	public function editar($bus_id_per) {
		$bus_id_per = base64_decode($bus_id_per);
		$dados = array();
		
		$this->carregarPerfil($bus_id_per, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('perfil_cadastro', $dados);	
	}
	
	public function salvar() {
		global $bus_id_per;
		global $bus_descricao_per;
		global $bus_alttelefone_per;
		global $bus_althorario_per;
		global $bus_cadcategoria_per;
		global $bus_cadproduto_per;

		
		$bus_id_per           = $this->input->post('bus_id_per');			
		$bus_descricao_per    = $this->input->post('bus_descricao_per');
		$bus_alttelefone_per  = $this->input->post('bus_alttelefone_per');
		$bus_althorario_per   = $this->input->post('bus_althorario_per');
		$bus_cadcategoria_per = $this->input->post('bus_cadcategoria_per');
		$bus_cadproduto_per   = $this->input->post('bus_cadproduto_per');

		
		
		if ($this->testarDados()) {
			$perfil = array(
				"bus_busemp_per"       => $this->session->userdata('bus_id_emp'),
				"bus_descricao_per"    => $bus_descricao_per,
				"bus_alttelefone_per"  => ($bus_alttelefone_per)?'1':'0',	
				"bus_althorario_per"   => ($bus_althorario_per)?'1':'0',
				"bus_cadcategoria_per" => ($bus_cadcategoria_per)?'1':'0',
				"bus_cadproduto_per"   => ($bus_cadproduto_per)?'1':'0',
				"bus_bususumod_per"    => $this->session->userdata('bus_id_usu'),
				"bus_datahoramod_per"  => date('Y-m-d H:i:s')							
			);
			
			if (!$bus_id_per) {				
				$perfil['bus_cadperfil_per']  = 0;
				$perfil['bus_cadusuario_per'] = 0;
				
				$bus_id_per = $this->PerfilModel->insert($perfil);
			} else {
				$bus_id_per = $this->PerfilModel->update($perfil, $bus_id_per);
			}

			if (is_numeric($bus_id_per)) {
				$this->session->set_flashdata('sucesso', 'Perfil salvo com sucesso.');
				redirect('perfil');
			} else {
				$this->session->set_flashdata('erro', $bus_id_per);	
				redirect('perfil');
			}
		} else {
			if (!$bus_id_per) {
				redirect('perfil/novo/');
			} else {
				redirect('perfil/editar/'.base64_encode($bus_id_per));
			}			
		}
	}
	
	public function apagar($bus_id_per) {
		if ($this->testarApagar(base64_decode($bus_id_per))) {
			$res = $this->PerfilModel->delete(base64_decode($bus_id_per));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Perfil apagado com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar perfil.');				
			}
		}
		
		redirect('perfil');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_PERFIL'] = site_url('perfil');
		$dados['ACAO_FORM']       = site_url('perfil/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->PerfilModel->getPerfisEmpresaNaoLogado($this->session->userdata('bus_id_emp'), $this->session->userdata('bus_id_per'));
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"BUS_DESCRICAO_PER" => $registro->bus_descricao_per,
				"EDITAR_PERFIL"     => site_url('perfil/editar/'.base64_encode($registro->bus_id_per)),
				"APAGAR_PERFIL"     => "abrirConfirmacao('".base64_encode($registro->bus_id_per)."')"
			);
		}
	}
	
	private function carregarPerfil($bus_id_per, &$dados) {
		$resultado = $this->PerfilModel->getPerfil($bus_id_per);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
			
			$dados['bus_alttelefone_per']  = ($resultado->bus_alttelefone_per == 1)?'checked':'';
			$dados['bus_althorario_per']   = ($resultado->bus_althorario_per == 1)?'checked':'';
			$dados['bus_cadcategoria_per'] = ($resultado->bus_cadcategoria_per == 1)?'checked':'';
			$dados['bus_cadproduto_per']   = ($resultado->bus_cadproduto_per == 1)?'checked':'';

		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $bus_descricao_per;
		global $bus_alttelefone_per;
		global $bus_althorario_per;
		global $bus_cadcategoria_per;
		global $bus_cadproduto_per;

		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($bus_descricao_per)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_BUS_DESCRICAO_PER', 'has-error');				
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_BUS_PER', TRUE);				
			$this->session->set_flashdata('bus_descricao_per', $bus_descricao_per);				
			$this->session->set_flashdata('bus_alttelefone_per', $bus_alttelefone_per);				
			$this->session->set_flashdata('bus_althorario_per', $bus_althorario_per);
			$this->session->set_flashdata('bus_cadcategoria_per', $bus_cadcategoria_per);				
			$this->session->set_flashdata('bus_cadproduto_per', $bus_cadproduto_per);				

		}
		
		return !$erros;
	}
	
	private function testarApagar($bus_id_per) {
		$erros    = FALSE;
		$mensagem = null;
		
		$resultado = $this->UsuarioModel->getUsuariosPerfil($bus_id_per);
		
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais usuário com este perfil.\n";
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o perfil:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
		
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_BUS_PER           = $this->session->flashdata('ERRO_BUS_PER');
		$ERRO_BUS_DESCRICAO_PER = $this->session->flashdata('ERRO_BUS_DESCRICAO_PER');
		$bus_descricao_per      = $this->session->flashdata('bus_descricao_per');
		$bus_alttelefone_per    = $this->session->flashdata('bus_alttelefone_per');
		$bus_althorario_per     = $this->session->flashdata('bus_althorario_per');
		$bus_cadcategoria_per   = $this->session->flashdata('bus_cadcategoria_per');
		$bus_cadproduto_per     = $this->session->flashdata('bus_cadproduto_per');

		
		if ($ERRO_BUS_PER) {
			$dados['bus_descricao_per']    = $bus_descricao_per;
			$dados['bus_alttelefone_per']  = ($bus_alttelefone_per == 1)?'checked':'';
			$dados['bus_althorario_per']   = ($bus_althorario_per == 1)?'checked':'';
			$dados['bus_cadcategoria_per'] = ($bus_cadcategoria_per == 1)?'checked':'';
			$dados['bus_cadproduto_per']   = ($bus_cadproduto_per == 1)?'checked':'';

				
			$dados['ERRO_BUS_DESCRICAO_PER'] = $ERRO_BUS_DESCRICAO_PER;
		}
	}
}