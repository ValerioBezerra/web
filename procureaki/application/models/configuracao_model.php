<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuracao_Model extends CI_Model {
	
	public function get() {
		$this->db->from('dlv_cfg');
		$this->db->where('dlv_fixo_cfg', 1, FALSE);
		return $this->db->get()->first_row();
	}
	
}