<?php if (!$_SERVER['HTTP_REFERER']) $this->redirect('');

class Cliente_JSON extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Cliente_Model', 'ClienteModel');
	}
	
	public function login() {
		$dados['resposta'] = false;
		$cliente           = $this->ClienteModel->getClienteLoginEmail($this->input->post('dlv_email_cli'), md5($this->input->post('dlv_senha_cli')));
			
		if ($cliente) {
			$this->session->set_userdata(array('cliente_logado' => TRUE,
					'dlv_id_cli'     => $cliente->dlv_id_cli,
					'dlv_nome_cli'   => $cliente->dlv_nome_cli
			));
	
			$dados['resposta'] = true;
		}
	
		echo json_encode($dados);
	}
	
	public function login_facebook() {
		$dados['resposta'] = false;
		$cliente           = $this->ClienteModel->verificarFacebook($this->input->post('dlv_idfacebook_cli'));
			
		if ($cliente) {
			$this->session->set_userdata(array('cliente_logado' => TRUE,
					'dlv_id_cli'     => $cliente->dlv_id_cli,
					'dlv_nome_cli'   => $cliente->dlv_nome_cli
			));
	
			$dados['resposta'] = true;
		}
	
		echo json_encode($dados);
	}
	
	public function verificar_email($dlv_email_cli) {
		$dados = array();
	
		$resultado = $this->ClienteModel->verificarEmail($dlv_email_cli);
			
		if ($resultado) {
			$dados['resposta'] = false;
		} else {
			$dados['resposta'] = true;
		}
	
		echo json_encode($dados);
	}
	
	public function cadastrar() {
		$cliente = array (
			'dlv_nome_cli'   => $this->input->post('dlv_nome_cli'),
			'dlv_email_cli'  => $this->input->post('dlv_email_cli'),
			'dlv_fone_cli'   => $this->input->post('dlv_fone_cli'),
			'dlv_senha_cli'  => md5($this->input->post('dlv_senha_cli'))
		);
		
		$dados['resposta'] = false;
		$dlv_id_cli        = $this->ClienteModel->insert($cliente);
		
		if (is_numeric($dlv_id_cli)) {
			$cliente = $this->ClienteModel->getCliente($dlv_id_cli);
			
			if ($cliente) {
				$this->session->set_userdata(array('cliente_logado' => TRUE,
						                           'dlv_id_cli'     => $cliente->dlv_id_cli,
						                           'dlv_nome_cli'   => $cliente->dlv_nome_cli
				));
				
				$dados['resposta'] = true;
			}					                           
		}
		
		echo json_encode($dados);
	}
	
	public function cadastrar_facebook() {
		$cliente = array (
			'dlv_nome_cli'       => $this->input->post('dlv_nome_cli'),
			'dlv_idfacebook_cli' => $this->input->post('dlv_idfacebook_cli'),
			'dlv_fone_cli'       => $this->input->post('dlv_fone_cli')
		);
	
		$dados['resposta'] = false;
		
		$cliente_facebook = $this->ClienteModel->verificarFacebook($this->input->post('dlv_idfacebook_cli'));
		
		if ($cliente_facebook) {
			$dlv_id_cli = $cliente_facebook->dlv_id_cli;
		} else {
			$dlv_id_cli = $this->ClienteModel->insert($cliente);				
		}
	
		if (is_numeric($dlv_id_cli)) {
			$cliente = $this->ClienteModel->getCliente($dlv_id_cli);
				
			if ($cliente) {
				$this->session->set_userdata(array('cliente_logado' => TRUE,
						'dlv_id_cli'     => $cliente->dlv_id_cli,
						'dlv_nome_cli'   => $cliente->dlv_nome_cli
				));
	
				$dados['resposta'] = true;
			}
		}
	
		echo json_encode($dados);
	}
	
	
	public function alterar($chave, $dlv_id_cli) {
		$json    = $this->input->post('json');
		$cliente = json_decode($json,true);
	
		$msgErros = "";
		$erros    = FALSE;
	
		if ($chave != CHAVE_MD5) {
			$msgErros .= "- Chave de acesso inválida.\n";
			$erros     = TRUE;
		} 
	
		if (!$erros) {
			$dlv_id_cli = $this->ClienteModel->update($cliente, $dlv_id_cli);
				
			if (is_numeric($dlv_id_cli)) {
				echo "s";
			} else {
				echo "n";
			}
		} else {
			echo $msgErros;
		}
	}	
	
	public function retornar_login_email($chave, $dlv_email_cli, $dlv_senha_cli) {
		$dados = array();
		
		$dlv_email_cli = str_ireplace("%20", " ", $dlv_email_cli);
		
		if ($chave == CHAVE_MD5) {
			$resultado = $this->ClienteModel->getClienteLoginEmail($dlv_email_cli, $dlv_senha_cli);
			
			if ($resultado) {
				foreach ($resultado as $chave => $valor) {
					$dados[$chave] = $valor;
				}
			}			
		}	

		echo json_encode($dados);		
	}	
	
	public function verificar_facebook($chave, $dlv_idfacebook_cli) {
		$dados = array();
		
		if ($chave == CHAVE_MD5) {
			$resultado = $this->ClienteModel->verificarFacebook($dlv_idfacebook_cli);
							
			if ($resultado) {
				foreach ($resultado as $chave => $valor) {
					$dados[$chave] = $valor;
				}
			}
		}
		
		
		echo json_encode($dados);
	}
	
	public function recuperar_senha($chave, $dlv_email_cli) {
		$dados = array();
		$this->load->library('email');
		$verificarEmail = $this->ClienteModel->verificarEmail($dlv_email_cli);
		
		$msgErros = "";
		$erros    = FALSE;
		
		if ($chave != CHAVE_MD5) {
			$msgErros .= "- Chave de acesso inválida.";
			$erros     = TRUE;
		} else {
			if (!$verificarEmail) {
				$msgErros .= "- Nenhum usuário cadastrado com este email."; 
				$erros     = TRUE;
			}
		}
		
		if (!$erros) {
			$nova_senha = $this->gerarSenha(6, 5);
			$subject    = "Recuperação de senha - Encontre Delivery";
			$from       = "contato@encontredelivery.com.br";
			$from_nick  = "Encontre Delivery";		
			$to         = $dlv_email_cli;
			$body       = "Olá, ".$verificarEmail->dlv_nome_cli.".<br>".
					      "Alteramos sua senha, logue com ela e em seguida altere na tela de 'configurações'.<br>".
					      "Sua nova senha é: <strong>".$nova_senha."</strong>";
			
			$this->email->initialize(); // Aqui carrega todo config criado anteriormente
			$this->email->subject($subject); //assunto
			$this->email->from($from, $from_nick); //quem mandou
			$this->email->to($to); //quem recebe
			$this->email->message($body); //corpo da mensagem
			
			$this->email->send(); // Envia o email
			
			$cliente['dlv_senha_cli'] = md5($nova_senha);
			
			$dlv_id_cli = $this->ClienteModel->update($cliente, $verificarEmail->dlv_id_cli);
			
			if (is_numeric($dlv_id_cli)) {
				$dados['status'] = 'sucesso';
			} else {
				$dados['status']   = 'erro';
				$dados['mensagem'] = 'Erro ao atualizar senha. Ignore o e-mail e tente novamente.';
			}
			
			
		} else {
			$dados['status']   = 'erro';
			$dados['mensagem'] = $msgErros;
		}
		
		echo json_encode($dados);
	}
	
	function gerarSenha($tamanho=9, $forca=0) {
		$vogais = 'aeuy';
		$consoantes = 'bdghjmnpqrstvz';
		if ($forca >= 1) {
			$consoantes .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($forca >= 2) {
			$vogais .= "AEUY";
		}
		if ($forca >= 4) {
			$consoantes .= '23456789';
		}
		if ($forca >= 8 ) {
			$vogais .= '@#$%';
		}
	
		$senha = '';
		$alt = time() % 2;
		for ($i = 0; $i < $tamanho; $i++) {
			if ($alt == 1) {
				$senha .= $consoantes[(rand() % strlen($consoantes))];
				$alt = 0;
			} else {
				$senha .= $vogais[(rand() % strlen($vogais))];
				$alt = 1;
			}
		}
		return $senha;
	}	

}