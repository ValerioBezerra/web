<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Area_Nao_Entrega extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_altarea_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Endereco_Model', 'EnderecoModel');
		$this->load->model('Area_Nao_Entrega_Model', 'AreaNaoEntregaModel');
	}
	
	public function index($dlv_dlvaen_ane, $glo_id_cid) {
		$dados = array();
		
		$dados['ATUALIZAR']                   = site_url('area_nao_entrega/index/'.$dlv_dlvaen_ane.'/'.$glo_id_cid);
		$dados['BLC_DADOS']                   = array();
		$dados['ACAO_FORM']                   = site_url('area_nao_entrega/adicionar');
		$dados['URL_APAGAR_AREA_NAO_ENTREGA'] = site_url('area_nao_entrega/remover/');
		$dados['URL_VOLTAR']                  = site_url('area_entrega/');
		
		
		$dados['dlv_id_ane']     = '';
		$dados['dlv_dlvaen_ane'] = base64_decode($dlv_dlvaen_ane);
		$dados['glo_id_cid']     = base64_decode($glo_id_cid);
		$dados['dlv_globai_ane'] = '';
		
		$this->carregarCidade($dados);
		$this->carregarAreaNaoEntregaEmpresa($dados);
		$this->carregarDadosFlash($dados);	
		$this->carregarBairros($dados);
		
		$this->parser->parse('area_nao_entrega_cadastro_consulta', $dados);
	}
	
	public function adicionar() {
		global $dlv_id_ane;
		global $dlv_dlvaen_ane;
		global $glo_id_cid;
		global $dlv_globai_ane;
		
		$dlv_id_ane     = $this->input->post('dlv_id_ane');			
		$dlv_dlvaen_ane = $this->input->post('dlv_dlvaen_ane');
		$glo_id_cid     = $this->input->post('glo_id_cid');
		$dlv_globai_ane = $this->input->post('dlv_globai_ane');
		
		if ($this->testarDados()) {
			$area_nao_entrega = array(
				"dlv_dlvemp_ane"      => $this->session->userdata('dlv_id_emp'),
				"dlv_dlvaen_ane"	  => $dlv_dlvaen_ane,
				"dlv_globai_ane"      => $dlv_globai_ane,
				"dlv_dlvusumod_ane"   => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_ane" => date('Y-m-d H:i:s')							
			);
			
			if (!$dlv_id_ane) {	
				$dlv_id_ane = $this->AreaNaoEntregaModel->insert($area_nao_entrega);
			} else {
				$dlv_id_ane = $this->AreaNaoEntregaModel->update($area_nao_entrega, $dlv_id_ane);
			}

			if (is_numeric($dlv_id_ane)) {
				$this->session->set_flashdata('sucesso', 'Área de não entrega adicionada com sucesso.');
				redirect('area_nao_entrega/index/'.base64_encode($dlv_dlvaen_ane).'/'.base64_encode($glo_id_cid));
			} else {
				$this->session->set_flashdata('erro', $dlv_id_ane);	
				redirect('area_nao_entrega/index/'.base64_encode($dlv_dlvaen_ane).'/'.base64_encode($glo_id_cid));
			}
		} else {
			redirect('area_nao_entrega/index/'.base64_encode($dlv_dlvaen_ane).'/'.base64_encode($glo_id_cid));
		}
	}
	
	public function remover($dlv_id_ane) {
		$resultado = $this->AreaNaoEntregaModel->get(base64_decode($dlv_id_ane));
		$res       = $this->AreaNaoEntregaModel->delete(base64_decode($dlv_id_ane));

		if ($res) {
			$this->session->set_flashdata('sucesso', 'Área de não entrega removida com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível remover área de não entrega.');		
					
		}
		
		redirect('area_nao_entrega/index/'.base64_encode($resultado->dlv_dlvaen_ane).'/'.base64_encode($resultado->glo_glocid_bai));
	}
	
	private function carregarAreaNaoEntregaEmpresa(&$dados) {
		$resultado = $this->AreaNaoEntregaModel->getAreaEntregaEmpresa($this->session->userdata('dlv_id_emp'), $dados['glo_id_cid']);
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"GLO_NOME_BAI"        => $registro->glo_nome_bai,
				"APAGAR_AREA_NAO_ENTREGA" => "abrirConfirmacao('".base64_encode($registro->dlv_id_ane)."')"
			);
		}
	}
	
	private function carregarCidade(&$dados) {
		$resultado = $this->EnderecoModel->getCidade($dados['glo_id_cid']);
		
		if ($resultado) {
			$dados['GLO_NOME_CID'] = $resultado->glo_nome_cid;
			$dados['GLO_UF_EST']   = $resultado->glo_uf_est;
		}
	}
	
	private function carregarBairros(&$dados) {
		$resultado = $this->EnderecoModel->getBairros($dados['glo_id_cid']);
		
		$dados['BLC_BAIRROS'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_BAIRROS'][] = array(
					"GLO_ID_BAI"     => $registro->glo_id_bai,
					"GLO_NOME_BAI"   => $registro->glo_nome_bai,
					"SEL_GLO_ID_BAI" => ($dados['dlv_globai_ane'] == $registro->glo_id_bai)?'selected':''
			);
		}
	}
	
	private function testarDados() {
		global $dlv_id_ane;
		global $dlv_globai_ane;
										
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($dlv_globai_ane)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione um bairro.\n";
			$this->session->set_flashdata('ERRO_DLV_GLOBAI_ANE', 'has-error');
		} else {
			$resultado = $this->AreaNaoEntregaModel->getEmpresaAreaNaoEntrega($dlv_globai_ane, $this->session->userdata('dlv_id_emp'));
			
			if ($resultado) {
				$erros    = TRUE;
				$mensagem .= "- Bairro já adicionado.\n";
				$this->session->set_flashdata('ERRO_DLV_GLOBAI_ANE', 'has-error');
			}
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_ANE', TRUE);				
			$this->session->set_flashdata('dlv_globai_ane', $dlv_globai_ane);				
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_ANE         = $this->session->flashdata('ERRO_DLV_ANE');
		$ERRO_DLV_GLOBAI_ANE  = $this->session->flashdata('ERRO_DLV_GLOBAI_ANE');
		
		$dlv_globai_ane       = $this->session->flashdata('dlv_globai_ane');
	
		if ($ERRO_DLV_ANE) {
			$dados['dlv_globai_ane']      = $dlv_globai_ane;
			
			$dados['ERRO_DLV_GLOBAI_ANE'] = $ERRO_DLV_GLOBAI_ANE;
		}
	}
}