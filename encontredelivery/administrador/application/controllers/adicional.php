<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adicional extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_cadadicional_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Adicional_Model', 'AdicionalModel');
		$this->load->model('Produto_Adicional_Model', 'ProdutoAdicionalModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']  = site_url('adicional');
		$dados['NOVO_ADICIONAL'] = site_url('adicional/novo');
		$dados['BLC_DADOS']  = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('adicional_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['dlv_id_adi']        = 0;		
		$dados['dlv_descricao_adi'] = '';
		$dados['dlv_valor_adi']     = 'R$ 0,00';
		$dados['dlv_ativo_adi']     = 'checked';
		
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		

		$this->parser->parse('adicional_cadastro', $dados);
	}
	
	public function editar($dlv_id_adi) {
		$dlv_id_adi = base64_decode($dlv_id_adi);
		$dados = array();
		
		$this->carregaradicional($dlv_id_adi, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('adicional_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_adi;
		global $dlv_descricao_adi;
		global $dlv_valor_adi;		
		global $dlv_ativo_adi;
		
		$dlv_id_adi   	   = $this->input->post('dlv_id_adi');			
		$dlv_descricao_adi = $this->input->post('dlv_descricao_adi');
		$dlv_valor_adi     = $this->input->post('dlv_valor_adi');
		$dlv_ativo_adi 	   = $this->input->post('dlv_ativo_adi');
		
		$dlv_valor_adi = str_replace("R$ ", null, $dlv_valor_adi);
		$dlv_valor_adi = str_replace(".", null, $dlv_valor_adi);
		$dlv_valor_adi = str_replace(",", ".", $dlv_valor_adi);
		
		if ($this->testarDados()) {
			$adicional = array(
				"dlv_dlvemp_adi"      => $this->session->userdata('dlv_id_emp'),
				"dlv_descricao_adi"   => $dlv_descricao_adi,
				"dlv_valor_adi"       => $dlv_valor_adi,			
				"dlv_ativo_adi"       => ($dlv_ativo_adi)?'1':'0',
				"dlv_dlvusumod_adi"   => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_adi" => date('Y-m-d H:i:s')
			);
			
			if (!$dlv_id_adi) {	
				$dlv_id_adi = $this->AdicionalModel->insert($adicional);
			} else {
				$dlv_id_adi = $this->AdicionalModel->update($adicional, $dlv_id_adi);
			}

			if (is_numeric($dlv_id_adi)) {
				$this->session->set_flashdata('sucesso', 'Adicional salvo com sucesso.');
				redirect('adicional');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_adi);	
				redirect('adicional');
			}
		} else {
			if (!$dlv_id_adi) {
				redirect('adicional/novo/');
			} else {
				redirect('adicional/editar/'.base64_encode($dlv_id_adi));
			}			
		}
	}
	
	public function apagar($dlv_id_adi) {
		if ($this->testarApagar(base64_decode($dlv_id_adi))) {
			$res = $this->AdicionalModel->delete(base64_decode($dlv_id_adi));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Adicional apagado com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar adicional.');				
			}
		}
		
		redirect('adicional');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_ADICIONAL']   = site_url('adicional');
		$dados['ACAO_FORM']          = site_url('adicional/salvar');
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->AdicionalModel->getAdicionalEmpresa($this->session->userdata('dlv_id_emp'));
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_DESCRICAO_ADI" => $registro->dlv_descricao_adi,
				"DLV_VALOR_ADI"     => 'R$ '.number_format($registro->dlv_valor_adi,  2, ',', '.'),				
				"DLV_ATIVO_ADI"     => ($registro->dlv_ativo_adi == 1)?'checked':'',
				"EDITAR_ADICIONAL"  => site_url('adicional/editar/'.base64_encode($registro->dlv_id_adi)),
				"APAGAR_ADICIONAL"  => "abrirConfirmacao('".base64_encode($registro->dlv_id_adi)."')"
			);
		}
	}
	
	private function carregarAdicional($dlv_id_adi, &$dados) {
		$resultado = $this->AdicionalModel->get($dlv_id_adi);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
			
			$dados['dlv_valor_adi'] = 'R$ '.number_format($resultado->dlv_valor_adi,  2, ',', '.');
			$dados['dlv_ativo_adi'] = ($resultado->dlv_ativo_adi == 1)?'checked':'';

		} else {
			show_error('NÃo foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $dlv_id_adi;
		global $dlv_descricao_adi;
		global $dlv_valor_adi;	
		global $dlv_ativo_adi;
				
		$erros    = FALSE;
		$mensagem = null;
		
		if (empty($dlv_descricao_adi)) {
			$erros    = TRUE;
			$mensagem .= "- Descrição não preenchida.\n";
			$this->session->set_flashdata('ERRO_DLV_DESCRICAO_ADI', 'has-error');
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_ADI', TRUE);				
			$this->session->set_flashdata('dlv_descricao_adi', $dlv_descricao_adi);				
			$this->session->set_flashdata('dlv_valor_adi', $dlv_valor_adi);			
			$this->session->set_flashdata('dlv_ativo_adi', $dlv_ativo_adi);
		}
		
		return !$erros;
	}
	
	private function testarApagar($dlv_id_adi) {
		$erros    = FALSE;
		$mensagem = null;
	
		$resultado = $this->ProdutoAdicionalModel->getEmpresaAdicional($dlv_id_adi);
		
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Uma ou mais produtos com este adicional.\n";
		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar o adicional:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_ADI   		= $this->session->flashdata('ERRO_DLV_ADI');
		$ERRO_DLV_DESCRICAO_ADI = $this->session->flashdata('ERRO_DLV_DESCRICAO_ADI');
	 	$ERRO_DLV_VALOR_ADI     = $this->session->flashdata('ERRO_DLV_VALOR_ADI');		
	 	
		
		$dlv_descricao_adi = $this->session->flashdata('dlv_descricao_adi');
		$dlv_valor_adi     = $this->session->flashdata('dlv_valor_adi');
		$dlv_ativo_adi     = $this->session->flashdata('dlv_ativo_adi');
		
		
		if ($ERRO_DLV_ADI) {
			
			$dados['dlv_descricao_adi']  = $dlv_descricao_adi;
			$dados['dlv_valor_adi']      = 'R$ '.number_format($dlv_valor_adi,  2, ',', '.');
			$dados['dlv_ativo_adi']      = ($dlv_ativo_adi == 1)?'checked':'';
			
				
			$dados['ERRO_DLV_DESCRICAO_ADI']  = $ERRO_DLV_DESCRICAO_ADI;
			$dados['ERRO_DLV_VALOR_ADI']      = $ERRO_DLV_VALOR_ADI;
			
		}
	}
}