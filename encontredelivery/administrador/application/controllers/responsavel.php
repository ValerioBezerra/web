<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Responsavel extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Responsavel_Model', 'ResponsavelModel');
		$this->load->model('Cargo_Model', 'CargoModel');
		$this->load->model('Empresa_Model', 'EmpresaModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']  = site_url('responsavel');
		$dados['NOVO_RESPONSAVEL'] = site_url('responsavel/novo');
		$dados['BLC_DADOS']  = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('responsavel_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['dlv_id_res']        = 0;		
		$dados['dlv_nome_res']      = '';
		$dados['dlv_dlvcar_res']    = '';
		$dados['dlv_telefone_res']  = '';
		$dados['dlv_celular_res']   = '';
		$dados['dlv_email_res']     = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->carregarCargos($dados);
		
		$this->parser->parse('responsavel_cadastro', $dados);
	}
	
	public function editar($dlv_id_res) {
		$dlv_id_res = base64_decode($dlv_id_res);
		$dados = array();
		
		$this->carregarResponsavel($dlv_id_res, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->carregarCargos($dados);
		
		$this->parser->parse('responsavel_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_res;
		global $dlv_nome_res;
		global $dlv_dlvcar_res;
		global $dlv_telefone_res;
		global $dlv_celular_res;
		global $dlv_email_res;
		
		$dlv_id_res       = $this->input->post('dlv_id_res');			
		$dlv_nome_res     = $this->input->post('dlv_nome_res');
		$dlv_dlvcar_res   = $this->input->post('dlv_dlvcar_res');
		$dlv_telefone_res = $this->input->post('dlv_telefone_res');
		$dlv_celular_res  = $this->input->post('dlv_celular_res');
		$dlv_email_res    = $this->input->post('dlv_email_res');
		
		
		if ($this->testarDados()) {
			$responsavel = array(
				"dlv_nome_res"     => $dlv_nome_res,
				"dlv_dlvcar_res"   => $dlv_dlvcar_res,	
				"dlv_telefone_res" => $dlv_telefone_res,
				"dlv_celular_res"  => $dlv_celular_res,
				"dlv_email_res"    => $dlv_email_res
			);
			
			if (!$dlv_id_res) {
				$dlv_id_res = $this->ResponsavelModel->insert($responsavel);
			} else {
				$dlv_id_res = $this->ResponsavelModel->update($responsavel, $dlv_id_res);
			}

			if (is_numeric($dlv_id_res)) {
				$this->session->set_flashdata('sucesso', 'Responsável salvo com sucesso.');
				redirect('responsavel');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_res);	
				redirect('responsavel');
			}
		} else {
			if (!$dlv_id_res) {
				redirect('responsavel/novo/');
			} else {
				redirect('responsavel/editar/'.base64_encode($dlv_id_res));
			}			
		}
	}
	
	public function apagar($dlv_id_res) {
		if ($this->testarApagar(base64_decode($dlv_id_res))) {
			$res = $this->ResponsavelModel->delete(base64_decode($dlv_id_res));

			if ($res) {
				$this->session->set_flashdata('sucesso', 'Responsável apagado com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar responsável.');				
			}			
		}
		
		redirect('responsavel');		
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_RESPONSAVEL'] = site_url('responsavel');
		$dados['ACAO_FORM']            = site_url('responsavel/salvar');
		$dados['MASCARA_FONE']         = MASCARA_FONE;		
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->ResponsavelModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_ID_RES"          => $registro->dlv_id_res,
				"DLV_NOME_RES"        => $registro->dlv_nome_res,
				"DLV_DESCRICAO_CAR"   => $registro->dlv_descricao_car, 	
				"EDITAR_RESPONSAVEL"  => site_url('responsavel/editar/'.base64_encode($registro->dlv_id_res)),
				"APAGAR_RESPONSAVEL"  => "abrirConfirmacao('".base64_encode($registro->dlv_id_res)."')"
			);
		}
	}
	
	private function carregarCargos(&$dados) {
		$resultado = $this->CargoModel->get();
		
		$dados['BLC_CARGOS'] = array();
		
		foreach ($resultado as $registro) {
			$dados['BLC_CARGOS'][] = array(
					"DLV_ID_CAR"          => $registro->dlv_id_car,
					"DLV_DESCRICAO_CAR"   => $registro->dlv_descricao_car,
					"SEL_DLV_ID_CAR"      => ($dados['dlv_dlvcar_res'] == $registro->dlv_id_car)?'selected':''
			);
		}
	}	
	
	private function carregarResponsavel($dlv_id_res, &$dados) {
		$resultado = $this->ResponsavelModel->getResponsavel($dlv_id_res);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
					
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $dlv_nome_res;
		global $dlv_dlvcar_res;
		global $dlv_telefone_res;
		global $dlv_celular_res;
		global $dlv_email_res;
		
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($dlv_nome_res)) {
			$erros    = TRUE;
			$mensagem .= "- Nome não preenchido.\n";
			$this->session->set_flashdata('ERRO_DLV_NOME_RES', 'has-error');				
		}
		
		if (empty($dlv_dlvcar_res)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione um cargo.\n";
			$this->session->set_flashdata('ERRO_DLV_DLVCAR_RES', 'has-error');
		} else {
			$resultado = $this->CargoModel->getCargo($dlv_dlvcar_res);
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Cargo não cadastrado.\n";
				$this->session->set_flashdata('ERRO_DLV_DLVCAR_RES', 'has-error');				
			}
		}
		
		
		if (empty($dlv_telefone_res) and empty($dlv_celular_res)) {
			$erros    = TRUE;
			$mensagem .= "- Telefone ou celular precisa(m) ser preenchido(s).\n";
			$this->session->set_flashdata('ERRO_DLV_TELEFONE_RES', 'has-error');
			$this->session->set_flashdata('ERRO_DLV_CELULAR_RES', 'has-error');
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_RES', TRUE);				
			$this->session->set_flashdata('dlv_nome_res', $dlv_nome_res);				
			$this->session->set_flashdata('dlv_dlvcar_res', $dlv_dlvcar_res);				
			$this->session->set_flashdata('dlv_telefone_res', $dlv_telefone_res);				
			$this->session->set_flashdata('dlv_celular_res', $dlv_celular_res);				
			$this->session->set_flashdata('dlv_email_res', $dlv_email_res);				
		}
		
		return !$erros;
	}
	
	private function testarApagar($dlv_id_res) {
		$erros    = FALSE;
		$mensagem = null;
		
		$resultado = $this->EmpresaModel->getEmpresasResponsaveis($dlv_id_res);
		
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais empresas com este responsável.\n";
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o responsável:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_RES          = $this->session->flashdata('ERRO_DLV_RES');
		$ERRO_DLV_NOME_RES     = $this->session->flashdata('ERRO_DLV_NOME_RES');
		$ERRO_DLV_DLVCAR_RES   = $this->session->flashdata('ERRO_DLV_DLVCAR_RES');
		$ERRO_DLV_TELEFONE_RES = $this->session->flashdata('ERRO_DLV_TELEFONE_RES');
		$ERRO_DLV_CELULAR_RES  = $this->session->flashdata('ERRO_DLV_CELULAR_RES');
		
		$dlv_nome_res     = $this->session->flashdata('dlv_nome_res');
		$dlv_dlvcar_res   = $this->session->flashdata('dlv_dlvcar_res');
		$dlv_telefone_res = $this->session->flashdata('dlv_telefone_res');
		$dlv_celular_res  = $this->session->flashdata('dlv_celular_res');
		$dlv_email_res    = $this->session->flashdata('dlv_email_res');
		
		if ($ERRO_DLV_RES) {
			$dados['dlv_nome_res']     = $dlv_nome_res;
			$dados['dlv_dlvcar_res']   = $dlv_dlvcar_res;
			$dados['dlv_telefone_res'] = $dlv_telefone_res;
			$dados['dlv_celular_res']  = $dlv_celular_res;
			$dados['dlv_email_res']    = $dlv_email_res;
				
			$dados['ERRO_DLV_NOME_RES']     = $ERRO_DLV_NOME_RES;
			$dados['ERRO_DLV_DLVCAR_RES']   = $ERRO_DLV_DLVCAR_RES;
			$dados['ERRO_DLV_TELEFONE_RES'] = $ERRO_DLV_TELEFONE_RES;
			$dados['ERRO_DLV_CELULAR_RES']  = $ERRO_DLV_CELULAR_RES;
		}
	}
}