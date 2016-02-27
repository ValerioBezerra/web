<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Produto_Adicional_Model', 'ProdutoAdicionalModel');
		$this->load->model('Empresa_Rede_Social_Model', 'EmpresaRedeSocialModel');
		$this->load->model('Produto_Tamanho_Model', 'ProdutoTamanhoModel');
		$this->load->model('Taxa_Bairro_Model', 'TaxaBairroModel');
	}
		
	public function retornar_produto_adicional($dlv_dlvpro_pxa, $dlv_dlvadi_pxa) {
		$resultado = $this->ProdutoAdicionalModel->getEmpresaAdicionalProduto($dlv_dlvpro_pxa, $dlv_dlvadi_pxa);
	
		$dados = array();
	
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
		}
	
		echo json_encode($dados);
	}
	
	public function retornar_rede_social_empresa($dlv_dlvred_exr) {
		$resultado = $this->EmpresaRedeSocialModel->getEmpresaRedeSocial($dlv_dlvred_exr, $this->session->userdata('dlv_id_emp'));
	
		$dados = array();
	
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
		}
	
		echo json_encode($dados);
	}
	
	public function retornar_tamanho_produto($dlv_dlvpro_pxt, $dlv_dlvtam_pxt) {
		$resultado = $this->ProdutoTamanhoModel->getEmpresaTamanhoProduto($dlv_dlvpro_pxt, $dlv_dlvtam_pxt);
	
		$dados = array();
	
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
		}
	
		echo json_encode($dados);
	}
	
	public function retornar_taxa_bairro($dlv_dlvaen_txb, $dlv_globai_txb) {
		$resultado = $this->TaxaBairroModel->getAreaEntregaTaxaBairro($dlv_dlvaen_txb, $dlv_globai_txb);
	
		$dados = array();
	
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
		}
	
		echo json_encode($dados);
	}

}