<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Horario extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_althorario_per') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Horario_Model', 'HorarioModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']  = site_url('horario');
		$dados['NOVO_HORARIO'] = site_url('horario/novo');
		$dados['BLC_DADOS']  = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('horario_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['dlv_id_exh']      = 0;		
		$dados['dlv_dia_exh']     = 'selected';
		$dados['dlv_dia_exh_1']   = '';
		$dados['dlv_dia_exh_2']   = '';
		$dados['dlv_dia_exh_3']   = '';
		$dados['dlv_dia_exh_4']   = '';
		$dados['dlv_dia_exh_5']   = '';
		$dados['dlv_dia_exh_6']   = '';
		$dados['dlv_dia_exh_7']   = '';
		$dados['dlv_horaini_exh'] = '';
		$dados['dlv_horafin_exh'] = '';
		
		$dados['ACAO'] = 'Novo';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);

		$this->parser->parse('horario_cadastro', $dados);
	}
	
	public function editar($dlv_id_exh) {
		$dlv_id_exh = base64_decode($dlv_id_exh);
		$dados = array();
		
		$this->carregarHorario($dlv_id_exh, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		
		$this->parser->parse('horario_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_exh;
		global $dlv_dia_exh;
		global $dlv_horaini_exh;
		global $dlv_horafin_exh;
		
		$dlv_id_exh      = $this->input->post('dlv_id_exh');			
		$dlv_dia_exh     = $this->input->post('dlv_dia_exh');
		$dlv_horaini_exh = $this->input->post('dlv_horaini_exh');
		$dlv_horafin_exh = $this->input->post('dlv_horafin_exh');
		
		
		if ($this->testarDados()) {
			$horario = array(
				"dlv_dlvemp_exh"      => $this->session->userdata('dlv_id_emp'),
				"dlv_dia_exh"         => $dlv_dia_exh,
				"dlv_horaini_exh"     => $dlv_horaini_exh,
				"dlv_horafin_exh"     => $dlv_horafin_exh,
				"dlv_dlvusumod_exh"   => $this->session->userdata('dlv_id_usu'),
				"dlv_datahoramod_exh" => date('Y-m-d H:i:s')							
			);
			
			if (!$dlv_id_exh) {	
				$dlv_id_exh = $this->HorarioModel->insert($horario);
			} else {
				$dlv_id_exh = $this->HorarioModel->update($horario, $dlv_id_exh);
			}

			if (is_numeric($dlv_id_exh)) {
				$this->session->set_flashdata('sucesso', 'Horário salvo com sucesso.');
				redirect('horario');
			} else {
				$this->session->set_flashdata('erro', $dlv_id_exh);	
				redirect('horario');
			}
		} else {
			if (!$dlv_id_exh) {
				redirect('horario/novo/');
			} else {
				redirect('horario/editar/'.base64_encode($dlv_id_exh));
			}			
		}
	}
	
	public function apagar($dlv_id_exh) {
		$res = $this->HorarioModel->delete(base64_decode($dlv_id_exh));

		if ($res) {
			$this->session->set_flashdata('sucesso', 'Horário apagado com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível apagar horário.');				
		}
		
		redirect('horario');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_HORARIO']  = site_url('horario');
		$dados['ACAO_FORM']          = site_url('horario/salvar');
		$dados['MASCARA_HORA']       = MASCARA_HORA;		
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->HorarioModel->getHorariosEmpresa($this->session->userdata('dlv_id_emp'));
		
		foreach ($resultado as $registro) {
			switch ($registro->dlv_dia_exh) {
				case 1: $dlv_dia_exh = "Domingo";
				        break;
		        case 2: $dlv_dia_exh = "Segunda-Feira";
		        		break;
		        case 3: $dlv_dia_exh = "Terça-Feira";
		        		break;
		        case 4: $dlv_dia_exh = "Quarta-Feira";
		        		break;
		        case 5: $dlv_dia_exh = "Quinta-Feira";
		        		break;
		        case 6: $dlv_dia_exh = "Sexta-Feira";
		        		break;
		        case 7: $dlv_dia_exh = "Sábado";
		        		break;
		        default: $dlv_dia_exh = "";
		                 break;		
			} 
			
			$dados['BLC_DADOS'][] = array(
				"DLV_DIA_EXT"     => $dlv_dia_exh,
				"DLV_HORAINI_EXT" => $registro->dlv_horaini_exh,
				"DLV_HORAFIN_EXT" => $registro->dlv_horafin_exh,
				"EDITAR_HORARIO"  => site_url('horario/editar/'.base64_encode($registro->dlv_id_exh)),
				"APAGAR_HORARIO"  => "abrirConfirmacao('".base64_encode($registro->dlv_id_exh)."')"
			);
		}
	}
	
	private function carregarHorario($dlv_id_exh, &$dados) {
		$resultado = $this->HorarioModel->get($dlv_id_exh);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
			
			$dados['dlv_dia_exh']   = ($resultado->dlv_dia_exh == '')?'selected':'';
			$dados['dlv_dia_exh_1'] = ($resultado->dlv_dia_exh == 1)?'selected':'';
			$dados['dlv_dia_exh_2'] = ($resultado->dlv_dia_exh == 2)?'selected':'';
			$dados['dlv_dia_exh_3'] = ($resultado->dlv_dia_exh == 3)?'selected':'';
			$dados['dlv_dia_exh_4'] = ($resultado->dlv_dia_exh == 4)?'selected':'';
			$dados['dlv_dia_exh_5'] = ($resultado->dlv_dia_exh == 5)?'selected':'';
			$dados['dlv_dia_exh_6'] = ($resultado->dlv_dia_exh == 6)?'selected':'';
			$dados['dlv_dia_exh_7'] = ($resultado->dlv_dia_exh == 7)?'selected':'';
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function testarDados() {
		global $dlv_id_exh;
		global $dlv_dia_exh;
		global $dlv_horaini_exh;
		global $dlv_horafin_exh;
						
		$erros      = FALSE;
		$errosTempo = FALSE;
		$mensagem   = null;
		
		if (empty($dlv_dia_exh)) {
			$erros     = TRUE;
			$mensagem .= "- Selecione o dia.\n";
			$this->session->set_flashdata('ERRO_DLV_DIA_EXT', 'has-error');
		}
		
		if (empty($dlv_horaini_exh)) {
			$erros      = TRUE;
			$errosTempo = TRUE;
			$mensagem  .= "- Hora inicial não preenchida.\n";
			$this->session->set_flashdata('ERRO_DLV_HORAINI_EXT', 'has-error');				
		} else {
			if(!preg_match("^([0-1][0-9]|[0-2][0-3]):[0-5][0-9]:[0-5][0-9]$^", $dlv_horaini_exh)) {
				$erros      = TRUE;
				$errosTempo = TRUE;
				$mensagem  .= "- Hora inicial inválida.\n";
				$this->session->set_flashdata('ERRO_DLV_HORAINI_EXT', 'has-error');
			} else {
				if (!empty($dlv_dia_exh)) {
					$resultado = $this->HorarioModel->getHorarioIntervalo($dlv_id_exh, $this->session->userdata('dlv_id_emp'), $dlv_dia_exh, $dlv_horaini_exh);
	
					if ($resultado) {
						$erros      = TRUE;
						$errosTempo = TRUE;
						$mensagem  .= "- Hora inicial já cadastrada no intervalo de outro horário.\n";
						$this->session->set_flashdata('ERRO_DLV_HORAINI_EXT', 'has-error');
					}
				}
			}
		}
					
		if (empty($dlv_horafin_exh)) {
			$erros      = TRUE;
			$errosTempo = TRUE;
			$mensagem  .= "- Hora final não preenchida.\n";
			$this->session->set_flashdata('ERRO_DLV_HORAFIN_EXT', 'has-error');				
		} else {
			if(!preg_match("^([0-1][0-9]|[0-2][0-3]):[0-5][0-9]:[0-5][0-9]$^", $dlv_horafin_exh)) {
				$erros      = TRUE;
				$errosTempo = TRUE;
				$mensagem  .= "- Hora final inválida.\n";
				$this->session->set_flashdata('ERRO_DLV_HORAFIN_EXT', 'has-error');
			} else {
				if (!empty($dlv_dia_exh)) {
					$resultado = $this->HorarioModel->getHorarioIntervalo($dlv_id_exh, $this->session->userdata('dlv_id_emp'), $dlv_dia_exh, $dlv_horafin_exh);
	
					if ($resultado) {
						$erros      = TRUE;
						$errosTempo = TRUE;
						$mensagem  .= "- Hora final já cadastrada no intervalo de outro horário.\n";
						$this->session->set_flashdata('ERRO_DLV_HORAFIN_EXT', 'has-error');
					}
				}
			}
		}
		
		if ((!$errosTempo) and (strtotime($dlv_horaini_exh) > strtotime($dlv_horafin_exh))) {
			$erros      = TRUE;
			$errosTempo = TRUE;
			$mensagem  .= "- Hora inicial maior que hora final inválida.\n";
			$this->session->set_flashdata('ERRO_DLV_HORAINI_EXT', 'has-error');
			$this->session->set_flashdata('ERRO_DLV_HORAFIN_EXT', 'has-error');
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_EXT', TRUE);				
			$this->session->set_flashdata('dlv_dia_exh', $dlv_dia_exh);				
			$this->session->set_flashdata('dlv_horaini_exh', $dlv_horaini_exh);				
			$this->session->set_flashdata('dlv_horafin_exh', $dlv_horafin_exh);				
		}
		
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_EXT         = $this->session->flashdata('ERRO_DLV_EXT');
		$ERRO_DLV_DIA_EXT     = $this->session->flashdata('ERRO_DLV_DIA_EXT');
		$ERRO_DLV_HORAINI_EXT = $this->session->flashdata('ERRO_DLV_HORAINI_EXT');
		$ERRO_DLV_HORAFIN_EXT = $this->session->flashdata('ERRO_DLV_HORAFIN_EXT');
		
		$dlv_dia_exh     = $this->session->flashdata('dlv_dia_exh');
		$dlv_horaini_exh = $this->session->flashdata('dlv_horaini_exh');
		$dlv_horafin_exh = $this->session->flashdata('dlv_horafin_exh');
		
		if ($ERRO_DLV_EXT) {
			$dados['dlv_dia_exh']   = ($dlv_dia_exh == '')?'selected':'';
			$dados['dlv_dia_exh_1'] = ($dlv_dia_exh == 1)?'selected':'';
			$dados['dlv_dia_exh_2'] = ($dlv_dia_exh == 2)?'selected':'';
			$dados['dlv_dia_exh_3'] = ($dlv_dia_exh == 3)?'selected':'';
			$dados['dlv_dia_exh_4'] = ($dlv_dia_exh == 4)?'selected':'';
			$dados['dlv_dia_exh_5'] = ($dlv_dia_exh == 5)?'selected':'';
			$dados['dlv_dia_exh_6'] = ($dlv_dia_exh == 6)?'selected':'';
			$dados['dlv_dia_exh_7'] = ($dlv_dia_exh == 7)?'selected':'';
			$dados['dlv_horaini_exh'] = $dlv_horaini_exh;
			$dados['dlv_horafin_exh'] = $dlv_horafin_exh;
							
			$dados['ERRO_DLV_DIA_EXT']     = $ERRO_DLV_DIA_EXT;
			$dados['ERRO_DLV_HORAINI_EXT'] = $ERRO_DLV_HORAINI_EXT;
			$dados['ERRO_DLV_HORAFIN_EXT'] = $ERRO_DLV_HORAFIN_EXT;
		}
	}
}