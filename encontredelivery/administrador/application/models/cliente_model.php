<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cliente_Model extends CI_Model {
	
	public function getCliente($dlv_id_cli) {
		$this->db->where('dlv_id_cli', $dlv_id_cli, FALSE);
		$this->db->from('dlv_cli');
		return $this->db->get()->first_row();
	}
	
	public function getClienteLoginEmail($dlv_email_cli, $dlv_senha_cli) {
		$this->db->where('dlv_email_cli', $dlv_email_cli);
		$this->db->where('dlv_senha_cli', $dlv_senha_cli);
		$this->db->where('dlv_idfacebook_cli', NULL, FALSE);
		$this->db->from('dlv_cli');
		return $this->db->get()->first_row();
	}
	
	public function verificarEmail($dlv_email_cli) {
		$this->db->where('dlv_email_cli', $dlv_email_cli);
		$this->db->where('dlv_idfacebook_cli', NULL, FALSE);
		$this->db->from('dlv_cli');
		return $this->db->get()->first_row();
	}
	
	public function insert($cliente) {
		$res = $this->db->insert('dlv_cli', $cliente);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($cliente, $dlv_id_cli) {
		$this->db->where('dlv_id_cli', $dlv_id_cli, FALSE);
		$res = $this->db->update('dlv_cli', $cliente);
	
		if ($res) {
			return $dlv_id_cli;
		} else {
			return FALSE;
		}
	}	
	
	public function delete($dlv_id_cli) {
		$this->db->where('dlv_id_cli', $dlv_id_cli, FALSE);
		return $this->db->delete('dlv_cli');
	}
	
	public function verificarFacebook($dlv_idfacebook_cli) {
		$this->db->where('dlv_idfacebook_cli', $dlv_idfacebook_cli);
		$this->db->from('dlv_cli');
		return $this->db->get()->first_row();
	}	
	
}