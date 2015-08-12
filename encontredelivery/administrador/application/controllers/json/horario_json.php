<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Horario_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Horario_Model', 'HorarioModel');
	}
	
	public function retornar_horarios_empresa($chave, $dlv_dlvemp_exh) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->HorarioModel->getHorariosEmpresaGroupBy($dlv_dlvemp_exh);
	
			foreach ($resultado as $registro) {
				$resultado_dia = $this->HorarioModel->getHorariosEmpresaDia($dlv_dlvemp_exh, $registro->dlv_dia_exh);
				
				$horarios  = "";
				$separador = "";
				
				foreach ($resultado_dia as $registro_dia) {
					$horarios .= $separador.$registro_dia->dlv_horaini_exh." até ".$registro_dia->dlv_horafin_exh;
					$separador = "\n";
				}
				
				
				$dados[] = array(
					"dlv_dia_exh" => $registro->dlv_dia_exh,
					"horarios"    => $horarios	
				);
			}
		}
	
		echo json_encode(array("horarios" => $dados));
	}	

}