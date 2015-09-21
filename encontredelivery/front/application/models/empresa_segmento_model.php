<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa_Segmento_Model extends CI_Model {
	
	public function get($dlv_dlvemp_exs) {
		$this->db->from('dlv_exs');
		$this->db->join('dlv_seg', 'dlv_id_seg = dlv_dlvseg_exs');
		$this->db->where('dlv_dlvemp_exs', $dlv_dlvemp_exs, FALSE);
		$this->db->order_by('dlv_descricao_seg');
		return $this->db->get()->result();
	}
}