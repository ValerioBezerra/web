<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Status extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Status_Model', 'StatusModel');
		$this->load->model('Empresa_Segmento_Model', 'EmpresaSegmentoModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']     = site_url('status');
		$dados['NOVO_STATUS'] = site_url('status/novo');
		$dados['BLC_DADOS']     = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('status_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['dlv_id_sta']        = 0;		
		$dados['dlv_descricao_sta'] = '';
		$dados['dlv_ativo_sta']    = 'checked';
		
		$dados['dlv_indicador_sta_0'] = 'selected';
		$dados['dlv_indicador_sta_1'] = '';
		$dados['dlv_indicador_sta_2'] = '';
		$dados['dlv_indicador_sta_3'] = '';
		$dados['dlv_ordem_sta'] = '';
		
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('status_cadastro', $dados);
	}
	
	public function editar($dlv_id_sta) {
		$dlv_id_sta = base64_decode($dlv_id_sta);
		$dados = array();
		
		$this->carregarStatus($dlv_id_sta, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('status_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_sta;
		global $dlv_descricao_sta;
		global $dlv_ativo_sta;
		global $dlv_indicador_sta;
		global $dlv_ordem_sta;
		
		$dlv_id_sta        = $this->input->post('dlv_id_sta');			
		$dlv_descricao_sta = $this->input->post('dlv_descricao_sta');
		$dlv_ativo_sta = $this->input->post('dlv_ativo_sta');
		$dlv_indicador_sta = $this->input->post('dlv_indicador_sta');
		$dlv_ordem_sta = $this->input->post('dlv_ordem_sta');
		
		
		if ($this->testarDados()) {
			$status = array(
				"dlv_descricao_sta" => $dlv_descricao_sta,
				"dlv_indicador_sta" => $dlv_indicador_sta,
				"dlv_ordem_sta" => $dlv_ordem_sta,
				"dlv_ativo_sta" => $dlv_ativo_sta
			);
			
			if (!$dlv_id_sta) {
				$dlv_id_sta = $this->StatusModel->insert($status);
			} else {
				$dlv_id_sta = $this->StatusModel->update($status, $dlv_id_sta);
			}

			if (is_numeric($dlv_id_sta)) {
				$this->session->set_flashdata('sucesso', 'Status salvo com sucesso.');
				redirect('status');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_sta);	
				redirect('status');
			}
		} else {
			if (!$dlv_id_sta) {
				redirect('status/novo/');
			} else {
				redirect('status/editar/'.base64_encode($dlv_id_sta));
			}			
		}
	}
	
	public function apagar($dlv_id_sta) {
		if ($this->testarApagar(base64_decode($dlv_id_sta))) {
			$res = $this->StatusModel->delete(base64_decode($dlv_id_sta));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Status apagado com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar status.');				
			}
		}
		
		redirect('status');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_STATUS'] = site_url('status');
		$dados['ACAO_FORM']         = site_url('status/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->StatusModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_ID_STA"        => $registro->dlv_id_sta,
				"DLV_DESCRICAO_STA" => $registro->dlv_descricao_sta,
				"DLV_ORDEM_STA" => $registro->dlv_ordem_sta,
				"DLV_ATIVO_STA" => ($registro->dlv_ativo_sta == 1)?'checked':'',
				"EDITAR_STATUS"   => site_url('status/editar/'.base64_encode($registro->dlv_id_sta)),
				"APAGAR_STATUS"   => "abrirConfirmacao('".base64_encode($registro->dlv_id_sta)."')"
			);
		}
	}
	
	private function carregarStatus($dlv_id_sta, &$dados) {
		$resultado = $this->StatusModel->getStatus($dlv_id_sta);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
			$dados['dlv_ativo_sta']               = ($resultado->dlv_ativo_sta == 1)?'checked':'';
			$dados['dlv_indicador_sta_0']          = ($resultado->dlv_indicador_sta == 0)?'selected':'';
			$dados['dlv_indicador_sta_1']          = ($resultado->dlv_indicador_sta == 1)?'selected':'';
			$dados['dlv_indicador_sta_2']          = ($resultado->dlv_indicador_sta == 2)?'selected':'';
			$dados['dlv_indicador_sta_3']          = ($resultado->dlv_indicador_sta == 3)?'selected':'';
					
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $dlv_descricao_sta;
		global $dlv_descricao_sta;
		global $dlv_ativo_sta;
		global $dlv_indicador_sta;
		global $dlv_ordem_sta;
		
		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($dlv_descricao_sta)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_DLV_DESCRICAO_STA', 'has-error');				
		}
		
		if (empty($dlv_ordem_sta)) {
			$erros    = TRUE;
			$mensagem .= "- Ordem não preenchida.\n";
			$this->session->set_flashdata('ERRO_DLV_ORDEM_STA', 'has-error');
		} else 	if ($this->StatusModel->getStatusOrdem($dlv_ordem_sta)) {
			$erros    = TRUE;
			$mensagem .= "- Esta ordem está cadastrada em outro status.\n";
			$this->session->set_flashdata('ERRO_DLV_ORDEM_STA', 'has-error');
		}
		
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_STA', TRUE);				
			
			
			$this->session->set_flashdata('dlv_descricao_sta', $dlv_descricao_sta);				
			$this->session->set_flashdata('dlv_indicador_sta', $dlv_indicador_sta);
			$this->session->set_flashdata('dlv_ativo_sta', $dlv_ativo_sta);
			$this->session->set_flashdata('dlv_ordem_sta', $dlv_ordem_sta);
		}
		
		return !$erros;
	}
	
	private function testarApagar($dlv_id_sta) {
		$erros    = FALSE;
		$mensagem = null;
	
	/* 	$resultado = $this->EmpresaStatusModel->getEmpresas($dlv_id_sta);
	
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais empresas com este segmento.\n";
		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o segmento:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
	 */	return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_STA           = $this->session->flashdata('ERRO_DLV_STA');
		$ERRO_DLV_DESCRICAO_STA = $this->session->flashdata('ERRO_DLV_DESCRICAO_STA');
		$ERRO_DLV_ORDEM_STA = $this->session->flashdata('ERRO_DLV_ORDEM_STA');
		$ERRO_DLV_INDICADOR_STA = $this->session->flashdata('ERRO_DLV_INDICADOR_STA');
		$ERRO_DLV_ATIVO_STA = $this->session->flashdata('ERRO_DLV_ATIVO_STA');
		
		$dlv_descricao_sta      = $this->session->flashdata('dlv_descricao_sta');
		$dlv_ordem_sta      = $this->session->flashdata('dlv_ordem_sta');
		$dlv_indicador_sta      = $this->session->flashdata('dlv_indicador_sta');
		$dlv_ativo_sta      = $this->session->flashdata('dlv_ativo_sta');
		
		if ($ERRO_DLV_STA) {
			$dados['dlv_descricao_sta']      = $dlv_descricao_sta;
			$dados['dlv_ativo_sta']      = $dlv_ativo_sta;
			$dados['dlv_ordem_sta']      = $dlv_ordem_sta;
			
			
			$dados['ERRO_DLV_DESCRICAO_STA'] = $ERRO_DLV_DESCRICAO_STA;
			$dados['dlv_indicador_sta_0'] = ($dlv_indicador_sta == '0')?'selected':'';
			$dados['dlv_indicador_sta_1'] = ($dlv_indicador_sta == '1')?'selected':'';
			$dados['dlv_indicador_sta_2'] = ($dlv_indicador_sta == '2')?'selected':'';
			$dados['dlv_indicador_sta_3'] = ($dlv_indicador_sta == '3')?'selected':'';
			$dados['ERRO_DLV_ATIVO_STA'] = ($dlv_ativo_sta == 1)?'checked':'';
			$dados['ERRO_DLV_ORDEM_STA'] = $ERRO_DLV_ORDEM_STA;
		}
	}
}