<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Status_Model extends CI_Model {
	
	public function get() {
		$this->db->from('dlv_sta');
		$this->db->order_by('dlv_descricao_sta');
		return $this->db->get()->result();
	}
	
	public function getStatus($dlv_id_sta) {
		$this->db->where('dlv_id_sta', $dlv_id_sta, FALSE);
		$this->db->from('dlv_sta');
		return $this->db->get()->first_row();
	}
	
	public function getStatusOrdem($dlv_ordem_sta) {
		$this->db->where('dlv_ordem_sta', $dlv_ordem_sta, FALSE);
		$this->db->where('dlv_id_sta', $dlv_id_sta, FALSE);

		$this->db->from('dlv_sta');
		return $this->db->get()->result();
	}
	
	public function getStatusOrdemAtivoNaoCancelado($dlv_ordem_sta) {
		$this->db->from('dlv_sta');
		$this->db->where('dlv_ordem_sta', $dlv_ordem_sta, FALSE);
		$this->db->where('dlv_ativo_sta', 1, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getStatusCanceladoAtivo() {
		$this->db->from('dlv_sta');
		$this->db->where('dlv_indicador_sta', 3, FALSE);
		$this->db->where('dlv_ativo_sta', 1, FALSE);
		return $this->db->get()->first_row();
	}	
	
	public function getStatusAtivo($opcao_status) {
		$this->db->from('dlv_sta');
		
		if ($opcao_status == 0) {
			$this->db->where('dlv_indicador_sta', 0, FALSE);
		}
		
		if ($opcao_status == 1) {
			$this->db->where('dlv_indicador_sta', 1, FALSE);
			$this->db->or_where('dlv_indicador_sta ', 3, FALSE);
		}
		
		if ($opcao_status == 2) {
			$this->db->where('dlv_indicador_sta', 1, FALSE);
			$this->db->or_where('dlv_indicador_sta', 2, FALSE);
			$this->db->or_where('dlv_indicador_sta ', 3, FALSE);
		}
		
		$this->db->where('dlv_ativo_sta', 1, FALSE);
		
		
		$this->db->order_by('dlv_ordem_sta');
		return $this->db->get()->result();
	}
	
	public function insert($status) {
		$res = $this->db->insert('dlv_sta', $status);
		
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}		
	}
	
	public function update($status, $dlv_id_sta) {
		$this->db->where('dlv_id_sta', $dlv_id_sta, FALSE);
		$res = $this->db->update('dlv_sta', $status);
	
		if ($res) {
			return $dlv_id_sta;
		} else {
			return FALSE;
		}
	}
	
	
	public function delete($dlv_id_sta) {
		$this->db->where('dlv_id_sta', $dlv_id_sta, FALSE);
		return $this->db->delete('dlv_sta');
	}
	
	public function insertStatusPedido($status_pedido) {
		$this->load->model('GCM_Model', 'GCMModel');
		$res = $this->db->insert('dlv_spe', $status_pedido);
	
		if ($res) {
			$this->GCMModel->notificacao_mudanca_status($status_pedido['dlv_dlvped_spe'], $status_pedido['dlv_dlvsta_spe']);
			
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function deleteStatusPedido($dlv_id_spe) {
		$this->db->where('dlv_id_spe', $dlv_id_spe, FALSE);
		return $this->db->delete('dlv_spe');
	}
	
	public function getStatusPedidoChave($dlv_id_spe) {
		$this->db->where('dlv_id_spe', $dlv_id_spe, FALSE);
		$this->db->from('dlv_spe');
		return $this->db->get()->first_row();
	}
	
	public function getUltimoStatusPedido($dlv_dlvped_spe) {
		$this->db->select('dlv_id_sta, dlv_descricao_sta, dlv_indicador_sta, dlv_ordem_sta');
		$this->db->from('dlv_spe');
		$this->db->join('dlv_sta', 'dlv_id_sta = dlv_dlvsta_spe', 'LEFT');
		$this->db->where('dlv_dlvped_spe', $dlv_dlvped_spe, FALSE);
		$this->db->order_by('dlv_datahoramod_spe DESC, dlv_id_spe DESC');
		return $this->db->get()->first_row();
	}
	
	public function getStatusPedido($dlv_dlvped_spe) {
		$this->db->select('dlv_id_spe, dlv_id_sta, dlv_descricao_sta, dlv_indicador_sta, dlv_ativo_sta, dlv_datahoramod_spe, dlv_motivocanc_spe');
		$this->db->from('dlv_spe');
		$this->db->join('dlv_sta', 'dlv_id_sta = dlv_dlvsta_spe', 'LEFT');
		$this->db->where('dlv_dlvped_spe', $dlv_dlvped_spe, FALSE);
		$this->db->order_by('dlv_datahoramod_spe DESC, dlv_id_spe DESC');
		return $this->db->get()->result();
	}

	public function getStatusPedidoUnico($dlv_dlvped_spe, $dlv_dlvsta_spe) {
		$this->db->from('dlv_spe');
		$this->db->where('dlv_dlvped_spe', $dlv_dlvped_spe, FALSE);
		$this->db->where('dlv_dlvsta_spe', $dlv_dlvsta_spe, FALSE);
		return $this->db->get()->first_row();
	}
	
	
}