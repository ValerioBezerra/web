<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto_Tamanho_Model extends CI_Model {
	
	public function get($bus_id_pxt) {
		$this->db->where('bus_id_pxt', $bus_id_pxt, FALSE);
		$this->db->from('bus_pxt');
		return $this->db->get()->first_row();
	}
	
	public function getProdutoTamanhoEmpresa($bus_buspro_pxt) {
		$this->db->from('bus_pxt');
		$this->db->join('bus_tam', 'bus_id_tam = bus_bustam_pxt', 'LEFT');		
		$this->db->where('bus_buspro_pxt', $bus_buspro_pxt, FALSE);
		return $this->db->get()->result();
	}
	
	public function getProdutoTamanhoAtivoEmpresa($bus_buspro_pxt) {
		$this->db->from('bus_pxt');
		$this->db->join('bus_tam', 'bus_id_tam = bus_bustam_pxt', 'LEFT');		
		$this->db->where('bus_buspro_pxt', $bus_buspro_pxt, FALSE);
		$this->db->where('bus_ativo_tam', 1, FALSE);
		$this->db->order_by('bus_ordem_tam, bus_descricao_tam');
		return $this->db->get()->result();
	}
	
	public function getEmpresaTamanho($bus_bustam_pxt) {
		$this->db->from('bus_pxt');
		$this->db->where('bus_bustam_pxt', $bus_bustam_pxt, FALSE);
		return $this->db->get()->result();
	}
	
	public function getEmpresaTamanhoProduto($bus_buspro_pxt, $bus_bustam_pxt) {
		$this->db->from('bus_pxt');
		$this->db->where('bus_buspro_pxt', $bus_buspro_pxt, FALSE);
		$this->db->where('bus_bustam_pxt', $bus_bustam_pxt, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function insert($produto_tamanho) {
		$res = $this->db->insert('bus_pxt', $produto_tamanho);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($produto_tamanho, $bus_id_pxt) {
		$this->db->where('bus_id_pxt', $bus_id_pxt, FALSE);
		$res = $this->db->update('bus_pxt', $produto_tamanho);
	
		if ($res) {
			return $bus_id_pxt;
		} else {
			return FALSE;
		}
	}
	
	public function delete($bus_id_pxt) {
		$this->db->where('bus_id_pxt', $bus_id_pxt, FALSE);
		return $this->db->delete('bus_pxt');
	}
	
}