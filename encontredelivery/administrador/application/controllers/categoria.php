<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categoria extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_cadcategoria_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Categoria_Model', 'CategoriaModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']  		   = site_url('categoria');
		$dados['NOVO_CATEGORIA']   	   = site_url('categoria/novo');
		$dados['URL_ATIVAR_DESATIVAR'] = site_url('json/categoria_json/ativar_desativar');
		$dados['BLC_DADOS']            = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('categoria_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['dlv_id_cat']        = 0;		
		$dados['dlv_descricao_cat'] = '';
		$dados['dlv_ordem_cat']     = '';
		$dados['dlv_ativo_cat']     = 'checked';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);

		$this->parser->parse('categoria_cadastro', $dados);
	}
	
	public function editar($dlv_id_cat) {
		$dlv_id_cat = base64_decode($dlv_id_cat);
		$dados = array();
		
		$this->carregarCategoria($dlv_id_cat, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('categoria_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_cat;
		global $dlv_descricao_cat;
		global $dlv_ordem_cat;
		global $dlv_ativo_cat;
		
		$dlv_id_cat   		 = $this->input->post('dlv_id_cat');			
		$dlv_descricao_cat   = $this->input->post('dlv_descricao_cat');
		$dlv_ordem_cat 		 = $this->input->post('dlv_ordem_cat');
		$dlv_ativo_cat 		 = $this->input->post('dlv_ativo_cat');
		
		
		if (empty($dlv_ordem_cat)) {
			$dlv_ordem_cat = 0;
		}

		
		if ($this->testarDados()) {
			$categoria = array(
				"dlv_dlvemp_cat" => $this->session->userdata('dlv_id_emp'),
				"dlv_descricao_cat"        => $dlv_descricao_cat,
				"dlv_ordem_cat"            => $dlv_ordem_cat,
				"dlv_ativo_cat"            => ($dlv_ativo_cat)?'1':'0',
			);
			
			if (!$dlv_id_cat) {	
				$dlv_id_cat = $this->CategoriaModel->insert($categoria);
			} else {
				$dlv_id_cat = $this->CategoriaModel->update($categoria, $dlv_id_cat);
			}

			if (is_numeric($dlv_id_cat)) {
				$this->session->set_flashdata('sucesso', 'Categoria salva com sucesso.');
				redirect('categoria');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_cat);	
				redirect('categoria');
			}
		} else {
			if (!$dlv_id_cat) {
				redirect('categoria/novo/');
			} else {
				redirect('categoria/editar/'.base64_encode($dlv_id_cat));
			}			
		}
	}
	
	public function apagar($dlv_id_cat) {
		$res = $this->CategoriaModel->delete(base64_decode($dlv_id_cat));

		if ($res) {
			$this->session->set_flashdata('sucesso', 'Categoria apagada com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível apagar categoria.');				
		}
		
		redirect('categoria');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_CATEGORIA']   = site_url('categoria');
		$dados['ACAO_FORM']          = site_url('categoria/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->CategoriaModel->getCategoriaEmpresa($this->session->userdata('dlv_id_emp'));
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_DESCRICAO_CAT"   => $registro->dlv_descricao_cat,				
				"DLV_ORDEM_CAT"       => $registro->dlv_ordem_cat,
				"DISPLAY_ATIVAR"      => ($registro->dlv_ativo_cat == 1)?'none':'',
				"DISPLAY_DESATIVAR"   => ($registro->dlv_ativo_cat == 1)?'':'none',
				"ATIVAR_CATEGORIA"    => "ativarCategoria('".base64_encode($registro->dlv_id_cat)."')",
				"DESATIVAR_CATEGORIA" => "desativarCategoria('".base64_encode($registro->dlv_id_cat)."')",
				"EDITAR_CATEGORIA" 	  => site_url('categoria/editar/'.base64_encode($registro->dlv_id_cat)),
				"APAGAR_CATEGORIA"    => "abrirConfirmacao('".base64_encode($registro->dlv_id_cat)."')"
			);
		}
	}
	
	private function carregarCategoria($dlv_id_cat, &$dados) {
		$resultado = $this->CategoriaModel->get($dlv_id_cat);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}

		$dados['dlv_ativo_cat'] = ($resultado->dlv_ativo_cat == 1)?'checked':'';

		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $dlv_id_cat;
		global $dlv_descricao_cat;
		global $dlv_ordem_cat;
		global $dlv_ativo_cat;

				
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($dlv_descricao_cat)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_DLV_DESCRICAO_CAT', 'has-error');
		}		
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_CAT', TRUE);				
			$this->session->set_flashdata('dlv_descricao_cat', $dlv_descricao_cat);	
			$this->session->set_flashdata('dlv_ordem_cat', $dlv_ordem_cat);
			$this->session->set_flashdata('dlv_ativo_cat', $dlv_ativo_cat);
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_CAT   		 = $this->session->flashdata('ERRO_DLV_CAT');
		$ERRO_DLV_DESCRICAO_CAT  = $this->session->flashdata('ERRO_DLV_DESCRICAO_CAT');
		
		$dlv_descricao_cat   = $this->session->flashdata('dlv_descricao_cat');
		$dlv_ordem_cat       = $this->session->flashdata('dlv_ordem_cat');		
		$dlv_ativo_cat       = $this->session->flashdata('dlv_ativo_cat');
		
		if ($ERRO_DLV_CAT) {			
			$dados['dlv_descricao_cat'] = $dlv_descricao_cat;
			$dados['dlv_ordem_cat']     = $dlv_ordem_cat;
			$dados['dlv_ativo_cat']     = ($dlv_ativo_cat == 1)?'checked':'';

				
			$dados['ERRO_DLV_DESCRICAO_CAT']  = $ERRO_DLV_DESCRICAO_CAT;

		}
	}
}