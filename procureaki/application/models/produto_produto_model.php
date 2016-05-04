<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto_Produto_Model extends CI_Model {
	
	public function get($bus_id_pxp) {
		$this->db->where('bus_id_pxp', $bus_id_pxp, FALSE);
		$this->db->from('bus_pxp');
		return $this->db->get()->first_row();
	}
	
	public function getProdutoProdutoEmpresa($bus_busproprincipal_pxp) {
		$this->db->from('bus_pxp');
		$this->db->join('bus_pro', 'bus_id_pro = bus_buspro_pxp', 'LEFT');		
		$this->db->where('bus_busproprincipal_pxp', $bus_busproprincipal_pxp, FALSE);
		$this->db->order_by('bus_promocao_pro DESC, bus_ordem_pro, bus_descricao_pro');
		return $this->db->get()->result();
	}
	
	public function getProdutoProdutoAtivoEmpresa($bus_busproprincipal_pxp) {
		$this->db->from('bus_pxp');
		$this->db->join('bus_pro', 'bus_id_pro = bus_buspro_pxp', 'LEFT');		
		$this->db->where('bus_busproprincipal_pxp', $bus_busproprincipal_pxp, FALSE);
		$this->db->where('bus_ativo_pro', 1, FALSE);
		$this->db->order_by('bus_promocao_pro DESC, bus_ordem_pro, bus_descricao_pro');
		return $this->db->get()->result();
	}
	
	public function getEmpresaProduto($bus_buspro_pxp) {
		$this->db->from('bus_pxp');
		$this->db->where('bus_buspro_pxp', $bus_buspro_pxp, FALSE);
		return $this->db->get()->result();
	}
	
	public function getEmpresaProdutoProduto($bus_busproprincipal_pxp, $bus_buspro_pxp) {
		$this->db->from('bus_pxp');
		$this->db->where('bus_busproprincipal_pxp', $bus_busproprincipal_pxp, FALSE);
		$this->db->where('bus_buspro_pxp', $bus_buspro_pxp, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function insert($produto_produto) {
		$res = $this->db->insert('bus_pxp', $produto_produto);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($produto_produto, $bus_id_pxp) {
		$this->db->where('bus_id_pxp', $bus_id_pxp, FALSE);
		$res = $this->db->update('bus_pxp', $produto_produto);
	
		if ($res) {
			return $bus_id_pxp;
		} else {
			return FALSE;
		}
	}
	
	public function delete($bus_id_pxp) {
		$this->db->where('bus_id_pxp', $bus_id_pxp, FALSE);
		return $this->db->delete('bus_pxp');
	}
	
}