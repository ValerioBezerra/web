<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Segmento_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Segmento_Model', 'SegmentoModel');
	}
	
	public function retornar_segmentos($chave) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {

			$resultado = $this->db->query(" SELECT * FROM bus_seg ".
//										  " LEFT JOIN bus_emp ON bus_busseg_emp = bus_id_seg ".
//										  " LEFT JOIN glo_end ON bus_gloend_emp = glo_id_end ".
//										  " WHERE bus_id_emp <> 1 ".
//										  "   AND (acos(sin(radians(".$latitude.")) * sin(radians(glo_latitude_end)) + ".
//										  "         cos(radians(".$latitude.")) * cos(radians(glo_latitude_end)) * ".
//										  "         cos(radians(".$longitude.") - radians(glo_longitude_end))) * 6378) <=  ".$distanciaKm.
										  " ORDER BY bus_ordem_seg ")->result();


			foreach ($resultado as $registro) {
				$dados[] = array(
					"bus_id_seg"         => $registro->bus_id_seg,
					"bus_descricao_seg"  => $registro->bus_descricao_seg,
					"bus_icone_seg"      => $registro->bus_icone_seg
				);
			}
		}
	
		echo json_encode(array("segmentos" => $dados));
	}

}