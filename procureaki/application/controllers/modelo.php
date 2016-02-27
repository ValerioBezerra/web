<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modelo extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('vei_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Modelo_Model', 'ModeloModel');
		$this->load->model('Marca_Model', 'MarcaModel');
		$this->load->model('Tipo_Model', 'TipoModel');
//		$this->load->model('Empresa_Segmento_Model', 'EmpresaSegmentoModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']     = site_url('modelo');
		$dados['NOVO_MODELO'] = site_url('modelo/novo');
		$dados['BLC_DADOS']     = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('modelo_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['vei_id_mod']        = 0;
		$dados['vei_nome_mod']      = '';
		$dados['vei_veimar_mod']    = '';
		$dados['vei_veitip_mod']    = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		$this->carregarMarcas($dados);
		$this->carregarTipos($dados);
		
		$this->parser->parse('modelo_cadastro', $dados);
	}
	
	public function editar($vei_id_mod) {
		$vei_id_mod = base64_decode($vei_id_mod);
		$dados = array();
		
		$this->carregarModelo($vei_id_mod, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		$this->carregarMarcas($dados);
		$this->carregarTipos($dados);
		
		$this->parser->parse('modelo_cadastro', $dados);
	}
	
	public function salvar() {
		global $vei_id_mod;
		global $vei_nome_mod;
		global $vei_veimar_mod;
		global $vei_veitip_mod;
		
		$vei_id_mod     = $this->input->post('vei_id_mod');
		$vei_nome_mod   = $this->input->post('vei_nome_mod');
		$vei_veimar_mod = $this->input->post('vei_veimar_mod');
		$vei_veitip_mod = $this->input->post('vei_veitip_mod');
		
		
		if ($this->testarDados()) {
			$modelo = array(
				"vei_nome_mod"   => $vei_nome_mod,
				"vei_veimar_mod" => $vei_veimar_mod,
				"vei_veitip_mod" => $vei_veitip_mod
			);
			
			if (!$vei_id_mod) {
				$vei_id_mod = $this->ModeloModel->insert($modelo);
			} else {
				$vei_id_mod = $this->ModeloModel->update($modelo, $vei_id_mod);
			}

			if (is_numeric($vei_id_mod)) {
				$this->session->set_flashdata('sucesso', 'Modelo salvo com sucesso.');
				redirect('modelo');
			} else {
				$this->session->set_flashdata('erro', $vei_id_mod);
				redirect('modelo');
			}
		} else {
			if (!$vei_id_mod) {
				redirect('modelo/novo/');
			} else {
				redirect('modelo/editar/'.base64_encode($vei_id_mod));
			}			
		}
	}
	
	public function apagar($vei_id_mod) {
		if ($this->testarApagar(base64_decode($vei_id_mod))) {
			$res = $this->ModeloModel->delete(base64_decode($vei_id_mod));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Modelo apagado com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar a modelo.');
			}
		}
		
		redirect('modelo');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_MODELO'] = site_url('modelo');
		$dados['ACAO_FORM']         = site_url('modelo/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->ModeloModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"VEI_ID_MOD"        => $registro->vei_id_mod,
				"VEI_NOME_MOD" => $registro->vei_nome_mod,
				"EDITAR_MODELO"   => site_url('modelo/editar/'.base64_encode($registro->vei_id_mod)),
				"APAGAR_MODELO"   => "abrirConfirmacao('".base64_encode($registro->vei_id_mod)."')"
			);
		}
	}
	
	private function carregarModelo($vei_id_mod, &$dados) {
		$resultado = $this->ModeloModel->getModelo($vei_id_mod);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
					
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}

	private function carregarMarcas(&$dados) {
		$resultado = $this->MarcaModel->getMarcas();

		$dados['BLC_MARCAS'] = array();

		foreach ($resultado as $registro) {
			$dados['BLC_MARCAS'][] = array(
				"VEI_ID_MAR"          => $registro->vei_id_mar,
				"VEI_NOME_MAR"        => $registro->vei_nome_mar,
				"SEL_VEI_ID_MAR"      => ($dados['vei_veimar_mod'] == $registro->vei_id_mar)?'selected':''
			);
		}
	}

	private function carregarTipos(&$dados) {
		$resultado = $this->TipoModel->getTipos();

		$dados['BLC_TIPOS'] = array();

		foreach ($resultado as $registro) {
			$dados['BLC_TIPOS'][] = array(
				"VEI_ID_TIP"          => $registro->vei_id_tip,
				"VEI_DESCRICAO_TIP"   => $registro->vei_descricao_tip,
				"SEL_VEI_ID_TIP"      => ($dados['vei_veitip_mod'] == $registro->vei_id_tip)?'selected':''
			);
		}
	}
	
	private function testarDados() {
		global $vei_nome_mod;
		global $vei_veimar_mod;
		global $vei_veitip_mod;
		
		$erros    = FALSE;
		$mensagem = null;

		if (empty($vei_veimar_mod)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione uma marca.\n";
			$this->session->set_flashdata('ERRO_VEI_VEIMAR_MOD', 'has-error');
		} else {
			$resultado = $this->MarcaModel->getMarca($vei_veimar_mod);
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Marca não cadastrada.\n";
				$this->session->set_flashdata('ERRO_VEI_VEIMAR_MOD', 'has-error');
			}
		}

		if (empty($vei_veitip_mod)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione um tipo.\n";
			$this->session->set_flashdata('ERRO_VEI_VEITIP_MOD', 'has-error');
		} else {
			$resultado = $this->TipoModel->getTipo($vei_veitip_mod);
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Tipo não cadastrado.\n";
				$this->session->set_flashdata('ERRO_VEI_VEITIP_MOD', 'has-error');
			}
		}


		if (empty($vei_nome_mod)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_VEI_NOME_MOD', 'has-error');
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_VEI_MOD', TRUE);
			$this->session->set_flashdata('vei_nome_mod', $vei_nome_mod);
			$this->session->set_flashdata('vei_veimar_mod', $vei_veimar_mod);
			$this->session->set_flashdata('vei_veitip_mod', $vei_veitip_mod);
		}
		
		return !$erros;
	}
	
	private function testarApagar($vei_id_mod) {
		$erros    = FALSE;
		$mensagem = null;
	
//		$resultado = $this->EmpresaSegmentoModel->getEmpresas($dlv_id_seg);
//
//		if ($resultado) {
//			$erros    = TRUE;
//			$mensagem .= "- Um ou mais empresas mod este segmento.\n";
//		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar a modelo:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_VEI_MOD         = $this->session->flashdata('ERRO_VEI_MOD');
		$ERRO_VEI_NOME_MOD    = $this->session->flashdata('ERRO_VEI_NOME_MOD');
		$ERRO_VEI_VEIMAR_MOD  = $this->session->flashdata('ERRO_VEI_VEIMAR_MOD');
		$ERRO_VEI_VEITIP_MOD  = $this->session->flashdata('ERRO_VEI_VEITIP_MOD');

		$vei_nome_mod         = $this->session->flashdata('vei_nome_mod');
		$vei_veimar_mod       = $this->session->flashdata('vei_veimar_mod');
		$vei_veitip_mod       = $this->session->flashdata('vei_veitip_mod');
		
		if ($ERRO_VEI_MOD) {
			$dados['vei_nome_mod']      = $vei_nome_mod;
			$dados['vei_veimar_mod']         = $vei_veimar_mod;
			$dados['vei_veitip_mod']         = $vei_veitip_mod;
			
			$dados['ERRO_VEI_NOME_MOD'] = $ERRO_VEI_NOME_MOD;
			$dados['ERRO_VEI_VEIMAR_MOD']  = $ERRO_VEI_VEIMAR_MOD;
			$dados['ERRO_VEI_VEITIP_MOD']  = $ERRO_VEI_VEITIP_MOD;
		}
	}
}