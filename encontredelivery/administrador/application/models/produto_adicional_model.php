<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto_Adicional_Model extends CI_Model {
	
	public function get($dlv_id_pxa) {
		$this->db->where('dlv_id_pxa', $dlv_id_pxa, FALSE);
		$this->db->from('dlv_pxa');
		return $this->db->get()->first_row();
	}
	
	public function getProdutoAdicionalEmpresa($dlv_dlvpro_pxa) {
		$this->db->from('dlv_pxa');
		$this->db->join('dlv_adi', 'dlv_id_adi = dlv_dlvadi_pxa', 'LEFT');		
		$this->db->where('dlv_dlvpro_pxa', $dlv_dlvpro_pxa, FALSE);
		return $this->db->get()->result();
	}
	
	public function getProdutoAdicionalAtivoEmpresa($dlv_dlvpro_pxa) {
		$this->db->from('dlv_pxa');
		$this->db->join('dlv_adi', 'dlv_id_adi = dlv_dlvadi_pxa', 'LEFT');		
		$this->db->where('dlv_dlvpro_pxa', $dlv_dlvpro_pxa, FALSE);
		$this->db->where('dlv_ativo_adi', 1, FALSE);
		$this->db->order_by('dlv_descricao_adi');
		return $this->db->get()->result();
	}
	
	public function getEmpresaAdicional($dlv_dlvadi_pxa) {
		$this->db->from('dlv_pxa');
		$this->db->where('dlv_dlvadi_pxa', $dlv_dlvadi_pxa, FALSE);
		return $this->db->get()->result();
	}
	
	public function getEmpresaAdicionalProduto($dlv_dlvpro_pxa, $dlv_dlvadi_pxa) {
		$this->db->from('dlv_pxa');
		$this->db->where('dlv_dlvpro_pxa', $dlv_dlvpro_pxa, FALSE);
		$this->db->where('dlv_dlvadi_pxa', $dlv_dlvadi_pxa, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function insert($produto_adicional) {
		$res = $this->db->insert('dlv_pxa', $produto_adicional);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($produto_adicional, $dlv_id_pxa) {
		$this->db->where('dlv_id_pxa', $dlv_id_pxa, FALSE);
		$res = $this->db->update('dlv_pxa', $produto_adicional);
	
		if ($res) {
			return $dlv_id_pxa;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_pxa) {
		$this->db->where('dlv_id_pxa', $dlv_id_pxa, FALSE);
		return $this->db->delete('dlv_pxa');
	}
	
}