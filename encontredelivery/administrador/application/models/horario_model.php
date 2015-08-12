<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Horario_Model extends CI_Model {
	
	public function get($dlv_id_exh) {
		$this->db->where('dlv_id_exh', $dlv_id_exh, FALSE);
		$this->db->from('dlv_exh');
		return $this->db->get()->first_row();
	}
	
	public function getHorariosEmpresa($dlv_dlvemp_exh) {
		$this->db->where('dlv_dlvemp_exh', $dlv_dlvemp_exh, FALSE);
		$this->db->from('dlv_exh');
		return $this->db->get()->result();
	}
	
	public function getHorariosEmpresaGroupBy($dlv_dlvemp_exh) {
		$this->db->where('dlv_dlvemp_exh', $dlv_dlvemp_exh, FALSE);
		$this->db->from('dlv_exh');
		$this->db->group_by('dlv_dia_exh');
		return $this->db->get()->result();
	}
	
	public function getHorariosEmpresaDia($dlv_dlvemp_exh, $dlv_dia_exh) {
		$this->db->where('dlv_dlvemp_exh', $dlv_dlvemp_exh, FALSE);
		$this->db->where('dlv_dia_exh', $dlv_dia_exh, FALSE);
		$this->db->from('dlv_exh');
		return $this->db->get()->result();
	}	
	
	public function getHorarioIntervalo($dlv_id_exh, $dlv_dlvemp_exh, $dlv_dia_exh, $hora) {
		$this->db->where('dlv_id_exh !=', $dlv_id_exh, FALSE);
		$this->db->where('dlv_dlvemp_exh', $dlv_dlvemp_exh, FALSE);
		$this->db->where('dlv_dia_exh', $dlv_dia_exh, FALSE);
		$this->db->where('dlv_horaini_exh <=', $hora);
		$this->db->where('dlv_horafin_exh >=', $hora);
		$this->db->from('dlv_exh');
		return $this->db->get()->result();
	}
	
	public function insert($telefone) {
		$res = $this->db->insert('dlv_exh', $telefone);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($telefone, $dlv_id_exh) {
		$this->db->where('dlv_id_exh', $dlv_id_exh, FALSE);
		$res = $this->db->update('dlv_exh', $telefone);
	
		if ($res) {
			return $dlv_id_exh;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_exh) {
		$this->db->where('dlv_id_exh', $dlv_id_exh, FALSE);
		return $this->db->delete('dlv_exh');
	}
		
}