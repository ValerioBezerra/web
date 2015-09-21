<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Template {
	public function init() {
		$CI		= &get_instance();
		
		$output = $CI->output->get_output();
        
        if (isset($CI->layout)) {
			
			if ($CI->layout) {
				
				if ($CI->layout == LAYOUT_PADRAO) {
					$tituloErroDash  = $CI->session->flashdata('titulo_erro');	
					$erroDash        = $CI->session->flashdata('erro');	
					$sucessoDash     = $CI->session->flashdata('sucesso');
				}
				
				if (!preg_match('/(.+).php$/', $CI->layout)) {
					$CI->layout .= '.php';
				}
				
				$template = APPPATH . 'templates/'.$CI->layout;
				
				if (file_exists($template)){
					$layout = $CI->load->file($template, TRUE);
				} else {
					die('Template inválida.');
				}
				
				$html	= str_replace("{CONTEUDO}", $output, $layout);

				$this->carregarURLs($CI, $html);
				$this->carregarDadosSessao($CI, $html);
				
				if (!$tituloErroDash) {
					$tituloErroDash = '';
				}				
				
				if ($erroDash) {
					$html	= str_replace("{MENSAGEM_SISTEMA_ERRO}", $this->criarAlterta('alert-danger', $tituloErroDash, $erroDash), $html);
				} else {
					$html	= str_replace("{MENSAGEM_SISTEMA_ERRO}", null, $html);						
				}
				
				if ($sucessoDash) {
					$html	= str_replace("{MENSAGEM_SISTEMA_SUCESSO}", $this->criarAlterta('alert-success', $sucessoDash, ''), $html);
				} else {
					$html	= str_replace("{MENSAGEM_SISTEMA_SUCESSO}", null, $html);						
				}
				
				
			} else {
				$html = $output;
			}
		} else {
			$html = $output;
		}
		
		$CI->output->_display($html);
	}
	
	private function criarAlterta($tipo, $titulo, $mensagem) {
		$html = " <br/>
				 <div class='alert {$tipo}' role='alert' align='center' >
					 <button type='button' class='close' data-dismiss='alert'>
						 <span aria-hidden='true'>&times;</span>
					 </button>
					 <h4>
						 <strong>{$titulo}</strong>
					 </h4>";
		
		if (!empty($mensagem)) {
			$html .= "<div align='left'>
						<strong>{$mensagem}</strong>
		     		  </div>";
		}
				 
				 
				 
		$html .= "</div>";
		
		return $html;
	}
	
	private function carregarURLs($CI, &$html) {
		$html   = str_replace("{URL_LOGIN_CLIENTE}", base_url('json/cliente_json/login'), $html);
		$html   = str_replace("{URL_LOGIN_CLIENTE_FACEBOOK}", base_url('json/cliente_json/login_facebook'), $html);
		$html   = str_replace("{URL_VERIFICAR_EMAIL}", base_url('json/cliente_json/verificar_email/'), $html);
		$html   = str_replace("{URL_CADASTRAR_CLIENTE}", base_url('json/cliente_json/cadastrar'), $html);
		$html   = str_replace("{URL_CADASTRAR_CLIENTE_FACEBOOK}", base_url('json/cliente_json/cadastrar_facebook'), $html);
		
	}
	
	private function carregarDadosSessao($CI, &$html) {
		$html	= str_replace("{DISPLAY_CLIENTE_DESLOGADO}", ($CI->session->userdata('cliente_logado'))?'display: none;':'', $html);
		$html	= str_replace("{DISPLAY_CLIENTE_DESLOGADO}", ($CI->session->userdata('cliente_logado'))?'display: none;':'', $html);
		$html	= str_replace("{DISPLAY_CLIENTE_LOGADO}", ($CI->session->userdata('cliente_logado'))?'':'display: none;', $html);
		
		if ($CI->session->userdata('cliente_logado')) {
			$html = str_replace("{DLV_NOME_CLI}", $CI->session->userdata('dlv_nome_cli'), $html);
		}
		
// 		$html	= str_replace("{dlv_cadperfil_per}", ($CI->session->userdata('dlv_cadperfil_per') == 1)?'':'display: none;', $html);
// 		$html	= str_replace("{dlv_cadusuario_per}", ($CI->session->userdata('dlv_cadusuario_per') == 1)?'':'display: none;', $html);
// 		$html	= str_replace("{dlv_alttelefone_per}", ($CI->session->userdata('dlv_alttelefone_per') == 1)?'':'display: none;', $html);
// 		$html	= str_replace("{dlv_althorario_per}", ($CI->session->userdata('dlv_althorario_per') == 1)?'':'display: none;', $html);
// 		$html	= str_replace("{dlv_alttaxa_per}", ($CI->session->userdata('dlv_alttaxa_per') == 1)?'':'display: none;', $html);
// 		$html	= str_replace("{dlv_altfpg_per}", ($CI->session->userdata('dlv_altfpg_per') == 1)?'':'display: none;', $html);
// 		$html	= str_replace("{dlv_altrede_per}", ($CI->session->userdata('dlv_altrede_per') == 1)?'':'display: none;', $html);
// 		$html	= str_replace("{dlv_altarea_per}", ($CI->session->userdata('dlv_altarea_per') == 1)?'':'display: none;', $html);
// 		$html	= str_replace("{dlv_cadcategoria_per}", ($CI->session->userdata('dlv_cadcategoria_per') == 1)?'':'display: none;', $html);
// 		$html	= str_replace("{dlv_cadtamanho_per}", ($CI->session->userdata('dlv_cadtamanho_per') == 1)?'':'display: none;', $html);
// 		$html	= str_replace("{dlv_cadadicional_per}", ($CI->session->userdata('dlv_cadadicional_per') == 1)?'':'display: none;', $html);
// 		$html	= str_replace("{dlv_cadproduto_per}", ($CI->session->userdata('dlv_cadproduto_per') == 1)?'':'display: none;', $html);
// 		$html	= str_replace("{dlv_cadproduto_per}", ($CI->session->userdata('dlv_cadproduto_per') == 1)?'':'display: none;', $html);
// 		$html	= str_replace("{dlv_altstatusped_per}", ($CI->session->userdata('dlv_altstatusped_per') == 1)?'':'display: none;', $html);
	}
}