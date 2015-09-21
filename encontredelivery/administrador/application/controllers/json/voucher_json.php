<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Voucher_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Voucher_Model', 'VoucherModel');
	}
	
	public function retornar_vouchers_clientes($chave, $dlv_dlvcli_vou) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->VoucherModel->getCliente($dlv_dlvcli_vou);	
			foreach ($resultado as $registro) {
				$dados[] = array(
					"dlv_id_vou"        => $registro->dlv_id_vou,
					"dlv_codigo_vou"    => $registro->dlv_codigo_vou,
					"dlv_descricao_vou" => $registro->dlv_descricao_vou,
					"dlv_tipo_vou"   	=> $registro->dlv_tipo_vou,
					"dlv_valor_vou"  	=> $registro->dlv_valor_vou,
				);
			}
			
			$resultado = $this->VoucherModel->getSemCliente($dlv_dlvcli_vou);			
			foreach ($resultado as $registro) {
				$dados[] = array(
					"dlv_id_vou"        => $registro->dlv_id_vou,
					"dlv_codigo_vou"    => $registro->dlv_codigo_vou,
					"dlv_descricao_vou" => $registro->dlv_descricao_vou,
					"dlv_tipo_vou"   	=> $registro->dlv_tipo_vou,
					"dlv_valor_vou"  	=> $registro->dlv_valor_vou,
				);
			}
		}
	
		echo json_encode(array("vouchers" => $dados));
	}
	
	public function retornar_voucher_codigo($chave, $dlv_codigo_vou, $dlv_dlvcli_vou, $dlv_dlvemp_vou) {
		$dados = array();
		
		if ($chave == CHAVE_MD5) {
			$resultado = $this->VoucherModel->getCodigo($dlv_codigo_vou, $dlv_dlvcli_vou);
				
			if ($resultado) {
				if (is_null($resultado->dlv_dlvcli_vou) || ($dlv_dlvcli_vou == $resultado->dlv_dlvcli_vou)) {
					if (is_null($resultado->dlv_dlvemp_vou) || ($dlv_dlvemp_vou == $resultado->dlv_dlvemp_vou)) {
						$dados['dlv_id_vou'] 		= $resultado->dlv_id_vou;
						$dados['dlv_tipo_vou']      = $resultado->dlv_tipo_vou;
						$dados['dlv_valor_vou']     = $resultado->dlv_valor_vou;
					}
				}
			}
		}
		
		
		echo json_encode($dados);				
	}

}