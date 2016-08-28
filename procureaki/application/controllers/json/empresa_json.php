<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Empresa_Model', 'EmpresaModel');
		$this->load->model('Endereco_Model', 'EnderecoModel');
	}
	
		public function retornar_empresas($chave, $bus_busseg_emp, $latitude, $longitude, $distanciaKm) {
		$dados = array();
		
		if ($chave == CHAVE_MD5) {
			$resultado = $this->db->query(" SELECT bus_emp.*, glo_end.*, glo_bai.*, glo_cid.*, glo_est.*, ".
										  "        (acos(sin(radians(".$latitude.")) * sin(radians(glo_latitude_end)) + ".
				                          "         cos(radians(".$latitude.")) * cos(radians(glo_latitude_end)) * ".
				                          "         cos(radians(".$longitude.") - radians(glo_longitude_end))) * 6378) AS distancia_km  ".
										  " FROM bus_emp ".
										  " LEFT JOIN glo_end ON glo_id_end = bus_gloend_emp ".
						   				  " LEFT JOIN glo_bai ON glo_id_bai = glo_globai_end ".
				                          " LEFT JOIN glo_cid ON glo_id_cid = glo_glocid_bai ".
				                          " LEFT JOIN glo_est ON glo_id_est = glo_gloest_cid ".
										  " WHERE bus_id_emp <> 1 ".
				                          "   AND bus_busseg_emp = ".$bus_busseg_emp.
										  "   AND (acos(sin(radians(".$latitude.")) * sin(radians(glo_latitude_end)) + ".
										  "         cos(radians(".$latitude.")) * cos(radians(glo_latitude_end)) * ".
										  "         cos(radians(".$longitude.") - radians(glo_longitude_end))) * 6378) <=  ".$distanciaKm.
			                              " ORDER BY (acos(sin(radians(".$latitude.")) * sin(radians(glo_latitude_end)) + ".
				                          "           cos(radians(".$latitude.")) * cos(radians(glo_latitude_end)) * ".
				                          "           cos(radians(".$longitude.") - radians(glo_longitude_end))) * 6378)")->result();

			foreach ($resultado as $registro) {
				$dados[] = array(
					"bus_id_emp"                   => $registro->bus_id_emp,
					"bus_nome_emp"                 => $registro->bus_nome_emp,
					"bus_aberto_emp"               => $registro->bus_aberto_emp,
					"bus_detalhamento_emp"         => $registro->bus_detalhamento_emp,
					"glo_id_end"                   => $registro->glo_id_end,
					"glo_cep_end"                  => $registro->glo_cep_end,
					"glo_logradouro_end"           => $registro->glo_logradouro_end,
					"glo_latitude_end"             => $registro->glo_latitude_end,
					"glo_longitude_end"            => $registro->glo_longitude_end,
					"glo_id_bai"                   => $registro->glo_id_bai,
					"glo_nome_bai"                 => $registro->glo_nome_bai,
					"glo_id_cid"                   => $registro->glo_id_cid,
					"glo_nome_cid"                 => $registro->glo_nome_cid,
					"glo_uf_est"                   => $registro->glo_uf_est,
					"bus_numero_emp"	           => $registro->bus_numero_emp,
					"bus_complemento_emp"          => $registro->bus_complemento_emp,
					"quantidade_dias_atualizacao"  => 10,
					"distancia_km"                 => $registro->distancia_km,
					"url_imagem"                   => base_url('assets/images/empresas/'.$registro->bus_id_emp.".png")
				);
			}
		}
	
		echo json_encode(array("empresas" => $dados));
	}

}