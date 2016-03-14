<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plano extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('bus_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Plano_Model', 'PlanoModel');

	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']     = site_url('plano');
		$dados['NOVO_PLANO'] = site_url('plano/novo');
		$dados['BLC_DADOS']     = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('plano_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['bus_id_pla']        = 0;
		$dados['bus_descricao_pla'] = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('plano_cadastro', $dados);
	}
	
	public function editar($bus_id_pla) {
		$bus_id_pla = base64_decode($bus_id_pla);
		$dados = array();
		
		$this->carregarPlano($bus_id_pla, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('plano_cadastro', $dados);
	}
	
	public function salvar() {
		global $bus_id_pla;
		global $bus_descricao_pla;
		
		$bus_id_pla        = $this->input->post('bus_id_pla');
		$bus_descricao_pla = $this->input->post('bus_descricao_pla');
		
		
		if ($this->testarDados()) {
			$plano = array(
				"bus_descricao_pla" => $bus_descricao_pla
			);
			
			if (!$bus_id_pla) {
				$bus_id_pla = $this->PlanoModel->insert($plano);
			} else {
				$bus_id_pla = $this->PlanoModel->update($plano, $bus_id_pla);
			}

			if (is_numeric($bus_id_pla)) {
				$this->session->set_flashdata('sucesso', 'plano salvo com sucesso.');
				redirect('plano');
			} else {
				$this->session->set_flashdata('erro', $bus_id_pla);
				redirect('plano');
			}
		} else {
			if (!$bus_id_pla) {
				redirect('plano/novo/');
			} else {
				redirect('plano/editar/'.base64_encode($bus_id_pla));
			}			
		}
	}
	
	public function apagar($bus_id_pla) {
		if ($this->testarApagar(base64_decode($bus_id_pla))) {
			$res = $this->PlanoModel->delete(base64_decode($bus_id_pla));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Plano apagado com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar plano.');
			}
		}
		
		redirect('plano');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_PLANO'] = site_url('plano');
		$dados['ACAO_FORM']         = site_url('plano/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->PlanoModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"BUS_ID_PLA"        => $registro->bus_id_pla,
				"BUS_DESCRICAO_PLA" => $registro->bus_descricao_pla,
				"EDITAR_PLANO"   => site_url('plano/editar/'.base64_encode($registro->bus_id_pla)),
				"APAGAR_PLANO"   => "abrirConfirmacao('".base64_encode($registro->bus_id_pla)."')"
			);
		}
	}
	
	private function carregarPlano($bus_id_pla, &$dados) {
		$resultado = $this->PlanoModel->getPlano($bus_id_pla);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
					
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $bus_descricao_pla;
		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($bus_descricao_pla)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_BUS_DESCRICAO_PLA', 'has-error');
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_BUS_PLA', TRUE);
			$this->session->set_flashdata('bus_descricao_pla', $bus_descricao_pla);
		}
		
		return !$erros;
	}
	
	private function testarApagar($bus_id_pla) {
		$erros    = FALSE;
		$mensagem = null;
	
//		$resultado = $this->EmpresaSegmentoModel->getEmpresas($dlv_id_seg);
//
//		if ($resultado) {
//			$erros    = TRUE;
//			$mensagem .= "- Um ou mais empresas seg este segmento.\n";
//		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o plano:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_BUS_PLA           = $this->session->flashdata('ERRO_BUS_PLA');
		$ERRO_BUS_DESCRICAO_PLA = $this->session->flashdata('ERRO_BUS_DESCRICAO_PLA');
		$bus_descricao_pla      = $this->session->flashdata('bus_descricao_pla');
		
		if ($ERRO_BUS_PLA) {
			$dados['bus_descricao_pla']      = $bus_descricao_pla;
			
			$dados['ERRO_BUS_DESCRICAO_PLA'] = $ERRO_BUS_DESCRICAO_PLA;
		}
	}
}