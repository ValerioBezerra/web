<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Endereco extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Endereco_Model', 'EnderecoModel');
	}
	
	public function retornar_endereco_completo($cep) {
		$cep       = str_replace("-", null, $cep);
		$resultado = $this->EnderecoModel->getEnderecoCompleto($cep);
		
		$dados = array();
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
		}
		
		echo json_encode($dados);
	}
	
}