<?php if (!$_SERVER['HTTP_REFERER']) $this->redirect('');

class Endereco_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Endereco_Model', 'EnderecoModel');
	}
	
	public function retornar_endereco_cep($cep) {
		$dados = array();
		$cep   = str_replace("-", null, $cep);
	
		$resultado = $this->EnderecoModel->getEnderecoCompleto($cep);
			
		if ($resultado) {
			$dados['glo_id_end']          = $resultado->glo_id_end;
			$dados['glo_cep_end']         = $resultado->glo_cep_end;
			$dados['glo_cep_end_mascara'] = mascara($resultado->glo_cep_end, MASCARA_CEP);
			$dados['glo_logradouro_end']  = $resultado->glo_logradouro_end;
			$dados['glo_id_bai']          = $resultado->glo_id_bai;
			$dados['glo_nome_bai']        = $resultado->glo_nome_bai;
			$dados['glo_id_cid']          = $resultado->glo_id_cid;
			$dados['glo_nome_cid']        = $resultado->glo_nome_cid;
			$dados['glo_uf_est']          = $resultado->glo_uf_est;
		}
	
		echo json_encode($dados);
	}
	
	public function retornar_enderecos_logradouro($glo_glocid_bai, $glo_globai_end, $glo_logradouro_end) {
		$dados = array();
	
		$glo_logradouro_end = str_ireplace("%20", " ", $glo_logradouro_end);
	
		$resultado = $this->EnderecoModel->getEnderecoCompletoLogradouro($glo_glocid_bai, $glo_globai_end, $glo_logradouro_end);

		foreach ($resultado as $registro) {
			$dados[] = array(
				"glo_id_end"          => $registro->glo_id_end,
				"glo_cep_end"         => $registro->glo_cep_end,
				"glo_cep_end_mascara" => mascara($registro->glo_cep_end, MASCARA_CEP),					
				"glo_logradouro_end"  => $registro->glo_logradouro_end,
				"glo_id_bai"          => $registro->glo_id_bai,
				"glo_nome_bai"        => $registro->glo_nome_bai,
				"glo_id_cid"          => $registro->glo_id_cid,
				"glo_nome_cid"        => $registro->glo_nome_cid,
				"glo_uf_est"          => $registro->glo_uf_est
			);
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
	
	public function retornar_bairros($glo_glocid_bai) {
		if (empty($glo_glocid_bai)) {
			$glo_glocid_bai = 0;
		}
	
		$resultado = $this->EnderecoModel->getBairros($glo_glocid_bai);
	
		$dados = array();
	
		foreach ($resultado as $registro) {
			$dados[] = array(
					"glo_id_bai"     => $registro->glo_id_bai,
					"glo_nome_bai"   => $registro->glo_nome_bai,
					"glo_glocid_bai" => $registro->glo_glocid_bai
			);
		}
	
		echo json_encode($dados);
	}
	
	public function salvar_novo_endereco_sessao($cep) {
		$dados['resposta'] = false;
		$cep               = str_replace("-", null, $cep);
	
		$resultado = $this->EnderecoModel->getEnderecoCompleto($cep);
		if ($resultado) {
			$this->session->set_userdata(array('endereco_escolhido'  => TRUE,
											   'dlv_id_ecl'          => NULL,
											   'dlv_gloend_ecl'      => $resultado->glo_id_end,
											   'dlv_numero_ecl'      => NULL,
											   'dlv_complemento_ecl' => NULL
			));		

			$dados['resposta'] = true;
		}
		
		echo json_encode($dados);	
	}
	
}