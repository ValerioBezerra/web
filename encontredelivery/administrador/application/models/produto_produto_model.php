<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto_Produto_Model extends CI_Model {
	
	public function get($dlv_id_pxp) {
		$this->db->where('dlv_id_pxp', $dlv_id_pxp, FALSE);
		$this->db->from('dlv_pxp');
		return $this->db->get()->first_row();
	}
	
	public function getProdutoProdutoEmpresa($dlv_dlvproprincipal_pxp) {
		$this->db->from('dlv_pxp');
		$this->db->join('dlv_pro', 'dlv_id_pro = dlv_dlvpro_pxp', 'LEFT');		
		$this->db->where('dlv_dlvproprincipal_pxp', $dlv_dlvproprincipal_pxp, FALSE);
		$this->db->order_by('dlv_promocao_pro DESC, dlv_ordem_pro, dlv_descricao_pro');
		return $this->db->get()->result();
	}
	
	public function getProdutoProdutoAtivoEmpresa($dlv_dlvproprincipal_pxp) {
		$this->db->from('dlv_pxp');
		$this->db->join('dlv_pro', 'dlv_id_pro = dlv_dlvpro_pxp', 'LEFT');		
		$this->db->where('dlv_dlvproprincipal_pxp', $dlv_dlvproprincipal_pxp, FALSE);
		$this->db->where('dlv_ativo_pro', 1, FALSE);
		$this->db->order_by('dlv_promocao_pro DESC, dlv_ordem_pro, dlv_descricao_pro');
		return $this->db->get()->result();
	}
	
	public function getEmpresaProduto($dlv_dlvpro_pxp) {
		$this->db->from('dlv_pxp');
		$this->db->where('dlv_dlvpro_pxp', $dlv_dlvpro_pxp, FALSE);
		return $this->db->get()->result();
	}
	
	public function getEmpresaProdutoProduto($dlv_dlvproprincipal_pxp, $dlv_dlvpro_pxp) {
		$this->db->from('dlv_pxp');
		$this->db->where('dlv_dlvproprincipal_pxp', $dlv_dlvproprincipal_pxp, FALSE);
		$this->db->where('dlv_dlvpro_pxp', $dlv_dlvpro_pxp, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function insert($produto_produto) {
		$res = $this->db->insert('dlv_pxp', $produto_produto);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($produto_produto, $dlv_id_pxp) {
		$this->db->where('dlv_id_pxp', $dlv_id_pxp, FALSE);
		$res = $this->db->update('dlv_pxp', $produto_produto);
	
		if ($res) {
			return $dlv_id_pxp;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_pxp) {
		$this->db->where('dlv_id_pxp', $dlv_id_pxp, FALSE);
		return $this->db->delete('dlv_pxp');
	}
	
}