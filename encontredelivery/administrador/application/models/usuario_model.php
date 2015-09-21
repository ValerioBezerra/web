<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_Model extends CI_Model {
	
	public function getUsuariosEmpresa($dlv_dlvemp_usu) {
		$this->db->where('dlv_dlvemp_usu', $dlv_dlvemp_usu, FALSE);
		$this->db->join('dlv_per', 'dlv_id_per = dlv_dlvper_usu', 'LEFT');		
		$this->db->from('dlv_usu');
		return $this->db->get()->result();
	}
	
	public function getUsuariosPerfisEmpresaNaoLogado($dlv_dlvemp_usu, $dlv_id_usu_logado) {
		$this->db->from('dlv_usu');
		$this->db->join('dlv_per', 'dlv_id_per = dlv_dlvper_usu', 'LEFT');		
		$this->db->where('dlv_id_usu != ', $dlv_id_usu_logado, FALSE);
		$this->db->where('dlv_dlvemp_usu', $dlv_dlvemp_usu, FALSE);
		$this->db->where('dlv_kingsoft_usu', 0, FALSE);
		return $this->db->get()->result();
	}
	
	public function getUsuariosPerfil($dlv_dlvper_usu) {
		$this->db->where('dlv_dlvper_usu', $dlv_dlvper_usu, FALSE);
		$this->db->from('dlv_usu');
		return $this->db->get()->result();
	}
	
	public function getUsuario($dlv_dlvemp_usu, $dlv_login_usu, $dlv_senha_usu) {
		$this->db->where('dlv_dlvemp_usu', $dlv_dlvemp_usu, FALSE);
		$this->db->where('dlv_login_usu', $dlv_login_usu);
		$this->db->where('dlv_senha_usu', $dlv_senha_usu);
		$this->db->from('dlv_usu');
		return $this->db->get()->first_row();
	}
	
	public function getUsuarioLogin($dlv_dlvemp_usu, $dlv_login_usu) {
		$this->db->where('dlv_dlvemp_usu', $dlv_dlvemp_usu, FALSE);
		$this->db->where('dlv_login_usu', $dlv_login_usu);
		$this->db->from('dlv_usu');
		return $this->db->get()->first_row();
	}
	
	
	public function getUsuarioChave($dlv_id_usu) {
		$this->db->where('dlv_id_usu', $dlv_id_usu, FALSE);
		$this->db->from('dlv_usu');
		return $this->db->get()->first_row();
	}
	
	
	public function insert($usuario) {
		$res = $this->db->insert('dlv_usu', $usuario);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($usuario, $dlv_id_usu) {
		$this->db->where('dlv_id_usu', $dlv_id_usu, FALSE);
		$res = $this->db->update('dlv_usu', $usuario);
	
		if ($res) {
			return $dlv_id_usu;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_usu) {
		$this->db->where('dlv_id_usu', $dlv_id_usu, FALSE);
		return $this->db->delete('dlv_usu');
	}
		
}