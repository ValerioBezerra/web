<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Template {
	public function init() {
		$CI		= &get_instance();
		
		$output = $CI->output->get_output();
        
        if (isset($CI->layout)) {
			
			if ($CI->layout) {
				
				if ($CI->layout == LAYOUT_DASHBOARD_ADMINISTRATIVO) {
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
				
				if ($CI->session->userdata('logado')) {
					$this->carregarDadosSessao($CI, $html);
				} else {
					redirect('login/');
				}
				
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
	
	private function carregarDadosSessao($CI, &$html) {
		$html   = str_replace("{URL_PEDIDO}", site_url('/json/pedido_json/retornar_quantidade_pedido/'.$CI->session->userdata('bus_id_emp')), $html);
		$html	= str_replace("{BUS_NOME_EMP}", $CI->session->userdata('bus_nome_emp'), $html);
		$html	= str_replace("{BUS_NOME_USU}", $CI->session->userdata('bus_nome_usu'), $html);
		
		$html	= str_replace("{DISPLAY_VALLESOFT}", ($CI->session->userdata('bus_id_emp') == 1)?'':'display: none;', $html);
		$html	= str_replace("{DISPLAY_VALLESOFT_C}", ($CI->session->userdata('bus_id_emp') == 1)?'display: none;':'', $html);
		
		$html	= str_replace("{bus_cadperfil_per}", ($CI->session->userdata('bus_cadperfil_per') == 1)?'':'display: none;', $html);
		$html	= str_replace("{bus_cadusuario_per}", ($CI->session->userdata('bus_cadusuario_per') == 1)?'':'display: none;', $html);
		$html	= str_replace("{bus_alttelefone_per}", ($CI->session->userdata('bus_alttelefone_per') == 1)?'':'display: none;', $html);
		$html	= str_replace("{bus_althorario_per}", ($CI->session->userdata('bus_althorario_per') == 1)?'':'display: none;', $html);
		$html	= str_replace("{bus_alttaxa_per}", ($CI->session->userdata('bus_alttaxa_per') == 1)?'':'display: none;', $html);
		$html	= str_replace("{bus_altfpg_per}", ($CI->session->userdata('bus_altfpg_per') == 1)?'':'display: none;', $html);
		$html	= str_replace("{bus_altrede_per}", ($CI->session->userdata('bus_altrede_per') == 1)?'':'display: none;', $html);
		$html	= str_replace("{bus_altarea_per}", ($CI->session->userdata('bus_altarea_per') == 1)?'':'display: none;', $html);
		$html	= str_replace("{bus_cadcategoria_per}", ($CI->session->userdata('bus_cadcategoria_per') == 1)?'':'display: none;', $html);
		$html	= str_replace("{bus_cadtamanho_per}", ($CI->session->userdata('bus_cadtamanho_per') == 1)?'':'display: none;', $html);
		$html	= str_replace("{bus_cadadicional_per}", ($CI->session->userdata('bus_cadadicional_per') == 1)?'':'display: none;', $html);
		$html	= str_replace("{bus_cadproduto_per}", ($CI->session->userdata('bus_cadproduto_per') == 1)?'':'display: none;', $html);
		$html	= str_replace("{bus_cadproduto_per}", ($CI->session->userdata('bus_cadproduto_per') == 1)?'':'display: none;', $html);
		$html	= str_replace("{bus_altstatusped_per}", (($CI->session->userdata('bus_altstatusped_per') == 1) || ($CI->session->userdata('bus_id_emp') == 1))?'':'display: none;', $html);
	}
}