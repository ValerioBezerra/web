<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forma_Pagamento_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Empresa_Forma_Pagamento_Model', 'EmpresaFormaPagamentoEmpresaModel');
	}
	
	public function retornar_formas_pagamento_empresa($chave, $dlv_dlvemp_exf) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->EmpresaFormaPagamentoEmpresaModel->getFormasPagamentoEmpresa($dlv_dlvemp_exf);
	
			foreach ($resultado as $registro) {
				$dados[] = array(
					"dlv_id_fpg"           => $registro->dlv_id_fpg,
					"dlv_descricao_fpg"    => $registro->dlv_descricao_fpg,
					"dlv_calculatroco_fpg" => $registro->dlv_calculatroco_fpg,
					"url_imagem"           => base_url('assets/images/formas_pagamento/'.$registro->dlv_id_fpg.".png")
				);
			}
		}
	
		echo json_encode(array("formas_pagamento" => $dados));
	}	

}