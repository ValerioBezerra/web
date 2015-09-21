<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa_Segmento_Model extends CI_Model {
	
	public function get($dlv_dlvemp_exs) {
		$this->db->from('dlv_exs');
		$this->db->join('dlv_seg', 'dlv_id_seg = dlv_dlvseg_exs');
		$this->db->where('dlv_dlvemp_exs', $dlv_dlvemp_exs, FALSE);
		$this->db->order_by('dlv_descricao_seg');
		return $this->db->get()->result();
	}
	
	public function getEmpresaSegmento($dlv_dlvseg_exs, $dlv_dlvemp_exs) {
		$this->db->from('dlv_exs');
		$this->db->where('dlv_dlvseg_exs', $dlv_dlvseg_exs, FALSE);
		$this->db->where('dlv_dlvemp_exs', $dlv_dlvemp_exs, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getEmpresaSegmentoChave($dlv_id_exs) {
		$this->db->from('dlv_exs');
		$this->db->where('dlv_id_exs', $dlv_id_exs, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getEmpresas($dlv_dlvseg_exs) {
		$this->db->from('dlv_exs');
		$this->db->where('dlv_dlvseg_exs', $dlv_dlvseg_exs, FALSE);
		return $this->db->get()->result();
	}
	
	public function insert($empresa_segmento) {
		$res = $this->db->insert('dlv_exs', $empresa_segmento);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($empresa_segmento, $dlv_id_exs) {
		$this->db->where('dlv_id_exs', $dlv_id_exs, FALSE);
		$res = $this->db->update('dlv_exs', $empresa_segmento);
	
		if ($res) {
			return $dlv_id_emp;
		} else {
			return FALSE;
		}
	}
		
	public function delete($dlv_id_exs) {
		$this->db->where('dlv_id_exs', $dlv_id_exs, FALSE);
		return $this->db->delete('dlv_exs');
	}
	
}