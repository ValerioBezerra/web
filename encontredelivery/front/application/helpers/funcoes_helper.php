<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('mascara')) {
	    function mascara($valor, $mascara) {
        $mascarado = '';
        
        $k = 0;
        
        for($i = 0; $i <= strlen($mascara) - 1; $i ++) {
            if ($mascara [$i] == '9') {
                if (isset ( $valor [$k] )) {
                    $mascarado .= $valor [$k ++];
                }
            } else {
                if (isset ( $mascara [$i] )) {
                    $mascarado .= $mascara [$i];
                }
            }
        }
        
        return $mascarado;
    }
}

if (!function_exists('validaCNPJ')) {
	function validaCNPJ ($valor) {
		$cnpj_original = $valor;
		$primeiros_numeros_cnpj = substr($valor, 0, 12 );
		$primeiro_calculo = CalcularDigitos($primeiros_numeros_cnpj, 5);
		$segundo_calculo =  CalcularDigitos($primeiro_calculo, 6);
		$cnpj = $segundo_calculo;
		if ( $cnpj === $cnpj_original ) {
			return true;
		}
	}
}

if (!function_exists('validaCPF')) {
	function validaCPF($valor) {
		$digitos = substr($valor, 0, 9);
		$novo_cpf = CalcularDigitos($digitos);
		$novo_cpf = CalcularDigitos($novo_cpf, 11);
		if ( $novo_cpf === $valor ) {
			return true;
		} else {
			return false;
		}
	}
}


if (!function_exists('CalcularDigitos')) {
	function CalcularDigitos($digitos, $posicoes = 10, $soma_digitos = 0)  {
		for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
			$soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );
			$posicoes--;
			if ( $posicoes < 2 ) {
				$posicoes = 9;
			}
		}
	
		$soma_digitos = $soma_digitos % 11;
		if ( $soma_digitos < 2 ) {
			$soma_digitos = 0;
		} else {
			$soma_digitos = 11 - $soma_digitos;
		}
	
		$cpf = $digitos . $soma_digitos;
	
		return $cpf;
	}
}


