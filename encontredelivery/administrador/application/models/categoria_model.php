<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categoria_Model extends CI_Model {
	
	public function get($dlv_id_cat) {
		$this->db->where('dlv_id_cat', $dlv_id_cat, FALSE);
		$this->db->from('dlv_cat');
		return $this->db->get()->first_row();
	}
	
	public function getCategoriaEmpresa($dlv_dlvemp_cat) {
		$this->db->where('dlv_dlvemp_cat', $dlv_dlvemp_cat, FALSE);
		$this->db->from('dlv_cat');
		return $this->db->get()->result();
	}
	
	public function getCategoriaAtivo($dlv_dlvemp_cat) {
		$this->db->where('dlv_dlvemp_cat', $dlv_dlvemp_cat, FALSE);
		$this->db->where('dlv_ativo_cat', 1, FALSE);
		$this->db->from('dlv_cat');
		$this->db->order_by('dlv_ordem_cat, dlv_descricao_cat');
		return $this->db->get()->result();
	}
	
	public function insert($categoria) {
		$res = $this->db->insert('dlv_cat', $categoria);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($categoria, $dlv_id_cat) {
		$this->db->where('dlv_id_cat', $dlv_id_cat, FALSE);
		$res = $this->db->update('dlv_cat', $categoria);
	
		if ($res) {
			return $dlv_id_cat;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_cat) {
		$this->db->where('dlv_id_cat', $dlv_id_cat, FALSE);
		return $this->db->delete('dlv_cat');
	}
		
}