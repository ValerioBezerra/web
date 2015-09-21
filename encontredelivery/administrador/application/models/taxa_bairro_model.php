<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Taxa_Bairro_Model extends CI_Model {
	
	public function get($dlv_id_txb) {
		$this->db->where('dlv_id_txb', $dlv_id_txb, FALSE);
		$this->db->join('glo_bai', 'glo_id_bai = dlv_globai_txb', 'LEFT');
		$this->db->from('dlv_txb');
		return $this->db->get()->first_row();
	}
	
	public function getAreaEntregaEmpresa($dlv_dlvemp_txb, $glo_id_cid) {
		$this->db->from('dlv_txb');
		$this->db->join('glo_bai', 'glo_id_bai = dlv_globai_txb', 'LEFT');
		$this->db->where('dlv_dlvemp_txb', $dlv_dlvemp_txb, FALSE);
		$this->db->where('glo_glocid_bai', $glo_id_cid, FALSE);
		return $this->db->get()->result();
	}
	
	
	public function getEmpresaTaxaBairro($dlv_globai_txb, $dlv_dlvemp_txb) {
		$this->db->from('dlv_txb');
		$this->db->where('dlv_globai_txb', $dlv_globai_txb, FALSE);
		$this->db->where('dlv_dlvemp_txb', $dlv_dlvemp_txb, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getAreaEntregaTaxaBairro($dlv_dlvaen_txb, $dlv_globai_txb) {
		$this->db->from('dlv_txb');
		$this->db->where('dlv_dlvaen_txb', $dlv_dlvaen_txb, FALSE);
		$this->db->where('dlv_globai_txb', $dlv_globai_txb, FALSE);
		return $this->db->get()->first_row();
	}
	
	
	public function getEmpresas($dlv_dlvemp_txb) {
		$this->db->from('dlv_txb');
		$this->db->where('dlv_dlvemp_txb', $dlv_dlvemp_txb, FALSE);
		return $this->db->get()->result();
	}
	
	public function insert($taxa_bairro) {
		$res = $this->db->insert('dlv_txb', $taxa_bairro);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($taxa_bairro, $dlv_id_txb) {
		$this->db->where('dlv_id_txb', $dlv_id_txb, FALSE);
		$res = $this->db->update('dlv_txb', $taxa_bairro);
	
		if ($res) {
			return $dlv_id_txb;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_txb) {
		$this->db->where('dlv_id_txb', $dlv_id_txb, FALSE);
		return $this->db->delete('dlv_txb');
	}
	
	
}