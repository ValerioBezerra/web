<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuracao_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Configuracao_Model', 'ConfiguracaoModel');
	}
	
	public function retornar_versao($chave) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->ConfiguracaoModel->get();
			
			if ($resultado) {
				foreach ($resultado as $chave => $valor) {
					$dados[$chave] = $valor;
				}
			}
		}
	
		echo json_encode($dados);
	}

}