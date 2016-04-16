<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		if ($this->session->userdata('bus_id_emp') != 1) {redirect('');}
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Empresa_Model', 'EmpresaModel');
		$this->load->model('Endereco_Model', 'EnderecoModel');
		$this->load->model('Perfil_Model', 'PerfilModel');
		$this->load->model('Usuario_Model', 'UsuarioModel');
		$this->load->model('Telefone_Model', 'TelefoneModel');
		$this->load->model('Segmento_Model', 'SegmentoModel');
//		$this->load->model('Produto_Model', 'ProdutoModel');
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
		
		$dados['bus_id_emp']             = 0;		
		$dados['bus_nome_emp']           = '';
		$dados['bus_detalhamento_emp']   = '';
		$dados['bus_tipopessoa_emp']     = '';
		$dados['bus_cpfcnpj_emp']        = '';
		$dados['bus_numero_emp']         = '';
		$dados['bus_complemento_emp']    = '';
		$dados['bus_busseg_emp']         = '';
		$dados['bus_ativo_emp']          = 'checked';
		$dados['bus_escolheproduto_emp'] = 'checked';
		$dados['bus_usaadicionais_emp']  = 'checked';
		$dados['bus_usatamanhos_emp']    = 'checked';
		$dados['bus_controlaentregador_emp']    = 'checked';
		
		
		$dados['bus_tipopessoa_emp_f'] = '';
		$dados['bus_tipopessoa_emp_j'] = 'selected';
		
		$dados['CLASS_DIV_BUS_CPFCNPJ_EMP_F'] = 'transp';
		$dados['CLASS_DIV_BUS_CPFCNPJ_EMP_J'] = '';
		$dados['DIV_BUS_CPFCNPJ_EMP_F']       = 'disabled';
		$dados['DIV_BUS_CPFCNPJ_EMP_J']       = '';
		
		$dados['glo_cep_end']        = '';
		$dados['glo_logradouro_end'] = '';
		$dados['glo_nome_bai']       = '';
		$dados['glo_nome_cid']       = '';
		
		$dados['ACAO'] = 'Nova';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);

		$this->carregarSegmentos($dados);
		
		$this->parser->parse('empresa_cadastro', $dados);
	}
	
	public function editar($bus_id_emp) {
		$bus_id_emp = base64_decode($bus_id_emp);
		$dados = array();
		
		$this->carregarEmpresa($bus_id_emp, $dados);
		
		$dados['ACAO'] = 'Editar';
		$this->setarURL($dados);
		
		$this->carregarDadosFlash($dados);

		$this->carregarSegmentos($dados);
		
		$this->parser->parse('empresa_cadastro', $dados);	
	}
	
	public function salvar() {
		global $bus_id_emp;
		global $bus_nome_emp;
		global $bus_detalhamento_emp;
		global $bus_tipopessoa_emp;
		global $bus_cpfcnpj_emp;
		global $bus_gloend_emp;
		global $bus_numero_emp;
		global $bus_complemento_emp;
		global $bus_ativo_emp;
		global $bus_busseg_emp;

		
		
		global $glo_cep_end;
		global $glo_logradouro_end;
		global $glo_nome_bai;
		global $glo_nome_cid;
		
		$bus_id_emp             = $this->input->post('bus_id_emp');			
		$bus_nome_emp           = $this->input->post('bus_nome_emp');
		$bus_detalhamento_emp   = $this->input->post('bus_detalhamento_emp');
		$bus_tipopessoa_emp     = $this->input->post('bus_tipopessoa_emp');
		$bus_cpfcnpj_emp        = $this->input->post('bus_cpfcnpj_emp');
		$bus_numero_emp         = $this->input->post('bus_numero_emp');
		$bus_complemento_emp    = $this->input->post('bus_complemento_emp');
		$bus_ativo_emp          = $this->input->post('bus_ativo_emp');
		$bus_busseg_emp         = $this->input->post('bus_busseg_emp');

				
		$glo_cep_end          = $this->input->post('glo_cep_end');
		$glo_logradouro_end   = $this->input->post('glo_logradouro_end');
		$glo_nome_bai         = $this->input->post('glo_nome_bai');
		$glo_nome_cid         = $this->input->post('glo_nome_cid');
		
		$bus_cpfcnpj_emp  = str_replace(".", null, $bus_cpfcnpj_emp);
		$bus_cpfcnpj_emp  = str_replace("-", null, $bus_cpfcnpj_emp);
		$bus_cpfcnpj_emp  = str_replace("/", null, $bus_cpfcnpj_emp);
		
		$glo_cep_end      = str_replace("-", null, $glo_cep_end);
		
		if ($this->testarDados()) {
			$empresa = array(
				"bus_nome_emp"               => $bus_nome_emp,
				"bus_detalhamento_emp"       => $bus_detalhamento_emp,
				"bus_tipopessoa_emp"         => $bus_tipopessoa_emp,
				"bus_cpfcnpj_emp"            => $bus_cpfcnpj_emp,
				"bus_cpfcnpj_emp"            => $bus_cpfcnpj_emp,
				"bus_gloend_emp"	         => $bus_gloend_emp,
				"bus_numero_emp"	         => $bus_numero_emp,
				"bus_complemento_emp"        => $bus_complemento_emp,
				"bus_ativo_emp"              => ($bus_ativo_emp)?'1':'0',
				"bus_busseg_emp"             => $bus_busseg_emp
			);
			
			$inclusao = !	$bus_id_emp;
			
			if (!$bus_id_emp) {
				$empresa['bus_aberto_emp']        = 0;


				$bus_id_emp = $this->EmpresaModel->insert($empresa);
				
				if ($bus_id_emp) {
					$perfil = array (
						"bus_busemp_per"       => $bus_id_emp,
						"bus_descricao_per"    => 'Administrador',
						"bus_cadperfil_per"	   => 1,
						"bus_cadusuario_per"   => 1,
						"bus_alttelefone_per"  => 1,
						"bus_althorario_per"   => 1,
						"bus_cadcategoria_per"      => 1,
						"bus_cadproduto_per"   => 1,
					);
					
					$bus_id_per = $this->PerfilModel->insert($perfil);
					
					if ($bus_id_per) {
						$usuario = array(
							"bus_busemp_usu"         => $bus_id_emp,
							"bus_nome_usu"           => 'Administrador',
							"bus_login_usu"          => 'admin',
							"bus_senha_usu"	         => md5('admin'),		 
							"bus_busper_usu"         => $bus_id_per,
							"bus_ativo_usu"          => 1,				 							
							"bus_kingsoft_usu"       => 0				 							
						);
						
						$bus_id_usu = $this->UsuarioModel->insert($usuario);
						
						$usuario = array(
								"bus_busemp_usu"         => $bus_id_emp,
								"bus_nome_usu"           => 'King Soft',
								"bus_login_usu"          => 'kingsoft',
								"bus_senha_usu"	         => md5('K1ngeenv'),
								"bus_busper_usu"         => $bus_id_per,
								"bus_ativo_usu"          => 1,
								"bus_kingsoft_usu"       => 1				 							
						);
						
						$bus_id_usu = $this->UsuarioModel->insert($usuario);
						
					}
				}
			} else {
				$bus_id_emp = $this->EmpresaModel->update($empresa, $bus_id_emp);
			}

			if (is_numeric($bus_id_emp)) {
				$this->session->set_flashdata('sucesso', 'Empresa salva com sucesso.');
				
				if ($inclusao) {
					redirect('empresa/editar/'.base64_encode($bus_id_emp));
				} else {
					redirect('empresa');
				}
			} else {
				$this->session->set_flashdata('erro', $bus_id_emp);	
				redirect('empresa');
			}
		} else {
			if (!$bus_id_emp) {
				redirect('empresa/novo/');
			} else {
				redirect('empresa/editar/'.base64_encode($bus_id_emp));
			}			
		}
	}
	
	public function apagar($bus_id_emp) {
		if ($this->testarApagar(base64_decode($bus_id_emp))) {
			$res = $this->EmpresaModel->delete(base64_decode($bus_id_emp));
	
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
		$dados['URL_TIPO']     = site_url('empresa_tipo/index/'.base64_encode($dados['bus_id_emp']). '/' . base64_encode($dados['bus_busseg_emp']));
		$dados['DISABLE_TIPO']	= ($dados['bus_id_emp'] == 0)?'disabled':'';
	}
	
	private function carregarDados(&$dados) {
		$resultado = $this->EmpresaModel->get();
		
		foreach ($resultado as $registro) {
			$dados['BLC_DADOS'][] = array(
				"BUS_ID_EMP"             => $registro->bus_id_emp,
				"BUS_NOME_EMP"           => $registro->bus_nome_emp,
				"BUS_ATIVO_EMP"          => ($registro->bus_ativo_emp == 1)?'checked':'',
				"VALLESOFT"        => ($registro->bus_id_emp == 1)?'hidden':'',
				"EDITAR_EMPRESA"   => site_url('empresa/editar/'.base64_encode($registro->bus_id_emp)),
				"APAGAR_EMPRESA"   => "abrirConfirmacao('".base64_encode($registro->bus_id_emp)."')"
			);
		}
	}
	
	private function carregarEmpresa($bus_id_emp, &$dados) {
		$resultado = $this->EmpresaModel->getEmpresa($bus_id_emp);
		
		if ($resultado) {
			foreach ($resultado as $chave => $valor) {
				$dados[$chave] = $valor;
			}
			
			$dados['bus_tipopessoa_emp_f']        = ($resultado->bus_tipopessoa_emp == 'f')?'selected':'';
			$dados['bus_tipopessoa_emp_j']        = ($resultado->bus_tipopessoa_emp == 'j')?'selected':'';
			
			$dados['bus_cpfcnpj_emp']             = mascara($resultado->bus_cpfcnpj_emp, ($resultado->bus_tipopessoa_emp == 'f')?MASCARA_CPF:MASCARA_CNPJ);
			
			$dados['bus_ativo_emp']               = ($resultado->bus_ativo_emp == 1)?'checked':'';
			
			
			$dados['CLASS_DIV_BUS_CPFCNPJ_EMP_F'] = ($resultado->bus_tipopessoa_emp == 'f')?'':'transp';
			$dados['CLASS_DIV_BUS_CPFCNPJ_EMP_J'] = ($resultado->bus_tipopessoa_emp == 'j')?'':'transp';
			$dados['DIV_BUS_CPFCNPJ_EMP_F']       = ($resultado->bus_tipopessoa_emp == 'f')?'':'disabled';
			$dados['DIV_BUS_CPFCNPJ_EMP_J']       = ($resultado->bus_tipopessoa_emp == 'j')?'':'disabled';
			
			$resultado_endereco = $this->EnderecoModel->getEnderecoCompletoId($resultado->bus_gloend_emp);
			
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

	private function carregarSegmentos(&$dados) {
		$resultado = $this->SegmentoModel->get();

		$dados['BLC_SEGMENTO'] = array();

		foreach ($resultado as $registro) {
			$dados['BLC_SEGMENTO'][] = array(
				"BUS_ID_SEG"          => $registro->bus_id_seg,
				"BUS_DESCRICAO_SEG"   => $registro->bus_descricao_seg,
				"SEL_BUS_ID_SEG"      => ($dados['bus_busseg_emp'] == $registro->bus_id_seg)?'selected':''
			);
		}
	}
	
	
	private function testarDados() {
		global $bus_id_emp;
		global $bus_nome_emp;
		global $bus_detalhamento_emp;
		global $bus_tipopessoa_emp;
		global $bus_cpfcnpj_emp;
		global $bus_gloend_emp;
		global $bus_numero_emp;
		global $bus_complemento_emp;
		global $bus_ativo_emp;
		global $bus_busseg_emp;
		global $bus_escolheproduto_emp;
		global $bus_usaadicionais_emp;
		global $bus_usatamanhos_emp;
		global $bus_controlaentregador_emp;
		
		global $glo_cep_end;
		global $glo_logradouro_end;
		global $glo_nome_bai;
		global $glo_nome_cid;
				
		$erros    = FALSE;
		$mensagem = null;
		
		
		if (empty($bus_nome_emp)) {
			$erros    = TRUE;
			$mensagem .= "- Nome não preenchido.\n";
			$this->session->set_flashdata('ERRO_BUS_NOME_EMP', 'has-error');				
		}
		
		if (empty($bus_cpfcnpj_emp)) {
			$erros    = TRUE;
			
			if ($bus_tipopessoa_emp == 'f') {
				$mensagem .= "- CPF não preenchido.\n";
			} else {
				$mensagem .= "- CNPJ não preenchido.\n";
			} 
			
			$this->session->set_flashdata('ERRO_BUS_CPFCNPJ_EMP', 'has-error');
		} else {
			if ($bus_tipopessoa_emp == 'f') {
				if (!validaCPF($bus_cpfcnpj_emp)) {
					$erros    = TRUE;
					$mensagem .= "- CPF inválido.\n";
					$this->session->set_flashdata('ERRO_BUS_CPFCNPJ_EMP', 'has-error');
				} 	
			} else {
				if (!validaCNPJ($bus_cpfcnpj_emp)) {
					$erros    = TRUE;
					$mensagem .= "- CNPJ inválido.\n";
					$this->session->set_flashdata('ERRO_BUS_CPFCNPJ_EMP', 'has-error');
				}
			}

			if (empty($bus_busseg_emp)) {
				$erros    = TRUE;
				$mensagem .= "- Selecione um  segmento.\n";
				$this->session->set_flashdata('ERRO_BUS_BUSSEG_EMP', 'has-error');
			} else {
				$resultado = $this->SegmentoModel->getSegmento($bus_busseg_emp);
				if (!$resultado) {
					$erros = TRUE;
					$mensagem .= "- Segmento não cadastrado.\n";
					$this->session->set_flashdata('ERRO_BUS_BUSSEG_EMP', 'has-error');
				}
			}
			
			if ($this->EmpresaModel->getEmpresaCpfCnpj($bus_id_emp, $bus_cpfcnpj_emp)) {
				$erros    = TRUE;
				
				if ($bus_tipopessoa_emp == 'f') {
					$mensagem .= "- Este CPF está cadastrado em outra empresa.\n";
				} else {
					$mensagem .= "- Este CNPJ está cadastrado em outra empresa.\n";
				}
				
				$this->session->set_flashdata('ERRO_BUS_CPFCNPJ_EMP', 'has-error');
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
				$bus_gloend_emp     = $resultado->glo_id_end;
				
				$glo_logradouro_end = $resultado->glo_logradouro_end;
				$glo_nome_bai       = $resultado->glo_nome_bai;
				$glo_nome_cid       = $resultado->glo_nome_cid;			
			}
		}
		
		if (empty($bus_numero_emp)) {
			$erros    = TRUE;
			$mensagem .= "- Número não preenchido.\n";
			$this->session->set_flashdata('ERRO_BUS_NUMERO_EMP', 'has-error');
		} 
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
			
			$this->session->set_flashdata('ERRO_BUS_EMP', TRUE);				
			$this->session->set_flashdata('bus_nome_emp', $bus_nome_emp);				
			$this->session->set_flashdata('bus_detalhamento_emp', $bus_detalhamento_emp);				
			$this->session->set_flashdata('bus_tipopessoa_emp', $bus_tipopessoa_emp);				
			$this->session->set_flashdata('bus_cpfcnpj_emp', $bus_cpfcnpj_emp);
			$this->session->set_flashdata('bus_numero_emp', $bus_numero_emp);
			$this->session->set_flashdata('bus_complemento_emp', $bus_complemento_emp);
			$this->session->set_flashdata('bus_ativo_emp', $bus_ativo_emp);
			$this->session->set_flashdata('bus_escolheproduto_emp', $bus_escolheproduto_emp);
			$this->session->set_flashdata('bus_usaadicionais_emp', $bus_usaadicionais_emp);
			$this->session->set_flashdata('bus_usatamanhos_emp', $bus_usatamanhos_emp);
			$this->session->set_flashdata('bus_controlaentregador_emp', $bus_controlaentregador_emp);
			$this->session->set_flashdata('bus_busseg_emp', $bus_busseg_emp);
				
			$this->session->set_flashdata('glo_cep_end', $glo_cep_end);
			$this->session->set_flashdata('glo_logradouro_end', $glo_logradouro_end);
			$this->session->set_flashdata('glo_nome_bai', $glo_nome_bai);
			$this->session->set_flashdata('glo_nome_cid', $glo_nome_cid);
		}
		
		return !$erros;
	}
	
	private function testarApagar($bus_id_emp) {
		$erros    = FALSE;
		$mensagem = null;
	
		$resultado = $this->UsuarioModel->getUsuariosEmpresa($bus_id_emp);	
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais usuários com esta empresa.\n";
		}
		
		$resultado = $this->PerfilModel->getPerfisEmpresa($bus_id_emp);
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais perfis com esta empresa.\n";
		}
		
		$resultado = $this->TelefoneModel->getTelefonesEmpresa($bus_id_emp);		
		if ($resultado) {
			$erros    = TRUE;
			$mensagem .= "- Um ou mais telefones com esta empresa.\n";
		}
		

		


		
//		$resultado = $this->ProdutoModel->getProdutoEmpresa($bus_id_emp);
//		if ($resultado) {
//			$erros    = TRUE;
//			$mensagem .= "- Um ou mais produtos com esta empresa.\n";
//		}
		
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Não foi possível apagar a empresa:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
		}
	
		return !$erros;
	}
	
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_BUS_EMP         = $this->session->flashdata('ERRO_BUS_EMP');
		$ERRO_BUS_NOME_EMP    = $this->session->flashdata('ERRO_BUS_NOME_EMP');
		$ERRO_BUS_CPFCNPJ_EMP = $this->session->flashdata('ERRO_BUS_CPFCNPJ_EMP');
		$ERRO_BUS_NUMERO_EMP  = $this->session->flashdata('ERRO_BUS_NUMERO_EMP');
		$ERRO_BUS_BUSSEG_EMP = $this->session->flashdata('ERRO_BUS_BUSSSEG_EMP');
		
		$ERRO_ENDERECO        = $this->session->flashdata('ERRO_ENDERECO');
		$ERRO_GLO_CEP_END     = $this->session->flashdata('ERRO_GLO_CEP_END');
		
		$bus_nome_emp           = $this->session->flashdata('bus_nome_emp');
		$bus_detalhamento_emp   = $this->session->flashdata('bus_detalhamento_emp');
		$bus_tipopessoa_emp     = $this->session->flashdata('bus_tipopessoa_emp');
		$bus_cpfcnpj_emp        = $this->session->flashdata('bus_cpfcnpj_emp');
		$bus_numero_emp         = $this->session->flashdata('bus_numero_emp');
		$bus_complemento_emp    = $this->session->flashdata('bus_complemento_emp');
		$bus_ativo_emp          = $this->session->flashdata('bus_ativo_emp');
		$bus_busseg_emp         = $this->session->flashdata('bus_busseg_emp');
		$bus_escolheproduto_emp = $this->session->flashdata('bus_escolheproduto_emp');
		$bus_usaadicionais_emp  = $this->session->flashdata('bus_usaadicionais_emp');
		$bus_usatamanhos_emp    = $this->session->flashdata('bus_usatamanhos_emp');
		$bus_controlaentregador_emp    = $this->session->flashdata('bus_controlaentregador_emp');
		
		$glo_cep_end        = $this->session->flashdata('glo_cep_end');
		$glo_logradouro_end = $this->session->flashdata('glo_logradouro_end');
		$glo_nome_bai       = $this->session->flashdata('glo_nome_bai');
		$glo_nome_cid       = $this->session->flashdata('glo_nome_cid');
		
		
		
		if ($ERRO_BUS_EMP) {
			$dados['bus_nome_emp']           = $bus_nome_emp;
			$dados['bus_detalhamento_emp']   = $bus_detalhamento_emp;
			$dados['bus_tipopessoa_emp']     = $bus_tipopessoa_emp;
			$dados['bus_tipopessoa_emp_f']   = ($bus_tipopessoa_emp == 'f')?'selected':'';
			$dados['bus_tipopessoa_emp_j']   = ($bus_tipopessoa_emp == 'j')?'selected':'';
			$dados['bus_numero_emp']         = $bus_numero_emp;
			$dados['bus_complemento_emp']    = $bus_complemento_emp;
			$dados['bus_ativo_emp']          = ($bus_ativo_emp == 1)?'checked':'';
			$dados['bus_escolheproduto_emp'] = ($bus_escolheproduto_emp == 1)?'checked':'';
			$dados['bus_usaadicionais_emp']  = ($bus_usaadicionais_emp == 1)?'checked':'';
			$dados['bus_usatamanhos_emp']    = ($bus_usatamanhos_emp == 1)?'checked':'';
			$dados['bus_controlaentregador_emp']    = ($bus_controlaentregador_emp == 1)?'checked':'';
			$dados['bus_busseg_emp']          = $bus_busseg_emp;
				
			if (empty($bus_cpfcnpj_emp)) {
				$dados['bus_cpfcnpj_emp']  = '';
			} else {
				$dados['bus_cpfcnpj_emp']  = mascara($bus_cpfcnpj_emp, ($bus_tipopessoa_emp == 'f')?MASCARA_CPF:MASCARA_CNPJ);
			}
			
			$dados['glo_cep_end']        = mascara($glo_cep_end, MASCARA_CEP);
			$dados['glo_logradouro_end'] = $glo_logradouro_end;
			$dados['glo_nome_bai']       = $glo_nome_bai;
			$dados['glo_nome_cid']       = $glo_nome_cid;
				
				
			$dados['CLASS_DIV_BUS_CPFCNPJ_EMP_F'] = ($bus_tipopessoa_emp == 'f')?'':'transp';
			$dados['CLASS_DIV_BUS_CPFCNPJ_EMP_J'] = ($bus_tipopessoa_emp == 'j')?'':'transp';
			$dados['DIV_BUS_CPFCNPJ_EMP_F']       = ($bus_tipopessoa_emp == 'f')?'':'disabled';
			$dados['DIV_BUS_CPFCNPJ_EMP_J']       = ($bus_tipopessoa_emp == 'j')?'':'disabled';
			$dados['ERRO_BUSSEG_EMP']             = $ERRO_BUS_BUSSEG_EMP;
				
			$dados['ERRO_BUS_NOME_EMP']    = $ERRO_BUS_NOME_EMP;
			$dados['ERRO_BUS_CPFCNPJ_EMP'] = $ERRO_BUS_CPFCNPJ_EMP;
			$dados['ERRO_BUS_NUMERO_EMP']  = $ERRO_BUS_NUMERO_EMP;
			
			$dados['ERRO_GLO_CEP_END']     = $ERRO_GLO_CEP_END;
			$dados['ERRO_ENDERECO']        = $ERRO_ENDERECO;
		}		
		
	}
}