<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GCM_JSON extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('GCM_Model', 'GCMModel');
	}
	
	public function cadastrarAlterar($chave) {
		$json      = $this->input->post('json');
		$GCM_TEMP  = json_decode($json,true);
		
		$GCM = array(
			"dlv_regid_gcm"  => $GCM_TEMP['dlv_regid_gcm'],
			"dlv_dlvcli_gcm" => $GCM_TEMP['dlv_dlvcli_gcm']
		);
		
		$msgErros = "";
		$erros    = FALSE;
		
		if ($chave != CHAVE_MD5) {
			$msgErros .= "- Chave de acesso inválida.\n";
			$erros     = TRUE;
		} 
		
		if (!$erros) {
			if (empty($GCM_TEMP['registroIDAntigo'])) {
				$dlv_id_gcm = $this->GCMModel->insert($GCM);
			} else {
				$resultado = $this->GCMModel->getRegId($GCM_TEMP['registroIDAntigo']);
				
				if ($resultado) {
					$dlv_id_gcm = $this->GCMModel->update($GCM, $resultado->dlv_id_gcm);
				}
			}
					
			if (is_numeric($dlv_id_gcm)) {
				echo "s".$dlv_id_gcm;
			} else {
				echo "n";
			}
		} else {
			echo $msgErros;
		}
	}
	
}