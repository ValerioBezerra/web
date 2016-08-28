<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cliente_Model extends CI_Model {
	
	public function getCliente($bus_id_cli) {
		$this->db->where('bus_id_cli', $bus_id_cli, FALSE);
		$this->db->from('bus_cli');
		return $this->db->get()->first_row();
	}
	
	public function getClienteLoginEmail($bus_email_cli, $bus_senha_cli) {
		$this->db->where('bus_email_cli', $bus_email_cli);
		$this->db->where('bus_senha_cli', $bus_senha_cli);
		$this->db->where('bus_idfacebook_cli', NULL, FALSE);
		$this->db->from('bus_cli');
		return $this->db->get()->first_row();
	}
	
	public function verificarEmail($bus_email_cli) {
		$this->db->where('bus_email_cli', $bus_email_cli);
		$this->db->where('bus_idfacebook_cli', NULL, FALSE);
		$this->db->from('bus_cli');
		return $this->db->get()->first_row();
	}
	
	public function insert($cliente) {
		$res = $this->db->insert('bus_cli', $cliente);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($cliente, $bus_id_cli) {
		$this->db->where('bus_id_cli', $bus_id_cli, FALSE);
		$res = $this->db->update('bus_cli', $cliente);
	
		if ($res) {
			return $bus_id_cli;
		} else {
			return FALSE;
		}
	}	
	
	public function delete($bus_id_cli) {
		$this->db->where('bus_id_cli', $bus_id_cli, FALSE);
		return $this->db->delete('bus_cli');
	}
	
	public function verificarFacebook($bus_idfacebook_cli) {
		$this->db->where('bus_idfacebook_cli', $bus_idfacebook_cli);
		$this->db->from('bus_cli');
		return $this->db->get()->first_row();
	}	
	
}