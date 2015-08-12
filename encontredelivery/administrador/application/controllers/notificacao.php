<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notificacao extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
	if ($this->session->userdata('dlv_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('GCM_Model', 'GCMModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ACAO_FORM'] = site_url('notificacao/enviar');
		
		$this->carregarDadosFlash($dados);		
		$this->parser->parse('notificacao_view', $dados);
	}
	
	public function contador($contador) {
		$this->session->set_flashdata('sucesso', $contador.' notificações enviadas com sucesso.');
		redirect('notificacao');
	}
	
	public function enviar() {
		global $mensagem;
		$mensagem = $this->input->post('mensagem');
		
		if ($this->testarDados()) {
			$contador         = 0;
			$inicio_limit     = 0;
			$quantidade_total = count($this->GCMModel->getTodos());
			
			for ($i = 0; $i <= $quantidade_total; $i++) {
				$resultado = $this->GCMModel->getTodosLimit($inicio_limit, 300); 
				$notificacoes = array();
				foreach ($resultado as $registro) {
					$notificacoes[] = $registro->dlv_regid_gcm;
					$contador++;
				}
				
				$this->GCMModel->send_notification($notificacoes, array("msg" => $mensagem, "idPedido" => '', "nomeEmpresa" => '', "foneEmpresa" => ''));
				
				$i            += 300;
				$inicio_limit += 300;
			}
			
			redirect('notificacao/contador/'.$contador);
		} else {
			redirect('notificacao');
		}
	}
	
	private function testarDados() {
		global $mensagem;
		$erros = FALSE;
		
		if (empty($mensagem)) {
			$erros    = TRUE;
			$mensagem .= "- Mensagem não preenchida.\n";
			$this->session->set_flashdata('ERRO_MENSAGEM', 'has-error');
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_MENSAGEM          = $this->session->flashdata('ERRO_MENSAGEM');
		$dados['ERRO_MENSAGEM'] = $ERRO_MENSAGEM;
	}
	
}