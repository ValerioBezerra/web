<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil_Model extends CI_Model {
	
	public function getPerfisEmpresa($dlv_dlvemp_per) {
		$this->db->where('dlv_dlvemp_per', $dlv_dlvemp_per, FALSE);
		$this->db->from('dlv_per');
		return $this->db->get()->result();
	}
	
	public function getPerfisEmpresaNaoLogado($dlv_dlvemp_per, $dlv_id_per_logado) {
		$this->db->where('dlv_id_per != ', $dlv_id_per_logado, FALSE);
		$this->db->where('dlv_dlvemp_per', $dlv_dlvemp_per, FALSE);
		$this->db->from('dlv_per');
		return $this->db->get()->result();
	}
	
	public function getPerfil($dlv_id_per) {
		$this->db->where('dlv_id_per', $dlv_id_per, FALSE);
		$this->db->from('dlv_per');
		return $this->db->get()->first_row();
	}
	
	public function insert($perfil) {
		$res = $this->db->insert('dlv_per', $perfil);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($perfil, $dlv_id_per) {
		$this->db->where('dlv_id_per', $dlv_id_per, FALSE);
		$res = $this->db->update('dlv_per', $perfil);
	
		if ($res) {
			return $dlv_id_per;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_per) {
		$this->db->where('dlv_id_per', $dlv_id_per, FALSE);
		return $this->db->delete('dlv_per');
	}
	
	
	
}