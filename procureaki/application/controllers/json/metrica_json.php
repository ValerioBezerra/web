<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Metrica_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Metrica_Model', 'MetricaModel');
	}
	
	public function retornar_metricas($chave) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->MetricaModel->get();
	
			foreach ($resultado as $registro) {
				$dados[] = array(
					"dlv_id_met"         => $registro->dlv_id_met,
					"dlv_descricao_met"  => $registro->dlv_descricao_met
				);
			}
		}
	
		echo json_encode(array("metricas" => $dados));
	}

}