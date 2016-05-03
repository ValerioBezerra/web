<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Telefone extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('bus_alttelefone_per') != 1) {redirect('');}
		
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
		
		$dados['bus_id_ext']     = 0;
		$dados['bus_tipo_ext']   = 'selected';
		$dados['bus_tipo_ext_t'] = '';
		$dados['bus_tipo_ext_c'] = '';
		$dados['bus_tipo_ext_f'] = '';
		$dados['bus_fone_ext']   = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);

		$this->parser->parse('telefone_cadastro', $dados);
	}
	
	public function editar($bus_id_ext) {
		$bus_id_ext = base64_decode($bus_id_ext);
		$dados = array();
		
		$this->carregarTelefone($bus_id_ext, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('telefone_cadastro', $dados);	
	}
	
	public function salvar() {
		global $bus_id_ext;
		global $bus_tipo_ext;
		global $bus_fone_ext;
		
		$bus_id_ext   = $this->input->post('bus_id_ext');
		$bus_tipo_ext = $this->input->post('bus_tipo_ext');
		$bus_fone_ext = $this->input->post('bus_fone_ext');
		
		
		if ($this->testarDados()) {
			$telefone = array(
				"bus_busemp_ext"      => $this->session->userdata('bus_id_emp'),
				"bus_tipo_ext"        => $bus_tipo_ext,
				"bus_fone_ext"        => $bus_fone_ext,
				"bus_bususumod_ext"   => $this->session->userdata('bus_id_usu'),
				"bus_datahoramod_ext" => date('Y-m-d H:i:s')
			);
			
			if (!$bus_id_ext) {
				$bus_id_ext = $this->TelefoneModel->insert($telefone);
			} else {
				$bus_id_ext = $this->TelefoneModel->update($telefone, $bus_id_ext);
			}

			if (is_numeric($bus_id_ext)) {
				$this->session->set_flashdata('sucesso', 'Fone salvo com sucesso.');
				redirect('telefone');
			} else {
				$this->session->set_flashdata('erro', $bus_id_ext);
				redirect('telefone');
			}
		} else {
			if (!$bus_id_ext) {
				redirect('telefone/novo/');
			} else {
				redirect('telefone/editar/'.base64_encode($bus_id_ext));
			}			
		}
	}
	
	public function apagar($bus_id_ext) {
		$res = $this->TelefoneModel->delete(base64_decode($bus_id_ext));

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
		$resultado = $this->TelefoneModel->getTelefonesEmpresa($this->session->userdata('bus_id_emp'));
		
		foreach ($resultado as $registro) {
			$bus_tipo_ext = "";
			
			if ($registro->bus_tipo_ext == "t") {
				$bus_tipo_ext = "Telefone";
			} else if ($registro->bus_tipo_ext == "c") {
				$bus_tipo_ext = "Celular";
			} else if ($registro->bus_tipo_ext == "f") {
				$bus_tipo_ext = "Fax";
			}
			
			$dados['BLC_DADOS'][] = array(
				"BUS_FONE_EXT"    => $registro->bus_fone_ext,
				"BUS_TIPO_EXT"    => $bus_tipo_ext,
				"EDITAR_TELEFONE" => site_url('telefone/editar/'.base64_encode($registro->bus_id_ext)),
				"APAGAR_TELEFONE" => "abrirConfirmacao('".base64_encode($registro->bus_id_ext)."')"
			);
		}
	}
	
	private function carregarTelefone($bus_id_ext, &$dados) {
		$resultado = $this->TelefoneModel->get($bus_id_ext);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
			
			$dados['bus_tipo_ext']   = ($resultado->bus_tipo_ext == '')?'selected':'';
			$dados['bus_tipo_ext_t'] = ($resultado->bus_tipo_ext == 't')?'selected':'';
			$dados['bus_tipo_ext_c'] = ($resultado->bus_tipo_ext == 'c')?'selected':'';
			$dados['bus_tipo_ext_f'] = ($resultado->bus_tipo_ext == 'f')?'selected':'';
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $bus_id_ext;
		global $bus_tipo_ext;
		global $bus_fone_ext;
				
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($bus_tipo_ext)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione o tipo.\n";
			$this->session->set_flashdata('ERRO_BUS_TIPO_EXT', 'has-error');
		}
		
		if (empty($bus_fone_ext)) {
			$erros    = TRUE;
			$mensagem .= "- Fone não preenchido.\n";
			$this->session->set_flashdata('ERRO_BUS_FONE_EXT', 'has-error');
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_BUS_EXT', TRUE);
			$this->session->set_flashdata('bus_tipo_ext', $bus_tipo_ext);
			$this->session->set_flashdata('bus_fone_ext', $bus_fone_ext);
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_BUS_EXT      = $this->session->flashdata('ERRO_BUS_EXT');
		$ERRO_BUS_TIPO_EXT = $this->session->flashdata('ERRO_BUS_TIPO_EXT');
		$ERRO_BUS_FONE_EXT = $this->session->flashdata('ERRO_BUS_FONE_EXT');
		
		$bus_tipo_ext      = $this->session->flashdata('bus_tipo_ext');
		$bus_fone_ext      = $this->session->flashdata('bus_fone_ext');
		
		if ($ERRO_BUS_EXT) {
			$dados['bus_tipo_ext']   = ($bus_tipo_ext == '')?'selected':'';
			$dados['bus_tipo_ext_t'] = ($bus_tipo_ext == 't')?'selected':'';
			$dados['bus_tipo_ext_c'] = ($bus_tipo_ext == 'c')?'selected':'';
			$dados['bus_tipo_ext_f'] = ($bus_tipo_ext == 'f')?'selected':'';
			$dados['bus_fone_ext']   = $bus_fone_ext;
				
			$dados['ERRO_BUS_TIPO_EXT'] = $ERRO_BUS_TIPO_EXT;
			$dados['ERRO_BUS_FONE_EXT'] = $ERRO_BUS_FONE_EXT;
		}
	}
}