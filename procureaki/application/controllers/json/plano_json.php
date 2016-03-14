<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plano_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Plano_Model', 'PlanoModel');
	}
	
	public function retornar_planos($chave) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->PlanoModel->get();
	
			foreach ($resultado as $registro) {
				$dados[] = array(
					"dlv_id_pla"         => $registro->dlv_id_pla,
					"dlv_descricao_pla"  => $registro->dlv_descricao_pla
				);
			}
		}
	
		echo json_encode(array("planos" => $dados));
	}

}