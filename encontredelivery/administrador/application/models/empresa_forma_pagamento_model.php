<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa_Forma_Pagamento_Model extends CI_Model {
	
	public function get($dlv_id_exf) {
		$this->db->where('dlv_id_exf', $dlv_id_exf, FALSE);
		$this->db->from('dlv_id_exf');
		return $this->db->get()->first_row();
	}
	
	public function getFormasPagamentoEmpresa($dlv_dlvemp_exf) {
		$this->db->from('dlv_exf');
		$this->db->join('dlv_fpg', 'dlv_id_fpg = dlv_dlvfpg_exf', 'LEFT');		
		$this->db->where('dlv_dlvemp_exf', $dlv_dlvemp_exf, FALSE);
		return $this->db->get()->result();
	}
	
	public function getEmpresaFormaPagamento($dlv_dlvfpg_exf, $dlv_dlvemp_exf) {
		$this->db->from('dlv_exf');
		$this->db->where('dlv_dlvfpg_exf', $dlv_dlvfpg_exf, FALSE);
		$this->db->where('dlv_dlvemp_exf', $dlv_dlvemp_exf, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getEmpresas($dlv_dlvfpg_exf) {
		$this->db->from('dlv_exf');
		$this->db->where('dlv_dlvfpg_exf', $dlv_dlvfpg_exf, FALSE);
		return $this->db->get()->result();
	}
	
	public function insert($forma_pagamento_empresa) {
		$res = $this->db->insert('dlv_exf', $forma_pagamento_empresa);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($forma_pagamento_empresa, $dlv_id_exf) {
		$this->db->where('dlv_id_exf', $dlv_id_exf, FALSE);
		$res = $this->db->update('dlv_exf', $forma_pagamento_empresa);
	
		if ($res) {
			return $dlv_id_exf;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_exf) {
		$this->db->where('dlv_id_exf', $dlv_id_exf, FALSE);
		return $this->db->delete('dlv_exf');
	}
		
}