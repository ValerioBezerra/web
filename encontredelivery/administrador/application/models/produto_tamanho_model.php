<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto_Tamanho_Model extends CI_Model {
	
	public function get($dlv_id_pxt) {
		$this->db->where('dlv_id_pxt', $dlv_id_pxt, FALSE);
		$this->db->from('dlv_pxt');
		return $this->db->get()->first_row();
	}
	
	public function getProdutoTamanhoEmpresa($dlv_dlvpro_pxt) {
		$this->db->from('dlv_pxt');
		$this->db->join('dlv_tam', 'dlv_id_tam = dlv_dlvtam_pxt', 'LEFT');		
		$this->db->where('dlv_dlvpro_pxt', $dlv_dlvpro_pxt, FALSE);
		return $this->db->get()->result();
	}
	
	public function getProdutoTamanhoAtivoEmpresa($dlv_dlvpro_pxt) {
		$this->db->from('dlv_pxt');
		$this->db->join('dlv_tam', 'dlv_id_tam = dlv_dlvtam_pxt', 'LEFT');		
		$this->db->where('dlv_dlvpro_pxt', $dlv_dlvpro_pxt, FALSE);
		$this->db->where('dlv_ativo_tam', 1, FALSE);
		$this->db->order_by('dlv_ordem_tam, dlv_descricao_tam');
		return $this->db->get()->result();
	}
	
	public function getEmpresaTamanho($dlv_dlvtam_pxt) {
		$this->db->from('dlv_pxt');
		$this->db->where('dlv_dlvtam_pxt', $dlv_dlvtam_pxt, FALSE);
		return $this->db->get()->result();
	}
	
	public function getEmpresaTamanhoProduto($dlv_dlvpro_pxt, $dlv_dlvtam_pxt) {
		$this->db->from('dlv_pxt');
		$this->db->where('dlv_dlvpro_pxt', $dlv_dlvpro_pxt, FALSE);
		$this->db->where('dlv_dlvtam_pxt', $dlv_dlvtam_pxt, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function insert($produto_tamanho) {
		$res = $this->db->insert('dlv_pxt', $produto_tamanho);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($produto_tamanho, $dlv_id_pxt) {
		$this->db->where('dlv_id_pxt', $dlv_id_pxt, FALSE);
		$res = $this->db->update('dlv_pxt', $produto_tamanho);
	
		if ($res) {
			return $dlv_id_pxt;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_pxt) {
		$this->db->where('dlv_id_pxt', $dlv_id_pxt, FALSE);
		return $this->db->delete('dlv_pxt');
	}
	
}