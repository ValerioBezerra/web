<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entregador_Model extends CI_Model {
	
	public function get() {
		$this->db->from('dlv_ent');
		return $this->db->get()->result();
	}
	
	public function getEntregador($dlv_id_ent) {
		$this->db->where('dlv_id_ent', $dlv_id_ent, FALSE);
		$this->db->from('dlv_ent');
		return $this->db->get()->first_row();
	}
	
	public function getEntregadorEmpresa($dlv_dlvemp_ent) {
		$this->db->where('dlv_dlvemp_ent', $dlv_dlvemp_ent, FALSE);
		$this->db->from('dlv_ent');
		return $this->db->get()->result();
	}
	
	public function getEntregadorEmpresaAtivo($dlv_dlvemp_ent) {
		$this->db->from('dlv_ent');
		$this->db->where('dlv_dlvemp_ent', $dlv_dlvemp_ent, FALSE);
		$this->db->where('dlv_ativo_ent', 1, FALSE);
		$this->db->order_by('dlv_nome_ent');
		return $this->db->get()->result();
	}
	
	public function insert($entregador) {
		$res = $this->db->insert('dlv_ent', $entregador);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($entregador, $dlv_id_ent) {
		$this->db->where('dlv_id_ent', $dlv_id_ent, FALSE);
		$res = $this->db->update('dlv_ent', $entregador);
	
		if ($res) {
			return $dlv_id_ent;
		} else {
			return FALSE;
		}
	}	
	
	public function delete($dlv_id_ent) {
		$this->db->where('dlv_id_ent', $dlv_id_ent, FALSE);
		return $this->db->delete('dlv_ent');
	}
	
}