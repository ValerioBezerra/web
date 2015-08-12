<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adicional_Model extends CI_Model {
	
	public function get($dlv_id_adi) {
		$this->db->where('dlv_id_adi', $dlv_id_adi, FALSE);
		$this->db->from('dlv_adi');
		return $this->db->get()->first_row();
	}
	
	public function getAdicionalEmpresa($dlv_dlvemp_adi) {
		$this->db->where('dlv_dlvemp_adi', $dlv_dlvemp_adi, FALSE);
		$this->db->from('dlv_adi');
		return $this->db->get()->result();
	}
	
	public function getAdicionalEmpresaAtivo($dlv_dlvemp_adi) {
		$this->db->where('dlv_dlvemp_adi', $dlv_dlvemp_adi, FALSE);
		$this->db->where('dlv_ativo_adi', 1, FALSE);
		$this->db->from('dlv_adi');
		return $this->db->get()->result();
	}
	
	public function insert($adicional) {
		$res = $this->db->insert('dlv_adi', $adicional);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($adicional, $dlv_id_adi) {
		$this->db->where('dlv_id_adi', $dlv_id_adi, FALSE);
		$res = $this->db->update('dlv_adi', $adicional);
	
		if ($res) {
			return $dlv_id_adi;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_adi) {
		$this->db->where('dlv_id_adi', $dlv_id_adi, FALSE);
		return $this->db->delete('dlv_adi');
	}
	
	public function getAdicionaisProdutoPedido($dlv_dlvppe_ppa) {
		$this->db->from('dlv_ppa');
		$this->db->join('dlv_adi', 'dlv_id_adi = dlv_dlvadi_ppa', 'LEFT');
		$this->db->where('dlv_dlvppe_ppa', $dlv_dlvppe_ppa, FALSE);
		$this->db->order_by('dlv_id_ppa');
		return $this->db->get()->result();
	}
	
		
}