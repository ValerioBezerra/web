<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Telefone_Model extends CI_Model {
	
	public function get($dlv_id_ext) {
		$this->db->where('dlv_id_ext', $dlv_id_ext, FALSE);
		$this->db->from('dlv_ext');
		return $this->db->get()->first_row();
	}
	
	public function getTelefonesEmpresa($dlv_dlvemp_ext) {
		$this->db->where('dlv_dlvemp_ext', $dlv_dlvemp_ext, FALSE);
		$this->db->from('dlv_ext');
		return $this->db->get()->result();
	}
	
	public function insert($telefone) {
		$res = $this->db->insert('dlv_ext', $telefone);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($telefone, $dlv_id_ext) {
		$this->db->where('dlv_id_ext', $dlv_id_ext, FALSE);
		$res = $this->db->update('dlv_ext', $telefone);
	
		if ($res) {
			return $dlv_id_ext;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_ext) {
		$this->db->where('dlv_id_ext', $dlv_id_ext, FALSE);
		return $this->db->delete('dlv_ext');
	}
		
}