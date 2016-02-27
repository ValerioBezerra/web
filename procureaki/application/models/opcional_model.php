<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Opcional_Model extends CI_Model {
	
	public function get() {
		$this->db->from('vei_opc');
		$this->db->order_by('vei_descricao_opc');
		return $this->db->get()->result();
	}
	
	public function getOpcional($vei_id_opc) {
		$this->db->where('vei_id_opc', $vei_id_opc, FALSE);
		$this->db->from('vei_opc');
		return $this->db->get()->first_row();
	}
	
	public function insert($opcional) {
		$res = $this->db->insert('vei_opc', $opcional);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($opcional, $vei_id_opc) {
		$this->db->where('vei_id_opc', $vei_id_opc, FALSE);
		$res = $this->db->update('vei_opc', $opcional);
	
		if ($res) {
			return $vei_id_opc;
		} else {
			return FALSE;
		}
	}
	
	
	public function delete($vei_id_opc) {
		$this->db->where('vei_id_opc', $vei_id_opc, FALSE);
		return $this->db->delete('vei_opc');
	}
	
}