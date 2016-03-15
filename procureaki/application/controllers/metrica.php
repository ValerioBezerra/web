<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Metrica extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('bus_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Metrica_Model', 'MetricaModel');

	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']     = site_url('metrica');
		$dados['NOVO_METRICA'] = site_url('metrica/novo');
		$dados['BLC_DADOS']     = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('metrica_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['bus_id_met']        = 0;
		$dados['bus_descricao_met'] = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('metrica_cadastro', $dados);
	}
	
	public function editar($bus_id_met) {
		$bus_id_met = base64_decode($bus_id_met);
		$dados = array();
		
		$this->carregarMetrica($bus_id_met, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('metrica_cadastro', $dados);
	}
	
	public function salvar() {
		global $bus_id_met;
		global $bus_descricao_met;
		
		$bus_id_met        = $this->input->post('bus_id_met');
		$bus_descricao_met = $this->input->post('bus_descricao_met');
		
		
		if ($this->testarDados()) {
			$metrica = array(
				"bus_descricao_met" => $bus_descricao_met
			);
			
			if (!$bus_id_met) {
				$bus_id_met = $this->MetricaModel->insert($metrica);
			} else {
				$bus_id_met = $this->MetricaModel->update($metrica, $bus_id_met);
			}

			if (is_numeric($bus_id_met)) {
				$this->session->set_flashdata('sucesso', 'metrica salva com sucesso.');
				redirect('metrica');
			} else {
				$this->session->set_flashdata('erro', $bus_id_met);
				redirect('metrica');
			}
		} else {
			if (!$bus_id_met) {
				redirect('metrica/novo/');
			} else {
				redirect('metrica/editar/'.base64_encode($bus_id_met));
			}			
		}
	}
	
	public function apagar($bus_id_met) {
		if ($this->testarApagar(base64_decode($bus_id_met))) {
			$res = $this->MetricaModel->delete(base64_decode($bus_id_met));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Metrica apagada com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar a metrica.');
			}
		}
		
		redirect('metrica');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_METRICA'] = site_url('metrica');
		$dados['ACAO_FORM']         = site_url('metrica/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->MetricaModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"BUS_ID_MET"        => $registro->bus_id_met,
				"BUS_DESCRICAO_MET" => $registro->bus_descricao_met,
				"EDITAR_METRICA"   => site_url('metrica/editar/'.base64_encode($registro->bus_id_met)),
				"APAGAR_METRICA"   => "abrirConfirmacao('".base64_encode($registro->bus_id_met)."')"
			);
		}
	}
	
	private function carregarMetrica($bus_id_met, &$dados) {
		$resultado = $this->MetricaModel->getMetrica($bus_id_met);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
					
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $bus_descricao_met;
		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($bus_descricao_met)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_BUS_DESCRICAO_MET', 'has-error');
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_BUS_MET', TRUE);
			$this->session->set_flashdata('bus_descricao_met', $bus_descricao_met);
		}
		
		return !$erros;
	}
	
	private function testarApagar($bus_id_met) {
		$erros    = FALSE;
		$mensagem = null;
	
//		$resultado = $this->EmpresaSegmentoModel->getEmpresas($dlv_id_seg);
//
//		if ($resultado) {
//			$erros    = TRUE;
//			$mensagem .= "- Um ou mais empresas seg este segmento.\n";
//		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar a metrica:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_BUS_MET           = $this->session->flashdata('ERRO_BUS_MET');
		$ERRO_BUS_DESCRICAO_MET = $this->session->flashdata('ERRO_BUS_DESCRICAO_MET');
		$bus_descricao_met      = $this->session->flashdata('bus_descricao_met');
		
		if ($ERRO_BUS_MET) {
			$dados['bus_descricao_met']      = $bus_descricao_met;
			
			$dados['ERRO_BUS_DESCRICAO_MET'] = $ERRO_BUS_DESCRICAO_MET;
		}
	}
}