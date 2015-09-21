<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa_Rede_Social_Model extends CI_Model {
	
	public function get($dlv_id_exr) {
		$this->db->where('dlv_id_exr', $dlv_id_exr, FALSE);
		$this->db->from('dlv_id_exr');
		return $this->db->get()->first_row();
	}
	
	public function getRedeSocialEmpresa($dlv_dlvemp_exr) {
		$this->db->from('dlv_exr');
		$this->db->join('dlv_red', 'dlv_id_red = dlv_dlvred_exr', 'LEFT');		
		$this->db->where('dlv_dlvemp_exr', $dlv_dlvemp_exr, FALSE);
		return $this->db->get()->result();
	}
	
	public function getEmpresaRedeSocial($dlv_dlvred_exr, $dlv_dlvemp_exr) {
		$this->db->from('dlv_exr');
		$this->db->where('dlv_dlvred_exr', $dlv_dlvred_exr, FALSE);
		$this->db->where('dlv_dlvemp_exr', $dlv_dlvemp_exr, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getEmpresas($dlv_dlvred_exr) {
		$this->db->from('dlv_exr');
		$this->db->where('dlv_dlvred_exr', $dlv_dlvred_exr, FALSE);
		return $this->db->get()->result();
	}
	
	public function insert($rede_social_empresa) {
		$res = $this->db->insert('dlv_exr', $rede_social_empresa);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($rede_social_empresa, $dlv_id_exr) {
		$this->db->where('dlv_id_exr', $dlv_id_exr, FALSE);
		$res = $this->db->update('dlv_exr', $rede_social_empresa);
	
		if ($res) {
			return $dlv_id_exr;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_exr) {
		$this->db->where('dlv_id_exr', $dlv_id_exr, FALSE);
		return $this->db->delete('dlv_exr');
	}
		
}