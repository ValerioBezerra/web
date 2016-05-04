<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('bus_cadproduto_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Produto_Model', 'ProdutoModel');
		$this->load->model('Categoria_Model', 'CategoriaModel');
		$this->load->model('Produto_Produto_Model', 'ProdutoProdutoModel');
		$this->load->model('Empresa_Model', 'EmpresaModel');
	}
	
	public function index($bus_buscat_pro = NULL, $opcao_status = NULL) {
		$dados = array();
		
		$dados['ATUALIZAR']            = site_url('produto');
		$dados['NOVO_PRODUTO']         = site_url('produto/novo');
		$dados['ACAO_FORM']            = site_url('produto/pesquisar');
		$dados['URL_ATIVAR_DESATIVAR'] = site_url('json/produto_json/ativar_desativar');
		$dados['URL_APAGAR_PRODUTO']   = site_url('produto/apagar/');
		$dados['BLC_DADOS']    		   = array();
		
		$dados['bus_buscat_pro'] = $bus_buscat_pro;
		$dados['opcao_status']   = $opcao_status;
		
		if ($opcao_status != NULL) {
			$dados['SEL_OPCAO_STATUS_1'] = ($opcao_status == 1)?'selected':'';
			$dados['SEL_OPCAO_STATUS_0'] = ($opcao_status == 0)?'selected':'';
			$dados['SEL_OPCAO_STATUS_2'] = ($opcao_status == 2)?'selected':'';
		} else {
			$dados['opcao_status'] = 1;
		}
		
		$this->carregarDados($dados);
		$this->carregarCategorias($dados);	

		$resultado = $this->EmpresaModel->getEmpresa($this->session->userdata('bus_id_emp'));
		$dados['DISPLAY_PRODUTOS']   = 'none';
		$dados['DISPLAY_ADICIONAIS'] = 'none';
		$dados['DISPLAY_TAMANHOS']   = 'none';
		
		
			
		
		$this->parser->parse('produto_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['bus_id_pro']                  = 0;		
		$dados['bus_descricao_pro']           = '';
		$dados['bus_detalhamento_pro']        = '';
		$dados['bus_buscat_pro']              = '';
				
				$dados['bus_preco_pro']               = 'R$ 0,00';
		$dados['bus_promocao_pro_0']          = 'selected';
		$dados['bus_promocao_pro_1']          = '';
		
		$dados['bus_ordem_pro']               = '';
			
				$dados['bus_ativo_pro']               = 'checked';
						
		
		$dados['bus_exibirfracao_pro_0']      = 'selected';
		$dados['bus_exibirfracao_pro_1']      = '';
				$dados["bus_foto_pro"]                = '';
		
		
	
		
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		$this->carregarCategorias($dados);
		

		$this->parser->parse('produto_cadastro', $dados);
	}
	
	public function editar($bus_id_pro) {
		$bus_id_pro = base64_decode($bus_id_pro);
		$dados = array();
		
		$this->carregarProduto($bus_id_pro, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		$this->carregarCategorias($dados);
		
		$this->parser->parse('produto_cadastro', $dados);	
	}
	
	public function salvar() {
		global $bus_id_pro;
		global $bus_descricao_pro;
		global $bus_detalhamento_pro;
		global $bus_buscat_pro;
				
		global $bus_preco_pro;
		global $bus_promocao_pro;
		
		global $bus_ordem_pro;
		global $bus_ativo_pro;
		
		
		
				global $bus_exibirfracao_pro;
		
		global $foto_produto;
		
		$bus_id_pro   		       = $this->input->post('bus_id_pro');			
		$bus_descricao_pro         = $this->input->post('bus_descricao_pro');
		$bus_detalhamento_pro 	   = $this->input->post('bus_detalhamento_pro');
		$bus_buscat_pro 		   = $this->input->post('bus_buscat_pro');
       
		$bus_preco_pro             = $this->input->post('bus_preco_pro');
		$bus_promocao_pro          = $this->input->post('bus_promocao_pro');
		
		$bus_ordem_pro     		   = $this->input->post('bus_ordem_pro');
		
	
		$bus_ativo_pro             = $this->input->post('bus_ativo_pro');
		
				
	
		
		$salvar_produto = $this->input->post('salvar_produto');
		
		$bus_preco_pro = str_replace("R$ ", null, $bus_preco_pro);
		$bus_preco_pro = str_replace(".", null, $bus_preco_pro);
		$bus_preco_pro = str_replace(",", ".", $bus_preco_pro);
		
	
		
		if (empty($bus_ordem_pro)) {
			$bus_ordem_pro = 0;
		}
		
	
	
		
		
		
		if ($this->testarDados()) {
			$produto = array(
				"bus_busemp_pro"            => $this->session->userdata('bus_id_emp'),
				"bus_descricao_pro"         => $bus_descricao_pro,
				"bus_detalhamento_pro"      => $bus_detalhamento_pro,
				"bus_buscat_pro"            => $bus_buscat_pro,
				
				
				"bus_preco_pro"             => $bus_preco_pro,
				"bus_promocao_pro"          => $bus_promocao_pro,
							"bus_ordem_pro"             => $bus_ordem_pro,
				
				"bus_ativo_pro"             => ($bus_ativo_pro)?'1':'0',
				
				
				
				
				"bus_bususumod_pro"         => $this->session->userdata('bus_id_usu'),
				"bus_datahoramod_pro"       => date('Y-m-d H:i:s')
			);
			
			if (!$bus_id_pro) {	
				$bus_id_pro = $this->ProdutoModel->insert($produto);
			} else {
				$bus_id_pro = $this->ProdutoModel->update($produto, $bus_id_pro);
			}

			if (is_numeric($bus_id_pro)) {
				$this->salvarFoto($bus_id_pro);
				$this->session->set_flashdata('sucesso', 'Produto salvo com sucesso.');
				
				if ($salvar_produto == 'salvar') { 
					redirect('produto');
				} else {
					redirect('produto/novo/');
				}
			} else {
				$this->session->set_flashdata('erro', $bus_id_pro);	
				redirect('produto');
			}
		} else {
			if (!$bus_id_pro) {
				redirect('produto/novo/');
			} else {
				redirect('produto/editar/'.base64_encode($bus_id_pro));
			}			
		}
	}
	
	
	public function pesquisar() {						
		$bus_buscat_pro = $this->input->post('bus_buscat_pro');
		$opcao_status  =  $this->input->post('opcao_status');
		
	
		redirect('produto/index/'.$bus_buscat_pro.'/'.$opcao_status);
	}
	
	public function apagar($bus_id_pro) {
		if ($this->testarApagar(base64_decode($bus_id_pro))) {
			$res = $this->ProdutoModel->delete(base64_decode($bus_id_pro));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Produto apagado com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar produto.');				
			}
		}
		
		redirect('produto');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_PRODUTO'] = site_url('produto');
		$dados['ACAO_FORM']        = site_url('produto/salvar');
		$dados['MASCARA_HORA']     = MASCARA_HORA;
		
		$resultado = $this->EmpresaModel->getEmpresa($this->session->userdata('bus_id_emp'));
		
		$dados['DIV_USA_ADICIONAIS']   = 'transp';
		$dados['DIV_ESCOLHE_PRODUTOS'] = 'transp';
		
		if ($resultado) {
			
		}
		
	}
	
	private function carregarDados(&$dados) {
		
		$resultado = $this->ProdutoModel->getProdutoEmpresa($this->session->userdata('bus_id_emp'), $dados['bus_buscat_pro'], $dados['opcao_status']);
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"bus_DESCRICAO_PRO"    => $registro->bus_descricao_pro,				
				"bus_DESCRICAO_CAT"    => $registro->bus_descricao_cat,
				"DISPLAY_ATIVAR"       => ($registro->bus_ativo_pro == 1)?'none':'',
				"DISPLAY_DESATIVAR"    => ($registro->bus_ativo_pro == 1)?'':'none',
				"ATIVAR_PRODUTO"       => "ativarProduto('".base64_encode($registro->bus_id_pro)."')",
				"DESATIVAR_PRODUTO"    => "desativarProduto('".base64_encode($registro->bus_id_pro)."')",
				"PRODUTO_ADICIONAIS"   => site_url('produto_adicional/index/'.base64_encode($registro->bus_id_pro)),
				"PRODUTO_PRODUTOS"     => site_url('produto_produto/index/'.base64_encode($registro->bus_id_pro)),
				"PRODUTO_TAMANHOS"     => site_url('produto_tamanho/index/'.base64_encode($registro->bus_id_pro)),
				
				"EDITAR_PRODUTO" 	   => site_url('produto/editar/'.base64_encode($registro->bus_id_pro)),
				"APAGAR_PRODUTO"       => "abrirConfirmacao('".base64_encode($registro->bus_id_pro)."')"
			);
		}
	}
	
	private function carregarProduto($bus_id_pro, &$dados) {
		$resultado = $this->ProdutoModel->get($bus_id_pro);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}

		
				$dados['bus_preco_pro']               = 'R$ '.number_format($resultado->bus_preco_pro,  2, ',', '.');
		$dados['bus_promocao_pro_0']          = ($resultado->bus_promocao_pro == 0)?'selected':'';
		$dados['bus_promocao_pro_1']          = ($resultado->bus_promocao_pro == 1)?'selected':'';
		
		
		
		$dados['bus_ativo_pro']               = ($resultado->bus_ativo_pro == 1)?'checked':'';
       
		$dados['bus_exibirfracao_pro_0']      = ($resultado->bus_exibirfracao_pro == 0)?'selected':'';
		$dados['bus_exibirfracao_pro_1']      = ($resultado->bus_exibirfracao_pro == 1)?'selected':'';
		
		$urlImagem = 'assets/images/produtos/'.$resultado->bus_id_pro.'.jpg';
				
		if (file_exists($urlImagem)) {
			$dados["bus_foto_pro"] =  base_url($urlImagem);
		} else {
			$urlImagem = 'assets/images/produtos/'.$resultado->bus_id_pro.'.png';
		
			if (file_exists($urlImagem)) {
				$dados["bus_foto_pro"] =  base_url($urlImagem);
			} else {
				$dados["bus_foto_pro"] = "";
			}
		}
		
	
		} else {
			show_error('NÃo foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function carregarCategorias(&$dados) {
		$resultado = $this->CategoriaModel->getCategoriaAtivo($this->session->userdata('bus_id_emp'));
	
		$dados['BLC_CATEGORIAS'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_CATEGORIAS'][] = array(
					"bus_ID_CAT"        => $registro->bus_id_cat,
					"bus_DESCRICAO_CAT" => $registro->bus_descricao_cat,
					"SEL_bus_ID_CAT"    => ($dados['bus_buscat_pro'] == $registro->bus_id_cat)?'selected':''
			);
		}
	}
	
	private function testarDados() {
		global $bus_id_pro;
		global $bus_descricao_pro;
		global $bus_detalhamento_pro;
		global $bus_buscat_pro;
		
				global $bus_preco_pro;
		global $bus_promocao_pro;
		
		global $bus_ordem_pro;
		
			global $bus_ativo_pro;
		
		
		
			
		
		
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($bus_descricao_pro)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_bus_DESCRICAO_PRO', 'has-error');
		}
		
		if (empty($bus_buscat_pro)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione uma categoria.\n";
			$this->session->set_flashdata('ERRO_bus_busCAT_PRO', 'has-error');
		} else {
			$resultado = $this->CategoriaModel->get($bus_buscat_pro);
				
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Categoria não cadastrada.\n";
				$this->session->set_flashdata('ERRO_bus_busCAT_PRO', 'has-error');
			}
		}
		
		
		
		if ($bus_preco_pro == 0) {
			$erros    = TRUE;
			$mensagem .= "- Preço não preenchido.\n";
			$this->session->set_flashdata('ERRO_bus_PRECO_PRO', 'has-error');
		}
		
		
		
			
			
			
			
		
					
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:'.$bus_unidade_pro);
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_bus_PRO', TRUE);				
			$this->session->set_flashdata('bus_descricao_pro', $bus_descricao_pro);				
			$this->session->set_flashdata('bus_detalhamento_pro', $bus_detalhamento_pro);
			$this->session->set_flashdata('bus_buscat_pro', $bus_buscat_pro);
		
			
			$this->session->set_flashdata('bus_preco_pro', $bus_preco_pro);
			$this->session->set_flashdata('bus_promocao_pro', $bus_promocao_pro);
			
			$this->session->set_flashdata('bus_ordem_pro', $bus_ordem_pro);
			
						$this->session->set_flashdata('bus_ativo_pro', $bus_ativo_pro);
				}
		
		return !$erros;
	}
	
	private function testarApagar($bus_id_pro) {
		$erros    = FALSE;
		$mensagem = null;
	
		$resultado = $this->ProdutoProdutoModel->getEmpresaProduto($bus_id_pro);
	
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Este produto está sendo usando como sabor/complemento.\n";
		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o produto:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	  
	private function carregarDadosFlash(&$dados) {
		$ERRO_bus_PRO   		 	    = $this->session->flashdata('ERRO_bus_PRO');
		$ERRO_bus_DESCRICAO_PRO  	    = $this->session->flashdata('ERRO_bus_DESCRICAO_PRO');
		$ERRO_bus_busCAT_PRO            = $this->session->flashdata('ERRO_bus_busCAT_PRO');
				$ERRO_bus_PRECO_PRO			    = $this->session->flashdata('ERRO_bus_PRECO_PRO');
		$ERRO_bus_PRECOPROMOCINAL_PRO   = $this->session->flashdata('ERRO_bus_PRECOPROMOCINAL_PRO');
		
		
				
		$bus_descricao_pro         = $this->session->flashdata('bus_descricao_pro');
		$bus_detalhamento_pro      = $this->session->flashdata('bus_detalhamento_pro');
		$bus_buscat_pro            = $this->session->flashdata('bus_buscat_pro');
		
		
		$bus_preco_pro             = $this->session->flashdata('bus_preco_pro');
		$bus_promocao_pro          = $this->session->flashdata('bus_promocao_pro');
				$bus_ordem_pro             = $this->session->flashdata('bus_ordem_pro');
		
		
		$bus_ativo_pro             = $this->session->flashdata('bus_ativo_pro');
		
		
			
		
		if ($ERRO_bus_PRO) {
			$dados['bus_descricao_pro']           = $bus_descricao_pro;
			$dados['bus_detalhamento_pro']        = $bus_detalhamento_pro;
			$dados['bus_buscat_pro']              = $bus_buscat_pro;
			
						
			$dados['bus_preco_pro']               = 'R$ '.number_format($bus_preco_pro,  2, ',', '.');
			$dados['bus_promocao_pro_0']          = ($bus_promocao_pro == 0)?'selected':'';
			$dados['bus_promocao_pro_1']          = ($bus_promocao_pro == 1)?'selected':'';
			
			$dados['bus_ordem_pro']               = $bus_ordem_pro;
			
			
			$dados['bus_ativo_pro']               = ($bus_ativo_pro == 1)?'checked':'';
			
		
				
			$dados['DIS_bus_PRECOPROMOCINAL_PRO']   = ($bus_promocao_pro == 0)?'readonly':'';
			
			
				
			$dados['ERRO_bus_DESCRICAO_PRO']         = $ERRO_bus_DESCRICAO_PRO;
			$dados['ERRO_bus_busCAT_PRO']            = $ERRO_bus_busCAT_PRO;
		
			$dados['ERRO_bus_PRECO_PRO']             = $ERRO_bus_PRECO_PRO;
			$dados['ERRO_bus_PRECOPROMOCINAL_PRO']   = $ERRO_bus_PRECOPROMOCINAL_PRO;
			
		
		}
	}
	
	private function salvarFoto($bus_id_pro) {
		$urlImagem = 'assets/images/produtos/'.$bus_id_pro.'.jpg';
		
		if (isset($_FILES['image'])) {
			if ($_FILES["image"]["error"] == 0) {
				$destino = './assets/images/produtos/'.$bus_id_pro;
				if ($_FILES['image']['type'] == 'image/jpg') {
					$destino = $destino.'.jpg';
				} else if ($_FILES['image']['type'] == 'image/jpeg')  {
					$destino = $destino.'.jpg';
				} else {
					$destino = $destino.'.png';
				}
				$arquivo_tmp = $_FILES['image']['tmp_name'];
				move_uploaded_file($arquivo_tmp, $destino);
			}
		} 	
	}
}