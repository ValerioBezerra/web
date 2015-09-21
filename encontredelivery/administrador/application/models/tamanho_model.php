<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tamanho_Model extends CI_Model {
	
	public function get($dlv_id_tam) {
		$this->db->where('dlv_id_tam', $dlv_id_tam, FALSE);
		$this->db->from('dlv_tam');
		return $this->db->get()->first_row();
	}
	
	public function getTamanhoEmpresa($dlv_dlvemp_tam) {
		$this->db->where('dlv_dlvemp_tam', $dlv_dlvemp_tam, FALSE);
		$this->db->from('dlv_tam');
		return $this->db->get()->result();
	}
	
	public function getTamanhoEmpresaAtivo($dlv_dlvemp_tam) {
		$this->db->where('dlv_dlvemp_tam', $dlv_dlvemp_tam, FALSE);
		$this->db->where('dlv_ativo_tam', 1, FALSE);
		$this->db->from('dlv_tam');
		return $this->db->get()->result();
	}
	
	
	public function insert($tamanho) {
		$res = $this->db->insert('dlv_tam', $tamanho);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($tamanho, $dlv_id_tam) {
		$this->db->where('dlv_id_tam', $dlv_id_tam, FALSE);
		$res = $this->db->update('dlv_tam', $tamanho);
	
		if ($res) {
			return $dlv_id_tam;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_tam) {
		$this->db->where('dlv_id_tam', $dlv_id_tam, FALSE);
		return $this->db->delete('dlv_tam');
	}
		
}