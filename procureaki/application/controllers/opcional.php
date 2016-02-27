<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Opcional extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('vei_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Opcional_Model', 'OpcionalModel');
//		$this->load->model('Empresa_Segmento_Model', 'EmpresaSegmentoModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']     = site_url('opcional');
		$dados['NOVO_OPCIONAL'] = site_url('opcional/novo');
		$dados['BLC_DADOS']     = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('opcional_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['vei_id_opc']        = 0;
		$dados['vei_descricao_opc'] = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('opcional_cadastro', $dados);
	}
	
	public function editar($vei_id_opc) {
		$vei_id_opc = base64_decode($vei_id_opc);
		$dados = array();
		
		$this->carregarOpcional($vei_id_opc, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('opcional_cadastro', $dados);
	}
	
	public function salvar() {
		global $vei_id_opc;
		global $vei_descricao_opc;
		
		$vei_id_opc        = $this->input->post('vei_id_opc');
		$vei_descricao_opc = $this->input->post('vei_descricao_opc');
		
		
		if ($this->testarDados()) {
			$opcional = array(
				"vei_descricao_opc" => $vei_descricao_opc
			);
			
			if (!$vei_id_opc) {
				$vei_id_opc = $this->OpcionalModel->insert($opcional);
			} else {
				$vei_id_opc = $this->OpcionalModel->update($opcional, $vei_id_opc);
			}

			if (is_numeric($vei_id_opc)) {
				$this->session->set_flashdata('sucesso', 'Opcional salvo com sucesso.');
				redirect('opcional');
			} else {
				$this->session->set_flashdata('erro', $vei_id_opc);
				redirect('opcional');
			}
		} else {
			if (!$vei_id_opc) {
				redirect('opcional/novo/');
			} else {
				redirect('opcional/editar/'.base64_encode($vei_id_opc));
			}			
		}
	}
	
	public function apagar($vei_id_opc) {
		if ($this->testarApagar(base64_decode($vei_id_opc))) {
			$res = $this->OpcionalModel->delete(base64_decode($vei_id_opc));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Opcional apagado com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar o opcional.');
			}
		}
		
		redirect('opcional');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_OPCIONAL'] = site_url('opcional');
		$dados['ACAO_FORM']         = site_url('opcional/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->OpcionalModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"VEI_ID_OPC"        => $registro->vei_id_opc,
				"VEI_DESCRICAO_OPC" => $registro->vei_descricao_opc,
				"EDITAR_OPCIONAL"   => site_url('opcional/editar/'.base64_encode($registro->vei_id_opc)),
				"APAGAR_OPCIONAL"   => "abrirConfirmacao('".base64_encode($registro->vei_id_opc)."')"
			);
		}
	}
	
	private function carregarOpcional($vei_id_opc, &$dados) {
		$resultado = $this->OpcionalModel->getOpcional($vei_id_opc);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
					
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $vei_descricao_opc;
		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($vei_descricao_opc)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_VEI_DESCRICAO_OPC', 'has-error');
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_VEI_OPC', TRUE);
			$this->session->set_flashdata('vei_descricao_opc', $vei_descricao_opc);
		}
		
		return !$erros;
	}
	
	private function testarApagar($vei_id_opc) {
		$erros    = FALSE;
		$mensagem = null;
	
//		$resultado = $this->EmpresaSegmentoModel->getEmpresas($dlv_id_seg);
//
//		if ($resultado) {
//			$erros    = TRUE;
//			$mensagem .= "- Um ou mais empresas opc este segmento.\n";
//		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o opcional:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_VEI_OPC           = $this->session->flashdata('ERRO_VEI_OPC');
		$ERRO_VEI_DESCRICAO_OPC = $this->session->flashdata('ERRO_VEI_DESCRICAO_OPC');
		$vei_descricao_opc      = $this->session->flashdata('vei_descricao_opc');
		
		if ($ERRO_VEI_OPC) {
			$dados['vei_descricao_opc']      = $vei_descricao_opc;
			
			$dados['ERRO_VEI_DESCRICAO_OPC'] = $ERRO_VEI_DESCRICAO_OPC;
		}
	}
}