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
	
	public function retornar_cidades($glo_gloest_cid) {
		if (empty($glo_gloest_cid)) {
			$glo_gloest_cid = 0;
		}
		
		$resultado = $this->EnderecoModel->getCidades($glo_gloest_cid);
	
		$dados = array();
	
		foreach ($resultado as $registro) {
			$dados[] = array(
				"glo_id_cid"     => $registro->glo_id_cid,	
				"glo_nome_cid"   => $registro->glo_nome_cid,	
				"glo_gloest_cid" => $registro->glo_gloest_cid	
			);
		}
	
		echo json_encode($dados);
	}
	
}