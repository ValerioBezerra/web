<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Segmento_Model extends CI_Model {
	
	public function get() {
		$this->db->from('bus_seg');
		$this->db->order_by('bus_descricao_seg');
		return $this->db->get()->result();
	}
	
	public function getSegmento($bus_id_seg) {
		$this->db->where('bus_id_seg', $bus_id_seg, FALSE);
		$this->db->from('bus_seg');
		return $this->db->get()->first_row();
	}
	
	public function insert($segmento) {
		$res = $this->db->insert('bus_seg', $segmento);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($segmento, $bus_id_seg) {
		$this->db->where('bus_id_seg', $bus_id_seg, FALSE);
		$res = $this->db->update('bus_seg', $segmento);
	
		if ($res) {
			return $bus_id_seg;
		} else {
			return FALSE;
		}
	}
	
	
	public function delete($bus_id_seg) {
		$this->db->where('bus_id_seg', $bus_id_seg, FALSE);
		return $this->db->delete('bus_seg');
	}
	
}