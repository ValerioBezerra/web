<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto_Model extends CI_Model {
	
	public function get($bus_id_pro) {
		$this->db->where('bus_id_pro', $bus_id_pro, FALSE);
		$this->db->from('bus_pro');
		return $this->db->get()->first_row();
	}
	
	public function getProdutoEmpresa($bus_busemp_pro, $bus_buscat_pro = NULL, $bus_ativo_pro = NULL) {
		$this->db->where('bus_busemp_pro', $bus_busemp_pro, FALSE);
		
		if (($bus_buscat_pro != NULL) && ($bus_buscat_pro != 0)) {
			$this->db->where('bus_buscat_pro', $bus_buscat_pro, FALSE);
		}
		
		if (($bus_ativo_pro != NULL) && ($bus_ativo_pro != 2)) {
			$this->db->where('bus_ativo_pro', $bus_ativo_pro, FALSE);
		}
				
		$this->db->join('bus_cat', 'bus_id_cat = bus_buscat_pro', 'LEFT');		
		$this->db->from('bus_pro');
		return $this->db->get()->result();
	}
	
	public function getProdutoEmpresaCategoriaAtivo($bus_busemp_pro, $bus_buscat_pro) {
		$this->db->from('bus_pro');
		$this->db->where('bus_busemp_pro', $bus_busemp_pro, FALSE);
		$this->db->where('bus_buscat_pro', $bus_buscat_pro, FALSE);
		$this->db->where('bus_ativo_pro', 1, FALSE);
		
		return $this->db->get()->result();
	}
	
	public function getProdutoEscolhidoEmpresaAtivo($bus_busemp_pro, $bus_buscat_pro) {
		$this->db->from('bus_pro');
		$this->db->where('bus_busemp_pro', $bus_busemp_pro, FALSE);
		$this->db->where('bus_buscat_pro !=', $bus_buscat_pro, FALSE);
		$this->db->where('bus_ativo_pro', 1, FALSE);
				return $this->db->get()->result();
	}
	
	public function getProdutoCategoriaAtivoTelaPrincipal($bus_buscat_pro) {
		$this->db->from('bus_pro');
		$this->db->where('bus_buscat_pro', $bus_buscat_pro, FALSE);
		$this->db->where('bus_ativo_pro', 1, FALSE);
		
		$this->db->order_by('bus_promocao_pro DESC, bus_ordem_pro, bus_descricao_pro');
		return $this->db->get()->result();
	}

	public function getProdutoDescricaoOrdemPrecoMenor($bus_descricao_pro) {
		$this->db->from('bus_pro');
		$this->db->like('bus_descricao_pro', $bus_descricao_pro);
		$this->db->where('bus_ativo_pro', 1, FALSE);

		$this->db->order_by('bus_preco_pro, bus_descricao_pro');
		return $this->db->get()->result();
	}

	public function getQuantidadeTamanho($bus_id_pro) {
		$this->db->select('bus_bustam_pxt');
		$this->db->from('bus_pxt');
		$this->db->where('bus_buspro_pxt', $bus_id_pro, FALSE);
	
		return $this->db->get()->result();
	}	
	
	public function insert($produto) {
		$res = $this->db->insert('bus_pro', $produto);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($produto, $bus_id_pro) {
		$this->db->where('bus_id_pro', $bus_id_pro, FALSE);
		$res = $this->db->update('bus_pro', $produto);
	
		if ($res) {
			return $bus_id_pro;
		} else {
			return FALSE;
		}
	}
	
	public function delete($bus_id_pro) {
		$this->db->where('bus_id_pro', $bus_id_pro, FALSE);
		return $this->db->delete('bus_pro');
	}
	
	public function getProdutosPedido($bus_busped_ppe) {
		$this->db->from('bus_ppe');
		$this->db->join('bus_pro', 'bus_id_pro = bus_buspro_ppe', 'LEFT');
		$this->db->join('bus_tam', 'bus_id_tam = bus_bustam_ppe', 'LEFT');
		$this->db->where('bus_busped_ppe', $bus_busped_ppe, FALSE);
		$this->db->order_by('bus_id_ppe');
		return $this->db->get()->result();
	}

	public function getProdutosProdutoPedido($bus_busppe_ppp) {
		$this->db->from('bus_ppp');
		$this->db->join('bus_pro', 'bus_id_pro = bus_buspro_ppp', 'LEFT');
		$this->db->where('bus_busppe_ppp', $bus_busppe_ppp, FALSE);
		$this->db->order_by('bus_id_ppp');
		return $this->db->get()->result();
	}
	
		
}