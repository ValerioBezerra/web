<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Produto_Model', 'ProdutoModel');
		$this->load->model('Produto_Produto_Model', 'ProdutoProdutoModel');
		$this->load->model('Produto_Tamanho_Model', 'ProdutoTamanhoModel');
	}

	public function retornar_produtos_descricao($chave, $bus_descricao_pro) {
		$dados = array();

		if ($chave == CHAVE_MD5) {
			$resultado = $this->ProdutoModel->getProdutoDescricaoOrdemPrecoMenor($bus_descricao_pro);

			foreach ($resultado as $registro) {
				$dados[] = array(
					"bus_id_pro"        => $registro->bus_id_pro,
					"bus_descricao_pro" => $registro->bus_descricao_pro,
					"bus_preco_pro"     => $registro->bus_preco_pro,
					"urlImagemEmpresa"  => base_url('assets/images/empresas/'.$registro->bus_busemp_pro.".png")

				);
			}
		}

		echo json_encode(array("produtos" => $dados));
	}

	public function retornar_produtos_categoria($chave, $dlv_dlvcat_pro) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->ProdutoModel->getProdutoCategoriaAtivoTelaPrincipal($dlv_dlvcat_pro);
	
			foreach ($resultado as $registro) {
				$dados[] = array(
					"dlv_id_pro"                => $registro->dlv_id_pro,
					"dlv_descricao_pro"         => $registro->dlv_descricao_pro,
					"dlv_preco_pro"             => $registro->dlv_preco_pro,
					"dlv_promocao_pro"     	    => $registro->dlv_promocao_pro,
					"usa_tamanho"               => (count($this->ProdutoModel->getQuantidadeTamanho($registro->dlv_id_pro)) > 0)?'1':'0'
				);
			}
		}
	
		echo json_encode(array("produtos" => $dados));
	}

	public function retornar_informacoes_produto($chave, $dlv_id_pro) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->ProdutoModel->get($dlv_id_pro);
	
			if ($resultado) {
				$dados["dlv_detalhamento_pro"] = $resultado->dlv_detalhamento_pro;
				
				
				$dados["dlv_quantidade_pro"]   = $resultado->dlv_quantidade_pro;
				$dados["dlv_unidade_pro"]      = $resultado->dlv_unidade_pro;
				$dados["dlv_exibirfracao_pro"] = $resultado->dlv_exibirfracao_pro;
				$dados["dlv_maxproduto_pro"]   = $resultado->dlv_maxproduto_pro;
				$dados["dlv_minproduto_pro"]   = $resultado->dlv_minproduto_pro;
				
				
				$urlImagem = 'assets/images/produtos/'.$resultado->dlv_id_pro.'.jpg';
				
				if (file_exists($urlImagem)) {
					$dados["url_imagem"] = base_url($urlImagem);
				} else {
					$urlImagem = 'assets/images/produtos/'.$resultado->dlv_id_pro.'.png';
				
					if (file_exists($urlImagem)) {
						$dados["url_imagem"] = base_url($urlImagem);
					} else {
						$dados["url_imagem"] = "";
					}
				}
				
				$dados["tamanhos"] = array();				
				$resultado = $this->ProdutoTamanhoModel->getProdutoTamanhoAtivoEmpresa($dlv_id_pro);
				foreach ($resultado as $registro) {
					$dados["tamanhos"][] = array(
							"dlv_id_tam"                => $registro->dlv_id_tam,
							"dlv_descricao_tam"         => $registro->dlv_descricao_tam,
							"dlv_quantidade_tam"        => $registro->dlv_quantidade_tam,
							"dlv_preco_pxt"             => $registro->dlv_preco_pxt,
							"dlv_promocao_pxt"     	    => $registro->dlv_promocao_pxt
					);
				}
				
				$dados["produtos"] = array();				
				$resultado = $this->ProdutoProdutoModel->getProdutoProdutoAtivoEmpresa($dlv_id_pro);
				foreach ($resultado as $registro) {
					$urlImagem = 'assets/images/produtos/'.$registro->dlv_id_pro.'.jpg';
						
					if (file_exists($urlImagem)) {
						$urlImagem = base_url($urlImagem);
					} else {
						$urlImagem = 'assets/images/produtos/'.$registro->dlv_id_pro.'.png';
							
						if (file_exists($urlImagem)) {
							$urlImagem = base_url($urlImagem);
						} else {
							$urlImagem = "";
						}
					}
					
					$tamanhos         = array();
					$resultadoTamanho = $this->ProdutoTamanhoModel->getProdutoTamanhoAtivoEmpresa($registro->dlv_id_pro);
					foreach ($resultadoTamanho as $registroTamanho) {
						$tamanhos[] = array(
								"dlv_id_tam"                => $registroTamanho->dlv_id_tam,
								"dlv_descricao_tam"         => $registroTamanho->dlv_descricao_tam,
								"dlv_quantidade_tam"        => $registroTamanho->dlv_quantidade_tam,
								"dlv_preco_pxt"             => $registroTamanho->dlv_preco_pxt,
								"dlv_promocao_pxt"     	    => $registroTamanho->dlv_promocao_pxt
														);
					}
				
					$dados["produtos"][] = array(
							"dlv_id_pro"                => $registro->dlv_id_pro,
							"dlv_descricao_pro"         => $registro->dlv_descricao_pro,
							"dlv_detalhamento_pro"      => $registro->dlv_detalhamento_pro,
							"dlv_preco_pro"             => $registro->dlv_preco_pro,
							"dlv_promocao_pro"     	    => $registro->dlv_promocao_pro,
						
							"usa_tamanho"               => (count($this->ProdutoModel->getQuantidadeTamanho($registro->dlv_id_pro)) > 0)?'1':'0',
							"url_imagem"	            => $urlImagem,
							"tamanhos"                  => $tamanhos
					);
				}
				
			}
		}
	
		echo json_encode($dados);
	}
	
	public function ativar_desativar($dlv_id_pro, $ativarDesativar) {
		$produto = array(
				"dlv_ativo_pro"             => $ativarDesativar,
				"dlv_dlvusumod_pro"         => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_pro"       => date('Y-m-d H:i:s')
		);
		
		$dados['resposta'] = false;		
		$atualizado        = $this->ProdutoModel->update($produto, base64_decode($dlv_id_pro));
		
		if ($atualizado) {
			$dados['resposta'] = true;
		}
		
		echo json_encode($dados);
	}
}