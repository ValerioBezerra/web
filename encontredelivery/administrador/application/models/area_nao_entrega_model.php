<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Area_Nao_Entrega_Model extends CI_Model {
	
	public function get($dlv_id_ane) {
		$this->db->where('dlv_id_ane', $dlv_id_ane, FALSE);
		$this->db->join('glo_bai', 'glo_id_bai = dlv_globai_ane', 'LEFT');		
		$this->db->from('dlv_ane');
		return $this->db->get()->first_row();
	}
	
	public function getAreaEntregaEmpresa($dlv_dlvemp_ane, $glo_id_cid) {
		$this->db->from('dlv_ane');
		$this->db->join('glo_bai', 'glo_id_bai = dlv_globai_ane', 'LEFT');		
		$this->db->where('dlv_dlvemp_ane', $dlv_dlvemp_ane, FALSE);
		$this->db->where('glo_glocid_bai', $glo_id_cid, FALSE);
		return $this->db->get()->result();
	}
	
	public function getEmpresaAreaNaoEntrega($dlv_globai_ane, $dlv_dlvemp_ane) {
		$this->db->from('dlv_ane');
		$this->db->where('dlv_globai_ane', $dlv_globai_ane, FALSE);
		$this->db->where('dlv_dlvemp_ane', $dlv_dlvemp_ane, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getEmpresas($dlv_dlvemp_ane) {
		$this->db->from('dlv_ane');
		$this->db->where('dlv_dlvemp_ane', $dlv_dlvemp_ane, FALSE);
		return $this->db->get()->result();
	}
	
	public function insert($area_nao_entrega) {
		$res = $this->db->insert('dlv_ane', $area_nao_entrega);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($area_nao_entrega, $dlv_id_ane) {
		$this->db->where('dlv_id_ane', $dlv_id_ane, FALSE);
		$res = $this->db->update('dlv_ane', $area_nao_entrega);
	
		if ($res) {
			return $dlv_id_ane;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_ane) {
		$this->db->where('dlv_id_ane', $dlv_id_ane, FALSE);
		return $this->db->delete('dlv_ane');
	}
	
}