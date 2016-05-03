<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categoria_Model extends CI_Model {
	
	public function get($bus_id_cat) {
		$this->db->where('bus_id_cat', $bus_id_cat, FALSE);
		$this->db->from('bus_cat');
		return $this->db->get()->first_row();
	}
	
	public function getCategoriaEmpresa($bus_busemp_cat) {
		$this->db->where('bus_busemp_cat', $bus_busemp_cat, FALSE);
		$this->db->from('bus_cat');
		return $this->db->get()->result();
	}
	
	public function getCategoriaAtivo($bus_busemp_cat) {
		$this->db->where('bus_busemp_cat', $bus_busemp_cat, FALSE);
		$this->db->where('bus_ativo_cat', 1, FALSE);
		$this->db->from('bus_cat');
		$this->db->order_by('bus_ordem_cat, bus_descricao_cat');
		return $this->db->get()->result();
	}
	
	public function insert($categoria) {
		$res = $this->db->insert('bus_cat', $categoria);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($categoria, $bus_id_cat) {
		$this->db->where('bus_id_cat', $bus_id_cat, FALSE);
		$res = $this->db->update('bus_cat', $categoria);
	
		if ($res) {
			return $bus_id_cat;
		} else {
			return FALSE;
		}
	}
	
	public function delete($bus_id_cat) {
		$this->db->where('bus_id_cat', $bus_id_cat, FALSE);
		return $this->db->delete('bus_cat');
	}
		
}