<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forma_Pagamento extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Forma_Pagamento_Model', 'FormaPagamentoModel');
		$this->load->model('Empresa_Forma_Pagamento_Model', 'EmpresaFormaPagamentoModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']        = site_url('forma_pagamento');
		$dados['NOVA_FORMA_PAGAMENTO'] = site_url('forma_pagamento/novo');
		$dados['BLC_DADOS']        = array();
		 
		$this->carregarDados($dados);
		
		$this->parser->parse('forma_pagamento_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['dlv_id_fpg']           = 0;		
		$dados['dlv_descricao_fpg']    = '';
		$dados['dlv_calculatroco_fpg'] = '';
		
		$dados['ACAO'] = 'Nova';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('forma_pagamento_cadastro', $dados);
	}
	
	public function editar($dlv_id_fpg) {
		$dlv_id_fpg = base64_decode($dlv_id_fpg);
		$dados = array();
		
		$this->carregarFormaPagamento($dlv_id_fpg, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('forma_pagamento_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_fpg;
		global $dlv_descricao_fpg;
		global $dlv_calculatroco_fpg;
		
		$dlv_id_fpg            = $this->input->post('dlv_id_fpg');			
		$dlv_descricao_fpg     = $this->input->post('dlv_descricao_fpg');
		$dlv_calculatroco_fpg  = $this->input->post('dlv_calculatroco_fpg');
		
		if ($this->testarDados()) {
			$forma_pagamento = array(
				"dlv_descricao_fpg"    => $dlv_descricao_fpg,
				"dlv_calculatroco_fpg" => ($dlv_calculatroco_fpg)?'1':'0'
			);
			
			if (!$dlv_id_fpg) {
				$dlv_id_fpg = $this->FormaPagamentoModel->insert($forma_pagamento);
			} else {
				$dlv_id_fpg = $this->FormaPagamentoModel->update($forma_pagamento, $dlv_id_fpg);
			}

			if (is_numeric($dlv_id_fpg)) {
				$this->session->set_flashdata('sucesso', 'Forma de pagamento salva com sucesso.');
				redirect('forma_pagamento');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_fpg);	
				redirect('forma_pagamento');
			}
		} else {
			if (!$dlv_id_fpg) {
				redirect('forma_pagamento/novo/');
			} else {
				redirect('forma_pagamento/editar/'.base64_encode($dlv_id_fpg));
			}			
		}
	}
	
	public function apagar($dlv_id_fpg) {
		if ($this->testarApagar(base64_decode($dlv_id_fpg))) {
			$res = $this->FormaPagamentoModel->delete(base64_decode($dlv_id_fpg));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Forma de pagamento apagada com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar forma de pagamento.');				
			}
		}
		
		redirect('forma_pagamento');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_FORMA_PAGAMENTO'] = site_url('forma_pagamento');
		$dados['ACAO_FORM']                = site_url('forma_pagamento/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->FormaPagamentoModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_ID_FPG"             => $registro->dlv_id_fpg,
				"DLV_DESCRICAO_FPG"      => $registro->dlv_descricao_fpg,
				"DLV_CALCULATROCO_FPG"   => ($registro->dlv_calculatroco_fpg == 1)?'checked':'',
				"EDITAR_FORMA_PAGAMENTO" => site_url('forma_pagamento/editar/'.base64_encode($registro->dlv_id_fpg)),
				"APAGAR_FORMA_PAGAMENTO" => "abrirConfirmacao('".base64_encode($registro->dlv_id_fpg)."')"
			);
		}
	}
	
	private function carregarFormaPagamento($dlv_id_fpg, &$dados) {
		$resultado = $this->FormaPagamentoModel->getFormaPagamento($dlv_id_fpg);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
			
			$dados['dlv_calculatroco_fpg'] = ($resultado->dlv_calculatroco_fpg == 1)?'checked':'';
					
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $dlv_descricao_fpg;
		global $dlv_calculatroco_fpg;
		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($dlv_descricao_fpg)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_DLV_DESCRICAO_FPG', 'has-error');				
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_FPG', TRUE);				
			$this->session->set_flashdata('dlv_descricao_fpg', $dlv_descricao_fpg);				
			$this->session->set_flashdata('dlv_calculatroco_fpg', $dlv_calculatroco_fpg);				
		}
		
		return !$erros;
	}
	
	private function testarApagar($dlv_id_fpg) {
		$erros    = FALSE;
		$mensagem = null;
	
		$resultado = $this->EmpresaFormaPagamentoModel->getEmpresas($dlv_id_fpg);
	
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais empresas com esta forma de pagamento.\n";
		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar a forma de pagamento:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_FPG           = $this->session->flashdata('ERRO_DLV_FPG');
		$ERRO_DLV_DESCRICAO_FPG = $this->session->flashdata('ERRO_DLV_DESCRICAO_FPG');
		
		$dlv_descricao_fpg      = $this->session->flashdata('dlv_descricao_fpg');
		$dlv_calculatroco_fpg   = $this->session->flashdata('dlv_calculatroco_fpg');
		
		if ($ERRO_DLV_FPG) {
			$dados['dlv_descricao_fpg']      = $dlv_descricao_fpg;
			$dados['dlv_calculatroco_fpg']   = ($dlv_calculatroco_fpg == 1)?'checked':'';
			
			$dados['ERRO_DLV_DESCRICAO_FPG'] = $ERRO_DLV_DESCRICAO_FPG;
		}
		
	}
}