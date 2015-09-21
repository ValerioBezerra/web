<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Taxas_Valores extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_alttaxa_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Empresa_Model', 'EmpresaModel');		
	}
	
	public function index() {
		$dados = array();
		
		$dados['dlv_taxaentrega_emp']   = 'R$ '.number_format($this->session->userdata('dlv_taxaentrega_emp'),  2, ',', '.');
		$dados['dlv_valorminimo_emp']   = 'R$ '.number_format($this->session->userdata('dlv_valorminimo_emp'),  2, ',', '.');
		$dados['dlv_tempomedio_emp']    = $this->session->userdata('dlv_tempomedio_emp');
        $dados['dlv_fechamentoaut_emp'] = ($this->session->userdata('dlv_fechamentoaut_emp') == 1)?'checked':'';
		
		$dados['ACAO_TAXAS_VALORES'] = site_url('taxas_valores/modificar');
		$dados['MASCARA_HORA']       = MASCARA_HORA;
		
		$this->parser->parse('taxas_valores_view', $dados);
	}
	
	public function modificar() {
		global $dlv_id_emp;
		
		$dlv_id_emp = $this->session->userdata('dlv_id_emp');
		
		global $dlv_taxaentrega_emp;
		global $dlv_valorminimo_emp;
		global $dlv_tempomedio_emp;
        global $dlv_fechamentoaut_emp;
		
		$dlv_taxaentrega_emp    = $this->input->post('dlv_taxaentrega_emp');
		$dlv_valorminimo_emp    = $this->input->post('dlv_valorminimo_emp');
		$dlv_tempomedio_emp     = $this->input->post('dlv_tempomedio_emp');
        $dlv_fechamentoaut_emp  = $this->input->post('dlv_fechamentoaut_emp');

		$dlv_taxaentrega_emp = str_replace("R$ ", null, $dlv_taxaentrega_emp);
		$dlv_taxaentrega_emp = str_replace(".", null, $dlv_taxaentrega_emp);
		$dlv_taxaentrega_emp = str_replace(",", ".", $dlv_taxaentrega_emp);
		
		$dlv_valorminimo_emp = str_replace("R$ ", null, $dlv_valorminimo_emp);
		$dlv_valorminimo_emp = str_replace(".", null, $dlv_valorminimo_emp);
		$dlv_valorminimo_emp = str_replace(",", ".", $dlv_valorminimo_emp);
		
		$empresa = array(
			"dlv_taxaentrega_emp"   => $dlv_taxaentrega_emp,
			"dlv_valorminimo_emp"   => $dlv_valorminimo_emp,
			"dlv_tempomedio_emp"    => $dlv_tempomedio_emp,
            "dlv_fechamentoaut_emp" => ($dlv_fechamentoaut_emp)?'1':'0',
			"dlv_dlvusumod_emp"     => $this->session->userdata('dlv_id_usu'),
			"dlv_datahoramod_emp"   => date('Y-m-d H:i:s')
		);	

		$dlv_id_usu = $this->EmpresaModel->update($empresa, $dlv_id_emp);
		
		if (is_numeric($dlv_id_usu)) {
			$this->session->set_userdata('dlv_taxaentrega_emp', $dlv_taxaentrega_emp);
			$this->session->set_userdata('dlv_valorminimo_emp', $dlv_valorminimo_emp);
			$this->session->set_userdata('dlv_tempomedio_emp', $dlv_tempomedio_emp);
            $this->session->set_userdata('dlv_fechamentoaut_emp', ($dlv_fechamentoaut_emp)?'1':'0');
			$this->session->set_flashdata('sucesso', 'Taxas e valores modificados com sucesso.');
		}
		
		redirect('taxas_valores/');
	}
	
}