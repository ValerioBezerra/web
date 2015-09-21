<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto_Produto extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_cadproduto_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Produto_Model', 'ProdutoModel');
		$this->load->model('Produto_Produto_Model', 'ProdutoProdutoModel');
	}
	
	public function index($dlv_dlvproprincipal_pxp) {
		$dados = array();
		
		$dados['ATUALIZAR']                  = site_url('produto_produto/index/'.$dlv_dlvproprincipal_pxp);
		$dados['BLC_DADOS']                  = array();
		$dados['ACAO_FORM']                  = site_url('produto_produto/adicionar');
		$dados['URL_APAGAR_PRODUTO_PRODUTO'] = site_url('produto_produto/remover/');
		$dados['URL_VOLTAR']                 = site_url('produto/');		
		
		$dados['dlv_id_pxp']                 = '';
		$dados['dlv_dlvproprincipal_pxp']    = base64_decode($dlv_dlvproprincipal_pxp);
		$dados['dlv_dlvpro_pxp']             = '';
		$dados['dlv_recebeqtd_pxp']          = '';
		
		$this->carregarProduto($dados);
		$this->carregarProdutoProduto($dados);
		$this->carregarDadosFlash($dados);	
		$this->carregarProdutos($dados);
		
		$this->parser->parse('produto_produto_cadastro_consulta', $dados);
	}
	
	public function adicionar() {
		global $dlv_id_pxp;
		global $dlv_dlvproprincipal_pxp;
		global $dlv_dlvpro_pxp;
		
		$dlv_id_pxp              = $this->input->post('dlv_id_pxp');			
		$dlv_dlvproprincipal_pxp = $this->input->post('dlv_dlvproprincipal_pxp');
		$dlv_dlvpro_pxp          = $this->input->post('dlv_dlvpro_pxp');
		
		if ($this->testarDados()) {
			$produto_produto = array(
				"dlv_dlvproprincipal_pxp" => $dlv_dlvproprincipal_pxp,
				"dlv_dlvpro_pxp"   	      => $dlv_dlvpro_pxp,	
				"dlv_dlvusumod_pxp"       => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_pxp"     => date('Y-m-d H:i:s')							
			);
			
			if (!$dlv_id_pxp) {	
				$dlv_id_pxp = $this->ProdutoProdutoModel->insert($produto_produto);
			} 

			if (is_numeric($dlv_id_pxp)) {
				$this->session->set_flashdata('sucesso', 'Sabor/Complemento do produto adicionado/editado com sucesso.');
				redirect('produto_produto/index/'.base64_encode($dlv_dlvproprincipal_pxp));
			} else {
				$this->session->set_flashdata('erro', $dlv_id_pxp);	
				redirect('produto_produto/index/'.base64_encode($dlv_dlvproprincipal_pxp));
			}
		} else {
			redirect('produto_produto/index/'.base64_encode($dlv_dlvproprincipal_pxp));
		}
	}
	
	public function remover($dlv_id_pxp) {
		$resultado = $this->ProdutoProdutoModel->get(base64_decode($dlv_id_pxp));
		$res       = $this->ProdutoProdutoModel->delete(base64_decode($dlv_id_pxp));

		if ($res) {
			$this->session->set_flashdata('sucesso', 'Sabor/Complemento do produto removido com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível remover sabor/complemento do produto.');		
					
		}
		
		redirect('produto_produto/index/'.base64_encode($resultado->dlv_dlvproprincipal_pxp));
	}
	
	private function carregarProdutoProduto(&$dados) {
		$resultado = $this->ProdutoProdutoModel->getProdutoProdutoEmpresa($dados['dlv_dlvproprincipal_pxp']);
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_DESCRICAO_PRO"      => $registro->dlv_descricao_pro,
				"APAGAR_PRODUTO_PRODUTO" => "abrirConfirmacao('".base64_encode($registro->dlv_id_pxp)."')"
			);
		}
	}
	
	private function carregarProduto(&$dados) {
		$resultado = $this->ProdutoModel->get($dados['dlv_dlvproprincipal_pxp']);
		
		if ($resultado) {
			$dados['DLV_DESCRICAO_PRO_PAI'] = $resultado->dlv_descricao_pro;
			$dados['dlv_dlvcat_pro']        = $resultado->dlv_dlvcat_pro;
		}
	}
	
	private function carregarProdutos(&$dados) {
		$resultado = $this->ProdutoModel->getProdutoEmpresaCategoriaAtivo($this->session->userdata('dlv_id_emp'), $dados['dlv_dlvcat_pro']);
		
		$dados['BLC_PRODUTOS'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_PRODUTOS'][] = array(
					"DLV_ID_PRO"        => $registro->dlv_id_pro,
					"DLV_DESCRICAO_PRO" => $registro->dlv_descricao_pro,
					"SEL_DLV_ID_PRO"    => ($dados['dlv_dlvpro_pxp'] == $registro->dlv_id_pro)?'selected':'',
					"DISABLED"          => '' 
			);
		}
		
		$dados['BLC_PRODUTOS'][] = array(
				"DLV_ID_PRO"        => '',
				"DLV_DESCRICAO_PRO" => '----------------------------------------------------------',
				"SEL_DLV_ID_PRO"    => '',
				"DISABLED"          => 'disabled'
		);
		
		$resultado = $this->ProdutoModel->getProdutoEscolhidoEmpresaAtivo($this->session->userdata('dlv_id_emp'), $dados['dlv_dlvcat_pro']);
		foreach ($resultado as $registro) {
			$dados['BLC_PRODUTOS'][] = array(
					"DLV_ID_PRO"        => $registro->dlv_id_pro,
					"DLV_DESCRICAO_PRO" => $registro->dlv_descricao_pro,
					"SEL_DLV_ID_PRO"    => ($dados['dlv_dlvpro_pxp'] == $registro->dlv_id_pro)?'selected':'',
					"DISABLED"          => ''
			);
		}
	}
	
	private function testarDados() {
		global $dlv_id_pxp;
		global $dlv_dlvproprincipal_pxp;;
		global $dlv_dlvpro_pxp;
										
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($dlv_dlvpro_pxp)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione um produto.\n";
			$this->session->set_flashdata('ERRO_DLV_DLVPRO_PXP', 'has-error');
		} else {
			$resultado = $this->ProdutoModel->get($dlv_dlvpro_pxp);
			
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Produto não cadastrado.\n";
				$this->session->set_flashdata('ERRO_DLV_DLVPRO_PXP', 'has-error');
			}  else {
				$resultado = $this->ProdutoProdutoModel->getEmpresaProdutoProduto($dlv_dlvproprincipal_pxp, $dlv_dlvpro_pxp);
				
				if ($resultado) {
					$erros    = TRUE;
					$mensagem .= "- Produto já adicionado.\n";
					$this->session->set_flashdata('ERRO_DLV_DLVPRO_PXP', 'has-error');
				}
			}
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_PXP', TRUE);				
			$this->session->set_flashdata('dlv_dlvpro_pxp', $dlv_dlvpro_pxp);				
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_PXP         = $this->session->flashdata('ERRO_DLV_PXP');
		$ERRO_DLV_DLVPRO_PXP  = $this->session->flashdata('ERRO_DLV_DLVPRO_PXP');
		
		$dlv_dlvpro_pxp       = $this->session->flashdata('dlv_dlvpro_pxp');
		
		if ($ERRO_DLV_PXP) {
			$dados['dlv_dlvpro_pxp'] = $dlv_dlvpro_pxp;
			
			$dados['ERRO_DLV_DLVPRO_PXP'] = $ERRO_DLV_DLVPRO_PXP;
		}
	}
	
}