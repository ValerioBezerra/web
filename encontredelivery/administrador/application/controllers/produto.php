<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_cadproduto_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Produto_Model', 'ProdutoModel');
		$this->load->model('Categoria_Model', 'CategoriaModel');
		$this->load->model('Produto_Produto_Model', 'ProdutoProdutoModel');
		$this->load->model('Empresa_Model', 'EmpresaModel');
	}
	
	public function index($dlv_dlvcat_pro = NULL, $opcao_status = NULL) {
		$dados = array();
		
		$dados['ATUALIZAR']            = site_url('produto');
		$dados['NOVO_PRODUTO']         = site_url('produto/novo');
		$dados['ACAO_FORM']            = site_url('produto/pesquisar');
		$dados['URL_ATIVAR_DESATIVAR'] = site_url('json/produto_json/ativar_desativar');
		$dados['URL_APAGAR_PRODUTO']   = site_url('produto/apagar/');
		$dados['BLC_DADOS']    		   = array();
		
		$dados['dlv_dlvcat_pro'] = $dlv_dlvcat_pro;
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

		$resultado = $this->EmpresaModel->getEmpresa($this->session->userdata('dlv_id_emp'));
		$dados['DISPLAY_PRODUTOS']   = 'none';
		$dados['DISPLAY_ADICIONAIS'] = 'none';
		$dados['DISPLAY_TAMANHOS']   = 'none';
		
		if ($resultado) {
			if ($resultado->dlv_escolheproduto_emp == 1) {
				$dados['DISPLAY_PRODUTOS'] = '';
			}

			if ($resultado->dlv_usaadicionais_emp == 1) {
				$dados['DISPLAY_ADICIONAIS'] = '';
			}
			
			if ($resultado->dlv_usatamanhos_emp == 1) {
				$dados['DISPLAY_TAMANHOS'] = '';
			}
		}		
		
		$this->parser->parse('produto_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['dlv_id_pro']                  = 0;		
		$dados['dlv_descricao_pro']           = '';
		$dados['dlv_detalhamento_pro']        = '';
		$dados['dlv_dlvcat_pro']              = '';
		$dados['dlv_tempopreparo_pro']        = '';
		$dados['dlv_destaque_pro_0']          = 'selected';
		$dados['dlv_destaque_pro_1']          = '';
		$dados['dlv_preco_pro']               = 'R$ 0,00';
		$dados['dlv_promocao_pro_0']          = 'selected';
		$dados['dlv_promocao_pro_1']          = '';
		$dados['dlv_precopromocional_pro']    = 'R$ 0,00';
		$dados['dlv_ordem_pro']               = '';
		$dados['dlv_umadicional_pro_0']       = 'selected';
		$dados['dlv_umadicional_pro_1']       = '';
		$dados['dlv_principal_pro_0']         = '';
		$dados['dlv_principal_pro_1']         = 'selected';
		$dados['dlv_ativo_pro']               = 'checked';
		$dados['dlv_escolheproduto_pro_0']    = 'selected';
		$dados['dlv_escolheproduto_pro_1']    = '';
		$dados['dlv_quantidade_pro']          = '';
		$dados['dlv_unidade_pro']             = '';
		$dados['dlv_precomaiorproduto_pro_0'] = 'selected';
		$dados['dlv_precomaiorproduto_pro_1'] = '';
		$dados['dlv_exibirfracao_pro_0']      = 'selected';
		$dados['dlv_exibirfracao_pro_1']      = '';
		$dados['dlv_minproduto_pro']          = '';
		$dados['dlv_maxproduto_pro']          = '';
		$dados["dlv_foto_pro"]                = '';
		
		
		$dados['DIS_DLV_PRECOPROMOCINAL_PRO']   = 'readonly';
		$dados['DIS_DLV_QUANTIDADE_PRO']        = 'readonly';
		$dados['DIS_DLV_UNIDADE_PRO']           = 'readonly';
		$dados['DIS_DLV_PRECOMAIORPRODUTO_PRO'] = 'readonly';
		$dados['DIS_DLV_EXIBIRFRACAO_PRO']      = 'readonly';
		$dados['DIS_DLV_MINPRODUTO_PRO']        = 'readonly';
		$dados['DIS_DLV_MAXPRODUTO_PRO']        = 'readonly';
		
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		$this->carregarCategorias($dados);
		

		$this->parser->parse('produto_cadastro', $dados);
	}
	
	public function editar($dlv_id_pro) {
		$dlv_id_pro = base64_decode($dlv_id_pro);
		$dados = array();
		
		$this->carregarProduto($dlv_id_pro, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		$this->carregarCategorias($dados);
		
		$this->parser->parse('produto_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_pro;
		global $dlv_descricao_pro;
		global $dlv_detalhamento_pro;
		global $dlv_dlvcat_pro;
		global $dlv_tempopreparo_pro;
		global $dlv_destaque_pro;
		global $dlv_preco_pro;
		global $dlv_promocao_pro;
		global $dlv_precopromocional_pro;
		global $dlv_ordem_pro;
		global $dlv_umadicional_pro;
		global $dlv_principal_pro;
		global $dlv_ativo_pro;
		global $dlv_escolheproduto_pro;
		global $dlv_quantidade_pro;
		global $dlv_unidade_pro;
		global $dlv_precomaiorproduto_pro;
		global $dlv_exibirfracao_pro;
		global $dlv_minproduto_pro;
		global $dlv_maxproduto_pro;
		global $foto_produto;
		
		$dlv_id_pro   		       = $this->input->post('dlv_id_pro');			
		$dlv_descricao_pro         = $this->input->post('dlv_descricao_pro');
		$dlv_detalhamento_pro 	   = $this->input->post('dlv_detalhamento_pro');
		$dlv_dlvcat_pro 		   = $this->input->post('dlv_dlvcat_pro');
		$dlv_tempopreparo_pro      = $this->input->post('dlv_tempopreparo_pro');
		$dlv_destaque_pro          = $this->input->post('dlv_destaque_pro');
		$dlv_preco_pro             = $this->input->post('dlv_preco_pro');
		$dlv_promocao_pro          = $this->input->post('dlv_promocao_pro');
		$dlv_precopromocional_pro  = $this->input->post('dlv_precopromocional_pro');
		$dlv_ordem_pro     		   = $this->input->post('dlv_ordem_pro');
		$dlv_umadicional_pro       = $this->input->post('dlv_umadicional_pro');
		$dlv_principal_pro         = $this->input->post('dlv_principal_pro');
		$dlv_ativo_pro             = $this->input->post('dlv_ativo_pro');
		$dlv_escolheproduto_pro    = $this->input->post('dlv_escolheproduto_pro');
		$dlv_quantidade_pro        = $this->input->post('dlv_quantidade_pro');
		$dlv_unidade_pro           = $this->input->post('dlv_unidade_pro');
		$dlv_precomaiorproduto_pro = $this->input->post('dlv_precomaiorproduto_pro');
		$dlv_exibirfracao_pro      = $this->input->post('dlv_exibirfracao_pro');
		$dlv_minproduto_pro        = $this->input->post('dlv_minproduto_pro');
		$dlv_maxproduto_pro        = $this->input->post('dlv_maxproduto_pro');
		
		$salvar_produto = $this->input->post('salvar_produto');
		
		$dlv_preco_pro = str_replace("R$ ", null, $dlv_preco_pro);
		$dlv_preco_pro = str_replace(".", null, $dlv_preco_pro);
		$dlv_preco_pro = str_replace(",", ".", $dlv_preco_pro);
		
		if (empty($dlv_precopromocional_pro)) {
			$dlv_precopromocional_pro = 0;
		}
		
		$dlv_precopromocional_pro = str_replace("R$ ", null, $dlv_precopromocional_pro);
		$dlv_precopromocional_pro = str_replace(".", null, $dlv_precopromocional_pro);
		$dlv_precopromocional_pro = str_replace(",", ".", $dlv_precopromocional_pro);
		
		if (empty($dlv_ordem_pro)) {
			$dlv_ordem_pro = 0;
		}
		
		if (empty($dlv_quantidade_pro)) {
			$dlv_quantidade_pro = 0;
		}
		
		if (empty($dlv_unidade_pro)) {
			$dlv_unidade_pro = '';
		}
		
		if (empty($dlv_precomaiorproduto_pro)) {
			$dlv_precomaiorproduto_pro = 0;
		}
		
		if (empty($dlv_exibirfracao_pro)) {
			$dlv_exibirfracao_pro = 0;
		}

		if (empty($dlv_minproduto_pro)) {
			$dlv_minproduto_pro = 0;
		}
		
		if (empty($dlv_maxproduto_pro)) {
			$dlv_maxproduto_pro = 0;
		}
		
		if ($this->testarDados()) {
			$produto = array(
				"dlv_dlvemp_pro"            => $this->session->userdata('dlv_id_emp'),
				"dlv_descricao_pro"         => $dlv_descricao_pro,
				"dlv_detalhamento_pro"      => $dlv_detalhamento_pro,
				"dlv_dlvcat_pro"            => $dlv_dlvcat_pro,
				"dlv_tempopreparo_pro"      => $dlv_tempopreparo_pro,
				"dlv_destaque_pro"          => $dlv_destaque_pro,
				"dlv_preco_pro"             => $dlv_preco_pro,
				"dlv_promocao_pro"          => $dlv_promocao_pro,
				"dlv_precopromocional_pro"  => $dlv_precopromocional_pro,
				"dlv_ordem_pro"             => $dlv_ordem_pro,
				"dlv_umadicional_pro"       => $dlv_umadicional_pro,
				"dlv_principal_pro"         => $dlv_principal_pro,
				"dlv_ativo_pro"             => ($dlv_ativo_pro)?'1':'0',
				"dlv_escolheproduto_pro"    => $dlv_escolheproduto_pro,	
				"dlv_quantidade_pro"        => $dlv_quantidade_pro,
				"dlv_unidade_pro"      	    => $dlv_unidade_pro,
				"dlv_precomaiorproduto_pro" => $dlv_precomaiorproduto_pro,	
				"dlv_exibirfracao_pro"      => $dlv_exibirfracao_pro,	
				"dlv_minproduto_pro"        => $dlv_minproduto_pro,
				"dlv_maxproduto_pro"        => $dlv_maxproduto_pro,
				"dlv_dlvusumod_pro"         => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_pro"       => date('Y-m-d H:i:s')
			);
			
			if (!$dlv_id_pro) {	
				$dlv_id_pro = $this->ProdutoModel->insert($produto);
			} else {
				$dlv_id_pro = $this->ProdutoModel->update($produto, $dlv_id_pro);
			}

			if (is_numeric($dlv_id_pro)) {
				$this->salvarFoto($dlv_id_pro);
				$this->session->set_flashdata('sucesso', 'Produto salvo com sucesso.');
				
				if ($salvar_produto == 'salvar') { 
					redirect('produto');
				} else {
					redirect('produto/novo/');
				}
			} else {
				$this->session->set_flashdata('erro', $dlv_id_pro);	
				redirect('produto');
			}
		} else {
			if (!$dlv_id_pro) {
				redirect('produto/novo/');
			} else {
				redirect('produto/editar/'.base64_encode($dlv_id_pro));
			}			
		}
	}
	
	
	public function pesquisar() {						
		$dlv_dlvcat_pro = $this->input->post('dlv_dlvcat_pro');
		$opcao_status  =  $this->input->post('opcao_status');
		
	
		redirect('produto/index/'.$dlv_dlvcat_pro.'/'.$opcao_status);
	}
	
	public function apagar($dlv_id_pro) {
		if ($this->testarApagar(base64_decode($dlv_id_pro))) {
			$res = $this->ProdutoModel->delete(base64_decode($dlv_id_pro));
	
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
		
		$resultado = $this->EmpresaModel->getEmpresa($this->session->userdata('dlv_id_emp'));
		
		$dados['DIV_USA_ADICIONAIS']   = 'transp';
		$dados['DIV_ESCOLHE_PRODUTOS'] = 'transp';
		
		if ($resultado) {
			if ($resultado->dlv_escolheproduto_emp == 1) {
				$dados['DIV_ESCOLHE_PRODUTOS'] = '';
			}
		
			if ($resultado->dlv_usaadicionais_emp == 1) {
				$dados['DIV_USA_ADICIONAIS'] = '';
			}
		}
		
	}
	
	private function carregarDados(&$dados) {
		
		$resultado = $this->ProdutoModel->getProdutoEmpresa($this->session->userdata('dlv_id_emp'), $dados['dlv_dlvcat_pro'], $dados['opcao_status']);
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_DESCRICAO_PRO"    => $registro->dlv_descricao_pro,				
				"DLV_DESCRICAO_CAT"    => $registro->dlv_descricao_cat,
				"DISPLAY_ATIVAR"       => ($registro->dlv_ativo_pro == 1)?'none':'',
				"DISPLAY_DESATIVAR"    => ($registro->dlv_ativo_pro == 1)?'':'none',
				"ATIVAR_PRODUTO"       => "ativarProduto('".base64_encode($registro->dlv_id_pro)."')",
				"DESATIVAR_PRODUTO"    => "desativarProduto('".base64_encode($registro->dlv_id_pro)."')",
				"PRODUTO_ADICIONAIS"   => site_url('produto_adicional/index/'.base64_encode($registro->dlv_id_pro)),
				"PRODUTO_PRODUTOS"     => site_url('produto_produto/index/'.base64_encode($registro->dlv_id_pro)),
				"PRODUTO_TAMANHOS"     => site_url('produto_tamanho/index/'.base64_encode($registro->dlv_id_pro)),
				"DIS_PRODUTO_PRODUTOS" => ($registro->dlv_escolheproduto_pro == 0)?'hidden':'',
				"EDITAR_PRODUTO" 	   => site_url('produto/editar/'.base64_encode($registro->dlv_id_pro)),
				"APAGAR_PRODUTO"       => "abrirConfirmacao('".base64_encode($registro->dlv_id_pro)."')"
			);
		}
	}
	
	private function carregarProduto($dlv_id_pro, &$dados) {
		$resultado = $this->ProdutoModel->get($dlv_id_pro);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}

		$dados['dlv_destaque_pro_0']          = ($resultado->dlv_destaque_pro == 0)?'selected':'';
		$dados['dlv_destaque_pro_1']          = ($resultado->dlv_destaque_pro == 1)?'selected':'';
		$dados['dlv_preco_pro']               = 'R$ '.number_format($resultado->dlv_preco_pro,  2, ',', '.');
		$dados['dlv_promocao_pro_0']          = ($resultado->dlv_promocao_pro == 0)?'selected':'';
		$dados['dlv_promocao_pro_1']          = ($resultado->dlv_promocao_pro == 1)?'selected':'';
		$dados['dlv_precopromocional_pro']    = 'R$ '.number_format($resultado->dlv_precopromocional_pro,  2, ',', '.');
		$dados['dlv_umadicional_pro_0']       = ($resultado->dlv_umadicional_pro == 0)?'selected':'';
		$dados['dlv_umadicional_pro_1']       = ($resultado->dlv_umadicional_pro == 1)?'selected':'';
		$dados['dlv_principal_pro_0']         = ($resultado->dlv_principal_pro == 0)?'selected':'';
		$dados['dlv_principal_pro_1']         = ($resultado->dlv_principal_pro == 1)?'selected':'';
		$dados['dlv_ativo_pro']               = ($resultado->dlv_ativo_pro == 1)?'checked':'';
		$dados['dlv_escolheproduto_pro_0']    = ($resultado->dlv_escolheproduto_pro == 0)?'selected':'';
		$dados['dlv_escolheproduto_pro_1']    = ($resultado->dlv_escolheproduto_pro == 1)?'selected':'';
		$dados['dlv_precomaiorproduto_pro_0'] = ($resultado->dlv_precomaiorproduto_pro == 0)?'selected':'';
		$dados['dlv_precomaiorproduto_pro_1'] = ($resultado->dlv_precomaiorproduto_pro == 1)?'selected':'';
		$dados['dlv_exibirfracao_pro_0']      = ($resultado->dlv_exibirfracao_pro == 0)?'selected':'';
		$dados['dlv_exibirfracao_pro_1']      = ($resultado->dlv_exibirfracao_pro == 1)?'selected':'';
		
		$urlImagem = 'assets/images/produtos/'.$resultado->dlv_id_pro.'.jpg';
				
		if (file_exists($urlImagem)) {
			$dados["dlv_foto_pro"] =  base_url($urlImagem);
		} else {
			$urlImagem = 'assets/images/produtos/'.$resultado->dlv_id_pro.'.png';
		
			if (file_exists($urlImagem)) {
				$dados["dlv_foto_pro"] =  base_url($urlImagem);
			} else {
				$dados["dlv_foto_pro"] = "";
			}
		}
		
		$dados['DIS_DLV_PRECOPROMOCINAL_PRO']   = ($resultado->dlv_promocao_pro == 0)?'readonly':'';
		$dados['DIS_DLV_QUANTIDADE_PRO']        = ($resultado->dlv_escolheproduto_pro == 0)?'readonly':'';
		$dados['DIS_DLV_UNIDADE_PRO']           = ($resultado->dlv_escolheproduto_pro == 0)?'readonly':'';
		$dados['DIS_DLV_PRECOMAIORPRODUTO_PRO'] = ($resultado->dlv_escolheproduto_pro == 0)?'readonly':'';
		$dados['DIS_DLV_EXIBIRFRACAO_PRO']      = ($resultado->dlv_escolheproduto_pro == 0)?'readonly':'';
		$dados['DIS_DLV_MINPRODUTO_PRO']        = ($resultado->dlv_escolheproduto_pro == 0)?'readonly':'';
		$dados['DIS_DLV_MAXPRODUTO_PRO']        = ($resultado->dlv_escolheproduto_pro == 0)?'readonly':'';
		} else {
			show_error('NÃo foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function carregarCategorias(&$dados) {
		$resultado = $this->CategoriaModel->getCategoriaAtivo($this->session->userdata('dlv_id_emp'));
	
		$dados['BLC_CATEGORIAS'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_CATEGORIAS'][] = array(
					"DLV_ID_CAT"        => $registro->dlv_id_cat,
					"DLV_DESCRICAO_CAT" => $registro->dlv_descricao_cat,
					"SEL_DLV_ID_CAT"    => ($dados['dlv_dlvcat_pro'] == $registro->dlv_id_cat)?'selected':''
			);
		}
	}
	
	private function testarDados() {
		global $dlv_id_pro;
		global $dlv_descricao_pro;
		global $dlv_detalhamento_pro;
		global $dlv_dlvcat_pro;
		global $dlv_tempopreparo_pro;
		global $dlv_destaque_pro;
		global $dlv_preco_pro;
		global $dlv_promocao_pro;
		global $dlv_precopromocional_pro;
		global $dlv_ordem_pro;
		global $dlv_umadicional_pro;
		global $dlv_principal_pro;
		global $dlv_ativo_pro;
		global $dlv_escolheproduto_pro;
		global $dlv_quantidade_pro;
		global $dlv_unidade_pro;
		global $dlv_precomaiorproduto_pro;
		global $dlv_exibirfracao_pro;
		global $dlv_minproduto_pro;
		global $dlv_maxproduto_pro;
		
		
		
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($dlv_descricao_pro)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_DLV_DESCRICAO_PRO', 'has-error');
		}
		
		if (empty($dlv_dlvcat_pro)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione uma categoria.\n";
			$this->session->set_flashdata('ERRO_DLV_DLVCAT_PRO', 'has-error');
		} else {
			$resultado = $this->CategoriaModel->get($dlv_dlvcat_pro);
				
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Categoria não cadastrada.\n";
				$this->session->set_flashdata('ERRO_DLV_DLVCAT_PRO', 'has-error');
			}
		}
		
		if (!empty($dlv_tempopreparo_pro)) {
			if(!preg_match("^([0-1][0-9]|[0-2][0-3]):[0-5][0-9]:[0-5][0-9]$^", $dlv_tempopreparo_pro)) {
				$erros      = TRUE;
				$errosTempo = TRUE;
				$mensagem  .= "- Tempo de preparo inválido.\n";
				$this->session->set_flashdata('ERRO_DLV_TEMPOPREPARO_PRO', 'has-error');
			} 	
		}
		
		if ($dlv_preco_pro == 0) {
			$erros    = TRUE;
			$mensagem .= "- Preço não preenchido.\n";
			$this->session->set_flashdata('ERRO_DLV_PRECO_PRO', 'has-error');
		}
		
		if ($dlv_promocao_pro) {
			if ($dlv_precopromocional_pro == 0) {
				$erros    = TRUE;
				$mensagem .= "- Preço promocional não preenchido.\n";
				$this->session->set_flashdata('ERRO_DLV_PRECOPROMOCINAL_PRO', 'has-error');
			} else {
				if ($dlv_preco_pro != 0) {
					if ($dlv_precopromocional_pro >= $dlv_preco_pro) {
						$erros    = TRUE;
						$mensagem .= "- Preço promocional maior ou igual que o preço.\n";
						$this->session->set_flashdata('ERRO_DLV_PRECO_PRO', 'has-error');
						$this->session->set_flashdata('ERRO_DLV_PRECOPROMOCINAL_PRO', 'has-error');
					}
				}				
			}
		}
		
		if ($dlv_escolheproduto_pro == 1) {
			if ($dlv_quantidade_pro == 0) {
				$erros    = TRUE;
				$mensagem .= "- Quantidade não preenchida.\n";
				$this->session->set_flashdata('ERRO_DLV_QUANTIDADE_PRO', 'has-error');			
			} else {
				if ($dlv_exibirfracao_pro == 1) {
					if ($dlv_quantidade_pro > 4) {
						$erros    = TRUE;
						$mensagem .= "- Quantidade maior que 4.\n";
						$this->session->set_flashdata('ERRO_DLV_QUANTIDADE_PRO', 'has-error');
					}
				}
			}
			
			if (empty($dlv_unidade_pro)) {
				$erros    = TRUE;
				$mensagem .= "- Unidade não preenchida.\n";
				$this->session->set_flashdata('ERRO_DLV_UNIDADE_PRO', 'has-error');
			}
			
			if ($dlv_exibirfracao_pro == 0) {
				if ($dlv_precomaiorproduto_pro == 1) {
					if ($dlv_quantidade_pro <> 1) {
						$erros    = TRUE;
						$mensagem .= "- Preço do maior produto marcado como sim e exibir por fração como não, quantidade tem que ser igual a 1'.\n";
						$this->session->set_flashdata('ERRO_DLV_EXIBIRFRACAO_PRO', 'has-error');
						$this->session->set_flashdata('ERRO_DLV_PRECOMAIORPRODUTO_PRO', 'has-error');
						$this->session->set_flashdata('ERRO_DLV_QUANTIDADE_PRO', 'has-error');
					}
				} 
			}
			
			if ($dlv_minproduto_pro > $dlv_maxproduto_pro) {
				$erros    = TRUE;
				$mensagem .= "- Quantidade mínima por item maior que quantidade máxima por item.\n";
				$this->session->set_flashdata('ERRO_DLV_MINPRODUTO_PRO', 'has-error');
				$this->session->set_flashdata('ERRO_DLV_MAXPRODUTO_PRO', 'has-error');
			}
					
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:'.$dlv_unidade_pro);
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_PRO', TRUE);				
			$this->session->set_flashdata('dlv_descricao_pro', $dlv_descricao_pro);				
			$this->session->set_flashdata('dlv_detalhamento_pro', $dlv_detalhamento_pro);
			$this->session->set_flashdata('dlv_dlvcat_pro', $dlv_dlvcat_pro);
			$this->session->set_flashdata('dlv_tempopreparo_pro', $dlv_tempopreparo_pro);
			$this->session->set_flashdata('dlv_destaque_pro', $dlv_destaque_pro);
			$this->session->set_flashdata('dlv_preco_pro', $dlv_preco_pro);
			$this->session->set_flashdata('dlv_promocao_pro', $dlv_promocao_pro);
			$this->session->set_flashdata('dlv_precopromocional_pro', $dlv_precopromocional_pro);
			$this->session->set_flashdata('dlv_ordem_pro', $dlv_ordem_pro);
			$this->session->set_flashdata('dlv_umadicional_pro', $dlv_umadicional_pro);
			$this->session->set_flashdata('dlv_principal_pro', $dlv_principal_pro);
			$this->session->set_flashdata('dlv_ativo_pro', $dlv_ativo_pro);
			$this->session->set_flashdata('dlv_escolheproduto_pro', $dlv_escolheproduto_pro);
			$this->session->set_flashdata('dlv_quantidade_pro', $dlv_quantidade_pro);
			$this->session->set_flashdata('dlv_unidade_pro', $dlv_unidade_pro);
			$this->session->set_flashdata('dlv_precomaiorproduto_pro', $dlv_precomaiorproduto_pro);
			$this->session->set_flashdata('dlv_exibirfracao_pro', $dlv_exibirfracao_pro);
			$this->session->set_flashdata('dlv_minproduto_pro', $dlv_minproduto_pro);
			$this->session->set_flashdata('dlv_maxproduto_pro', $dlv_maxproduto_pro);
		}
		
		return !$erros;
	}
	
	private function testarApagar($dlv_id_pro) {
		$erros    = FALSE;
		$mensagem = null;
	
		$resultado = $this->ProdutoProdutoModel->getEmpresaProduto($dlv_id_pro);
	
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
		$ERRO_DLV_PRO   		 	    = $this->session->flashdata('ERRO_DLV_PRO');
		$ERRO_DLV_DESCRICAO_PRO  	    = $this->session->flashdata('ERRO_DLV_DESCRICAO_PRO');
		$ERRO_DLV_DLVCAT_PRO            = $this->session->flashdata('ERRO_DLV_DLVCAT_PRO');
		$ERRO_DLV_TEMPOPREPARO_PRO	    = $this->session->flashdata('ERRO_DLV_TEMPOPREPARO_PRO');
		$ERRO_DLV_PRECO_PRO			    = $this->session->flashdata('ERRO_DLV_PRECO_PRO');
		$ERRO_DLV_PRECOPROMOCINAL_PRO   = $this->session->flashdata('ERRO_DLV_PRECOPROMOCINAL_PRO');
		$ERRO_DLV_QUANTIDADE_PRO        = $this->session->flashdata('ERRO_DLV_QUANTIDADE_PRO');
		$ERRO_DLV_UNIDADE_PRO           = $this->session->flashdata('ERRO_DLV_UNIDADE_PRO');
		$ERRO_DLV_EXIBIRFRACAO_PRO      = $this->session->flashdata('ERRO_DLV_EXIBIRFRACAO_PRO');
		$ERRO_DLV_PRECOMAIORPRODUTO_PRO = $this->session->flashdata('ERRO_DLV_PRECOMAIORPRODUTO_PRO');
		$ERRO_DLV_MINPRODUTO_PRO        = $this->session->flashdata('ERRO_DLV_MINPRODUTO_PRO');
		$ERRO_DLV_MAXPRODUTO_PRO        = $this->session->flashdata('ERRO_DLV_MAXPRODUTO_PRO');
		
		$dlv_descricao_pro         = $this->session->flashdata('dlv_descricao_pro');
		$dlv_detalhamento_pro      = $this->session->flashdata('dlv_detalhamento_pro');
		$dlv_dlvcat_pro            = $this->session->flashdata('dlv_dlvcat_pro');
		$dlv_tempopreparo_pro      = $this->session->flashdata('dlv_tempopreparo_pro');
		$dlv_destaque_pro          = $this->session->flashdata('dlv_destaque_pro');
		$dlv_preco_pro             = $this->session->flashdata('dlv_preco_pro');
		$dlv_promocao_pro          = $this->session->flashdata('dlv_promocao_pro');
		$dlv_precopromocional_pro  = $this->session->flashdata('dlv_precopromocional_pro');
		$dlv_ordem_pro             = $this->session->flashdata('dlv_ordem_pro');
		$dlv_umadicional_pro       = $this->session->flashdata('dlv_umadicional_pro');
		$dlv_principal_pro         = $this->session->flashdata('dlv_principal_pro');
		$dlv_ativo_pro             = $this->session->flashdata('dlv_ativo_pro');
		$dlv_escolheproduto_pro    = $this->session->flashdata('dlv_escolheproduto_pro');
		$dlv_quantidade_pro        = $this->session->flashdata('dlv_quantidade_pro');
		$dlv_unidade_pro           = $this->session->flashdata('dlv_unidade_pro');
		$dlv_precomaiorproduto_pro = $this->session->flashdata('dlv_precomaiorproduto_pro');
		$dlv_exibirfracao_pro      = $this->session->flashdata('dlv_exibirfracao_pro');		
		$dlv_minproduto_pro        = $this->session->flashdata('dlv_minproduto_pro');
		$dlv_maxproduto_pro        = $this->session->flashdata('dlv_maxproduto_pro');
		
		if ($ERRO_DLV_PRO) {
			$dados['dlv_descricao_pro']           = $dlv_descricao_pro;
			$dados['dlv_detalhamento_pro']        = $dlv_detalhamento_pro;
			$dados['dlv_dlvcat_pro']              = $dlv_dlvcat_pro;
			$dados['dlv_tempopreparo_pro']        = $dlv_tempopreparo_pro;
			$dados['dlv_destaque_pro_0']          = ($dlv_destaque_pro == 0)?'selected':'';
			$dados['dlv_destaque_pro_1']          = ($dlv_destaque_pro == 1)?'selected':'';
			$dados['dlv_preco_pro']               = 'R$ '.number_format($dlv_preco_pro,  2, ',', '.');
			$dados['dlv_promocao_pro_0']          = ($dlv_promocao_pro == 0)?'selected':'';
			$dados['dlv_promocao_pro_1']          = ($dlv_promocao_pro == 1)?'selected':'';
			$dados['dlv_precopromocional_pro']    = 'R$ '.number_format($dlv_precopromocional_pro,  2, ',', '.');
			$dados['dlv_ordem_pro']               = $dlv_ordem_pro;
			$dados['dlv_umadicional_pro_0']       = ($dlv_umadicional_pro == 0)?'selected':'';
			$dados['dlv_umadicional_pro_1']       = ($dlv_umadicional_pro == 1)?'selected':'';
			$dados['dlv_principal_pro_0']         = ($dlv_principal_pro == 0)?'selected':'';
			$dados['dlv_principal_pro_1']         = ($dlv_principal_pro == 1)?'selected':'';
			$dados['dlv_ativo_pro']               = ($dlv_ativo_pro == 1)?'checked':'';
			$dados['dlv_escolheproduto_pro_0']    = ($dlv_escolheproduto_pro == 0)?'selected':'';
			$dados['dlv_escolheproduto_pro_1']    = ($dlv_escolheproduto_pro == 1)?'selected':'';
			$dados['dlv_quantidade_pro']          = $dlv_quantidade_pro;
			$dados['dlv_unidade_pro']             = $dlv_unidade_pro;
			$dados['dlv_precomaiorproduto_pro_0'] = ($dlv_precomaiorproduto_pro == 0)?'selected':'';
			$dados['dlv_precomaiorproduto_pro_1'] = ($dlv_precomaiorproduto_pro == 1)?'selected':'';
			$dados['dlv_exibirfracao_pro_0']      = ($dlv_exibirfracao_pro == 0)?'selected':'';
			$dados['dlv_exibirfracao_pro_1']      = ($dlv_exibirfracao_pro == 1)?'selected':'';
			$dados['dlv_minproduto_pro']          = $dlv_minproduto_pro;
			$dados['dlv_maxproduto_pro']          = $dlv_maxproduto_pro;
				
			$dados['DIS_DLV_PRECOPROMOCINAL_PRO']   = ($dlv_promocao_pro == 0)?'readonly':'';
			$dados['DIS_DLV_QUANTIDADE_PRO']        = ($dlv_escolheproduto_pro == 0)?'readonly':'';
			$dados['DIS_DLV_UNIDADE_PRO']           = ($dlv_escolheproduto_pro == 0)?'readonly':'';
			$dados['DIS_DLV_PRECOMAIORPRODUTO_PRO'] = ($dlv_escolheproduto_pro == 0)?'readonly':'';
			$dados['DIS_DLV_EXIBIRFRACAO_PRO']      = ($dlv_escolheproduto_pro == 0)?'readonly':'';
			$dados['DIS_DLV_MINPRODUTO_PRO']        = ($dlv_escolheproduto_pro == 0)?'readonly':'';
			$dados['DIS_DLV_MAXPRODUTO_PRO']        = ($dlv_escolheproduto_pro == 0)?'readonly':'';
				
			$dados['ERRO_DLV_DESCRICAO_PRO']         = $ERRO_DLV_DESCRICAO_PRO;
			$dados['ERRO_DLV_DLVCAT_PRO']            = $ERRO_DLV_DLVCAT_PRO;
			$dados['ERRO_DLV_TEMPOPREPARO_PRO']      = $ERRO_DLV_TEMPOPREPARO_PRO;
			$dados['ERRO_DLV_PRECO_PRO']             = $ERRO_DLV_PRECO_PRO;
			$dados['ERRO_DLV_PRECOPROMOCINAL_PRO']   = $ERRO_DLV_PRECOPROMOCINAL_PRO;
			$dados['ERRO_DLV_QUANTIDADE_PRO']        = $ERRO_DLV_QUANTIDADE_PRO;
			$dados['ERRO_DLV_UNIDADE_PRO']           = $ERRO_DLV_UNIDADE_PRO;
			$dados['ERRO_DLV_EXIBIRFRACAO_PRO']      = $ERRO_DLV_EXIBIRFRACAO_PRO;
			$dados['ERRO_DLV_PRECOMAIORPRODUTO_PRO'] = $ERRO_DLV_PRECOMAIORPRODUTO_PRO;
			$dados['ERRO_DLV_MINPRODUTO_PRO']        = $ERRO_DLV_MINPRODUTO_PRO;
			$dados['ERRO_DLV_MAXPRODUTO_PRO']        = $ERRO_DLV_MAXPRODUTO_PRO;
		}
	}
	
	private function salvarFoto($dlv_id_pro) {
		$urlImagem = 'assets/images/produtos/'.$dlv_id_pro.'.jpg';
		
		if (isset($_FILES['image'])) {
			if ($_FILES["image"]["error"] == 0) {
				$destino = './assets/images/produtos/'.$dlv_id_pro;
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