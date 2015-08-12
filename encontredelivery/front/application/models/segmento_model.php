<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Segmento_Model extends CI_Model {
	
	public function get() {
		$this->db->from('dlv_seg');
		$this->db->order_by('dlv_descricao_seg');
		return $this->db->get()->result();
	}
	
	public function getSegmento($dlv_id_seg) {
		$this->db->where('dlv_id_seg', $dlv_id_seg, FALSE);
		$this->db->from('dlv_seg');
		return $this->db->get()->first_row();
	}
}