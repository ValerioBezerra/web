<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_Model extends CI_Model {
	
	public function getUsuariosEmpresa($bus_busemp_usu) {
		$this->db->where('bus_busemp_usu', $bus_busemp_usu, FALSE);
		$this->db->join('bus_per', 'bus_id_per = bus_busper_usu', 'LEFT');		
		$this->db->from('bus_usu');
		return $this->db->get()->result();
	}
	
	public function getUsuariosPerfisEmpresaNaoLogado($bus_busemp_usu, $bus_id_usu_logado) {
		$this->db->from('bus_usu');
		$this->db->join('bus_per', 'bus_id_per = bus_busper_usu', 'LEFT');		
		$this->db->where('bus_id_usu != ', $bus_id_usu_logado, FALSE);
		$this->db->where('bus_busemp_usu', $bus_busemp_usu, FALSE);
		$this->db->where('bus_kingsoft_usu', 0, FALSE);
		return $this->db->get()->result();
	}
	
	public function getUsuariosPerfil($bus_busper_usu) {
		$this->db->where('bus_busper_usu', $bus_busper_usu, FALSE);
		$this->db->from('bus_usu');
		return $this->db->get()->result();
	}
	
	public function getUsuario($bus_busemp_usu, $bus_login_usu, $bus_senha_usu) {
		$this->db->where('bus_busemp_usu', $bus_busemp_usu, FALSE);
		$this->db->where('bus_login_usu', $bus_login_usu);
		$this->db->where('bus_senha_usu', $bus_senha_usu);
		$this->db->from('bus_usu');
		return $this->db->get()->first_row();
	}
	
	public function getUsuarioLogin($bus_busemp_usu, $bus_login_usu) {
		$this->db->where('bus_busemp_usu', $bus_busemp_usu, FALSE);
		$this->db->where('bus_login_usu', $bus_login_usu);
		$this->db->from('bus_usu');
		return $this->db->get()->first_row();
	}
	
	
	public function getUsuarioChave($bus_id_usu) {
		$this->db->where('bus_id_usu', $bus_id_usu, FALSE);
		$this->db->from('bus_usu');
		return $this->db->get()->first_row();
	}
	
	
	public function insert($usuario) {
		$res = $this->db->insert('bus_usu', $usuario);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($usuario, $bus_id_usu) {
		$this->db->where('bus_id_usu', $bus_id_usu, FALSE);
		$res = $this->db->update('bus_usu', $usuario);
	
		if ($res) {
			return $bus_id_usu;
		} else {
			return FALSE;
		}
	}
	
	public function delete($bus_id_usu) {
		$this->db->where('bus_id_usu', $bus_id_usu, FALSE);
		return $this->db->delete('bus_usu');
	}
		
}