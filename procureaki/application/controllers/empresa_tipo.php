<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa_Tipo extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('bus_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Empresa_Model', 'EmpresaModel');
		$this->load->model('Tipo_Model', 'TipoModel');
		$this->load->model('Empresa_Tipo_Model', 'EmpresaTipoModel');
	}
	
	public function index($bus_id_emp, $bus_busseg_emp) {
		$bus_id_emp = base64_decode($bus_id_emp);
		$bus_busseg_emp = base64_decode($bus_busseg_emp);
		$dados = array();
	
		$this->carregarEmpresa($bus_id_emp, $dados);
		$dados['ATUALIZAR_TIPO'] = site_url('empresa_tipo/index/'.base64_encode($dados['bus_id_emp']). '/' . base64_encode($dados['bus_busseg_emp']));
		$dados['BLC_DADOS'] = array();
		$dados['URL_VOLTAR']          = site_url('empresa/editar/'.base64_encode($dados['bus_id_emp']). '/' . base64_encode($dados['bus_busseg_emp']));
		$dados['URL_APAGAR_TIPO'] = site_url('empresa_tipo/remover/');
		$dados['ACAO_FORM']           = site_url('empresa_tipo/adicionar/' . base64_encode($dados['bus_busseg_emp']));
		
		$dados['bus_id_ext']     = '';
		$dados['bus_busemp_ext'] = $bus_id_emp;
		$dados['bus_bustip_ext'] = '';
		
		
		$this->carregarTipoEmpresa($bus_busseg_emp, $dados);
		$this->carregarDadosFlash($dados);
		$this->carregarTipo($bus_busseg_emp, $dados);
		
		$this->parser->parse('empresa_tipo_cadastro_consulta', $dados);
	}
	
	public function adicionar($bus_busseg_emp) {
		global $bus_id_ext;
		global $bus_busemp_ext;
		global $bus_bustip_ext;

		$bus_busseg_emp = base64_decode($bus_busseg_emp);
				
		$bus_id_ext     = $this->input->post('bus_id_ext');
		$bus_busemp_ext = $this->input->post('bus_busemp_ext');
		$bus_bustip_ext = $this->input->post('bus_bustip_ext');
		
		
		if ($this->testarDados()) {
			$empresa_tipo = array(
				"bus_busemp_ext" => $bus_busemp_ext,
				"bus_bustip_ext" => $bus_bustip_ext,
			);
				
			$bus_id_ext = $this->EmpresaTipoModel->insert($empresa_tipo);
			
			if (is_numeric($bus_id_ext)) {
				$this->session->set_flashdata('sucesso', 'Tipo adicionado a empresa com sucesso.');
			} else {
				$this->session->set_flashdata('erro', $bus_id_ext);
			}
		}
		
		redirect('empresa_tipo/index/'.base64_encode($bus_busemp_ext) . '/' . base64_encode($bus_busseg_emp));
	}
	
	public function remover($bus_id_ext, $bus_busseg_emp) {
		$resultado = $this->EmpresaTipoModel->getEmpresaTipoChave(base64_decode($bus_id_ext));
		
		if ($resultado) {
			$bus_busemp_ext = $resultado->bus_busemp_ext;
		} else {
			$bus_busemp_ext = FALSE;
		}
		
		$res = $this->EmpresaTipoModel->delete(base64_decode($bus_id_ext));
	
		if ($res) {
			$this->session->set_flashdata('sucesso', 'Tipo removido da empresa com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível remover tipo da empresa.');
		}
		
		if ($bus_busemp_ext) {
			redirect('empresa_tipo/index/'.base64_encode($bus_busemp_ext). '/' . $bus_busseg_emp);
		} else {
			echo(base64_decode($bus_id_ext));
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');				
		}		
	
	}

	private function carregarTipoEmpresa($bus_busseg_emp, &$dados) {
		$resultado = $this->EmpresaTipoModel->get($dados['bus_busemp_ext']);

		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"BUS_ID_TIP"         => $registro->bus_id_tip,
				"BUS_DESCRICAO_TIP"  => $registro->bus_descricao_tip,
				"REMOVER_TIPO"   => "abrirConfirmacao('".base64_encode($registro->bus_id_ext). "','" . base64_encode($bus_busseg_emp). "')"
			);
		}
	}

	private function carregarEmpresa($bus_id_emp, &$dados) {
		$resultado = $this->EmpresaModel->getEmpresa($bus_id_emp);

		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}

		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');
		}
	}

	private function carregarTipo($bus_busseg_emp, &$dados) {
		$resultado = $this->TipoModel->getTipoSeg($bus_busseg_emp);
	
		foreach ($resultado as $registro) {
			$dados['BLC_TIPO'][] = array(
					"BUS_ID_TIP"         => $registro->bus_id_tip,
					"BUS_DESCRICAO_TIP"  => $registro->bus_descricao_tip,
					"SEL_BUS_ID_TIP"     => ($dados['bus_bustip_ext'] == $registro->bus_id_tip)?'selected':''
			);
		}
	}
	
	private function testarDados() {
		global $bus_id_ext;
		global $bus_busemp_ext;
		global $bus_bustip_ext;
		
		$erros    = FALSE;
		$mensagem = null;
	
	
		if (empty($bus_bustip_ext)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione um tipo.\n";
			$this->session->set_flashdata('ERRO_BUS_BUSTIP_EXT', 'has-error');
		} else {
			$resultado = $this->TipoModel->getTipo($bus_bustip_ext);
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Tipo não cadastrado.\n";
				$this->session->set_flashdata('ERRO_BUS_BUSTIP_EXT', 'has-error');				
			} else {
				$resultado = $this->EmpresaTipoModel->getEmpresaTipo($bus_bustip_ext, $bus_busemp_ext);
				
				if ($resultado) {
					$erros    = TRUE;
					$mensagem .= "- Tipo já adicionado a esta empresa.\n";
					$this->session->set_flashdata('ERRO_BUS_BUSTIP_EXT', 'has-error');
				}
				
			}
		} 
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
				
			$this->session->set_flashdata('ERRO_BUS_EXT', TRUE);
			$this->session->set_flashdata('bus_bustip_ext', $bus_bustip_ext);
		}
	
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_BUS_EXT         = $this->session->flashdata('ERRO_BUS_EXT');
		$ERRO_BUS_BUSTIP_EXT  = $this->session->flashdata('ERRO_BUS_BUSTIP_EXT');
		$bus_bustip_ext       = $this->session->flashdata('bus_bustip_ext');
	
		if ($ERRO_BUS_EXT) {
			$dados['bus_bustip_ext']      = $bus_bustip_ext;
				
			$dados['ERRO_BUS_BUSTIP_EXT'] = $ERRO_BUS_BUSTIP_EXT;
		}
	}	
	
	
	
}
