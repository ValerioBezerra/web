<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto_Adicional_Model extends CI_Model {
	
	public function get($bus_id_pxa) {
		$this->db->where('bus_id_pxa', $bus_id_pxa, FALSE);
		$this->db->from('bus_pxa');
		return $this->db->get()->first_row();
	}
	
	public function getProdutoAdicionalEmpresa($bus_buspro_pxa) {
		$this->db->from('bus_pxa');
		$this->db->join('bus_adi', 'bus_id_adi = bus_busadi_pxa', 'LEFT');		
		$this->db->where('bus_buspro_pxa', $bus_buspro_pxa, FALSE);
		return $this->db->get()->result();
	}
	
	public function getProdutoAdicionalAtivoEmpresa($bus_buspro_pxa) {
		$this->db->from('bus_pxa');
		$this->db->join('bus_adi', 'bus_id_adi = bus_busadi_pxa', 'LEFT');		
		$this->db->where('bus_buspro_pxa', $bus_buspro_pxa, FALSE);
		$this->db->where('bus_ativo_adi', 1, FALSE);
		$this->db->order_by('bus_descricao_adi');
		return $this->db->get()->result();
	}
	
	public function getEmpresaAdicional($bus_busadi_pxa) {
		$this->db->from('bus_pxa');
		$this->db->where('bus_busadi_pxa', $bus_busadi_pxa, FALSE);
		return $this->db->get()->result();
	}
	
	public function getEmpresaAdicionalProduto($bus_buspro_pxa, $bus_busadi_pxa) {
		$this->db->from('bus_pxa');
		$this->db->where('bus_buspro_pxa', $bus_buspro_pxa, FALSE);
		$this->db->where('bus_busadi_pxa', $bus_busadi_pxa, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function insert($produto_adicional) {
		$res = $this->db->insert('bus_pxa', $produto_adicional);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($produto_adicional, $bus_id_pxa) {
		$this->db->where('bus_id_pxa', $bus_id_pxa, FALSE);
		$res = $this->db->update('bus_pxa', $produto_adicional);
	
		if ($res) {
			return $bus_id_pxa;
		} else {
			return FALSE;
		}
	}
	
	public function delete($bus_id_pxa) {
		$this->db->where('bus_id_pxa', $bus_id_pxa, FALSE);
		return $this->db->delete('bus_pxa');
	}
	
}