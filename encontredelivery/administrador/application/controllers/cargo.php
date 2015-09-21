<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cargo extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Cargo_Model', 'CargoModel');
		$this->load->model('Responsavel_Model', 'ResponsavelModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']  = site_url('cargo');
		$dados['NOVO_CARGO'] = site_url('cargo/novo');
		$dados['BLC_DADOS']  = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('cargo_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['dlv_id_car']        = 0;		
		$dados['dlv_descricao_car'] = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('cargo_cadastro', $dados);
	}
	
	public function editar($dlv_id_car) {
		$dlv_id_car = base64_decode($dlv_id_car);
		$dados = array();
		
		$this->carregarCargo($dlv_id_car, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('cargo_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_car;
		global $dlv_descricao_car;
		
		$dlv_id_car        = $this->input->post('dlv_id_car');			
		$dlv_descricao_car = $this->input->post('dlv_descricao_car');
		
		
		if ($this->testarDados()) {
			$cargo = array(
				"dlv_descricao_car" => $dlv_descricao_car
			);
			
			if (!$dlv_id_car) {
				$dlv_id_car = $this->CargoModel->insert($cargo);
			} else {
				$dlv_id_car = $this->CargoModel->update($cargo, $dlv_id_car);
			}

			if (is_numeric($dlv_id_car)) {
				$this->session->set_flashdata('sucesso', 'Cargo salvo com sucesso.');
				redirect('cargo');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_car);	
				redirect('cargo');
			}
		} else {
			if (!$dlv_id_car) {
				redirect('cargo/novo/');
			} else {
				redirect('cargo/editar/'.base64_encode($dlv_id_car));
			}			
		}
	}
	
	public function apagar($dlv_id_car) {
		if ($this->testarApagar(base64_decode($dlv_id_car))) {
			$res = $this->CargoModel->delete(base64_decode($dlv_id_car));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Cargo apagado com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar cargo.');				
			}
		}
		
		redirect('cargo');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_CARGO']         = site_url('cargo');
		$dados['ACAO_FORM']              = site_url('cargo/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->CargoModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_ID_CAR"        => $registro->dlv_id_car,
				"DLV_DESCRICAO_CAR" => $registro->dlv_descricao_car,
				"EDITAR_CARGO"   => site_url('cargo/editar/'.base64_encode($registro->dlv_id_car)),
				"APAGAR_CARGO"   => "abrirConfirmacao('".base64_encode($registro->dlv_id_car)."')"
			);
		}
	}
	
	private function carregarCargo($dlv_id_car, &$dados) {
		$resultado = $this->CargoModel->getCargo($dlv_id_car);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
					
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $dlv_descricao_car;
		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($dlv_descricao_car)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_DLV_DESCRICAO_CAR', 'has-error');				
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_CAR', TRUE);				
			$this->session->set_flashdata('dlv_descricao_car', $dlv_descricao_car);				
		}
		
		return !$erros;
	}
	
	private function testarApagar($dlv_id_car) {
		$erros    = FALSE;
		$mensagem = null;
		
		$resultado = $this->ResponsavelModel->getResponsaveisCargo($dlv_id_car);
		
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais responsável com este cargo.\n";
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o cargo:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
		
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_CAR           = $this->session->flashdata('ERRO_DLV_CAR');
		$ERRO_DLV_DESCRICAO_CAR = $this->session->flashdata('ERRO_DLV_DESCRICAO_CAR');
		$dlv_descricao_car      = $this->session->flashdata('dlv_descricao_car');
		
		if ($ERRO_DLV_CAR) {
			$dados['dlv_descricao_car']      = $dlv_descricao_car;
			
			$dados['ERRO_DLV_DESCRICAO_CAR'] = $ERRO_DLV_DESCRICAO_CAR;
		}
	}
}