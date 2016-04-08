<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipo_Model extends CI_Model {
	
	public function get() {
		$this->db->from('bus_tip');
		$this->db->order_by('bus_descricao_tip');
		return $this->db->get()->result();
	}
	
	public function getTipo($bus_id_tip) {
		$this->db->where('bus_id_tip', $bus_id_tip, FALSE);
		$this->db->from('bus_tip');
		return $this->db->get()->first_row();
	}
	
	public function insert($tipo) {
		$res = $this->db->insert('bus_tip', $tipo);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($tipo, $bus_id_tip) {
		$this->db->where('bus_id_tip', $bus_id_tip, FALSE);
		$res = $this->db->update('bus_tip', $tipo);
	
		if ($res) {
			return $bus_id_tip;
		} else {
			return FALSE;
		}
	}
	
	
	public function delete($bus_id_tip) {
		$this->db->where('bus_id_tip', $bus_id_tip, FALSE);
		return $this->db->delete('bus_tip');
	}
	
}