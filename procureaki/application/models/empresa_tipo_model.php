<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa_Tipo_Model extends CI_Model {
	
	public function get($bus_busemp_ext) {
		$this->db->from('bus_ext');
		$this->db->join('bus_tip', 'bus_id_tip = bus_bustip_ext');
//		$this->db->join('bus_emp', 'bus_id_emp = bus_busemp_ext');
		$this->db->where('bus_busemp_ext', $bus_busemp_ext, FALSE);
//		$this->db->where('bus_busseg_ext', 'bus_busseg_tip', FALSE);
		$this->db->order_by('bus_descricao_tip');
		return $this->db->get()->result();
	}
	
	public function getEmpresaTipo($bus_bustip_ext, $bus_busemp_ext) {
		$this->db->from('bus_ext');
		$this->db->where('bus_bustip_ext', $bus_bustip_ext, FALSE);
		$this->db->where('bus_busemp_ext', $bus_busemp_ext, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getEmpresaTipoChave($bus_id_ext) {
		$this->db->from('bus_ext');
		$this->db->where('bus_id_ext', $bus_id_ext, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getEmpresas($bus_bustip_ext) {
		$this->db->from('bus_ext');
		$this->db->where('bus_bustip_ext', $bus_bustip_ext, FALSE);
		return $this->db->get()->result();
	}
	
	public function insert($empresa_tipo) {
		$res = $this->db->insert('bus_ext', $empresa_tipo);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($empresa_tipo, $bus_id_ext) {
		$this->db->where('bus_id_ext', $bus_id_ext, FALSE);
		$res = $this->db->update('bus_ext', $empresa_tipo);
	
		if ($res) {
			return $bus_id_emp;
		} else {
			return FALSE;
		}
	}
		
	public function delete($bus_id_ext) {
		$this->db->where('bus_id_ext', $bus_id_ext, FALSE);
		return $this->db->delete('bus_ext');
	}
	
}