<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto_Model extends CI_Model {
	
	public function get($dlv_id_pro) {
		$this->db->where('dlv_id_pro', $dlv_id_pro, FALSE);
		$this->db->from('dlv_pro');
		return $this->db->get()->first_row();
	}
	
	public function getProdutoEmpresa($dlv_dlvemp_pro, $dlv_dlvcat_pro = NULL, $dlv_ativo_pro = NULL) {
		$this->db->where('dlv_dlvemp_pro', $dlv_dlvemp_pro, FALSE);
		
		if (($dlv_dlvcat_pro != NULL) && ($dlv_dlvcat_pro != 0)) {
			$this->db->where('dlv_dlvcat_pro', $dlv_dlvcat_pro, FALSE);
		}
		
		if (($dlv_ativo_pro != NULL) && ($dlv_ativo_pro != 2)) {
			$this->db->where('dlv_ativo_pro', $dlv_ativo_pro, FALSE);
		}
				
		$this->db->join('dlv_cat', 'dlv_id_cat = dlv_dlvcat_pro', 'LEFT');		
		$this->db->from('dlv_pro');
		return $this->db->get()->result();
	}
	
	public function getProdutoEmpresaCategoriaAtivo($dlv_dlvemp_pro, $dlv_dlvcat_pro) {
		$this->db->from('dlv_pro');
		$this->db->where('dlv_dlvemp_pro', $dlv_dlvemp_pro, FALSE);
		$this->db->where('dlv_dlvcat_pro', $dlv_dlvcat_pro, FALSE);
		$this->db->where('dlv_ativo_pro', 1, FALSE);
		$this->db->where('dlv_escolheproduto_pro', 0, FALSE);
		return $this->db->get()->result();
	}
	
	public function getProdutoEscolhidoEmpresaAtivo($dlv_dlvemp_pro, $dlv_dlvcat_pro) {
		$this->db->from('dlv_pro');
		$this->db->where('dlv_dlvemp_pro', $dlv_dlvemp_pro, FALSE);
		$this->db->where('dlv_dlvcat_pro !=', $dlv_dlvcat_pro, FALSE);
		$this->db->where('dlv_ativo_pro', 1, FALSE);
		$this->db->where('dlv_escolheproduto_pro', 0, FALSE);
		return $this->db->get()->result();
	}
	
	public function getProdutoCategoriaAtivoTelaPrincipal($dlv_dlvcat_pro) {
		$this->db->from('dlv_pro');
		$this->db->where('dlv_dlvcat_pro', $dlv_dlvcat_pro, FALSE);
		$this->db->where('dlv_ativo_pro', 1, FALSE);
		$this->db->where('dlv_principal_pro', 1, FALSE);
		$this->db->order_by('dlv_promocao_pro DESC, dlv_ordem_pro, dlv_descricao_pro');
		return $this->db->get()->result();
	}
	
	public function getQuantidadeTamanho($dlv_id_pro) {
		$this->db->select('dlv_dlvtam_pxt');
		$this->db->from('dlv_pxt');
		$this->db->where('dlv_dlvpro_pxt', $dlv_id_pro, FALSE);
	
		return $this->db->get()->result();
	}	
	
	public function insert($produto) {
		$res = $this->db->insert('dlv_pro', $produto);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($produto, $dlv_id_pro) {
		$this->db->where('dlv_id_pro', $dlv_id_pro, FALSE);
		$res = $this->db->update('dlv_pro', $produto);
	
		if ($res) {
			return $dlv_id_pro;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_pro) {
		$this->db->where('dlv_id_pro', $dlv_id_pro, FALSE);
		return $this->db->delete('dlv_pro');
	}
	
	public function getProdutosPedido($dlv_dlvped_ppe) {
		$this->db->from('dlv_ppe');
		$this->db->join('dlv_pro', 'dlv_id_pro = dlv_dlvpro_ppe', 'LEFT');
		$this->db->join('dlv_tam', 'dlv_id_tam = dlv_dlvtam_ppe', 'LEFT');
		$this->db->where('dlv_dlvped_ppe', $dlv_dlvped_ppe, FALSE);
		$this->db->order_by('dlv_id_ppe');
		return $this->db->get()->result();
	}

	public function getProdutosProdutoPedido($dlv_dlvppe_ppp) {
		$this->db->from('dlv_ppp');
		$this->db->join('dlv_pro', 'dlv_id_pro = dlv_dlvpro_ppp', 'LEFT');
		$this->db->where('dlv_dlvppe_ppp', $dlv_dlvppe_ppp, FALSE);
		$this->db->order_by('dlv_id_ppp');
		return $this->db->get()->result();
	}
	
		
}