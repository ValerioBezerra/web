<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Taxa_Bairro extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_altarea_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Endereco_Model', 'EnderecoModel');
		$this->load->model('Taxa_Bairro_Model', 'TaxaBairroModel');
	}
	
	public function index($dlv_dlvaen_txb, $glo_id_cid) {
		$dados = array();
		
		$dados['ATUALIZAR']              = site_url('taxa_bairro/index/'.$dlv_dlvaen_txb.'/'.$glo_id_cid);
		$dados['BLC_DADOS']              = array();
		$dados['ACAO_FORM']              = site_url('taxa_bairro/adicionar');
		$dados['URL_APAGAR_TAXA_BAIRRO'] = site_url('taxa_bairro/remover/');
		$dados['URL_TAXA_BAIRRO']        = site_url('json/json/retornar_taxa_bairro/');
		$dados['URL_VOLTAR']             = site_url('area_entrega/');
		
		
		$dados['dlv_id_txb']     	  = '';
		$dados['dlv_dlvaen_txb'] 	  = base64_decode($dlv_dlvaen_txb);
		$dados['glo_id_cid']          = base64_decode($glo_id_cid);
		$dados['dlv_globai_txb']      = '';
		$dados['dlv_taxaentrega_txb'] = 'R$ 0,00';
		
		
		$this->carregarCidade($dados);
		$this->carregarTaxaBairroEmpresa($dados);
		$this->carregarDadosFlash($dados);	
		$this->carregarBairros($dados);
		
		$this->parser->parse('taxa_bairro_cadastro_consulta', $dados);
	}
	
	public function adicionar() {
		global $dlv_id_txb;
		global $dlv_dlvaen_txb;
		global $glo_id_cid;
		global $dlv_globai_txb;
		global $dlv_taxaentrega_txb;
		
		$dlv_id_txb          = $this->input->post('dlv_id_txb');			
		$dlv_dlvaen_txb      = $this->input->post('dlv_dlvaen_txb');
		$glo_id_cid          = $this->input->post('glo_id_cid');
		$dlv_globai_txb      = $this->input->post('dlv_globai_txb');
		$dlv_taxaentrega_txb = $this->input->post('dlv_taxaentrega_txb');
		
		$dlv_taxaentrega_txb = str_replace("R$ ", null, $dlv_taxaentrega_txb);
		$dlv_taxaentrega_txb = str_replace(".", null, $dlv_taxaentrega_txb);
		$dlv_taxaentrega_txb = str_replace(",", ".", $dlv_taxaentrega_txb);
		
		if (empty($dlv_taxaentrega_txb)) {
			$dlv_taxaentrega_txb = 0;
		}
		
		
		if ($this->testarDados()) {
			$taxa_bairro = array(
				"dlv_dlvemp_txb"      => $this->session->userdata('dlv_id_emp'),
				"dlv_dlvaen_txb"	  => $dlv_dlvaen_txb,
				"dlv_globai_txb"      => $dlv_globai_txb,
				"dlv_taxaentrega_txb" => $dlv_taxaentrega_txb,
				"dlv_dlvusumod_txb"   => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_txb" => date('Y-m-d H:i:s')							
			);
			
			if (!$dlv_id_txb) {	
				$dlv_id_txb = $this->TaxaBairroModel->insert($taxa_bairro);
			} else {
				$dlv_id_txb = $this->TaxaBairroModel->update($taxa_bairro, $dlv_id_txb);
			}

			if (is_numeric($dlv_id_txb)) {
				$this->session->set_flashdata('sucesso', 'Taxa por bairro adicionada/editada com sucesso.');
				redirect('taxa_bairro/index/'.base64_encode($dlv_dlvaen_txb).'/'.base64_encode($glo_id_cid));
			} else {
				$this->session->set_flashdata('erro', $dlv_id_txb);	
				redirect('taxa_bairro/index/'.base64_encode($dlv_dlvaen_txb).'/'.base64_encode($glo_id_cid));
			}
		} else {
			redirect('taxa_bairro/index/'.base64_encode($dlv_dlvaen_txb).'/'.base64_encode($glo_id_cid));
		}
	}
	
	public function remover($dlv_id_txb) {
		$resultado = $this->TaxaBairroModel->get(base64_decode($dlv_id_txb));
		$res       = $this->TaxaBairroModel->delete(base64_decode($dlv_id_txb));

		if ($res) {
			$this->session->set_flashdata('sucesso', 'Taxa de entrega por bairro removida com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível remover taxa de entrega por bairro.');		
					
		}
		
		redirect('taxa_bairro/index/'.base64_encode($resultado->dlv_dlvaen_txb).'/'.base64_encode($resultado->glo_glocid_bai));
	}
	
	private function carregarTaxaBairroEmpresa(&$dados) {
		$resultado = $this->TaxaBairroModel->getAreaEntregaEmpresa($this->session->userdata('dlv_id_emp'), $dados['glo_id_cid']);
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"GLO_NOME_BAI"        => $registro->glo_nome_bai,
				"DLV_TAXAENTREGA_TXB" => 'R$ '.number_format($registro->dlv_taxaentrega_txb,  2, ',', '.'),
				"APAGAR_TAXA_BAIRRO"  => "abrirConfirmacao('".base64_encode($registro->dlv_id_txb)."')"
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
					"SEL_GLO_ID_BAI" => ($dados['dlv_globai_txb'] == $registro->glo_id_bai)?'selected':''
			);
		}
	}
	
	private function testarDados() {
		global $dlv_id_txb;
		global $dlv_globai_txb;
		global $dlv_taxaentrega_txb;
		
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($dlv_globai_txb)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione um bairro.\n";
			$this->session->set_flashdata('ERRO_DLV_GLOBAI_TXB', 'has-error');
		} 
				
		if ($dlv_taxaentrega_txb == 0) {
			$erros    = TRUE;
			$mensagem .= "- Taxa não preenchida.\n";
			$this->session->set_flashdata('ERRO_DLV_TAXAENTREGA_TXB', 'has-error');
		}
		
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_TXB', TRUE);				
			$this->session->set_flashdata('dlv_globai_txb', $dlv_globai_txb);				
			$this->session->set_flashdata('dlv_taxaentrega_txb', $dlv_taxaentrega_txb);				
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_TXB             = $this->session->flashdata('ERRO_DLV_TXB');
		$ERRO_DLV_GLOBAI_TXB      = $this->session->flashdata('ERRO_DLV_GLOBAI_TXB');
		$ERRO_DLV_TAXAENTREGA_TXB = $this->session->flashdata('ERRO_DLV_TAXAENTREGA_TXB');
		
		$dlv_globai_txb      = $this->session->flashdata('dlv_globai_txb');
		$dlv_taxaentrega_txb = $this->session->flashdata('dlv_taxaentrega_txb');
	
		if ($ERRO_DLV_TXB) {
			$dados['dlv_globai_txb']      = $dlv_globai_txb;
			$dados['dlv_taxaentrega_txb'] = 'R$ '.number_format($dlv_taxaentrega_txb,  2, ',', '.');
				
			$dados['ERRO_DLV_GLOBAI_TXB']      = $ERRO_DLV_GLOBAI_TXB;
			$dados['ERRO_DLV_TAXAENTREGA_TXB'] = $ERRO_DLV_TAXAENTREGA_TXB;
		}
	}
}