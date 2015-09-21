<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Area_Entrega_Model extends CI_Model {
	
	public function get($dlv_id_aen) {
		$this->db->where('dlv_id_aen', $dlv_id_aen, FALSE);
		$this->db->from('dlv_id_aen');
		return $this->db->get()->first_row();
	}
	
	public function getAreaEntregaEmpresa($dlv_dlvemp_aen) {
		$this->db->from('dlv_aen');
		$this->db->join('glo_cid', 'glo_id_cid = dlv_glocid_aen', 'LEFT');		
		$this->db->join('glo_est', 'glo_id_est = glo_gloest_cid', 'LEFT');		
		$this->db->where('dlv_dlvemp_aen', $dlv_dlvemp_aen, FALSE);
		return $this->db->get()->result();
	}
	
	public function getEmpresaAreaEntrega($dlv_glocid_aen, $dlv_dlvemp_aen) {
		$this->db->from('dlv_aen');
		$this->db->where('dlv_glocid_aen', $dlv_glocid_aen, FALSE);
		$this->db->where('dlv_dlvemp_aen', $dlv_dlvemp_aen, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function insert($area_entrega) {
		$res = $this->db->insert('dlv_aen', $area_entrega);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($area_entrega, $dlv_id_aen) {
		$this->db->where('dlv_id_aen', $dlv_id_aen, FALSE);
		$res = $this->db->update('dlv_aen', $area_entrega);
	
		if ($res) {
			return $dlv_id_aen;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_aen) {
		$this->db->where('dlv_id_aen', $dlv_id_aen, FALSE);
		return $this->db->delete('dlv_aen');
	}
		
}