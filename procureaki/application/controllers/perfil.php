<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_cadperfil_per') != 1) {redirect('');}
		
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
		
		$dados['dlv_id_per']           = 0;		
		$dados['dlv_descricao_per']    = '';
		$dados['dlv_alttelefone_per']  = '';
		$dados['dlv_althorario_per']   = '';
		$dados['dlv_alttaxa_per']      = '';
		$dados['dlv_altfpg_per']       = '';
		$dados['dlv_altrede_per']      = '';
		$dados['dlv_altarea_per']      = '';
		$dados['dlv_cadtamanho_per']   = '';
		$dados['dlv_cadadicional_per'] = '';
		$dados['dlv_cadcategoria_per'] = '';
		$dados['dlv_cadproduto_per']   = '';
		$dados['dlv_altstatusped_per'] = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('perfil_cadastro', $dados);
	}
	
	public function editar($dlv_id_per) {
		$dlv_id_per = base64_decode($dlv_id_per);
		$dados = array();
		
		$this->carregarPerfil($dlv_id_per, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('perfil_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_per;
		global $dlv_descricao_per;
		global $dlv_alttelefone_per;
		global $dlv_althorario_per;
		global $dlv_alttaxa_per;
		global $dlv_altfpg_per;
		global $dlv_altrede_per;
		global $dlv_altarea_per;
		global $dlv_cadtamanho_per;
		global $dlv_cadadicional_per;
		global $dlv_cadcategoria_per;
		global $dlv_cadproduto_per;
		global $dlv_altstatusped_per;
		
		$dlv_id_per           = $this->input->post('dlv_id_per');			
		$dlv_descricao_per    = $this->input->post('dlv_descricao_per');
		$dlv_alttelefone_per  = $this->input->post('dlv_alttelefone_per');
		$dlv_althorario_per   = $this->input->post('dlv_althorario_per');
		$dlv_alttaxa_per      = $this->input->post('dlv_alttaxa_per');
		$dlv_altfpg_per       = $this->input->post('dlv_altfpg_per');
		$dlv_altrede_per      = $this->input->post('dlv_altrede_per');
		$dlv_altarea_per      = $this->input->post('dlv_altarea_per');
		$dlv_cadtamanho_per   = $this->input->post('dlv_cadtamanho_per');
		$dlv_cadadicional_per = $this->input->post('dlv_cadadicional_per');
		$dlv_cadcategoria_per = $this->input->post('dlv_cadcategoria_per');
		$dlv_cadproduto_per   = $this->input->post('dlv_cadproduto_per');
		$dlv_altstatusped_per = $this->input->post('dlv_altstatusped_per');
		
		
		if ($this->testarDados()) {
			$perfil = array(
				"dlv_dlvemp_per"       => $this->session->userdata('dlv_id_emp'),
				"dlv_descricao_per"    => $dlv_descricao_per,
				"dlv_alttelefone_per"  => ($dlv_alttelefone_per)?'1':'0',	
				"dlv_althorario_per"   => ($dlv_althorario_per)?'1':'0',
				"dlv_alttaxa_per"      => ($dlv_alttaxa_per)?'1':'0',
				"dlv_altfpg_per"       => ($dlv_altfpg_per)?'1':'0',
				"dlv_altrede_per"      => ($dlv_altrede_per)?'1':'0',
				"dlv_altarea_per"      => ($dlv_altarea_per)?'1':'0',
				"dlv_cadtamanho_per"   => ($dlv_cadtamanho_per)?'1':'0',
				"dlv_cadadicional_per" => ($dlv_cadadicional_per)?'1':'0',
				"dlv_cadcategoria_per" => ($dlv_cadcategoria_per)?'1':'0',
				"dlv_cadproduto_per"   => ($dlv_cadproduto_per)?'1':'0',
				"dlv_altstatusped_per" => ($dlv_altstatusped_per)?'1':'0',
				"dlv_dlvusumod_per"    => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_per"  => date('Y-m-d H:i:s')							
			);
			
			if (!$dlv_id_per) {				
				$perfil['dlv_cadperfil_per']  = 0;
				$perfil['dlv_cadusuario_per'] = 0;
				
				$dlv_id_per = $this->PerfilModel->insert($perfil);
			} else {
				$dlv_id_per = $this->PerfilModel->update($perfil, $dlv_id_per);
			}

			if (is_numeric($dlv_id_per)) {
				$this->session->set_flashdata('sucesso', 'Perfil salvo com sucesso.');
				redirect('perfil');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_per);	
				redirect('perfil');
			}
		} else {
			if (!$dlv_id_per) {
				redirect('perfil/novo/');
			} else {
				redirect('perfil/editar/'.base64_encode($dlv_id_per));
			}			
		}
	}
	
	public function apagar($dlv_id_per) {
		if ($this->testarApagar(base64_decode($dlv_id_per))) {
			$res = $this->PerfilModel->delete(base64_decode($dlv_id_per));
	
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
		$resultado = $this->PerfilModel->getPerfisEmpresaNaoLogado($this->session->userdata('dlv_id_emp'), $this->session->userdata('dlv_id_per'));
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_DESCRICAO_PER" => $registro->dlv_descricao_per,
				"EDITAR_PERFIL"     => site_url('perfil/editar/'.base64_encode($registro->dlv_id_per)),
				"APAGAR_PERFIL"     => "abrirConfirmacao('".base64_encode($registro->dlv_id_per)."')"
			);
		}
	}
	
	private function carregarPerfil($dlv_id_per, &$dados) {
		$resultado = $this->PerfilModel->getPerfil($dlv_id_per);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
			
			$dados['dlv_alttelefone_per']  = ($resultado->dlv_alttelefone_per == 1)?'checked':'';
			$dados['dlv_althorario_per']   = ($resultado->dlv_althorario_per == 1)?'checked':'';
			$dados['dlv_alttaxa_per']      = ($resultado->dlv_alttaxa_per == 1)?'checked':'';
			$dados['dlv_altfpg_per']       = ($resultado->dlv_altfpg_per == 1)?'checked':'';
			$dados['dlv_altrede_per']      = ($resultado->dlv_altrede_per == 1)?'checked':'';
			$dados['dlv_altarea_per']      = ($resultado->dlv_altarea_per == 1)?'checked':'';
			$dados['dlv_cadtamanho_per']   = ($resultado->dlv_cadtamanho_per == 1)?'checked':'';
			$dados['dlv_cadadicional_per'] = ($resultado->dlv_cadadicional_per == 1)?'checked':'';
			$dados['dlv_cadcategoria_per'] = ($resultado->dlv_cadcategoria_per == 1)?'checked':'';
			$dados['dlv_cadproduto_per']   = ($resultado->dlv_cadproduto_per == 1)?'checked':'';
			$dados['dlv_altstatusped_per'] = ($resultado->dlv_altstatusped_per == 1)?'checked':'';
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $dlv_descricao_per;
		global $dlv_alttelefone_per;
		global $dlv_althorario_per;
		global $dlv_alttaxa_per;
		global $dlv_altfpg_per;
		global $dlv_altrede_per;
		global $dlv_altarea_per;
		global $dlv_cadtamanho_per;
		global $dlv_cadadicional_per;
		global $dlv_cadcategoria_per;
		global $dlv_cadproduto_per;
		global $dlv_altstatusped_per;
		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($dlv_descricao_per)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_DLV_DESCRICAO_PER', 'has-error');				
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_PER', TRUE);				
			$this->session->set_flashdata('dlv_descricao_per', $dlv_descricao_per);				
			$this->session->set_flashdata('dlv_alttelefone_per', $dlv_alttelefone_per);				
			$this->session->set_flashdata('dlv_althorario_per', $dlv_althorario_per);				
			$this->session->set_flashdata('dlv_alttaxa_per', $dlv_alttaxa_per);				
			$this->session->set_flashdata('dlv_altfpg_per', $dlv_altfpg_per);				
			$this->session->set_flashdata('dlv_altrede_per', $dlv_altrede_per);				
			$this->session->set_flashdata('dlv_altarea_per', $dlv_altarea_per);				
			$this->session->set_flashdata('dlv_cadtamanho_per', $dlv_cadtamanho_per);				
			$this->session->set_flashdata('dlv_cadadicional_per', $dlv_cadadicional_per);				
			$this->session->set_flashdata('dlv_cadcategoria_per', $dlv_cadcategoria_per);				
			$this->session->set_flashdata('dlv_cadproduto_per', $dlv_cadproduto_per);				
			$this->session->set_flashdata('dlv_altstatusped_per', $dlv_altstatusped_per);				
		}
		
		return !$erros;
	}
	
	private function testarApagar($dlv_id_per) {
		$erros    = FALSE;
		$mensagem = null;
		
		$resultado = $this->UsuarioModel->getUsuariosPerfil($dlv_id_per);
		
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
		$ERRO_DLV_PER           = $this->session->flashdata('ERRO_DLV_PER');
		$ERRO_DLV_DESCRICAO_PER = $this->session->flashdata('ERRO_DLV_DESCRICAO_PER');
		$dlv_descricao_per      = $this->session->flashdata('dlv_descricao_per');
		$dlv_alttelefone_per    = $this->session->flashdata('dlv_alttelefone_per');
		$dlv_althorario_per     = $this->session->flashdata('dlv_althorario_per');
		$dlv_alttaxa_per        = $this->session->flashdata('dlv_alttaxa_per');
		$dlv_altfpg_per         = $this->session->flashdata('dlv_altfpg_per');
		$dlv_altrede_per        = $this->session->flashdata('dlv_altrede_per');
		$dlv_altarea_per        = $this->session->flashdata('dlv_altarea_per');
		$dlv_cadtamanho_per     = $this->session->flashdata('dlv_cadtamanho_per');
		$dlv_cadadicional_per   = $this->session->flashdata('dlv_cadadicional_per');
		$dlv_cadcategoria_per   = $this->session->flashdata('dlv_cadcategoria_per');
		$dlv_cadproduto_per     = $this->session->flashdata('dlv_cadproduto_per');
		$dlv_altstatusped_per   = $this->session->flashdata('dlv_altstatusped_per');
		
		if ($ERRO_DLV_PER) {
			$dados['dlv_descricao_per']    = $dlv_descricao_per;
			$dados['dlv_alttelefone_per']  = ($dlv_alttelefone_per == 1)?'checked':'';
			$dados['dlv_althorario_per']   = ($dlv_althorario_per == 1)?'checked':'';
			$dados['dlv_alttaxa_per']      = ($dlv_alttaxa_per == 1)?'checked':'';
			$dados['dlv_altfpg_per']       = ($dlv_altfpg_per == 1)?'checked':'';
			$dados['dlv_altrede_per']      = ($dlv_altrede_per == 1)?'checked':'';
			$dados['dlv_altarea_per']      = ($dlv_altarea_per == 1)?'checked':'';
			$dados['dlv_cadtamanho_per']   = ($dlv_cadtamanho_per == 1)?'checked':'';
			$dados['dlv_cadadicional_per'] = ($dlv_cadadicional_per == 1)?'checked':'';
			$dados['dlv_cadcategoria_per'] = ($dlv_cadcategoria_per == 1)?'checked':'';
			$dados['dlv_cadproduto_per']   = ($dlv_cadproduto_per == 1)?'checked':'';
			$dados['dlv_altstatusped_per'] = ($dlv_altstatusped_per == 1)?'checked':'';
				
			$dados['ERRO_DLV_DESCRICAO_PER'] = $ERRO_DLV_DESCRICAO_PER;
		}
	}
}