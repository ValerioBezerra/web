<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipo_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
	}

	public function retornar_tipos($chave, $bus_busseg_emp, $latitude, $longitude, $distanciaKm) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {

			$resultado = $this->db->query(" SELECT bus_id_tip, ".
										  "        MAX(bus_descricao_tip) AS bus_descricao_tip, ".
										  "        COUNT(bus_id_emp) quantidade_empresa ".
										  " FROM bus_tip ".
										  " LEFT JOIN bus_ext ON bus_id_tip = bus_bustip_ext ".
										  " LEFT JOIN bus_emp ON bus_busemp_ext = bus_id_emp ".
										  " LEFT JOIN glo_end ON bus_gloend_emp = glo_id_end ".
										  " WHERE bus_id_emp <> 1 ".
														" AND bus_busseg_tip = ".$bus_busseg_emp.
										  "   AND (acos(sin(radians(".$latitude.")) * sin(radians(glo_latitude_end)) + ".
										  "         cos(radians(".$latitude.")) * cos(radians(glo_latitude_end)) * ".
										  "         cos(radians(".$longitude.") - radians(glo_longitude_end))) * 6378) <=  ".$distanciaKm.
										  " GROUP BY bus_id_tip ")->result();


			foreach ($resultado as $registro) {
				$dados[] = array(
					"bus_id_tip"         => $registro->bus_id_tip,
					"bus_descricao_tip"  => $registro->bus_descricao_tip,
					"quantidade_empresa" => $registro->quantidade_empresa
				);
			}
		}
	
		echo json_encode(array("tipos" => $dados));
	}

}