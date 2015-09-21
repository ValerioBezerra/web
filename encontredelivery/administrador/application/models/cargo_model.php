<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cargo_Model extends CI_Model {
	
	public function get() {
		$this->db->from('dlv_car');
		$this->db->order_by('dlv_descricao_car');
		return $this->db->get()->result();
	}
	
	public function getCargo($dlv_id_car) {
		$this->db->where('dlv_id_car', $dlv_id_car, FALSE);
		$this->db->from('dlv_car');
		return $this->db->get()->first_row();
	}
	
	public function insert($cargo) {
		$res = $this->db->insert('dlv_car', $cargo);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($cargo, $dlv_id_car) {
		$this->db->where('dlv_id_car', $dlv_id_car, FALSE);
		$res = $this->db->update('dlv_car', $cargo);
	
		if ($res) {
			return $dlv_id_car;
		} else {
			return FALSE;
		}
	}	
	
	public function delete($dlv_id_car) {
		$this->db->where('dlv_id_car', $dlv_id_car, FALSE);
		return $this->db->delete('dlv_car');
	}
	
}