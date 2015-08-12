<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forma_Pagamento_Model extends CI_Model {
	
	public function get() {
		$this->db->from('dlv_fpg');
// 		$this->db->order_by('dlv_descricao_seg');
		return $this->db->get()->result();
	}
	
	public function getFormaPagamento($dlv_id_fpg) {
		$this->db->where('dlv_id_fpg', $dlv_id_fpg, FALSE);
		$this->db->from('dlv_fpg');
		return $this->db->get()->first_row();
	}
	
}