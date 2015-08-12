<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa_Forma_Pagamento extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_altfpg_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Forma_Pagamento_Model', 'FormaPagamentoModel');
		$this->load->model('Empresa_Forma_Pagamento_Model', 'EmpresaFormaPagamentoModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR'] = site_url('empresa_forma_pagamento');
		$dados['BLC_DADOS'] = array();
		$dados['ACAO_FORM'] = site_url('empresa_forma_pagamento/adicionar');
		
		$dados['dlv_id_exf']     = '';
		$dados['dlv_dlvfpg_exf'] = '';
		
		$this->carregarFormasPagamentoEmpresa($dados);
		$this->carregarDadosFlash($dados);
		$this->carregarFormasPagamento($dados);
		
		$this->parser->parse('empresa_forma_pagamento_cadastro_consulta', $dados);
	}
	
	public function adicionar() {
		global $dlv_id_exf;
		global $dlv_dlvfpg_exf;
		
		$dlv_id_exf     = $this->input->post('dlv_id_exf');			
		$dlv_dlvfpg_exf = $this->input->post('dlv_dlvfpg_exf');		
		
		if ($this->testarDados()) {
			$forma_pagamento = array(
				"dlv_dlvemp_exf"      => $this->session->userdata('dlv_id_emp'),
				"dlv_dlvfpg_exf"      => $dlv_dlvfpg_exf,
				"dlv_dlvusumod_exf"   => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_exf" => date('Y-m-d H:i:s')							
			);
			
			if (!$dlv_id_exf) {	
				$dlv_id_exf = $this->EmpresaFormaPagamentoModel->insert($forma_pagamento);
			} else {
				$dlv_id_exf = $this->EmpresaFormaPagamentoModel->update($forma_pagamento, $dlv_id_exf);
			}

			if (is_numeric($dlv_id_exf)) {
				$this->session->set_flashdata('sucesso', 'Forma de pagamento adicionada com sucesso.');
				redirect('empresa_forma_pagamento');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_exf);	
				redirect('empresa_forma_pagamento');
			}
		} else {
			redirect('empresa_forma_pagamento');			
		}
	}
	
	public function remover($dlv_id_exf) {
		$res = $this->EmpresaFormaPagamentoModel->delete(base64_decode($dlv_id_exf));

		if ($res) {
			$this->session->set_flashdata('sucesso', 'Forma de pagamento removida com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível remover forma de pagamento.');				
		}
		
		redirect('empresa_forma_pagamento');
	}
	
	private function carregarFormasPagamentoEmpresa(&$dados) {
		$resultado = $this->EmpresaFormaPagamentoModel->getFormasPagamentoEmpresa($this->session->userdata('dlv_id_emp'));
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_DESCRICAO_FPG"              => $registro->dlv_descricao_fpg,
				"APAGAR_FORMA_PAGAMENTO_EMPRESA" => "abrirConfirmacao('".base64_encode($registro->dlv_id_exf)."')"
			);
		}
	}
	
	private function carregarFormasPagamento(&$dados) {
		$resultado = $this->FormaPagamentoModel->get();
		
		$dados['BLC_FORMAS_PAGAMENTO'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_FORMAS_PAGAMENTO'][] = array(
					"DLV_ID_FPG"         => $registro->dlv_id_fpg,
					"DLV_DESCRICAO_FPG"  => $registro->dlv_descricao_fpg,
					"SEL_DLV_ID_FPG"     => ($dados['dlv_dlvfpg_exf'] == $registro->dlv_id_fpg)?'selected':''
			);
		}
	}
	
	private function testarDados() {
		global $dlv_id_exf;
		global $dlv_dlvfpg_exf;
						
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($dlv_dlvfpg_exf)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione uma forma de pagamento.\n";
			$this->session->set_flashdata('ERRO_DLV_DLVFPG_EXF', 'has-error');
		} else {
			$resultado = $this->FormaPagamentoModel->getFormaPagamento($dlv_dlvfpg_exf);
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Forma de pagamento não cadastrada.\n";
				$this->session->set_flashdata('ERRO_DLV_DLVFPG_EXF', 'has-error');
			} else {
				$resultado = $this->EmpresaFormaPagamentoModel->getEmpresaFormaPagamento($dlv_dlvfpg_exf, $this->session->userdata('dlv_id_emp'));
		
				if ($resultado) {
					$erros    = TRUE;
					$mensagem .= "- Forma de pagamento já adicionada.\n";
					$this->session->set_flashdata('ERRO_DLV_DLVFPG_EXF', 'has-error');
				}
			}
		}
		
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_EXF', TRUE);				
			$this->session->set_flashdata('dlv_dlvfpg_exf', $dlv_dlvfpg_exf);				
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_EXF         = $this->session->flashdata('ERRO_DLV_EXF');
		$ERRO_DLV_DLVFPG_EXF  = $this->session->flashdata('ERRO_DLV_DLVFPG_EXF');
		$dlv_dlvfpg_exf       = $this->session->flashdata('dlv_dlvfpg_exf');
	
		if ($ERRO_DLV_EXF) {
			$dados['dlv_dlvfpg_exf']      = $dlv_dlvfpg_exf;
			$dados['ERRO_DLV_DLVFPG_EXF'] = $ERRO_DLV_DLVFPG_EXF;
		}
	}
}