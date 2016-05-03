<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categoria extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('bus_cadcategoria_per') != 1) {redirect('');}
		
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
		
		$dados['bus_id_cat']        = 0;
		$dados['bus_descricao_cat'] = '';
		$dados['bus_ordem_cat']     = '';
		$dados['bus_ativo_cat']     = 'checked';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);

		$this->parser->parse('categoria_cadastro', $dados);
	}
	
	public function editar($bus_id_cat) {
		$bus_id_cat = base64_decode($bus_id_cat);
		$dados = array();
		
		$this->carregarCategoria($bus_id_cat, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('categoria_cadastro', $dados);	
	}
	
	public function salvar() {
		global $bus_id_cat;
		global $bus_descricao_cat;
		global $bus_ordem_cat;
		global $bus_ativo_cat;
		
		$bus_id_cat   		 = $this->input->post('bus_id_cat');
		$bus_descricao_cat   = $this->input->post('bus_descricao_cat');
		$bus_ordem_cat 		 = $this->input->post('bus_ordem_cat');
		$bus_ativo_cat 		 = $this->input->post('bus_ativo_cat');
		
		
		if (empty($bus_ordem_cat)) {
			$bus_ordem_cat = 0;
		}

		
		if ($this->testarDados()) {
			$categoria = array(
				"bus_busemp_cat" => $this->session->userdata('bus_id_emp'),
				"bus_descricao_cat"        => $bus_descricao_cat,
				"bus_ordem_cat"            => $bus_ordem_cat,
				"bus_ativo_cat"            => ($bus_ativo_cat)?'1':'0',
			);
			
			if (!$bus_id_cat) {
				$bus_id_cat = $this->CategoriaModel->insert($categoria);
			} else {
				$bus_id_cat = $this->CategoriaModel->update($categoria, $bus_id_cat);
			}

			if (is_numeric($bus_id_cat)) {
				$this->session->set_flashdata('sucesso', 'Categoria salva com sucesso.');
				redirect('categoria');
			} else {
				$this->session->set_flashdata('erro', $bus_id_cat);
				redirect('categoria');
			}
		} else {
			if (!$bus_id_cat) {
				redirect('categoria/novo/');
			} else {
				redirect('categoria/editar/'.base64_encode($bus_id_cat));
			}			
		}
	}
	
	public function apagar($bus_id_cat) {
		$res = $this->CategoriaModel->delete(base64_decode($bus_id_cat));

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
		$resultado = $this->CategoriaModel->getCategoriaEmpresa($this->session->userdata('bus_id_emp'));
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"BUS_DESCRICAO_CAT"   => $registro->bus_descricao_cat,
				"BUS_ORDEM_CAT"       => $registro->bus_ordem_cat,
				"DISPLAY_ATIVAR"      => ($registro->bus_ativo_cat == 1)?'none':'',
				"DISPLAY_DESATIVAR"   => ($registro->bus_ativo_cat == 1)?'':'none',
				"ATIVAR_CATEGORIA"    => "ativarCategoria('".base64_encode($registro->bus_id_cat)."')",
				"DESATIVAR_CATEGORIA" => "desativarCategoria('".base64_encode($registro->bus_id_cat)."')",
				"EDITAR_CATEGORIA" 	  => site_url('categoria/editar/'.base64_encode($registro->bus_id_cat)),
				"APAGAR_CATEGORIA"    => "abrirConfirmacao('".base64_encode($registro->bus_id_cat)."')"
			);
		}
	}
	
	private function carregarCategoria($bus_id_cat, &$dados) {
		$resultado = $this->CategoriaModel->get($bus_id_cat);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}

		$dados['bus_ativo_cat'] = ($resultado->bus_ativo_cat == 1)?'checked':'';

		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $bus_id_cat;
		global $bus_descricao_cat;
		global $bus_ordem_cat;
		global $bus_ativo_cat;

				
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($bus_descricao_cat)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_BUS_DESCRICAO_CAT', 'has-error');
		}		
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_BUS_CAT', TRUE);
			$this->session->set_flashdata('bus_descricao_cat', $bus_descricao_cat);
			$this->session->set_flashdata('bus_ordem_cat', $bus_ordem_cat);
			$this->session->set_flashdata('bus_ativo_cat', $bus_ativo_cat);
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_BUS_CAT   		 = $this->session->flashdata('ERRO_BUS_CAT');
		$ERRO_BUS_DESCRICAO_CAT  = $this->session->flashdata('ERRO_BUS_DESCRICAO_CAT');
		
		$bus_descricao_cat   = $this->session->flashdata('bus_descricao_cat');
		$bus_ordem_cat       = $this->session->flashdata('bus_ordem_cat');
		$bus_ativo_cat       = $this->session->flashdata('bus_ativo_cat');
		
		if ($ERRO_BUS_CAT) {
			$dados['bus_descricao_cat'] = $bus_descricao_cat;
			$dados['bus_ordem_cat']     = $bus_ordem_cat;
			$dados['bus_ativo_cat']     = ($bus_ativo_cat == 1)?'checked':'';

				
			$dados['ERRO_BUS_DESCRICAO_CAT']  = $ERRO_BUS_DESCRICAO_CAT;

		}
	}
}