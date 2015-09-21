<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Responsavel_Model extends CI_Model {
	
	public function get() {
		$this->db->from('dlv_res');
		$this->db->join('dlv_car', 'dlv_id_car = dlv_dlvcar_res', 'LEFT');		
		$this->db->order_by('dlv_nome_res');
		return $this->db->get()->result();
	}
	
	public function getResponsaveis() {
		$this->db->from('dlv_res');
		$this->db->order_by('dlv_nome_res');
		return $this->db->get()->result();
	}
	
	public function getResponsavel($dlv_id_res) {
		$this->db->where('dlv_id_res', $dlv_id_res, FALSE);
		$this->db->from('dlv_res');
		return $this->db->get()->first_row();
	}
	
	public function insert($responsavel) {
		$res = $this->db->insert('dlv_res', $responsavel);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($responsavel, $dlv_id_res) {
		$this->db->where('dlv_id_res', $dlv_id_res, FALSE);
		$res = $this->db->update('dlv_res', $responsavel);
	
		if ($res) {
			return $dlv_id_res;
		} else {
			return FALSE;
		}
	}
	
	public function getResponsaveisCargo($dlv_dlvcar_res) {
		$this->db->where('dlv_dlvcar_res', $dlv_dlvcar_res, FALSE);
		$this->db->from('dlv_res');
		return $this->db->get()->result();
	}	
	
	public function delete($dlv_id_res) {
		$this->db->where('dlv_id_res', $dlv_id_res, FALSE);
		return $this->db->delete('dlv_res');
	}
	
}