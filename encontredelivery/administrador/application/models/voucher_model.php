<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Voucher_Model extends CI_Model {
	
	public function getCodigo($dlv_codigo_vou, $dlv_dlvcli_vou) {
		$this->db->from('dlv_vou');
		$this->db->where('dlv_codigo_vou', $dlv_codigo_vou);
		$this->db->where('dlv_ativo_vou', 1, FALSE);
		$this->db->where('NOT EXISTS(SELECT * FROM dlv_ped
				                     WHERE dlv_dlvcli_ped = '.$dlv_dlvcli_vou.' 
				                       AND dlv_dlvvou_ped = dlv_vou.dlv_id_vou
				                       AND dlv_cancelado_ped = 0)', NULL, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getCliente($dlv_dlvcli_vou) {
		$this->db->from('dlv_vou');
		$this->db->join('dlv_emp', 'dlv_id_emp = dlv_dlvemp_vou', 'LEFT');
		$this->db->where('dlv_dlvcli_vou', $dlv_dlvcli_vou, FALSE);
		$this->db->where('dlv_ativo_vou', 1, FALSE);
		$this->db->where('NOT EXISTS(SELECT * FROM dlv_ped
				                     WHERE dlv_dlvcli_ped = '.$dlv_dlvcli_vou.' 
				                       AND dlv_dlvvou_ped = dlv_vou.dlv_id_vou
				                       AND dlv_cancelado_ped = 0)', NULL, FALSE);
		$this->db->order_by('dlv_id_vou');
		return $this->db->get()->result();
	}
	
	public function getSemCliente($dlv_dlvcli_vou) {
		$this->db->from('dlv_vou');
		$this->db->join('dlv_emp', 'dlv_id_emp = dlv_dlvemp_vou', 'LEFT');
		$this->db->where('dlv_dlvcli_vou', NULL, FALSE);
		$this->db->where('dlv_ativo_vou', 1, FALSE);
		$this->db->where('NOT EXISTS(SELECT * FROM dlv_ped
				                     WHERE dlv_dlvcli_ped = '.$dlv_dlvcli_vou.' 
				                       AND dlv_dlvvou_ped = dlv_vou.dlv_id_vou
				                       AND dlv_cancelado_ped = 0)', NULL, FALSE);
		$this->db->order_by('dlv_id_vou');
		return $this->db->get()->result();
	}
	
}