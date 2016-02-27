<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil_Model extends CI_Model {
	
	public function getPerfisEmpresa($bus_busemp_per) {
		$this->db->where('bus_busemp_per', $bus_busemp_per, FALSE);
		$this->db->from('bus_per');
		return $this->db->get()->result();
	}
	
	public function getPerfisEmpresaNaoLogado($bus_busemp_per, $bus_id_per_logado) {
		$this->db->where('bus_id_per != ', $bus_id_per_logado, FALSE);
		$this->db->where('bus_busemp_per', $bus_busemp_per, FALSE);
		$this->db->from('bus_per');
		return $this->db->get()->result();
	}
	
	public function getPerfil($bus_id_per) {
		$this->db->where('bus_id_per', $bus_id_per, FALSE);
		$this->db->from('bus_per');
		return $this->db->get()->first_row();
	}
	
	public function insert($perfil) {
		$res = $this->db->insert('bus_per', $perfil);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($perfil, $bus_id_per) {
		$this->db->where('bus_id_per', $bus_id_per, FALSE);
		$res = $this->db->update('bus_per', $perfil);
	
		if ($res) {
			return $bus_id_per;
		} else {
			return FALSE;
		}
	}
	
	public function delete($bus_id_per) {
		$this->db->where('bus_id_per', $bus_id_per, FALSE);
		return $this->db->delete('bus_per');
	}
	
	
	
}