<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plano_Model extends CI_Model {
	
	public function get() {
		$this->db->from('bus_pla');
		$this->db->order_by('bus_descricao_pla');
		return $this->db->get()->result();
	}
	
	public function getPlano($bus_id_pla) {
		$this->db->where('bus_id_pla', $bus_id_pla, FALSE);
		$this->db->from('bus_pla');
		return $this->db->get()->first_row();
	}
	
	public function insert($plano) {
		$res = $this->db->insert('bus_pla', $plano);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($plano, $bus_id_pla) {
		$this->db->where('bus_id_pla', $bus_id_pla, FALSE);
		$res = $this->db->update('bus_pla', $plano);
	
		if ($res) {
			return $bus_id_pla;
		} else {
			return FALSE;
		}
	}
	
	
	public function delete($bus_id_pla) {
		$this->db->where('bus_id_pla', $bus_id_pla, FALSE);
		return $this->db->delete('bus_pla');
	}
	
}