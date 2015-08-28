<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedido_Model extends CI_Model {
	
	public function get($dlv_id_ped) {
		$this->db->from('dlv_ped');
		$this->db->join('dlv_emp', 'dlv_id_emp = dlv_dlvemp_ped', 'LEFT');
		$this->db->join('dlv_cli', 'dlv_id_cli = dlv_dlvcli_ped', 'LEFT');
		$this->db->join('glo_end', 'glo_id_end = dlv_gloend_ped', 'LEFT');
		$this->db->join('glo_bai', 'glo_id_bai = glo_globai_end');
		$this->db->join('glo_cid', 'glo_id_cid = glo_glocid_bai');
		$this->db->join('glo_est', 'glo_id_est = glo_gloest_cid');
		$this->db->join('dlv_fpg', 'dlv_id_fpg = dlv_dlvfpg_ped', 'LEFT');
		$this->db->where('dlv_id_ped',$dlv_id_ped, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function insert($pedido, $produtos_pedido_array) {
		$this->db->trans_start();
		$this->db->insert('dlv_ped',$pedido);
		$insert_id = $this->db->insert_id();
		
		if (is_numeric($insert_id)) {
			foreach ($produtos_pedido_array as $produto_pedido) {
				$produto_pedido['dlv_dlvped_ppe'] = $insert_id;
				
				if (array_key_exists("dlv_ppp", $produto_pedido)) {
					$dlv_ppp    = $produto_pedido['dlv_ppp'];
				}
				
				if (array_key_exists("dlv_ppa", $produto_pedido)) {
					$dlv_ppa    = $produto_pedido['dlv_ppa'];
				}
				
				$dlv_id_ppe = $this->insertProduto($produto_pedido);
				
				if (is_numeric($dlv_id_ppe)) {
					if (array_key_exists("dlv_ppp", $produto_pedido)) {
						foreach ($dlv_ppp as $produto_escolhido) {
							$produto_escolhido['dlv_dlvppe_ppp'] = $dlv_id_ppe;
							$this->insertProdutoEscolhido($produto_escolhido);
						}
					}
					
					if (array_key_exists("dlv_ppa", $produto_pedido)) {
						foreach ($dlv_ppa as $adicional) {
							$adicional['dlv_dlvppe_ppa'] = $dlv_id_ppe;
							$this->insertAdicional($adicional);
						}
					}					
				}
			}			
		}
		
		$this->db->trans_complete();
		return $insert_id;		
	}
	
	public function update($pedido, $dlv_id_ped) {
		$this->db->where('dlv_id_ped', $dlv_id_ped, FALSE);
		$res = $this->db->update('dlv_ped', $pedido);
	
		if ($res) {
			return $dlv_id_ped;
		} else {
			return FALSE;
		}
	}
	
	public function insertProduto($produto_pedido_temp) {
		$dlv_dlvtam_ppe = null;
		
		if (array_key_exists("dlv_dlvtam_ppe", $produto_pedido_temp)) {
			$dlv_dlvtam_ppe = $produto_pedido_temp['dlv_dlvtam_ppe'];
		}
		
		$produto_pedido = array(
			"dlv_dlvped_ppe"      		  => $produto_pedido_temp['dlv_dlvped_ppe'],
			"dlv_dlvpro_ppe"      		  => $produto_pedido_temp['dlv_dlvpro_ppe'],
			"dlv_dlvtam_ppe"      		  => $dlv_dlvtam_ppe,
			"dlv_opcao_ppe"      		  => $produto_pedido_temp['dlv_opcao_ppe'],
			"dlv_quantidade_ppe"      	  => $produto_pedido_temp['dlv_quantidade_ppe'],
			"dlv_preco_ppe"     	 	  => $produto_pedido_temp['dlv_preco_ppe'],
			"dlv_observacao_ppe"      	  => $produto_pedido_temp['dlv_observacao_ppe'],
			"dlv_escolheproduto_ppe"      => $produto_pedido_temp['dlv_escolheproduto_ppe'],
			"dlv_quantidadeproduto_ppe"   => $produto_pedido_temp['dlv_quantidadeproduto_ppe'],
			"dlv_precomaiorproduto_ppe"   => $produto_pedido_temp['dlv_precomaiorproduto_ppe'],
			"dlv_exibirfracaoproduto_ppe" => $produto_pedido_temp['dlv_exibirfracaoproduto_ppe']				
		);
		
		$res = $this->db->insert('dlv_ppe', $produto_pedido);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}

	public function insertProdutoEscolhido($produto_escolhido) {
		$res = $this->db->insert('dlv_ppp', $produto_escolhido);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function insertAdicional($adicional) {
		$res = $this->db->insert('dlv_ppa', $adicional);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}

	public function getRecebido($dlv_id_ped) {
		$this->db->select('dlv_recebido_ped');
		$this->db->from('dlv_ped');
		$this->db->where('dlv_id_ped',$dlv_id_ped, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getQuantidadePedido($dlv_dlvemp_ped) {
		$this->db->select('COUNT(*) as quantidade');
		$this->db->from('dlv_ped');
		
		if ($dlv_dlvemp_ped != 1) {
			$this->db->where('dlv_dlvemp_ped',$dlv_dlvemp_ped, FALSE);
		}
		
		$this->db->where('dlv_recebido_ped', 0, FALSE);
		$this->db->where('dlv_cancelado_ped', 0, FALSE);
		return $this->db->get()->first_row();
	}

    public function getDetalhePedido($dlv_id_ped) {
        $this->db->from('dlv_ped');
        $this->db->join('dlv_emp', 'dlv_id_emp = dlv_dlvemp_ped', 'LEFT');
        $this->db->join('glo_end', 'glo_id_end = dlv_gloend_ped', 'LEFT');
        $this->db->join('glo_bai', 'glo_id_bai = glo_globai_end');
        $this->db->join('glo_cid', 'glo_id_cid = glo_glocid_bai');
        $this->db->join('glo_est', 'glo_id_est = glo_gloest_cid');
        $this->db->where('dlv_id_ped', $dlv_id_ped, FALSE);

        return $this->db->get()->first_row();
    }
	
	public function getPedidosCliente($dlv_dlvcli_ped, $opcao) {
		$this->db->from('dlv_ped');
		$this->db->join('dlv_emp', 'dlv_id_emp = dlv_dlvemp_ped', 'LEFT');
		$this->db->join('dlv_fpg', 'dlv_id_fpg = dlv_dlvfpg_ped', 'LEFT');
		$this->db->where('dlv_dlvcli_ped', $dlv_dlvcli_ped, FALSE);
		$this->db->order_by('dlv_datahora_ped DESC, dlv_id_ped DESC');
		
		if ($opcao == 0) {
			$this->db->limit(5, null);
		}
		return $this->db->get()->result();
	}
	
	public function getPedidosEmpresa($dlv_dlvemp_ped, $opcao_status, $data_inicial, $data_final) {
		$this->db->select('dlv_ped.*, dlv_emp.*, dlv_cli.*, glo_end.*, glo_bai.*, glo_cid.*, glo_est.*, 
				          (SELECT dlv_ordem_sta FROM dlv_spe
				           LEFT JOIN dlv_sta ON dlv_id_sta = dlv_dlvsta_spe 
				           WHERE dlv_dlvped_spe = dlv_ped.dlv_id_ped 
				           ORDER BY dlv_id_spe DESC LIMIT 1)AS ordem');
		$this->db->from('dlv_ped');
		$this->db->join('dlv_emp', 'dlv_id_emp = dlv_dlvemp_ped', 'LEFT');
		$this->db->join('dlv_cli', 'dlv_id_cli = dlv_dlvcli_ped', 'LEFT');
		$this->db->join('glo_end', 'glo_id_end = dlv_gloend_ped', 'LEFT');
		$this->db->join('glo_bai', 'glo_id_bai = glo_globai_end');
		$this->db->join('glo_cid', 'glo_id_cid = glo_glocid_bai');
		$this->db->join('glo_est', 'glo_id_est = glo_gloest_cid');
		
		if ($dlv_dlvemp_ped != 1) {
			$this->db->where('dlv_dlvemp_ped',$dlv_dlvemp_ped, FALSE);
		}
		
		$this->db->where('dlv_datahora_ped >= ', $data_inicial.' 00:00:00');
		$this->db->where('dlv_datahora_ped <= ', $data_final.' 23:59:59');
		
		if ($opcao_status == 0) {
			$this->db->where('dlv_recebido_ped', 0, FALSE);				
			$this->db->where('dlv_entregue_ped', 0, FALSE);				
			$this->db->where('dlv_cancelado_ped', 0, FALSE);				
		}
		
		if ($opcao_status == 1) {
			$this->db->where('dlv_recebido_ped', 1, FALSE);
			$this->db->where('dlv_entregue_ped', 0, FALSE);
			$this->db->where('dlv_cancelado_ped', 0, FALSE);
		}
		
		if ($opcao_status == 2) {
			$this->db->where('dlv_entregue_ped', 1, FALSE);
			$this->db->where('dlv_cancelado_ped', 0, FALSE);
		}
		
		if ($opcao_status == 3) {
			$this->db->where('dlv_cancelado_ped', 1, FALSE);
		}
		
		$this->db->order_by('dlv_datahora_ped DESC, dlv_id_ped DESC');		
		return $this->db->get()->result();
	}
	
	public function getValorTotalItens($dlv_id_ped) {
		$this->db->select('SUM(dlv_quantidade_ppe * dlv_preco_ppe) as valor_produtos');
		$this->db->from('dlv_ppe');
		$this->db->where('dlv_dlvped_ppe',$dlv_id_ped, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getPedidoEmpresaImprimir($dlv_dlvemp_ped) {
		$this->db->from('dlv_ped');
		$this->db->join('dlv_emp', 'dlv_id_emp = dlv_dlvemp_ped', 'LEFT');
		$this->db->join('dlv_cli', 'dlv_id_cli = dlv_dlvcli_ped', 'LEFT');
		$this->db->join('glo_end', 'glo_id_end = dlv_gloend_ped', 'LEFT');
		$this->db->join('glo_bai', 'glo_id_bai = glo_globai_end');
		$this->db->join('glo_cid', 'glo_id_cid = glo_glocid_bai');
		$this->db->join('glo_est', 'glo_id_est = glo_gloest_cid');
		$this->db->join('dlv_fpg', 'dlv_id_fpg = dlv_dlvfpg_ped', 'LEFT');
		$this->db->join('dlv_ent', 'dlv_id_ent = dlv_dlvent_ped', 'LEFT');
		$this->db->where('dlv_dlvemp_ped', $dlv_dlvemp_ped, FALSE);
		$this->db->where('(dlv_impbalcao_ped =', 1, FALSE);
		$this->db->or_where("dlv_impcozinha_ped = 1)", NULL, FALSE);		
		return $this->db->get()->result();
	}
	
	
}