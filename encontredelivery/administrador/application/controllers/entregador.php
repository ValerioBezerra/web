<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entregador extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_altarea_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Entregador_Model', 'EntregadorModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']  = site_url('entregador');
		$dados['NOVO_ENTREGADOR'] = site_url('entregador/novo');
		$dados['BLC_DADOS']  = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('entregador_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['dlv_id_ent']        = 0;		
		$dados['dlv_nome_ent'] = '';
		$dados['dlv_ativo_ent']     = 'checked';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);

		$this->parser->parse('entregador_cadastro', $dados);
	}
	
	public function editar($dlv_id_ent) {
		$dlv_id_ent = base64_decode($dlv_id_ent);
		$dados = array();
		
		$this->carregarEntregador($dlv_id_ent, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('entregador_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_ent;
		global $dlv_nome_ent;
		global $dlv_ativo_ent;
		
		$dlv_id_ent   		 = $this->input->post('dlv_id_ent');			
		$dlv_nome_ent   = $this->input->post('dlv_nome_ent');
		$dlv_ativo_ent 		 = $this->input->post('dlv_ativo_ent');
		
		
		

		
		if ($this->testarDados()) {
			$entregador = array(
				"dlv_dlvemp_ent"      => $this->session->userdata('dlv_id_emp'),
				"dlv_nome_ent"        => $dlv_nome_ent,
				"dlv_ativo_ent"       => ($dlv_ativo_ent)?'1':'0',
				"dlv_dlvusumod_ent"   => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_ent" => date('Y-m-d H:i:s')
			);
			
			if (!$dlv_id_ent) {	
				$dlv_id_ent = $this->EntregadorModel->insert($entregador);
			} else {
				$dlv_id_ent = $this->EntregadorModel->update($entregador, $dlv_id_ent);
			}

			if (is_numeric($dlv_id_ent)) {
				$this->session->set_flashdata('sucesso', 'Entregador salvo com sucesso.');
				redirect('entregador');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_ent);	
				redirect('entregador');
			}
		} else {
			if (!$dlv_id_ent) {
				redirect('entregador/novo/');
			} else {
				redirect('entregador/editar/'.base64_encode($dlv_id_ent));
			}			
		}
	}
	
	public function apagar($dlv_id_ent) {
		$res = $this->EntregadorModel->delete(base64_decode($dlv_id_ent));

		if ($res) {
			$this->session->set_flashdata('sucesso', 'Entregador apagado com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível apagar o entregador.');				
		}
		
		redirect('entregador');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_ENTREGADOR']   = site_url('entregador');
		$dados['ACAO_FORM']          = site_url('entregador/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->EntregadorModel->getEntregadorEmpresa($this->session->userdata('dlv_id_emp'));
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_NOME_ENT"   		=> $registro->dlv_nome_ent,				
				"DLV_ATIVO_ENT"       		=> ($registro->dlv_ativo_ent == 1)?'checked':'',
				"EDITAR_ENTREGADOR" 		    => site_url('entregador/editar/'.base64_encode($registro->dlv_id_ent)),
				"APAGAR_ENTREGADOR"     		=> "abrirConfirmacao('".base64_encode($registro->dlv_id_ent)."')"
			);
		}
	}
	
	private function carregarEntregador($dlv_id_ent, &$dados) {
		$resultado = $this->EntregadorModel->getEntregador($dlv_id_ent);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}

		$dados['dlv_ativo_ent'] = ($resultado->dlv_ativo_ent == 1)?'checked':'';

		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $dlv_id_ent;
		global $dlv_nome_ent;
		global $dlv_ativo_ent;

				
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($dlv_nome_ent)) {
			$erros    = TRUE;
			$mensagem .= "- Nome não preenchido.\n";
			$this->session->set_flashdata('ERRO_DLV_NOME_ENT', 'has-error');
		}
		
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_ENT', TRUE);				
			$this->session->set_flashdata('dlv_nome_ent', $dlv_nome_ent);	
			$this->session->set_flashdata('dlv_ativo_ent', $dlv_ativo_ent);
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_ENT   		 = $this->session->flashdata('ERRO_DLV_ENT');
		$ERRO_DLV_NOME_ENT  = $this->session->flashdata('ERRO_DLV_NOME_ENT');
		
		$dlv_nome_ent   = $this->session->flashdata('dlv_nome_ent');
		$dlv_ativo_ent       = $this->session->flashdata('dlv_ativo_ent');
		
		if ($ERRO_DLV_ENT) {			
			$dados['dlv_nome_ent'] = $dlv_nome_ent;
			$dados['dlv_ativo_ent']     = ($dlv_ativo_ent == 1)?'checked':'';

				
			$dados['ERRO_DLV_NOME_ENT']  = $ERRO_DLV_NOME_ENT;

		}
	}
}