<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Segmento_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Segmento_Model', 'SegmentoModel');
	}
	
	public function retornar_segmentos($chave) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->SegmentoModel->get();
	
			foreach ($resultado as $registro) {
				$dados[] = array(
					"dlv_id_seg"         => $registro->dlv_id_seg,
					"dlv_descricao_seg"  => $registro->dlv_descricao_seg
				);
			}
		}
	
		echo json_encode(array("segmentos" => $dados));
	}

}