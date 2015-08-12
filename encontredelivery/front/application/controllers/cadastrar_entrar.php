<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cadastrar_Entrar extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
	
		$this->layout = LAYOUT_PADRAO;
	}	
	public function index() {
		$dados = array();
		$this->parser->parse('estabelecimentos', $dados);
	}
}
