<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
	
		$this->layout = LAYOUT_PADRAO;
		$this->load->model('Endereco_Model', 'EnderecoModel');
		$this->session->set_userdata(array('endereco_escolhido'  => FALSE,
										   'dlv_id_ecl'          => NULL,
										   'dlv_gloend_ecl'      => NULL,
										   'dlv_numero_ecl'      => NULL,
										   'dlv_complemento_ecl' => NULL
		));
		
	}
			
	public function index() {
		$dados = array();
		
		$dados['URL_VERIFICAR_CEP']      = base_url('json/endereco_json/retornar_endereco_cep/'); 
		$dados['URL_ENDERECO_ESTADO']    = site_url('json/endereco_json/retornar_cidades/');
		$dados['URL_ENDERECO_CIDADE']    = site_url('json/endereco_json/retornar_bairros/');
		$dados['URL_VERIFICAR_ENDERECO'] = base_url('json/endereco_json/retornar_enderecos_logradouro/');
		$dados['URL_CONFIRMAR_ENDERECO'] = base_url('json/endereco_json/salvar_novo_endereco_sessao/');
		
		$this->carregarEstados($dados);
		$this->parser->parse('inicio_view', $dados);
	}
	
	public function sair() {
		if (!isset($_SESSION)) {
			session_start();
		}
		
		if ($this->session->userdata('cliente_logado')) {
			$this->session->destroy();
		}
		
		redirect('');
	}
	
	private function carregarEstados(&$dados) {
		$resultado = $this->EnderecoModel->getEstados();
	
		$dados['BLC_ESTADOS'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_ESTADOS'][] = array(
					"GLO_ID_EST" => $registro->glo_id_est,
					"GLO_UF_EST" => $registro->glo_uf_est
			);
		}
	}
	
}
