<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forma_Pagamento_Model extends CI_Model {
	
	public function get() {
		$this->db->from('dlv_fpg');
		return $this->db->get()->result();
	}
	
	public function getFormaPagamento($dlv_id_fpg) {
		$this->db->where('dlv_id_fpg', $dlv_id_fpg, FALSE);
		$this->db->from('dlv_fpg');
		return $this->db->get()->first_row();
	}
	
	public function insert($forma_pagamento) {
		$res = $this->db->insert('dlv_fpg', $forma_pagamento);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($forma_pagamento, $dlv_id_fpg) {
		$this->db->where('dlv_id_fpg', $dlv_id_fpg, FALSE);
		$res = $this->db->update('dlv_fpg', $forma_pagamento);
	
		if ($res) {
			return $dlv_id_fpg;
		} else {
			return FALSE;
		}
	}	
	
	public function delete($dlv_id_fpg) {
		$this->db->where('dlv_id_fpg', $dlv_id_fpg, FALSE);
		return $this->db->delete('dlv_fpg');
	}
	
}