<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('dlv_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Empresa_Model', 'EmpresaModel');
		$this->load->model('Responsavel_Model', 'ResponsavelModel');
		$this->load->model('Endereco_Model', 'EnderecoModel');
		$this->load->model('Perfil_Model', 'PerfilModel');
		$this->load->model('Usuario_Model', 'UsuarioModel');
		$this->load->model('Telefone_Model', 'TelefoneModel');
		$this->load->model('Horario_Model', 'HorarioModel');
		$this->load->model('Empresa_Forma_Pagamento_Model', 'EmpresaFormaPagamentoModel');
		$this->load->model('Area_Entrega_Model', 'AreaEntregaModel');
		$this->load->model('Area_Nao_Entrega_Model', 'AreaNaoEntregaModel');
		$this->load->model('Tamanho_Model', 'TamanhoModel');
		$this->load->model('Adicional_Model', 'AdicionalModel');
		$this->load->model('Categoria_Model', 'CategoriaModel');
		$this->load->model('Produto_Model', 'ProdutoModel');
	}
	
	public function index() {
		$dados = array();
		
		$dados['ATUALIZAR']    = site_url('empresa');
		$dados['NOVA_EMPRESA'] = site_url('empresa/novo');
		$dados['BLC_DADOS']    = array();
		
		$this->carregarDados($dados);
		
		$this->parser->parse('empresa_consulta', $dados);
	}
	
	public function novo() {
		$dados = array();
		
		$dados['dlv_id_emp']             = 0;		
		$dados['dlv_nome_emp']           = '';
		$dados['dlv_detalhamento_emp']   = '';
		$dados['dlv_tipopessoa_emp']     = '';
		$dados['dlv_cpfcnpj_emp']        = '';
		$dados['dlv_dlvres_emp']         = '';
		$dados['dlv_numero_emp']         = '';
		$dados['dlv_complemento_emp']    = '';
		$dados['dlv_ativo_emp']          = 'checked';
		$dados['dlv_escolheproduto_emp'] = 'checked';
		$dados['dlv_usaadicionais_emp']  = 'checked';
		$dados['dlv_usatamanhos_emp']    = 'checked';
		$dados['dlv_controlaentregador_emp']    = 'checked';
		
		
		$dados['dlv_tipopessoa_emp_f'] = '';
		$dados['dlv_tipopessoa_emp_j'] = 'selected';
		
		$dados['CLASS_DIV_DLV_CPFCNPJ_EMP_F'] = 'transp';
		$dados['CLASS_DIV_DLV_CPFCNPJ_EMP_J'] = '';
		$dados['DIV_DLV_CPFCNPJ_EMP_F']       = 'disabled';
		$dados['DIV_DLV_CPFCNPJ_EMP_J']       = '';
		
		$dados['glo_cep_end']        = '';
		$dados['glo_logradouro_end'] = '';
		$dados['glo_nome_bai']       = '';
		$dados['glo_nome_cid']       = '';
		
		$dados['ACAO'] = 'Nova';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		$this->carregarResponsaveis($dados);
		
		$this->parser->parse('empresa_cadastro', $dados);
	}
	
	public function editar($dlv_id_emp) {
		$dlv_id_emp = base64_decode($dlv_id_emp);
		$dados = array();
		
		$this->carregarEmpresa($dlv_id_emp, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);
		$this->carregarResponsaveis($dados);
		
		$this->parser->parse('empresa_cadastro', $dados);	
	}
	
	public function salvar() {
		global $dlv_id_emp;
		global $dlv_nome_emp;
		global $dlv_detalhamento_emp;
		global $dlv_tipopessoa_emp;
		global $dlv_cpfcnpj_emp;
		global $dlv_dlvres_emp;
		global $dlv_gloend_emp;
		global $dlv_numero_emp;
		global $dlv_complemento_emp;
		global $dlv_ativo_emp;
		global $dlv_escolheproduto_emp;
		global $dlv_usaadicionais_emp;
		global $dlv_usatamanhos_emp;
		global $dlv_controlaentregador_emp;
		
		
		global $glo_cep_end;
		global $glo_logradouro_end;
		global $glo_nome_bai;
		global $glo_nome_cid;
		
		$dlv_id_emp             = $this->input->post('dlv_id_emp');			
		$dlv_nome_emp           = $this->input->post('dlv_nome_emp');
		$dlv_detalhamento_emp   = $this->input->post('dlv_detalhamento_emp');
		$dlv_tipopessoa_emp     = $this->input->post('dlv_tipopessoa_emp');
		$dlv_cpfcnpj_emp        = $this->input->post('dlv_cpfcnpj_emp');
		$dlv_dlvres_emp         = $this->input->post('dlv_dlvres_emp');
		$dlv_numero_emp         = $this->input->post('dlv_numero_emp');
		$dlv_complemento_emp    = $this->input->post('dlv_complemento_emp');
		$dlv_ativo_emp          = $this->input->post('dlv_ativo_emp');
		$dlv_escolheproduto_emp = $this->input->post('dlv_escolheproduto_emp');
		$dlv_usaadicionais_emp  = $this->input->post('dlv_usaadicionais_emp');
		$dlv_usatamanhos_emp    = $this->input->post('dlv_usatamanhos_emp');
		$dlv_controlaentregador_emp    = $this->input->post('dlv_controlaentregador_emp');
				
		$glo_cep_end          = $this->input->post('glo_cep_end');
		$glo_logradouro_end   = $this->input->post('glo_logradouro_end');
		$glo_nome_bai         = $this->input->post('glo_nome_bai');
		$glo_nome_cid         = $this->input->post('glo_nome_cid');
		
		$dlv_cpfcnpj_emp  = str_replace(".", null, $dlv_cpfcnpj_emp);
		$dlv_cpfcnpj_emp  = str_replace("-", null, $dlv_cpfcnpj_emp);
		$dlv_cpfcnpj_emp  = str_replace("/", null, $dlv_cpfcnpj_emp);
		
		$glo_cep_end      = str_replace("-", null, $glo_cep_end);
		
		if ($this->testarDados()) {
			$empresa = array(
				"dlv_nome_emp"               => $dlv_nome_emp,
				"dlv_detalhamento_emp"       => $dlv_detalhamento_emp,
				"dlv_tipopessoa_emp"         => $dlv_tipopessoa_emp,
				"dlv_cpfcnpj_emp"            => $dlv_cpfcnpj_emp,
				"dlv_cpfcnpj_emp"            => $dlv_cpfcnpj_emp,
				"dlv_dlvres_emp"	         => $dlv_dlvres_emp,
				"dlv_gloend_emp"	         => $dlv_gloend_emp,
				"dlv_numero_emp"	         => $dlv_numero_emp,
				"dlv_complemento_emp"        => $dlv_complemento_emp,
				"dlv_ativo_emp"              => ($dlv_ativo_emp)?'1':'0',
				"dlv_escolheproduto_emp"     => ($dlv_escolheproduto_emp)?'1':'0',
				"dlv_usaadicionais_emp"      => ($dlv_usaadicionais_emp)?'1':'0',
				"dlv_usatamanhos_emp"        => ($dlv_usatamanhos_emp)?'1':'0',
    			"dlv_controlaentregador_emp" => ($dlv_controlaentregador_emp)?'1':'0'
			);
			
			$inclusao = !	$dlv_id_emp;
			
			if (!$dlv_id_emp) {
				$empresa['dlv_taxaentrega_emp']   = 0;
				$empresa['dlv_valorminimo_emp']   = 0;
				$empresa['dlv_tempomedio_emp']    = '00:00:00';
				$empresa['dlv_aberto_emp']        = 0;
                $empresa['dlv_fechamentoaut_emp'] = 1;

				$dlv_id_emp = $this->EmpresaModel->insert($empresa);
				
				if ($dlv_id_emp) {
					$perfil = array (
						"dlv_dlvemp_per"       => $dlv_id_emp,
						"dlv_descricao_per"    => 'Administrador',
						"dlv_cadperfil_per"	   => 1,
						"dlv_cadusuario_per"   => 1,
						"dlv_alttelefone_per"  => 1,
						"dlv_althorario_per"   => 1,
						"dlv_alttaxa_per"      => 1,
						"dlv_altfpg_per"       => 1,
						"dlv_altrede_per"      => 1,
						"dlv_altarea_per"      => 1,
						"dlv_cadcategoria_per" => 1,
						"dlv_cadtamanho_per"   => 1,
						"dlv_cadadicional_per" => 1,
						"dlv_cadproduto_per"   => 1,
						"dlv_altstatusped_per" => 1								
					);
					
					$dlv_id_per = $this->PerfilModel->insert($perfil);
					
					if ($dlv_id_per) {
						$usuario = array(
							"dlv_dlvemp_usu"         => $dlv_id_emp,
							"dlv_nome_usu"           => 'Administrador',
							"dlv_login_usu"          => 'admin',
							"dlv_senha_usu"	         => md5('admin'),		 
							"dlv_dlvper_usu"         => $dlv_id_per,
							"dlv_ativo_usu"          => 1,				 							
							"dlv_kingsoft_usu"       => 0				 							
						);
						
						$dlv_id_usu = $this->UsuarioModel->insert($usuario);
						
						$usuario = array(
								"dlv_dlvemp_usu"         => $dlv_id_emp,
								"dlv_nome_usu"           => 'King Soft',
								"dlv_login_usu"          => 'kingsoft',
								"dlv_senha_usu"	         => md5('K1ngeenv'),
								"dlv_dlvper_usu"         => $dlv_id_per,
								"dlv_ativo_usu"          => 1,
								"dlv_kingsoft_usu"       => 1				 							
						);
						
						$dlv_id_usu = $this->UsuarioModel->insert($usuario);
						
					}
				}
			} else {
				$dlv_id_emp = $this->EmpresaModel->update($empresa, $dlv_id_emp);
			}

			if (is_numeric($dlv_id_emp)) {
				$this->session->set_flashdata('sucesso', 'Empresa salva com sucesso.');
				
				if ($inclusao) {
					redirect('empresa/editar/'.base64_encode($dlv_id_emp));
				} else {
					redirect('empresa');
				}
			} else {
				$this->session->set_flashdata('erro', $dlv_id_emp);	
				redirect('empresa');
			}
		} else {
			if (!$dlv_id_emp) {
				redirect('empresa/novo/');
			} else {
				redirect('empresa/editar/'.base64_encode($dlv_id_emp));
			}			
		}
	}
	
	public function apagar($dlv_id_emp) {
		if ($this->testarApagar(base64_decode($dlv_id_emp))) {
			$res = $this->EmpresaModel->delete(base64_decode($dlv_id_emp));
	
			if ($res) {
				$this->session->set_flashdata('sucesso', 'Empresa apagada com sucesso.');
			} else {
				$this->session->set_flashdata('erro', 'Não foi possível apagar empresa.');				
			}
		}
		
		redirect('empresa');
	}
	
	private function setarURL(&$dados) {
		$dados['CONSULTA_EMPRESA']  = site_url('empresa');
		$dados['ACAO_FORM']         = site_url('empresa/salvar');
		$dados['MASCARA_CPF']       = MASCARA_CPF;		
		$dados['MASCARA_CNPJ']      = MASCARA_CNPJ;		
		$dados['MASCARA_CEP']       = MASCARA_CEP;		
		$dados['URL_ENDERECO']      = site_url('endereco/retornar_endereco_completo/');		
		$dados['URL_SEGMENTOS']     = site_url('empresa_segmento/index/'.base64_encode($dados['dlv_id_emp']));
		$dados['DISABLE_SEGMENTO']	= ($dados['dlv_id_emp'] == 0)?'disabled':'';	
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->EmpresaModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"DLV_ID_EMP"             => $registro->dlv_id_emp,
				"DLV_NOME_EMP"           => $registro->dlv_nome_emp,
				"DLV_ATIVO_EMP"          => ($registro->dlv_ativo_emp == 1)?'checked':'',
				"DLV_ESCOLHEPRODUTO_EMP" => ($registro->dlv_escolheproduto_emp == 1)?'checked':'',
				"DLV_USAADICIONAIS_EMP"  => ($registro->dlv_usaadicionais_emp == 1)?'checked':'',
				"DLV_USATAMANHOS_EMP"    => ($registro->dlv_usatamanhos_emp == 1)?'checked':'',
				"DLV_CONTROLAENTREGADOR_EMP"    => ($registro->dlv_controlaentregador_emp == 1)?'checked':'',
				"VALLESOFT"        => ($registro->dlv_id_emp == 1)?'hidden':'',
				"EDITAR_EMPRESA"   => site_url('empresa/editar/'.base64_encode($registro->dlv_id_emp)),
				"APAGAR_EMPRESA"   => "abrirConfirmacao('".base64_encode($registro->dlv_id_emp)."')"
			);
		}
	}
	
	private function carregarEmpresa($dlv_id_emp, &$dados) {
		$resultado = $this->EmpresaModel->getEmpresa($dlv_id_emp);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
			
			$dados['dlv_tipopessoa_emp_f']        = ($resultado->dlv_tipopessoa_emp == 'f')?'selected':'';
			$dados['dlv_tipopessoa_emp_j']        = ($resultado->dlv_tipopessoa_emp == 'j')?'selected':'';
			
			$dados['dlv_cpfcnpj_emp']             = mascara($resultado->dlv_cpfcnpj_emp, ($resultado->dlv_tipopessoa_emp == 'f')?MASCARA_CPF:MASCARA_CNPJ);
			
			$dados['dlv_ativo_emp']               = ($resultado->dlv_ativo_emp == 1)?'checked':'';
			$dados['dlv_escolheproduto_emp']      = ($resultado->dlv_escolheproduto_emp == 1)?'checked':'';
			$dados['dlv_usaadicionais_emp']       = ($resultado->dlv_usaadicionais_emp == 1)?'checked':'';
			$dados['dlv_usatamanhos_emp']         = ($resultado->dlv_usatamanhos_emp == 1)?'checked':'';
    		$dados['dlv_controlaentregador_emp']         = ($resultado->dlv_controlaentregador_emp == 1)?'checked':'';
			
			$dados['CLASS_DIV_DLV_CPFCNPJ_EMP_F'] = ($resultado->dlv_tipopessoa_emp == 'f')?'':'transp';
			$dados['CLASS_DIV_DLV_CPFCNPJ_EMP_J'] = ($resultado->dlv_tipopessoa_emp == 'j')?'':'transp';
			$dados['DIV_DLV_CPFCNPJ_EMP_F']       = ($resultado->dlv_tipopessoa_emp == 'f')?'':'disabled';
			$dados['DIV_DLV_CPFCNPJ_EMP_J']       = ($resultado->dlv_tipopessoa_emp == 'j')?'':'disabled';
			
			$resultado_endereco = $this->EnderecoModel->getEnderecoCompletoId($resultado->dlv_gloend_emp);
			
			if ($resultado_endereco) {
				$dados['glo_cep_end']        = mascara($resultado_endereco->glo_cep_end, MASCARA_CEP);
				$dados['glo_logradouro_end'] = $resultado_endereco->glo_logradouro_end;
				$dados['glo_nome_bai']       = $resultado_endereco->glo_nome_bai;
				$dados['glo_nome_cid']       = $resultado_endereco->glo_nome_cid;
			}
				
				
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');			
		}
	}
	
	private function carregarResponsaveis(&$dados) {
		$resultado = $this->ResponsavelModel->getResponsaveis();
	
		$dados['BLC_CARGOS'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_RESPONSAVEIS'][] = array(
					"DLV_ID_RES"          => $registro->dlv_id_res,
					"DLV_NOME_RES"        => $registro->dlv_nome_res,
					"SEL_DLV_ID_RES"      => ($dados['dlv_dlvres_emp'] == $registro->dlv_id_res)?'selected':''
			);
		}
	}
	
	
	private function testarDados() {
		global $dlv_id_emp;
		global $dlv_nome_emp;
		global $dlv_detalhamento_emp;
		global $dlv_tipopessoa_emp;
		global $dlv_cpfcnpj_emp;
		global $dlv_dlvres_emp;
		global $dlv_gloend_emp;
		global $dlv_numero_emp;
		global $dlv_complemento_emp;
		global $dlv_ativo_emp;
		global $dlv_escolheproduto_emp;
		global $dlv_usaadicionais_emp;
		global $dlv_usatamanhos_emp;
		global $dlv_controlaentregador_emp;
		
		global $glo_cep_end;
		global $glo_logradouro_end;
		global $glo_nome_bai;
		global $glo_nome_cid;
				
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($dlv_nome_emp)) {
			$erros    = TRUE;
			$mensagem .= "- Nome não preenchido.\n";
			$this->session->set_flashdata('ERRO_DLV_NOME_EMP', 'has-error');				
		}
		
		if (empty($dlv_cpfcnpj_emp)) {
			$erros    = TRUE;
			
			if ($dlv_tipopessoa_emp == 'f') {
				$mensagem .= "- CPF não preenchido.\n";
			} else {
				$mensagem .= "- CNPJ não preenchido.\n";
			} 
			
			$this->session->set_flashdata('ERRO_DLV_CPFCNPJ_EMP', 'has-error');
		} else {
			if ($dlv_tipopessoa_emp == 'f') {
				if (!validaCPF($dlv_cpfcnpj_emp)) {
					$erros    = TRUE;
					$mensagem .= "- CPF inválido.\n";
					$this->session->set_flashdata('ERRO_DLV_CPFCNPJ_EMP', 'has-error');
				} 	
			} else {
				if (!validaCNPJ($dlv_cpfcnpj_emp)) {
					$erros    = TRUE;
					$mensagem .= "- CNPJ inválido.\n";
					$this->session->set_flashdata('ERRO_DLV_CPFCNPJ_EMP', 'has-error');
				}
			}
			
			if ($this->EmpresaModel->getEmpresaCpfCnpj($dlv_id_emp, $dlv_cpfcnpj_emp)) {
				$erros    = TRUE;
				
				if ($dlv_tipopessoa_emp == 'f') {
					$mensagem .= "- Este CPF está cadastrado em outra empresa.\n";
				} else {
					$mensagem .= "- Este CNPJ está cadastrado em outra empresa.\n";
				}
				
				$this->session->set_flashdata('ERRO_DLV_CPFCNPJ_EMP', 'has-error');
			}
		}
		
		if (empty($dlv_dlvres_emp)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione um responsável.\n";
			$this->session->set_flashdata('ERRO_DLV_DLVRES_EMP', 'has-error');
		} else {
			$resultado = $this->ResponsavelModel->getResponsavel($dlv_dlvres_emp);
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Responsável não cadastrado.\n";
				$this->session->set_flashdata('ERRO_DLV_DLVRES_EMP', 'has-error');				
			}
		}
		
		if (empty($glo_cep_end)) {
			$erros    = TRUE;
			$mensagem .= "- CEP não preenchido.\n";
			$this->session->set_flashdata('ERRO_GLO_CEP_END', 'has-error');
		} else {
			$resultado = $this->EnderecoModel->getEnderecoCompleto($glo_cep_end);
				
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Endereço não encontrado.\n";
				$this->session->set_flashdata('ERRO_ENDERECO', 'has-error');
				
				$glo_logradouro_end = '';				
				$glo_nome_bai       = '';				
				$glo_nome_cid       = '';				
			} else {
				$dlv_gloend_emp     = $resultado->glo_id_end;
				
				$glo_logradouro_end = $resultado->glo_logradouro_end;
				$glo_nome_bai       = $resultado->glo_nome_bai;
				$glo_nome_cid       = $resultado->glo_nome_cid;			
			}
		}
		
		if (empty($dlv_numero_emp)) {
			$erros    = TRUE;
			$mensagem .= "- Número não preenchido.\n";
			$this->session->set_flashdata('ERRO_DLV_NUMERO_EMP', 'has-error');
		} 
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_DLV_EMP', TRUE);				
			$this->session->set_flashdata('dlv_nome_emp', $dlv_nome_emp);				
			$this->session->set_flashdata('dlv_detalhamento_emp', $dlv_detalhamento_emp);				
			$this->session->set_flashdata('dlv_tipopessoa_emp', $dlv_tipopessoa_emp);				
			$this->session->set_flashdata('dlv_cpfcnpj_emp', $dlv_cpfcnpj_emp);
			$this->session->set_flashdata('dlv_dlvres_emp', $dlv_dlvres_emp);				
			$this->session->set_flashdata('dlv_numero_emp', $dlv_numero_emp);
			$this->session->set_flashdata('dlv_complemento_emp', $dlv_complemento_emp);
			$this->session->set_flashdata('dlv_ativo_emp', $dlv_ativo_emp);
			$this->session->set_flashdata('dlv_escolheproduto_emp', $dlv_escolheproduto_emp);
			$this->session->set_flashdata('dlv_usaadicionais_emp', $dlv_usaadicionais_emp);
			$this->session->set_flashdata('dlv_usatamanhos_emp', $dlv_usatamanhos_emp);
			$this->session->set_flashdata('dlv_controlaentregador_emp', $dlv_controlaentregador_emp);
				
			$this->session->set_flashdata('glo_cep_end', $glo_cep_end);
			$this->session->set_flashdata('glo_logradouro_end', $glo_logradouro_end);
			$this->session->set_flashdata('glo_nome_bai', $glo_nome_bai);
			$this->session->set_flashdata('glo_nome_cid', $glo_nome_cid);
		}
		
		return !$erros;
	}
	
	private function testarApagar($dlv_id_emp) {
		$erros    = FALSE;
		$mensagem = null;
	
		$resultado = $this->UsuarioModel->getUsuariosEmpresa($dlv_id_emp);	
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais usuários com esta empresa.\n";
		}
		
		$resultado = $this->PerfilModel->getPerfisEmpresa($dlv_id_emp);
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais perfis com esta empresa.\n";
		}
		
		$resultado = $this->TelefoneModel->getTelefonesEmpresa($dlv_id_emp);		
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais telefones com esta empresa.\n";
		}
		
		$resultado = $this->HorarioModel->getHorariosEmpresa($dlv_id_emp);		
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais horários com esta empresa.\n";
		}
		
		$resultado = $this->EmpresaFormaPagamentoModel->getFormasPagamentoEmpresa($dlv_id_emp);		
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Uma ou mais formas de pagamentos com esta empresa.\n";
		}
		
		$resultado = $this->AreaEntregaModel->getAreaEntregaEmpresa($dlv_id_emp);
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Uma ou mais área de entrega com esta empresa.\n";
		}
		
		$resultado = $this->AreaNaoEntregaModel->getEmpresas($dlv_id_emp);
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Uma ou mais área de não entrega com esta empresa.\n";
		}
		
		$resultado = $this->TamanhoModel->getTamanhoEmpresa($dlv_id_emp);
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais tamanhos com esta empresa.\n";
		}
		
		$resultado = $this->AdicionalModel->getAdicionalEmpresa($dlv_id_emp);
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais adicionais com esta empresa.\n";
		}
		
		$resultado = $this->CategoriaModel->getCategoriaEmpresa($dlv_id_emp);
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Uma ou mais categorias com esta empresa.\n";
		}
		
		$resultado = $this->ProdutoModel->getProdutoEmpresa($dlv_id_emp);
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais produtos com esta empresa.\n";
		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar a empresa:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_EMP         = $this->session->flashdata('ERRO_DLV_EMP');
		$ERRO_DLV_NOME_EMP    = $this->session->flashdata('ERRO_DLV_NOME_EMP');
		$ERRO_DLV_CPFCNPJ_EMP = $this->session->flashdata('ERRO_DLV_CPFCNPJ_EMP');
		$ERRO_DLV_DLVRES_EMP  = $this->session->flashdata('ERRO_DLV_DLVRES_EMP');
		$ERRO_DLV_NUMERO_EMP  = $this->session->flashdata('ERRO_DLV_NUMERO_EMP');
		
		$ERRO_ENDERECO        = $this->session->flashdata('ERRO_ENDERECO');
		$ERRO_GLO_CEP_END     = $this->session->flashdata('ERRO_GLO_CEP_END');
		
		$dlv_nome_emp           = $this->session->flashdata('dlv_nome_emp');
		$dlv_detalhamento_emp   = $this->session->flashdata('dlv_detalhamento_emp');
		$dlv_tipopessoa_emp     = $this->session->flashdata('dlv_tipopessoa_emp');
		$dlv_cpfcnpj_emp        = $this->session->flashdata('dlv_cpfcnpj_emp');
		$dlv_dlvres_emp         = $this->session->flashdata('dlv_dlvres_emp');
		$dlv_numero_emp         = $this->session->flashdata('dlv_numero_emp');
		$dlv_complemento_emp    = $this->session->flashdata('dlv_complemento_emp');
		$dlv_ativo_emp          = $this->session->flashdata('dlv_ativo_emp');
		$dlv_escolheproduto_emp = $this->session->flashdata('dlv_escolheproduto_emp');
		$dlv_usaadicionais_emp  = $this->session->flashdata('dlv_usaadicionais_emp');
		$dlv_usatamanhos_emp    = $this->session->flashdata('dlv_usatamanhos_emp');
		$dlv_controlaentregador_emp    = $this->session->flashdata('dlv_controlaentregador_emp');
		
		$glo_cep_end        = $this->session->flashdata('glo_cep_end');
		$glo_logradouro_end = $this->session->flashdata('glo_logradouro_end');
		$glo_nome_bai       = $this->session->flashdata('glo_nome_bai');
		$glo_nome_cid       = $this->session->flashdata('glo_nome_cid');
		
		
		
		if ($ERRO_DLV_EMP) {
			$dados['dlv_nome_emp']           = $dlv_nome_emp;
			$dados['dlv_detalhamento_emp']   = $dlv_detalhamento_emp;
			$dados['dlv_tipopessoa_emp']     = $dlv_tipopessoa_emp;
			$dados['dlv_tipopessoa_emp_f']   = ($dlv_tipopessoa_emp == 'f')?'selected':'';
			$dados['dlv_tipopessoa_emp_j']   = ($dlv_tipopessoa_emp == 'j')?'selected':'';
			$dados['dlv_dlvres_emp']         = $dlv_dlvres_emp;
			$dados['dlv_numero_emp']         = $dlv_numero_emp;
			$dados['dlv_complemento_emp']    = $dlv_complemento_emp;
			$dados['dlv_ativo_emp']          = ($dlv_ativo_emp == 1)?'checked':'';
			$dados['dlv_escolheproduto_emp'] = ($dlv_escolheproduto_emp == 1)?'checked':'';
			$dados['dlv_usaadicionais_emp']  = ($dlv_usaadicionais_emp == 1)?'checked':'';
			$dados['dlv_usatamanhos_emp']    = ($dlv_usatamanhos_emp == 1)?'checked':'';
			$dados['dlv_controlaentregador_emp']    = ($dlv_controlaentregador_emp == 1)?'checked':'';
				
			if (empty($dlv_cpfcnpj_emp)) {
				$dados['dlv_cpfcnpj_emp']  = '';
			} else {
				$dados['dlv_cpfcnpj_emp']  = mascara($dlv_cpfcnpj_emp, ($dlv_tipopessoa_emp == 'f')?MASCARA_CPF:MASCARA_CNPJ);
			}
			
			$dados['glo_cep_end']        = mascara($glo_cep_end, MASCARA_CEP);
			$dados['glo_logradouro_end'] = $glo_logradouro_end;
			$dados['glo_nome_bai']       = $glo_nome_bai;
			$dados['glo_nome_cid']       = $glo_nome_cid;
				
				
			$dados['CLASS_DIV_DLV_CPFCNPJ_EMP_F'] = ($dlv_tipopessoa_emp == 'f')?'':'transp';
			$dados['CLASS_DIV_DLV_CPFCNPJ_EMP_J'] = ($dlv_tipopessoa_emp == 'j')?'':'transp';
			$dados['DIV_DLV_CPFCNPJ_EMP_F']       = ($dlv_tipopessoa_emp == 'f')?'':'disabled';
			$dados['DIV_DLV_CPFCNPJ_EMP_J']       = ($dlv_tipopessoa_emp == 'j')?'':'disabled';
				
			$dados['ERRO_DLV_NOME_EMP']    = $ERRO_DLV_NOME_EMP;
			$dados['ERRO_DLV_CPFCNPJ_EMP'] = $ERRO_DLV_CPFCNPJ_EMP;
			$dados['ERRO_DLV_DLVRES_EMP']  = $ERRO_DLV_DLVRES_EMP;
			$dados['ERRO_DLV_NUMERO_EMP']  = $ERRO_DLV_NUMERO_EMP;
			
			$dados['ERRO_GLO_CEP_END']     = $ERRO_GLO_CEP_END;
			$dados['ERRO_ENDERECO']        = $ERRO_ENDERECO;
		}		
		
	}
}