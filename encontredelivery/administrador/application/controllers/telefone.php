<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Telefone extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_alttelefone_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Telefone_Model', 'TelefoneModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']  = site_url('telefone');
		$dados['NOVO_TELEFONE'] = site_url('telefone/novo');
		$dados['BLC_DADOS']  = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('telefone_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['dlv_id_ext']     = 0;		
		$dados['dlv_tipo_ext']   = 'selected';
		$dados['dlv_tipo_ext_t'] = '';
		$dados['dlv_tipo_ext_c'] = '';
		$dados['dlv_tipo_ext_f'] = '';
		$dados['dlv_fone_ext']   = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);

		$this->parser->parse('telefone_cadastro', $dados);
	}
	
	public function editar($dlv_id_ext) {
		$dlv_id_ext = base64_decode($dlv_id_ext);
		$dados = array();
		
		$this->carregarTelefone($dlv_id_ext, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('telefone_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_ext;
		global $dlv_tipo_ext;
		global $dlv_fone_ext;
		
		$dlv_id_ext   = $this->input->post('dlv_id_ext');			
		$dlv_tipo_ext = $this->input->post('dlv_tipo_ext');
		$dlv_fone_ext = $this->input->post('dlv_fone_ext');
		
		
		if ($this->testarDados()) {
			$telefone = array(
				"dlv_dlvemp_ext"      => $this->session->userdata('dlv_id_emp'),
				"dlv_tipo_ext"        => $dlv_tipo_ext,
				"dlv_fone_ext"        => $dlv_fone_ext,
				"dlv_dlvusumod_ext"   => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_ext" => date('Y-m-d H:i:s')							
			);
			
			if (!$dlv_id_ext) {	
				$dlv_id_ext = $this->TelefoneModel->insert($telefone);
			} else {
				$dlv_id_ext = $this->TelefoneModel->update($telefone, $dlv_id_ext);
			}

			if (is_numeric($dlv_id_ext)) {
				$this->session->set_flashdata('sucesso', 'Fone salvo com sucesso.');
				redirect('telefone');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_ext);	
				redirect('telefone');
			}
		} else {
			if (!$dlv_id_ext) {
				redirect('telefone/novo/');
			} else {
				redirect('telefone/editar/'.base64_encode($dlv_id_ext));
			}			
		}
	}
	
	public function apagar($dlv_id_ext) {
		$res = $this->TelefoneModel->delete(base64_decode($dlv_id_ext));

		if ($res) {
			$this->session->set_flashdata('sucesso', 'Fone apagado com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível apagar fone.');				
		}
		
		redirect('telefone');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_TELEFONE']  = site_url('telefone');
		$dados['ACAO_FORM']          = site_url('telefone/salvar');
		$dados['MASCARA_FONE']       = MASCARA_FONE;		
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->TelefoneModel->getTelefonesEmpresa($this->session->userdata('dlv_id_emp'));
		
		foreach ($resultado as $registro) {
			$dlv_tipo_ext = "";
			
			if ($registro->dlv_tipo_ext == "t") {
				$dlv_tipo_ext = "Telefone";
			} else if ($registro->dlv_tipo_ext == "c") {
				$dlv_tipo_ext = "Celular";
			} else if ($registro->dlv_tipo_ext == "f") {
				$dlv_tipo_ext = "Fax";
			}
			
			$dados['BLC_DADOS'][] = array(
				"DLV_FONE_EXT"    => $registro->dlv_fone_ext,
				"DLV_TIPO_EXT"    => $dlv_tipo_ext,
				"EDITAR_TELEFONE" => site_url('telefone/editar/'.base64_encode($registro->dlv_id_ext)),
				"APAGAR_TELEFONE" => "abrirConfirmacao('".base64_encode($registro->dlv_id_ext)."')"
			);
		}
	}
	
	private function carregarTelefone($dlv_id_ext, &$dados) {
		$resultado = $this->TelefoneModel->get($dlv_id_ext);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
			
			$dados['dlv_tipo_ext']   = ($resultado->dlv_tipo_ext == '')?'selected':'';
			$dados['dlv_tipo_ext_t'] = ($resultado->dlv_tipo_ext == 't')?'selected':'';
			$dados['dlv_tipo_ext_c'] = ($resultado->dlv_tipo_ext == 'c')?'selected':'';
			$dados['dlv_tipo_ext_f'] = ($resultado->dlv_tipo_ext == 'f')?'selected':'';
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $dlv_id_ext;
		global $dlv_tipo_ext;
		global $dlv_fone_ext;
				
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($dlv_tipo_ext)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione o tipo.\n";
			$this->session->set_flashdata('ERRO_DLV_TIPO_EXT', 'has-error');
		}
		
		if (empty($dlv_fone_ext)) {
			$erros    = TRUE;
			$mensagem .= "- Fone não preenchido.\n";
			$this->session->set_flashdata('ERRO_DLV_FONE_EXT', 'has-error');				
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_EXT', TRUE);				
			$this->session->set_flashdata('dlv_tipo_ext', $dlv_tipo_ext);				
			$this->session->set_flashdata('dlv_fone_ext', $dlv_fone_ext);				
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_EXT      = $this->session->flashdata('ERRO_DLV_EXT');
		$ERRO_DLV_TIPO_EXT = $this->session->flashdata('ERRO_DLV_TIPO_EXT');
		$ERRO_DLV_FONE_EXT = $this->session->flashdata('ERRO_DLV_FONE_EXT');
		
		$dlv_tipo_ext      = $this->session->flashdata('dlv_tipo_ext');
		$dlv_fone_ext      = $this->session->flashdata('dlv_fone_ext');
		
		if ($ERRO_DLV_EXT) {
			$dados['dlv_tipo_ext']   = ($dlv_tipo_ext == '')?'selected':'';
			$dados['dlv_tipo_ext_t'] = ($dlv_tipo_ext == 't')?'selected':'';
			$dados['dlv_tipo_ext_c'] = ($dlv_tipo_ext == 'c')?'selected':'';
			$dados['dlv_tipo_ext_f'] = ($dlv_tipo_ext == 'f')?'selected':'';
			$dados['dlv_fone_ext']   = $dlv_fone_ext;
				
			$dados['ERRO_DLV_TIPO_EXT'] = $ERRO_DLV_TIPO_EXT;
			$dados['ERRO_DLV_FONE_EXT'] = $ERRO_DLV_FONE_EXT;
		}
	}
}