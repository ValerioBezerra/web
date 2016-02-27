<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adicional_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Produto_Adicional_Model', 'ProdutoAdicionalModel');
	}
	
	public function retornar_adicionais_produto($chave, $dlv_dlvpro_pxa) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->ProdutoAdicionalModel->getProdutoAdicionalAtivoEmpresa($dlv_dlvpro_pxa);
	
			foreach ($resultado as $registro) {
				$dados[] = array(
					"dlv_id_adi"         => $registro->dlv_id_adi,
					"dlv_descricao_adi"  => $registro->dlv_descricao_adi,
					"dlv_valor_adi"      => $registro->dlv_valor_adi
				);
			}
		}
	
		echo json_encode(array("adicionais" => $dados));
	}
	
}