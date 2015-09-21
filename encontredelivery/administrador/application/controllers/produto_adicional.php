<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto_Adicional extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_cadproduto_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Produto_Model', 'ProdutoModel');
		$this->load->model('Adicional_Model', 'AdicionalModel');
		$this->load->model('Produto_Adicional_Model', 'ProdutoAdicionalModel');
	}
	
	public function index($dlv_dlvpro_pxa) {
		$dados = array();
		
		$dados['ATUALIZAR']                    = site_url('produto_adicional/index/'.$dlv_dlvpro_pxa);
		$dados['BLC_DADOS']                    = array();
		$dados['ACAO_FORM']                    = site_url('produto_adicional/adicionar');
		$dados['URL_APAGAR_PRODUTO_ADICIONAL'] = site_url('produto_adicional/remover/');
		$dados['URL_VOLTAR']                   = site_url('produto/');		
		
		$dados['dlv_id_pxa']        = '';
		$dados['dlv_dlvpro_pxa']    = base64_decode($dlv_dlvpro_pxa);
		$dados['dlv_dlvadi_pxa']    = '';
		
		$this->carregarProduto($dados);
		$this->carregarProdutoAdicional($dados);
		$this->carregarDadosFlash($dados);	
		$this->carregarAdicionals($dados);
		
		$this->parser->parse('produto_adicional_cadastro_consulta', $dados);
	}
	
	public function adicionar() {
		global $dlv_id_pxa;
		global $dlv_dlvpro_pxa;
		global $dlv_dlvadi_pxa;
		
		$dlv_id_pxa        = $this->input->post('dlv_id_pxa');			
		$dlv_dlvpro_pxa    = $this->input->post('dlv_dlvpro_pxa');
		$dlv_dlvadi_pxa    = $this->input->post('dlv_dlvadi_pxa');
		
		if ($this->testarDados()) {
			$produto_adicional = array(
				"dlv_dlvpro_pxa"	  => $dlv_dlvpro_pxa,
				"dlv_dlvadi_pxa"      => $dlv_dlvadi_pxa,
				"dlv_dlvusumod_pxa"   => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_pxa" => date('Y-m-d H:i:s')							
			);
			
			if (!$dlv_id_pxa) {	
				$dlv_id_pxa = $this->ProdutoAdicionalModel->insert($produto_adicional);
			} else {
				$dlv_id_pxa = $this->ProdutoAdicionalModel->update($produto_adicional, $dlv_id_pxa);
			}

			if (is_numeric($dlv_id_pxa)) {
				$this->session->set_flashdata('sucesso', 'Adicional do produto adicionado/editado com sucesso.');
				redirect('produto_adicional/index/'.base64_encode($dlv_dlvpro_pxa));
			} else {
				$this->session->set_flashdata('erro', $dlv_id_pxa);	
				redirect('produto_adicional/index/'.base64_encode($dlv_dlvpro_pxa));
			}
		} else {
			redirect('produto_adicional/index/'.base64_encode($dlv_dlvpro_pxa));
		}
	}
	
	public function remover($dlv_id_pxa) {
		$resultado = $this->ProdutoAdicionalModel->get(base64_decode($dlv_id_pxa));
		$res       = $this->ProdutoAdicionalModel->delete(base64_decode($dlv_id_pxa));

		if ($res) {
			$this->session->set_flashdata('sucesso', 'Adicional do produto removido com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível remover adicional da produto.');		
					
		}
		
		redirect('produto_adicional/index/'.base64_encode($resultado->dlv_dlvpro_pxa));
	}
	
	private function carregarProdutoAdicional(&$dados) {
		$resultado = $this->ProdutoAdicionalModel->getProdutoAdicionalEmpresa($dados['dlv_dlvpro_pxa']);
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_DESCRICAO_ADI"          => $registro->dlv_descricao_adi,
				"APAGAR_PRODUTO_ADICIONAL" => "abrirConfirmacao('".base64_encode($registro->dlv_id_pxa)."')"
			);
		}
	}
	
	private function carregarProduto(&$dados) {
		$resultado = $this->ProdutoModel->get($dados['dlv_dlvpro_pxa']);
		
		if ($resultado) {
			$dados['DLV_DESCRICAO_PRO'] = $resultado->dlv_descricao_pro;
		}
	}
	
	private function carregarAdicionals(&$dados) {
		$resultado = $this->AdicionalModel->getAdicionalEmpresaAtivo($this->session->userdata('dlv_id_emp'));
		
		$dados['BLC_ADICIONAIS'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_ADICIONAIS'][] = array(
					"DLV_ID_ADI"        => $registro->dlv_id_adi,
					"DLV_DESCRICAO_ADI" => $registro->dlv_descricao_adi,
					"SEL_DLV_ID_ADI"    => ($dados['dlv_dlvadi_pxa'] == $registro->dlv_id_adi)?'selected':''
			);
		}
	}
	
	private function testarDados() {
		global $dlv_id_pxa;
		global $dlv_dlvpro_pxa;
		global $dlv_dlvadi_pxa;
		
										
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($dlv_dlvadi_pxa)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione um adicional.\n";
			$this->session->set_flashdata('ERRO_DLV_DLVADI_PXA', 'has-error');
		} else {
			$resultado = $this->AdicionalModel->get($dlv_dlvadi_pxa);
			
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Adicional não cadastrado.\n";
				$this->session->set_flashdata('ERRO_DLV_DLVADI_PXA', 'has-error');
			} else {
				$resultado = $this->ProdutoAdicionalModel->getEmpresaAdicionalProduto($dlv_dlvpro_pxa, $dlv_dlvadi_pxa);
				
				if ($resultado) {
					$erros    = TRUE;
					$mensagem .= "- Adicional já adicionado.\n";
					$this->session->set_flashdata('ERRO_DLV_DLVADI_PXA', 'has-error');
				}				
			}
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_PXA', TRUE);				
			$this->session->set_flashdata('dlv_dlvadi_pxa', $dlv_dlvadi_pxa);				
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_PXA         = $this->session->flashdata('ERRO_DLV_PXA');
		$ERRO_DLV_DLVADI_PXA  = $this->session->flashdata('ERRO_DLV_DLVADI_PXA');
		
		$dlv_dlvadi_pxa       = $this->session->flashdata('dlv_dlvadi_pxa');
		
		if ($ERRO_DLV_PXA) {
			$dados['dlv_dlvadi_pxa']      = $dlv_dlvadi_pxa;
				
			$dados['ERRO_DLV_DLVADI_PXA'] = $ERRO_DLV_DLVADI_PXA;
		}
	}
	
}