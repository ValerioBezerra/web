<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa_Segmento extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Empresa_Model', 'EmpresaModel');
		$this->load->model('Segmento_Model', 'SegmentoModel');
		$this->load->model('Empresa_Segmento_Model', 'EmpresaSegmentoModel');
	}
	
	public function index($dlv_id_emp) {
		$dlv_id_emp = base64_decode($dlv_id_emp);
		$dados = array();
	
		$this->carregarEmpresa($dlv_id_emp, $dados);
		$dados['ATUALIZAR_SEGMENTOS'] = site_url('empresa_segmento/index/'.base64_encode($dados['dlv_id_emp']));
		$dados['BLC_DADOS'] = array();
		$dados['URL_VOLTAR']          = site_url('empresa/editar/'.base64_encode($dados['dlv_id_emp']));
		$dados['URL_APAGAR_SEGMENTO'] = site_url('empresa_segmento/remover/');
		$dados['ACAO_FORM']           = site_url('empresa_segmento/adicionar');
		
		$dados['dlv_id_exs']     = '';
		$dados['dlv_dlvemp_exs'] = $dlv_id_emp;
		$dados['dlv_dlvseg_exs'] = '';
		
		
		$this->carregarSegmentosEmpresa($dados);
		$this->carregarDadosFlash($dados);
		$this->carregarSegmentos($dados);
		
		$this->parser->parse('empresa_segmento_cadastro_consulta', $dados);
	}
	
	public function adicionar() {
		global $dlv_id_exs;
		global $dlv_dlvemp_exs;
		global $dlv_dlvseg_exs;
				
		$dlv_id_exs     = $this->input->post('dlv_id_exs');
		$dlv_dlvemp_exs = $this->input->post('dlv_dlvemp_exs');
		$dlv_dlvseg_exs = $this->input->post('dlv_dlvseg_exs');
		
		
		if ($this->testarDados()) {
			$empresa_segmento = array(
				"dlv_dlvemp_exs" => $dlv_dlvemp_exs,
				"dlv_dlvseg_exs" => $dlv_dlvseg_exs,
			);
				
			$dlv_id_exs = $this->EmpresaSegmentoModel->insert($empresa_segmento);
			
			if (is_numeric($dlv_id_exs)) {
				$this->session->set_flashdata('sucesso', 'Segmento adicionado a empresa com sucesso.');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_exs);
			}
		}
		
		redirect('empresa_segmento/index/'.base64_encode($dlv_dlvemp_exs));
	}
	
	public function remover($dlv_id_exs) {
		$resultado = $this->EmpresaSegmentoModel->getEmpresaSegmentoChave(base64_decode($dlv_id_exs));
		
		if ($resultado) {
			$dlv_dlvemp_exs = $resultado->dlv_dlvemp_exs;
		} else {
			$dlv_dlvemp_exs = FALSE;
		}
		
		$res = $this->EmpresaSegmentoModel->delete(base64_decode($dlv_id_exs));
	
		if ($res) {
			$this->session->set_flashdata('sucesso', 'Segmento removido da empresa com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível remover segmento da empresa.');
		}
		
		if ($dlv_dlvemp_exs) {
			redirect('empresa_segmento/index/'.base64_encode($dlv_dlvemp_exs));
		} else {
			echo(base64_decode($dlv_id_exs));
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');				
		}		
	
	}	
	
	private function carregarEmpresa($dlv_id_emp, &$dados) {
		$resultado = $this->EmpresaModel->getEmpresa($dlv_id_emp);
	
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
	
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');
		}
	}	
	
	private function carregarSegmentosEmpresa(&$dados) {
		$resultado = $this->EmpresaSegmentoModel->get($dados['dlv_id_emp']);
	
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
					"DLV_ID_SEG"         => $registro->dlv_id_seg,
					"DLV_DESCRICAO_SEG"  => $registro->dlv_descricao_seg,
					"REMOVER_SEGMENTO"   => "abrirConfirmacao('".base64_encode($registro->dlv_id_exs)."')"
			);
		}
	}
	
	private function carregarSegmentos(&$dados) {
		$resultado = $this->SegmentoModel->get();
	
		foreach ($resultado as $registro) {
			$dados['BLC_SEGMENTOS'][] = array(
					"DLV_ID_SEG"         => $registro->dlv_id_seg,
					"DLV_DESCRICAO_SEG"  => $registro->dlv_descricao_seg,
					"SEL_DLV_ID_SEG"     => ($dados['dlv_dlvseg_exs'] == $registro->dlv_id_seg)?'selected':''
			);
		}
	}
	
	private function testarDados() {
		global $dlv_id_exs;
		global $dlv_dlvemp_exs;
		global $dlv_dlvseg_exs;
		
		$erros    = FALSE;
		$mensagem = null;
	
	
		if (empty($dlv_dlvseg_exs)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione um segmento.\n";
			$this->session->set_flashdata('ERRO_DLV_DLVSEG_EXS', 'has-error');
		} else {
			$resultado = $this->SegmentoModel->getSegmento($dlv_dlvseg_exs);
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Segmento não cadastrado.\n";
				$this->session->set_flashdata('ERRO_DLV_DLVSEG_EXS', 'has-error');				
			} else {
				$resultado = $this->EmpresaSegmentoModel->getEmpresaSegmento($dlv_dlvseg_exs, $dlv_dlvemp_exs);
				
				if ($resultado) {
					$erros    = TRUE;
					$mensagem .= "- Segmento já adicionado a esta empresa.\n";
					$this->session->set_flashdata('ERRO_DLV_DLVSEG_EXS', 'has-error');
				}
				
			}
		} 
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
				
			$this->session->set_flashdata('ERRO_DLV_EXS', TRUE);
			$this->session->set_flashdata('dlv_dlvseg_exs', $dlv_dlvseg_exs);
		}
	
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_EXS         = $this->session->flashdata('ERRO_DLV_EXS');
		$ERRO_DLV_DLVSEG_EXS  = $this->session->flashdata('ERRO_DLV_DLVSEG_EXS');
		$dlv_dlvseg_exs       = $this->session->flashdata('dlv_dlvseg_exs');
	
		if ($ERRO_DLV_EXS) {
			$dados['dlv_dlvseg_exs']      = $dlv_dlvseg_exs;
				
			$dados['ERRO_DLV_DLVSEG_EXS'] = $ERRO_DLV_DLVSEG_EXS;
		}
	}	
	
	
	
}
