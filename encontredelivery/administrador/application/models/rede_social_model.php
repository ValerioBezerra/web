<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rede_Social_Model extends CI_Model {
	
	public function get() {
		$this->db->from('dlv_red');
		return $this->db->get()->result();
	}
	
	public function getRedeSocial($dlv_id_red) {
		$this->db->where('dlv_id_red', $dlv_id_red, FALSE);
		$this->db->from('dlv_red');
		return $this->db->get()->first_row();
	}
	
	public function insert($rede_social) {
		$res = $this->db->insert('dlv_red', $rede_social);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($rede_social, $dlv_id_red) {
		$this->db->where('dlv_id_red', $dlv_id_red, FALSE);
		$res = $this->db->update('dlv_red', $rede_social);
	
		if ($res) {
			return $dlv_id_red;
		} else {
			return FALSE;
		}
	}	
	
	public function delete($dlv_id_red) {
		$this->db->where('dlv_id_red', $dlv_id_red, FALSE);
		return $this->db->delete('dlv_red');
	}
	
}