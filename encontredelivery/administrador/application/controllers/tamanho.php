<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tamanho extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_cadtamanho_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Tamanho_Model', 'TamanhoModel');
// 		$this->load->model('Produto_Tamanho_Model', 'ProdutoTamanhoModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']  = site_url('tamanho');
		$dados['NOVO_TAMANHO'] = site_url('tamanho/novo');
		$dados['BLC_DADOS']  = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('tamanho_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['dlv_id_tam']          = 0;		
		$dados['dlv_descricao_tam']   = '';
		$dados['dlv_quantidade_tam']  = '';
		$dados['dlv_ordem_tam']       = '';
		$dados['dlv_ativo_tam']       = 'checked';
		
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		

		$this->parser->parse('tamanho_cadastro', $dados);
	}
	
	public function editar($dlv_id_tam) {
		$dlv_id_tam = base64_decode($dlv_id_tam);
		$dados = array();
		
	$this->carregarTamanho($dlv_id_tam, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('tamanho_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_tam;
		global $dlv_descricao_tam;
		global $dlv_quantidade_tam;
		global $dlv_ordem_tam;
		global $dlv_ativo_tam;
		
		$dlv_id_tam   		= $this->input->post('dlv_id_tam');			
		$dlv_descricao_tam  = $this->input->post('dlv_descricao_tam');
		$dlv_quantidade_tam = $this->input->post('dlv_quantidade_tam');
		$dlv_ordem_tam 		= $this->input->post('dlv_ordem_tam');
		$dlv_ativo_tam 		= $this->input->post('dlv_ativo_tam');
		
		
		
		if ($this->testarDados()) {
			$tamanho = array(
				"dlv_dlvemp_tam" => $this->session->userdata('dlv_id_emp'),
				"dlv_descricao_tam"   => $dlv_descricao_tam,
				"dlv_quantidade_tam"   => $dlv_quantidade_tam,
				"dlv_ordem_tam"   => $dlv_ordem_tam,
				"dlv_ativo_tam"   => ($dlv_ativo_tam)?'1':'0',
				"dlv_dlvusumod_tam"   => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_tam" => date('Y-m-d H:i:s')
			);
			
			if (!$dlv_id_tam) {	
				$dlv_id_tam = $this->TamanhoModel->insert($tamanho);
			} else {
				$dlv_id_tam = $this->TamanhoModel->update($tamanho, $dlv_id_tam);
			}

			if (is_numeric($dlv_id_tam)) {
				$this->session->set_flashdata('sucesso', 'Tamanho salvo com sucesso.');
				redirect('tamanho');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_tam);	
				redirect('tamanho');
			}
		} else {
			if (!$dlv_id_tam) {
				redirect('tamanho/novo/');
			} else {
				redirect('tamanho/editar/'.base64_encode($dlv_id_tam));
			}			
		}
	}
	
	public function apagar($dlv_id_tam) {
		if ($this->testarApagar(base64_decode($dlv_id_tam))) {
			$res = $this->TamanhoModel->delete(base64_decode($dlv_id_tam));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Tamanho apagado com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar tamanho.');				
			}
		}
		
		redirect('tamanho');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_TAMANHO']   = site_url('tamanho');
		$dados['ACAO_FORM']          = site_url('tamanho/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->TamanhoModel->getTamanhoEmpresa($this->session->userdata('dlv_id_emp'));
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_DESCRICAO_TAM"  => $registro->dlv_descricao_tam,
				"DLV_QUANTIDADE_TAM" => $registro->dlv_quantidade_tam,
				"DLV_ORDEM_TAM"      => $registro->dlv_ordem_tam,
				"DLV_ATIVO_TAM"        => ($registro->dlv_ativo_tam == 1)?'checked':'',
				"EDITAR_TAMANHO"     => site_url('tamanho/editar/'.base64_encode($registro->dlv_id_tam)),
				"APAGAR_TAMANHO"     => "abrirConfirmacao('".base64_encode($registro->dlv_id_tam)."')"
			);
		}
	}
	
	private function carregarTamanho($dlv_id_tam, &$dados) {
		$resultado = $this->TamanhoModel->get($dlv_id_tam);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}

		$dados['dlv_ativo_tam']  = ($resultado->dlv_ativo_tam == 1)?'checked':'';

		} else {
			show_error('NÃo foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $dlv_id_tam;
		global $dlv_descricao_tam;
		global $dlv_quantidade_tam;
		global $dlv_ordem_tam;
		global $dlv_ativo_tam;
				
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($dlv_descricao_tam)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_DLV_DESCRICAO_TAM', 'has-error');
		}
		
		if (empty($dlv_quantidade_tam)) {
			$dlv_quantidade_tam = 0;
		}
		
		if (empty($dlv_ordem_tam)) {
			$dlv_ordem_tam = 0;
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_TAM', TRUE);				
			$this->session->set_flashdata('dlv_descricao_tam', $dlv_descricao_tam);				
			$this->session->set_flashdata('dlv_quantidade_tam', $dlv_quantidade_tam);
			$this->session->set_flashdata('dlv_ordem_tam', $dlv_ordem_tam);
			$this->session->set_flashdata('dlv_ativo_tam', $dlv_ativo_tam);
		}
		
		return !$erros;
	}
	
	private function testarApagar($dlv_id_tam) {
		$erros    = FALSE;
		$mensagem = null;
	
// 		$resultado = $this->ProdutoTamanhoModel->getEmpresaTamanho($dlv_id_tam);
	
// 		if ($resultado) {
// 			$erros    = TRUE;
// 			$mensagem .= "- Um ou mais produtos com este tamanho.\n";
// 		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o tamanho:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_TAM   		 = $this->session->flashdata('ERRO_DLV_TAM');
		$ERRO_DLV_DESCRICAO_TAM  = $this->session->flashdata('ERRO_DLV_DESCRICAO_TAM');
		$ERRO_DLV_ORDEM_TAM		 = $this->session->flashdata('ERRO_DLV_ORDEM_TAM');	 	
		
		$dlv_descricao_tam   = $this->session->flashdata('dlv_descricao_tam');
		$dlv_quantidade_tam  = $this->session->flashdata('dlv_quantidade_tam');
		$dlv_ordem_tam       = $this->session->flashdata('dlv_ordem_tam');
		$dlv_ativo_tam       = $this->session->flashdata('dlv_ativo_tam');
		
		if ($ERRO_DLV_TAM) {
			
			$dados['dlv_descricao_tam']  = $dlv_descricao_tam;
			$dados['dlv_quantidade_tam'] = $dlv_quantidade_tam;
			$dados['dlv_ordem_tam']      = $dlv_ordem_tam;
			$dados['dlv_ativo_tam']      = ($dlv_ativo_tam == 1)?'checked':'';
				
			$dados['ERRO_DLV_DESCRICAO_TAM']  = $ERRO_DLV_DESCRICAO_TAM;
			$dados['ERRO_DLV_ORDEM_TAM'] 	  = $ERRO_DLV_ORDEM_TAM;
		}
	}
}