<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Taxa_Bairro_Model extends CI_Model {
	
	public function getEmpresaTaxaBairro($dlv_globai_txb, $dlv_dlvemp_txb) {
		$this->db->from('dlv_txb');
		$this->db->where('dlv_globai_txb', $dlv_globai_txb, FALSE);
		$this->db->where('dlv_dlvemp_txb', $dlv_dlvemp_txb, FALSE);
		return $this->db->get()->first_row();
	}
	
}