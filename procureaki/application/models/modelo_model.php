<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modelo_Model extends CI_Model {
	
	public function get() {
		$this->db->from('vei_mod');
		$this->db->order_by('vei_nome_mod');
		return $this->db->get()->result();
	}
	
	public function getModelo($vei_id_mod) {
		$this->db->where('vei_id_mod', $vei_id_mod, FALSE);
		$this->db->from('vei_mod');
		return $this->db->get()->first_row();
	}
	
	public function insert($modelo) {
		$res = $this->db->insert('vei_mod', $modelo);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($modelo, $vei_id_mod) {
		$this->db->where('vei_id_mod', $vei_id_mod, FALSE);
		$res = $this->db->update('vei_mod', $modelo);
	
		if ($res) {
			return $vei_id_mod;
		} else {
			return FALSE;
		}
	}
	
	
	public function delete($vei_id_mod) {
		$this->db->where('vei_id_mod', $vei_id_mod, FALSE);
		return $this->db->delete('vei_mod');
	}
	
}