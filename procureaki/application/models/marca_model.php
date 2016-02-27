<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marca_Model extends CI_Model {
	
	public function get() {
		$this->db->from('vei_mar');
		$this->db->order_by('vei_nome_mar');
		return $this->db->get()->result();
	}
	
	public function getMarca($vei_id_mar) {
		$this->db->where('vei_id_mar', $vei_id_mar, FALSE);
		$this->db->from('vei_mar');
		return $this->db->get()->first_row();
	}

	public function getMarcas() {
		$this->db->from('vei_mar');
		$this->db->order_by('vei_nome_mar');
		return $this->db->get()->result();
	}
	
	public function insert($marca) {
		$res = $this->db->insert('vei_mar', $marca);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($marca, $vei_id_mar) {
		$this->db->where('vei_id_mar', $vei_id_mar, FALSE);
		$res = $this->db->update('vei_mar', $marca);
	
		if ($res) {
			return $vei_id_mar;
		} else {
			return FALSE;
		}
	}
	
	
	public function delete($vei_id_mar) {
		$this->db->where('vei_id_mar', $vei_id_mar, FALSE);
		return $this->db->delete('vei_mar');
	}
	
}