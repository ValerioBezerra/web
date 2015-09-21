<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto_Tamanho extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_cadproduto_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Produto_Model', 'ProdutoModel');
		$this->load->model('Tamanho_Model', 'TamanhoModel');
		$this->load->model('Produto_Tamanho_Model', 'ProdutoTamanhoModel');
	}
	
	public function index($dlv_dlvpro_pxt) {
		$dados = array();
		
		$dados['ATUALIZAR']                    = site_url('produto_tamanho/index/'.$dlv_dlvpro_pxt);
		$dados['BLC_DADOS']                    = array();
		$dados['ACAO_FORM']                    = site_url('produto_tamanho/adicionar');
		$dados['URL_APAGAR_PRODUTO_TAMANHO']   = site_url('produto_tamanho/remover/');
		$dados['URL_PRODUTO_TAMANHO']          = site_url('json/json/retornar_tamanho_produto/');
		$dados['URL_VOLTAR']                   = site_url('produto/');		
		
		$dados['dlv_id_pxt']         		  = '';
		$dados['dlv_dlvpro_pxt']    		  = base64_decode($dlv_dlvpro_pxt);
		$dados['dlv_dlvtam_pxt']      		  = '';
		$dados['dlv_preco_pxt']               = 'R$ 0,00';
		$dados['dlv_promocao_pro_0']          = 'selected';
		$dados['dlv_promocao_pro_1']          = '';
		$dados['dlv_precopromocional_pxt']    = 'R$ 0,00';
		
		$dados['DIS_DLV_PRECOPROMOCIONAL_PXT']   = 'readonly';
		
		$this->carregarProduto($dados);
		$this->carregarProdutoTamanho($dados);
		$this->carregarDadosFlash($dados);
		$this->carregarTamanhos($dados);
		
		$this->parser->parse('produto_tamanho_cadastro_consulta', $dados);
	}
	
	public function adicionar() {
		global $dlv_id_pxt;
		global $dlv_dlvpro_pxt;
		global $dlv_dlvtam_pxt;
		global $dlv_preco_pxt;
		global $dlv_promocao_pxt;
		global $dlv_precopromocional_pxt;
		
		$dlv_id_pxt                   = $this->input->post('dlv_id_pxt');			
		$dlv_dlvpro_pxt               = $this->input->post('dlv_dlvpro_pxt');
		$dlv_dlvtam_pxt               = $this->input->post('dlv_dlvtam_pxt');
		$dlv_preco_pxt				  = $this->input->post('dlv_preco_pxt');
		$dlv_promocao_pxt			  = $this->input->post('dlv_promocao_pxt');
		$dlv_precopromocional_pxt 	  = $this->input->post('dlv_precopromocional_pxt');
		
		
		$dlv_preco_pxt = str_replace("R$ ", null, $dlv_preco_pxt);
		$dlv_preco_pxt = str_replace(".", null, $dlv_preco_pxt);
		$dlv_preco_pxt = str_replace(",", ".", $dlv_preco_pxt);
		
		if (empty($dlv_precopromocional_pxt)) {
			$dlv_precopromocional_pxt = 0;
		}
		if (empty($dlv_preco_pxt)) {
			$dlv_preco_pxt = 0;
		}
		
		$dlv_precopromocional_pxt = str_replace("R$ ", null, $dlv_precopromocional_pxt);
		$dlv_precopromocional_pxt = str_replace(".", null, $dlv_precopromocional_pxt);
		$dlv_precopromocional_pxt = str_replace(",", ".", $dlv_precopromocional_pxt);

		
		if ($this->testarDados()) {
			$produto_tamanho = array(				
				"dlv_dlvpro_pxt"	       => $dlv_dlvpro_pxt,
				"dlv_dlvtam_pxt"           => $dlv_dlvtam_pxt,
				"dlv_preco_pxt"            => $dlv_preco_pxt,
				"dlv_promocao_pxt"         => $dlv_promocao_pxt,
				"dlv_precopromocional_pxt" => $dlv_precopromocional_pxt,
				"dlv_dlvusumod_pxt"   => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_pxt" => date('Y-m-d H:i:s')							
			);
			
			if (!$dlv_id_pxt) {	
				$dlv_id_pxt = $this->ProdutoTamanhoModel->insert($produto_tamanho);
			} else {
				$dlv_id_pxt = $this->ProdutoTamanhoModel->update($produto_tamanho, $dlv_id_pxt);
			}

			if (is_numeric($dlv_id_pxt)) {
				$this->session->set_flashdata('sucesso', 'Tamanho do produto adicionado/editado com sucesso.');
				redirect('produto_tamanho/index/'.base64_encode($dlv_dlvpro_pxt));
			} else {
				$this->session->set_flashdata('erro', $dlv_id_pxt);	
				redirect('produto_tamanho/index/'.base64_encode($dlv_dlvpro_pxt));
			}
		} else {
			redirect('produto_tamanho/index/'.base64_encode($dlv_dlvpro_pxt));
		}
	}
	
	public function remover($dlv_id_pxt) {
		$resultado = $this->ProdutoTamanhoModel->get(base64_decode($dlv_id_pxt));
		$res       = $this->ProdutoTamanhoModel->delete(base64_decode($dlv_id_pxt));

		if ($res) {
			$this->session->set_flashdata('sucesso', 'Tamanho do produto removido com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível remover tamanho do produto.');		
					
		}
		
		redirect('produto_tamanho/index/'.base64_encode($resultado->dlv_dlvpro_pxt));
	}
	
	private function carregarProdutoTamanho(&$dados) {
		$resultado = $this->ProdutoTamanhoModel->getProdutoTamanhoEmpresa($dados['dlv_dlvpro_pxt']);
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_DLVTAM_PXT"           => $registro->dlv_dlvtam_pxt,
				"DLV_DESCRICAO_TAM"        => $registro->dlv_descricao_tam,
				"DLV_PRECO_PXT"            => 'R$ '.number_format($registro->dlv_preco_pxt,  2, ',', '.'),
				"DLV_PROMOCAO_PXT"         => ($registro->dlv_promocao_pxt == 1)?'checked':'',
				"DLV_PRECOPROMOCIONAL_PXT" => 'R$ '.number_format($registro->dlv_precopromocional_pxt,  2, ',', '.'), 				
				"APAGAR_PRODUTO_TAMANHO" => "abrirConfirmacao('".base64_encode($registro->dlv_id_pxt)."')"
			);
		}
		
	}
	
	private function carregarProduto(&$dados) {
		$resultado = $this->ProdutoModel->get($dados['dlv_dlvpro_pxt']);
		
		if ($resultado) {
			$dados['DLV_DESCRICAO_PRO'] = $resultado->dlv_descricao_pro;
		}
	}
	
	private function carregarTamanhos(&$dados) {
		$resultado = $this->TamanhoModel->getTamanhoEmpresaAtivo($this->session->userdata('dlv_id_emp'));
		
		$dados['BLC_TAMANHOS'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_TAMANHOS'][] = array(
					"DLV_ID_TAM"        => $registro->dlv_id_tam,
					"DLV_DESCRICAO_TAM" => $registro->dlv_descricao_tam,
					"SEL_DLV_ID_TAM"    => ($dados['dlv_dlvtam_pxt'] == $registro->dlv_id_tam)?'selected':''
			);
		}
	}
	
	private function testarDados() {
		global $dlv_id_pxt;
		global $dlv_dlvpro_pxt;
		global $dlv_dlvtam_pxt;
		global $dlv_preco_pxt;
		global $dlv_promocao_pxt;
		global $dlv_precopromocional_pxt;
										
		$erros    = FALSE;
		$mensagem = null;
							
		if (empty($dlv_dlvtam_pxt)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione um tamanho.\n";
			$this->session->set_flashdata('ERRO_DLV_DLVTAM_PXT', 'has-error');
		} else {
			$resultado = $this->TamanhoModel->get($dlv_dlvtam_pxt);
			
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Tamanho não cadastrado.\n";
				$this->session->set_flashdata('ERRO_DLV_DLVTAM_PXT', 'has-error');
			} else {
				$resultado = $this->ProdutoTamanhoModel->getEmpresaTamanhoProduto($dlv_dlvpro_pxt, $dlv_dlvtam_pxt);
				
			
			}
		}
		

		if ($dlv_preco_pxt == 0) {
			$erros    = TRUE;
			$mensagem .= "- Preço não preenchido.\n";
			$this->session->set_flashdata('ERRO_DLV_PRECO_PXT', 'has-error');
		}
		
		if ($dlv_promocao_pxt) {
			if ($dlv_precopromocional_pxt == 0) {
				$erros    = TRUE;
				$mensagem .= "- Preço promocional não preenchido.\n";
				$this->session->set_flashdata('ERRO_DLV_PRECOPROMOCIONAL_PXT', 'has-error');
			} else {
				if ($dlv_preco_pxt != 0) {
					if ($dlv_precopromocional_pxt >= $dlv_preco_pxt) {
						$erros    = TRUE;
						$mensagem .= "- Preço promocional maior ou igual que o preço.\n";
						$this->session->set_flashdata('ERRO_DLV_PRECO_PXT', 'has-error');
						$this->session->set_flashdata('ERRO_DLV_PRECOPROMOCIONAL_PXT', 'has-error');
					}
				}
			}
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_PXT', TRUE);				
			$this->session->set_flashdata('dlv_id_pxt', $dlv_id_pxt);
			$this->session->set_flashdata('dlv_dlvtam_pxt', $dlv_dlvtam_pxt);
			$this->session->set_flashdata('dlv_preco_pxt', $dlv_preco_pxt);
			$this->session->set_flashdata('dlv_promocao_pxt', $dlv_promocao_pxt);
			$this->session->set_flashdata('dlv_precopromocional_pxt', $dlv_precopromocional_pxt);
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_PXT                  = $this->session->flashdata('ERRO_DLV_PXT');
		$ERRO_DLV_DLVTAM_PXT           = $this->session->flashdata('ERRO_DLV_DLVTAM_PXT');
		$ERRO_DLV_PRECO_PXT            = $this->session->flashdata('ERRO_DLV_PRECO_PXT');
		$ERRO_DLV_PROMOCAO_PXT         = $this->session->flashdata('ERRO_DLV_PROMOCAO_PXT');
		$ERRO_DLV_PRECOPROMOCIONAL_PXT = $this->session->flashdata('ERRO_DLV_PRECOPROMOCIONAL_PXT');
		
		$dlv_id_pxt                   = $this->session->flashdata('dlv_id_pxt');
		$dlv_dlvtam_pxt               = $this->session->flashdata('dlv_dlvtam_pxt');
		$dlv_preco_pxt                = $this->session->flashdata('dlv_preco_pxt');
		$dlv_promocao_pxt             = $this->session->flashdata('dlv_promocao_pxt');
		$dlv_precopromocional_pxt     = $this->session->flashdata('dlv_precopromocional_pxt');
		
		if ($ERRO_DLV_PXT) {
			$dados['dlv_id_pxt']                     = $dlv_id_pxt;
			$dados['dlv_dlvtam_pxt']                 = $dlv_dlvtam_pxt;
			$dados['dlv_preco_pxt']                  = 'R$ '.number_format($dlv_preco_pxt,  2, ',', '.');
			$dados['dlv_promocao_pxt_0']             = ($dlv_promocao_pxt == 0)?'selected':'';
			$dados['dlv_promocao_pxt_1']             = ($dlv_promocao_pxt == 1)?'selected':'';
			$dados['dlv_precopromocional_pxt']       = 'R$ '.number_format($dlv_precopromocional_pxt,  2, ',', '.');
				
			$dados['ERRO_DLV_DLVTAM_PXT']           = $ERRO_DLV_DLVTAM_PXT;
			$dados['ERRO_DLV_PRECO_PXT']            = $ERRO_DLV_PRECO_PXT;
			$dados['ERRO_DLV_PROMOCAO_PXT']         = $ERRO_DLV_PROMOCAO_PXT;
			$dados['ERRO_DLV_PRECOPROMOCIONAL_PXT'] = $ERRO_DLV_PRECOPROMOCIONAL_PXT;
			
			$dados['DIS_DLV_PRECOPROMOCIONAL_PXT']   = ($dlv_promocao_pxt == 0)?'readonly':'';		
		}
	}
	
}