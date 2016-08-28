<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cliente_JSON extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Cliente_Model', 'ClienteModel');
	}
	
	public function cadastrar($chave) {
		$json    = $this->input->post('json');
		$cliente = json_decode($json,true);
		
		$verificarEmail = FALSE;		
		if (empty($cliente['bus_idfacebook_cli'])) {
			$verificarEmail = $this->ClienteModel->verificarEmail($cliente['bus_email_cli']);				
		}
		
		$msgErros = "";
		$erros    = FALSE;
		
		if ($chave != CHAVE_MD5) {
			$msgErros .= "- Chave de acesso inválida.\n";
			$erros     = TRUE;
		} else {
			if ($verificarEmail) {
				$msgErros .= "- Email já cadastrado. \n"; 
				$erros     = TRUE;
			}
		}
		
		if (!$erros) {
            $bus_id_cli = $this->ClienteModel->insert($cliente);
			
			if (is_numeric($bus_id_cli)) {
				echo "s".$bus_id_cli;
			} else {
				echo "n";
			}
		} else {
			echo $msgErros;
		}
	}
	
	public function alterar($chave, $bus_id_cli) {
		$json    = $this->input->post('json');
		$cliente = json_decode($json,true);
	
		$msgErros = "";
		$erros    = FALSE;
	
		if ($chave != CHAVE_MD5) {
			$msgErros .= "- Chave de acesso inválida.\n";
			$erros     = TRUE;
		} 
	
		if (!$erros) {
			$bus_id_cli = $this->ClienteModel->update($cliente, $bus_id_cli);
				
			if (is_numeric($bus_id_cli)) {
				echo "s";
			} else {
				echo "n";
			}
		} else {
			echo $msgErros;
		}
	}	
	
	public function retornar_login_email($chave, $bus_email_cli, $bus_senha_cli) {
		$dados = array();
		
		$bus_email_cli = str_ireplace("%20", " ", $bus_email_cli);
		
		if ($chave == CHAVE_MD5) {
			$resultado = $this->ClienteModel->getClienteLoginEmail($bus_email_cli, $bus_senha_cli);
			
			if ($resultado) {
				foreach ($resultado as $chave => $valor) {
					$dados[$chave] = $valor;
				}
			}			
		}	

		echo json_encode($dados);		
	}	
	
	public function verificar_facebook($chave, $bus_idfacebook_cli) {
		$dados = array();
		
		if ($chave == CHAVE_MD5) {
			$resultado = $this->ClienteModel->verificarFacebook($bus_idfacebook_cli);
							
			if ($resultado) {
				foreach ($resultado as $chave => $valor) {
					$dados[$chave] = $valor;
				}
			}
		}
		
		
		echo json_encode($dados);
	}
	
	public function recuperar_senha($chave, $bus_email_cli) {
		$dados = array();
		$this->load->library('email');
		$verificarEmail = $this->ClienteModel->verificarEmail($bus_email_cli);
		
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
			$subject    = "Recuperação de senha - Procure Aki";
			$from       = "contato@encontredelivery.com.br";
			$from_nick  = "Procure Aki";
			$to         = $bus_email_cli;
			$body       = "Olá, ".$verificarEmail->bus_nome_cli.".<br>".
					      "Alteramos sua senha, logue com ela e em seguida altere na tela de 'configurações'.<br>".
					      "Sua nova senha é: <strong>".$nova_senha."</strong>";
			
			$this->email->initialize(); // Aqui carrega todo config criado anteriormente
			$this->email->subject($subject); //assunto
			$this->email->from($from, $from_nick); //quem mandou
			$this->email->to($to); //quem recebe
			$this->email->message($body); //corpo da mensagem
			
			$this->email->send(); // Envia o email
			
			$cliente['bus_senha_cli'] = md5($nova_senha);
			
			$bus_id_cli = $this->ClienteModel->update($cliente, $verificarEmail->bus_id_cli);
			
			if (is_numeric($bus_id_cli)) {
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