<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Metrica_Model extends CI_Model {
	
	public function get() {
		$this->db->from('bus_met');
		$this->db->order_by('bus_descricao_met');
		return $this->db->get()->result();
	}
	
	public function getMetrica($bus_id_met) {
		$this->db->where('bus_id_met', $bus_id_met, FALSE);
		$this->db->from('bus_met');
		return $this->db->get()->first_row();
	}
	
	public function insert($metrica) {
		$res = $this->db->insert('bus_met', $metrica);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($metrica, $bus_id_met) {
		$this->db->where('bus_id_met', $bus_id_met, FALSE);
		$res = $this->db->update('bus_met', $metrica);
	
		if ($res) {
			return $bus_id_met;
		} else {
			return FALSE;
		}
	}
	
	
	public function delete($bus_id_met) {
		$this->db->where('bus_id_met', $bus_id_met, FALSE);
		return $this->db->delete('bus_met');
	}
	
}