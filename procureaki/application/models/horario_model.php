<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Horario_Model extends CI_Model {
	
	public function get($bus_id_exh) {
		$this->db->where('bus_id_exh', $bus_id_exh, FALSE);
		$this->db->from('bus_exh');
		return $this->db->get()->first_row();
	}
	
	public function getHorariosEmpresa($bus_busemp_exh) {
		$this->db->where('bus_busemp_exh', $bus_busemp_exh, FALSE);
		$this->db->from('bus_exh');
		return $this->db->get()->result();
	}
	
	public function getHorariosEmpresaGroupBy($bus_busemp_exh) {
		$this->db->where('bus_busemp_exh', $bus_busemp_exh, FALSE);
		$this->db->from('bus_exh');
		$this->db->group_by('bus_dia_exh');
		return $this->db->get()->result();
	}
	
	public function getHorariosEmpresaDia($bus_busemp_exh, $bus_dia_exh) {
		$this->db->where('bus_busemp_exh', $bus_busemp_exh, FALSE);
		$this->db->where('bus_dia_exh', $bus_dia_exh, FALSE);
		$this->db->from('bus_exh');
		return $this->db->get()->result();
	}	
	
	public function getHorarioIntervalo($bus_id_exh, $bus_busemp_exh, $bus_dia_exh, $hora) {
		$this->db->where('bus_id_exh !=', $bus_id_exh, FALSE);
		$this->db->where('bus_busemp_exh', $bus_busemp_exh, FALSE);
		$this->db->where('bus_dia_exh', $bus_dia_exh, FALSE);
		$this->db->where('bus_horaini_exh <=', $hora);
		$this->db->where('bus_horafin_exh >=', $hora);
		$this->db->from('bus_exh');
		return $this->db->get()->result();
	}
	
	public function insert($telefone) {
		$res = $this->db->insert('bus_exh', $telefone);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($telefone, $bus_id_exh) {
		$this->db->where('bus_id_exh', $bus_id_exh, FALSE);
		$res = $this->db->update('bus_exh', $telefone);
	
		if ($res) {
			return $bus_id_exh;
		} else {
			return FALSE;
		}
	}
	
	public function delete($bus_id_exh) {
		$this->db->where('bus_id_exh', $bus_id_exh, FALSE);
		return $this->db->delete('bus_exh');
	}
		
}