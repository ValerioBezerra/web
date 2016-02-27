<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cambio extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('vei_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Cambio_Model', 'CambioModel');
//		$this->load->model('Empresa_Segmento_Model', 'EmpresaSegmentoModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']     = site_url('cambio');
		$dados['NOVO_CAMBIO'] = site_url('cambio/novo');
		$dados['BLC_DADOS']     = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('cambio_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['vei_id_cam']        = 0;
		$dados['vei_descricao_cam'] = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('cambio_cadastro', $dados);
	}
	
	public function editar($vei_id_cam) {
		$vei_id_cam = base64_decode($vei_id_cam);
		$dados = array();
		
		$this->carregarCambio($vei_id_cam, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('cambio_cadastro', $dados);
	}
	
	public function salvar() {
		global $vei_id_cam;
		global $vei_descricao_cam;
		
		$vei_id_cam        = $this->input->post('vei_id_cam');
		$vei_descricao_cam = $this->input->post('vei_descricao_cam');
		
		
		if ($this->testarDados()) {
			$cambio = array(
				"vei_descricao_cam" => $vei_descricao_cam
			);
			
			if (!$vei_id_cam) {
				$vei_id_cam = $this->CambioModel->insert($cambio);
			} else {
				$vei_id_cam = $this->CambioModel->update($cambio, $vei_id_cam);
			}

			if (is_numeric($vei_id_cam)) {
				$this->session->set_flashdata('sucesso', 'câmbio salvo cam sucesso.');
				redirect('cambio');
			} else {
				$this->session->set_flashdata('erro', $vei_id_cam);
				redirect('cambio');
			}
		} else {
			if (!$vei_id_cam) {
				redirect('cambio/novo/');
			} else {
				redirect('cambio/editar/'.base64_encode($vei_id_cam));
			}			
		}
	}
	
	public function apagar($vei_id_cam) {
		if ($this->testarApagar(base64_decode($vei_id_cam))) {
			$res = $this->CambioModel->delete(base64_decode($vei_id_cam));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Câmbio apagado cam sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar câmbio.');
			}
		}
		
		redirect('cambio');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_CAMBIO'] = site_url('cambio');
		$dados['ACAO_FORM']         = site_url('cambio/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->CambioModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"VEI_ID_CAM"        => $registro->vei_id_cam,
				"VEI_DESCRICAO_CAM" => $registro->vei_descricao_cam,
				"EDITAR_CAMBIO"    => site_url('cambio/editar/'.base64_encode($registro->vei_id_cam)),
				"APAGAR_CAMBIO"    => "abrirConfirmacao('".base64_encode($registro->vei_id_cam)."')"
			);
		}
	}
	
	private function carregarCambio($vei_id_cam, &$dados) {
		$resultado = $this->CambioModel->getCambio($vei_id_cam);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
					
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $vei_descricao_cam;
		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($vei_descricao_cam)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_VEI_DESCRICAO_CAM', 'has-error');
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_VEI_CAM', TRUE);
			$this->session->set_flashdata('vei_descricao_cam', $vei_descricao_cam);
		}
		
		return !$erros;
	}
	
	private function testarApagar($vei_id_cam) {
		$erros    = FALSE;
		$mensagem = null;
	
//		$resultado = $this->EmpresaSegmentoModel->getEmpresas($dlv_id_seg);
//
//		if ($resultado) {
//			$erros    = TRUE;
//			$mensagem .= "- Um ou mais empresas cam este segmento.\n";
//		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o segmento:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_VEI_CAM           = $this->session->flashdata('ERRO_VEI_CAM');
		$ERRO_VEI_DESCRICAO_CAM = $this->session->flashdata('ERRO_VEI_DESCRICAO_CAM');
		$vei_descricao_cam      = $this->session->flashdata('vei_descricao_cam');
		
		if ($ERRO_VEI_CAM) {
			$dados['vei_descricao_cam']      = $vei_descricao_cam;
			
			$dados['ERRO_VEI_DESCRICAO_CAM'] = $ERRO_VEI_DESCRICAO_CAM;
		}
	}
}