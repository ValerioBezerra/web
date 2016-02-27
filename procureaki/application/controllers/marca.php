<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marca extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('vei_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Marca_Model', 'MarcaModel');
//		$this->load->model('Empresa_Segmento_Model', 'EmpresaSegmentoModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']     = site_url('marca');
		$dados['NOVO_MARCA'] = site_url('marca/novo');
		$dados['BLC_DADOS']     = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('marca_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['vei_id_mar']        = 0;
		$dados['vei_nome_mar'] = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('marca_cadastro', $dados);
	}
	
	public function editar($vei_id_mar) {
		$vei_id_mar = base64_decode($vei_id_mar);
		$dados = array();
		
		$this->carregarMarca($vei_id_mar, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('marca_cadastro', $dados);
	}
	
	public function salvar() {
		global $vei_id_mar;
		global $vei_nome_mar;
		
		$vei_id_mar        = $this->input->post('vei_id_mar');
		$vei_nome_mar = $this->input->post('vei_nome_mar');
		
		
		if ($this->testarDados()) {
			$marca = array(
				"vei_nome_mar" => $vei_nome_mar
			);
			
			if (!$vei_id_mar) {
				$vei_id_mar = $this->MarcaModel->insert($marca);
			} else {
				$vei_id_mar = $this->MarcaModel->update($marca, $vei_id_mar);
			}

			if (is_numeric($vei_id_mar)) {
				$this->session->set_flashdata('sucesso', 'Marca salva com sucesso.');
				redirect('marca');
			} else {
				$this->session->set_flashdata('erro', $vei_id_mar);
				redirect('marca');
			}
		} else {
			if (!$vei_id_mar) {
				redirect('marca/novo/');
			} else {
				redirect('marca/editar/'.base64_encode($vei_id_mar));
			}			
		}
	}
	
	public function apagar($vei_id_mar) {
		if ($this->testarApagar(base64_decode($vei_id_mar))) {
			$res = $this->MarcaModel->delete(base64_decode($vei_id_mar));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Marca apagada com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar a marca.');
			}
		}
		
		redirect('marca');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_MARCA'] = site_url('marca');
		$dados['ACAO_FORM']         = site_url('marca/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->MarcaModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"VEI_ID_MAR"        => $registro->vei_id_mar,
				"VEI_NOME_MAR" => $registro->vei_nome_mar,
				"EDITAR_MARCA"   => site_url('marca/editar/'.base64_encode($registro->vei_id_mar)),
				"APAGAR_MARCA"   => "abrirConfirmacao('".base64_encode($registro->vei_id_mar)."')"
			);
		}
	}
	
	private function carregarMarca($vei_id_mar, &$dados) {
		$resultado = $this->MarcaModel->getMarca($vei_id_mar);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
					
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $vei_nome_mar;
		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($vei_nome_mar)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_VEI_NOME_MAR', 'has-error');
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_VEI_MAR', TRUE);
			$this->session->set_flashdata('vei_nome_mar', $vei_nome_mar);
		}
		
		return !$erros;
	}
	
	private function testarApagar($vei_id_mar) {
		$erros    = FALSE;
		$mensagem = null;
	
//		$resultado = $this->EmpresaSegmentoModel->getEmpresas($dlv_id_seg);
//
//		if ($resultado) {
//			$erros    = TRUE;
//			$mensagem .= "- Um ou mais empresas mar este segmento.\n";
//		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar a marca:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_VEI_MAR           = $this->session->flashdata('ERRO_VEI_MAR');
		$ERRO_VEI_NOME_MAR = $this->session->flashdata('ERRO_VEI_NOME_MAR');
		$vei_nome_mar      = $this->session->flashdata('vei_nome_mar');
		
		if ($ERRO_VEI_MAR) {
			$dados['vei_nome_mar']      = $vei_nome_mar;
			
			$dados['ERRO_VEI_NOME_MAR'] = $ERRO_VEI_NOME_MAR;
		}
	}
}