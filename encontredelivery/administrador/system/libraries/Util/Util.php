<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Util {
	
	private function gerarCaracteresDireita($quantidade, $texto, $char) {
		$retorno = $texto;
		for ($i = 0; $i <= ($quantidade - strlen($texto)); $i++) {
			$retorno .= $char;
		}
		
		return $retorno;
	}
}