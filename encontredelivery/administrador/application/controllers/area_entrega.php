<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Area_Entrega extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_altarea_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Endereco_Model', 'EnderecoModel');
		$this->load->model('Area_Entrega_Model', 'AreaEntregaModel');
		$this->load->model('Area_Nao_Entrega_Model', 'AreaNaoEntregaModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']    = site_url('area_entrega');
		$dados['BLC_DADOS']    = array();
		$dados['ACAO_FORM']    = site_url('area_entrega/adicionar');
		$dados['URL_ENDERECO'] = site_url('endereco/retornar_cidades/');
		
		
		$dados['dlv_id_aen'] 	 = '';
		$dados['glo_id_est']  	 = '';
		$dados['dlv_glocid_aen'] = '';
		
		$dados['DIS_DLV_GLOCID_AEN'] = 'DISABLED';
		$dados['BLC_CIDADE']         = array();
		
		$this->carregarAreaEntregaEmpresa($dados);
		$this->carregarDadosFlash($dados);
		$this->carregarEstados($dados);
		$this->carregarCidades($dados);
		
		$this->parser->parse('area_entrega_cadastro_consulta', $dados);
	}
	
	public function adicionar() {
		global $dlv_id_aen;
		global $glo_id_est;
		global $dlv_glocid_aen;
		
		$dlv_id_aen     = $this->input->post('dlv_id_aen');			
		$glo_id_est     = $this->input->post('glo_id_est');		
		$dlv_glocid_aen = $this->input->post('dlv_glocid_aen');
		
		if ($this->testarDados()) {
			$area_entrega = array(
				"dlv_dlvemp_aen"      => $this->session->userdata('dlv_id_emp'),
				"dlv_glocid_aen"      => $dlv_glocid_aen,
				"dlv_dlvusumod_aen"   => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_aen" => date('Y-m-d H:i:s')							
			);
			
			if (!$dlv_id_aen) {	
				$dlv_id_aen = $this->AreaEntregaModel->insert($area_entrega);
			} else {
				$dlv_id_aen = $this->AreaEntregaModel->update($area_entrega, $dlv_id_aen);
			}

			if (is_numeric($dlv_id_aen)) {
				$this->session->set_flashdata('sucesso', 'Área de entrega adicionada com sucesso.');
				redirect('area_entrega');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_aen);	
				redirect('area_entrega');
			}
		} else {
			redirect('area_entrega');			
		}
	}
	
	public function remover($dlv_id_aen) {
		$res = $this->AreaEntregaModel->delete(base64_decode($dlv_id_aen));

		if ($res) {
			$this->session->set_flashdata('sucesso', 'Área de entrega removida com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível remover área de entrega.');				
		}
		
		redirect('area_entrega');
	}
	
	private function carregarAreaEntregaEmpresa(&$dados) {
		$resultado = $this->AreaEntregaModel->getAreaEntregaEmpresa($this->session->userdata('dlv_id_emp'));
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"GLO_NOME_CID"        => $registro->glo_nome_cid,
				"GLO_NOME_EST"        => $registro->glo_nome_est,
				"AREA_NAO_ENTREGA"    => site_url('area_nao_entrega/index/'.base64_encode($registro->dlv_id_aen).'/'.base64_encode($registro->glo_id_cid)),
				"TAXA_BAIRRO"         => site_url('taxa_bairro/index/'.base64_encode($registro->dlv_id_aen).'/'.base64_encode($registro->glo_id_cid)),
				"APAGAR_AREA_ENTREGA" => "abrirConfirmacao('".base64_encode($registro->dlv_id_aen)."')"
			);
		}
	}
	
	private function carregarEstados(&$dados) {
		$resultado = $this->EnderecoModel->getEstados();
		
		$dados['BLC_ESTADO'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_ESTADO'][] = array(
					"GLO_ID_EST"     => $registro->glo_id_est,
					"GLO_NOME_EST"   => $registro->glo_nome_est,
					"SEL_GLO_ID_EST" => ($dados['glo_id_est'] == $registro->glo_id_est)?'selected':''
			);
		}
	}
	
	private function carregarCidades(&$dados) {
		$glo_gloest_cid = $dados['glo_id_est'];
		
		$dados['BLC_CIDADE'] = array();
		
		if (!empty($glo_gloest_cid)) {
			$resultado = $this->EnderecoModel->getCidades($glo_gloest_cid);
			
			if ($resultado) {
				$dados['DIS_DLV_GLOCID_AEN'] = '';
			}
		
			foreach ($resultado as $registro) {
				$dados['BLC_CIDADE'][] = array(
						"GLO_ID_CID"     => $registro->glo_id_cid,
						"GLO_NOME_CID"   => $registro->glo_nome_cid,
						"SEL_GLO_ID_CID" => ($dados['dlv_glocid_aen'] == $registro->glo_id_cid)?'selected':''
				);
			}
		}
	}
	
	
	private function testarDados() {
		global $dlv_id_aen;
		global $glo_id_est;
		global $dlv_glocid_aen;
								
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($glo_id_est)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione uma estado.\n";
			$this->session->set_flashdata('ERRO_GLO_ID_EST', 'has-error');
		} 
		
		if (empty($dlv_glocid_aen)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione uma cidade.\n";
			$this->session->set_flashdata('ERRO_DLV_GLOCID_AEN', 'has-error');
		} else {
			$resultado = $this->AreaEntregaModel->getEmpresaAreaEntrega($dlv_glocid_aen, $this->session->userdata('dlv_id_emp'));
			
			if ($resultado) {
				$erros    = TRUE;
				$mensagem .= "- Cidade já adicionada.\n";
				$this->session->set_flashdata('ERRO_DLV_GLOCID_AEN', 'has-error');
			}
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_AEN', TRUE);				
			$this->session->set_flashdata('glo_id_est', $glo_id_est);				
			$this->session->set_flashdata('dlv_glocid_aen', $dlv_glocid_aen);				
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_AEN         = $this->session->flashdata('ERRO_DLV_AEN');
		$ERRO_GLO_ID_EST      = $this->session->flashdata('ERRO_GLO_ID_EST');
		$ERRO_DLV_GLOCID_AEN  = $this->session->flashdata('ERRO_DLV_GLOCID_AEN');
		
		$glo_id_est           = $this->session->flashdata('glo_id_est');
		$dlv_glocid_aen       = $this->session->flashdata('dlv_glocid_aen');
	
		if ($ERRO_DLV_AEN) {
			$dados['glo_id_est']          = $glo_id_est;
			$dados['dlv_glocid_aen']      = $dlv_glocid_aen;
			$dados['ERRO_GLO_ID_EST']     = $ERRO_GLO_ID_EST;
			$dados['ERRO_DLV_GLOCID_AEN'] = $ERRO_DLV_GLOCID_AEN;
		}
	}
}