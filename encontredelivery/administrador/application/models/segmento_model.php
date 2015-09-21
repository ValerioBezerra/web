<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Segmento_Model extends CI_Model {
	
	public function get() {
		$this->db->from('dlv_seg');
		$this->db->order_by('dlv_descricao_seg');
		return $this->db->get()->result();
	}
	
	public function getSegmento($dlv_id_seg) {
		$this->db->where('dlv_id_seg', $dlv_id_seg, FALSE);
		$this->db->from('dlv_seg');
		return $this->db->get()->first_row();
	}
	
	public function insert($segmento) {
		$res = $this->db->insert('dlv_seg', $segmento);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($segmento, $dlv_id_seg) {
		$this->db->where('dlv_id_seg', $dlv_id_seg, FALSE);
		$res = $this->db->update('dlv_seg', $segmento);
	
		if ($res) {
			return $dlv_id_seg;
		} else {
			return FALSE;
		}
	}
	
	
	public function delete($dlv_id_seg) {
		$this->db->where('dlv_id_seg', $dlv_id_seg, FALSE);
		return $this->db->delete('dlv_seg');
	}
	
}