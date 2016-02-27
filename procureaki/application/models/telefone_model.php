<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Telefone_Model extends CI_Model {
	
	public function get($bus_id_ext) {
		$this->db->where('bus_id_ext', $bus_id_ext, FALSE);
		$this->db->from('bus_ext');
		return $this->db->get()->first_row();
	}
	
	public function getTelefonesEmpresa($bus_busemp_ext) {
		$this->db->where('bus_busemp_ext', $bus_busemp_ext, FALSE);
		$this->db->from('bus_ext');
		return $this->db->get()->result();
	}
	
	public function insert($telefone) {
		$res = $this->db->insert('bus_ext', $telefone);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($telefone, $bus_id_ext) {
		$this->db->where('bus_id_ext', $bus_id_ext, FALSE);
		$res = $this->db->update('bus_ext', $telefone);
	
		if ($res) {
			return $bus_id_ext;
		} else {
			return FALSE;
		}
	}
	
	public function delete($bus_id_ext) {
		$this->db->where('bus_id_ext', $bus_id_ext, FALSE);
		return $this->db->delete('bus_ext');
	}
		
}