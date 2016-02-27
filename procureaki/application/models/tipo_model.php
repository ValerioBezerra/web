<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipo_Model extends CI_Model {
	
	public function get() {
		$this->db->from('vei_tip');
		$this->db->order_by('vei_descricao_tip');
		return $this->db->get()->result();
	}
	
	public function getTipo($vei_id_tip) {
		$this->db->where('vei_id_tip', $vei_id_tip, FALSE);
		$this->db->from('vei_tip');
		return $this->db->get()->first_row();
	}

	public function getTipos() {
		$this->db->from('vei_tip');
		$this->db->order_by('vei_descricao_tip');
		return $this->db->get()->result();
	}

	public function insert($tipo) {
		$res = $this->db->insert('vei_tip', $tipo);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($tipo, $vei_id_tip) {
		$this->db->where('vei_id_tip', $vei_id_tip, FALSE);
		$res = $this->db->update('vei_tip', $tipo);
	
		if ($res) {
			return $vei_id_tip;
		} else {
			return FALSE;
		}
	}
	
	
	public function delete($vei_id_tip) {
		$this->db->where('vei_id_tip', $vei_id_tip, FALSE);
		return $this->db->delete('vei_tip');
	}
	
}