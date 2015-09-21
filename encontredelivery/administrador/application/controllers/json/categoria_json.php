<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categoria_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Categoria_Model', 'CategoriaModel');
	}
	
	public function retornar_categorias_empresa($chave, $dlv_dlvemp_cat) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->CategoriaModel->getCategoriaAtivo($dlv_dlvemp_cat);
	
			foreach ($resultado as $registro) {
				$dados[] = array(
					"dlv_id_cat"        => $registro->dlv_id_cat,
					"dlv_dlvemp_cat"    => $registro->dlv_dlvemp_cat,
					"dlv_descricao_cat" => $registro->dlv_descricao_cat,
					"dlv_ordem_cat"     => $registro->dlv_ordem_cat
				);
			}
		}
	
		echo json_encode(array("categorias" => $dados));
	}

	public function ativar_desativar($dlv_id_cat, $ativarDesativar) {
		$categoria = array(
			"dlv_ativo_cat"       => $ativarDesativar,
			"dlv_dlvusumod_cat"   => $this->session->userdata('dlv_id_usu'),
			"dlv_datahoramod_cat" => date('Y-m-d H:i:s')
		);
	
		$dados['resposta'] = false;
		$atualizado        = $this->CategoriaModel->update($categoria, base64_decode($dlv_id_cat));
	
		if ($atualizado) {
			$dados['resposta'] = true;
		}
	
		echo json_encode($dados);
	}	
}