<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa_Model extends CI_Model {
	
	public function get() {
		$this->db->from('dlv_emp');
		return $this->db->get()->result();
	}
	
	public function getEmpresa($dlv_id_emp) {
		$this->db->from('dlv_emp');
		$this->db->where('dlv_id_emp', $dlv_id_emp, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getEmpresaCpfCnpj($dlv_id_emp, $dlv_cpfcnpj_emp) {
		$this->db->from('dlv_emp');
		$this->db->where('dlv_id_emp !=', $dlv_id_emp, FALSE);
		$this->db->where('dlv_cpfcnpj_emp', $dlv_cpfcnpj_emp);
		return $this->db->get()->first_row();
	}
	
	public function getCpfCnpj($dlv_tipopessoa_emp, $dlv_cpfcnpj_emp) {
		$this->db->from('dlv_emp');
		$this->db->where('dlv_tipopessoa_emp', $dlv_tipopessoa_emp);
		$this->db->where('dlv_cpfcnpj_emp', $dlv_cpfcnpj_emp);
		return $this->db->get()->first_row();
	}
	
	public function getEmpresasResponsaveis($dlv_dlvres_emp) {
		$this->db->where('dlv_dlvres_emp', $dlv_dlvres_emp, FALSE);
		$this->db->from('dlv_emp');
		return $this->db->get()->result();
	}
	
	public function getSegmentos($dlv_id_emp) {
		$this->db->from('dlv_exs');
		$this->db->join('dlv_seg', 'dlv_id_seg = dlv_dlvseg_exs');
		$this->db->where('dlv_dlvemp_exs', $dlv_id_emp, FALSE);
		return $this->db->get()->result();
	}
	
	public function getEmpresaEndereco($dlv_nome_emp, $dlv_glocid_aen, $dlv_globai_ane, $where_segmentos) {
		$this->db->from('dlv_emp');
		$this->db->join('glo_end', 'glo_id_end = dlv_gloend_emp');
		$this->db->join('glo_bai', 'glo_id_bai = glo_globai_end');
		$this->db->join('glo_cid', 'glo_id_cid = glo_glocid_bai');
		$this->db->join('glo_est', 'glo_id_est = glo_gloest_cid');
		$this->db->where('dlv_ativo_emp', 1, FALSE);
		$this->db->like('dlv_nome_emp', $dlv_nome_emp);
		$this->db->where('EXISTS (SELECT * FROM dlv_aen
                          		  WHERE dlv_dlvemp_aen = dlv_emp.dlv_id_emp
                                    AND dlv_glocid_aen ='.$dlv_glocid_aen.')', null, FALSE);
		$this->db->where('NOT EXISTS (SELECT * FROM dlv_ane
                  					  WHERE dlv_dlvemp_ane = dlv_emp.dlv_id_emp
                    					AND dlv_globai_ane ='.$dlv_globai_ane.')', null, FALSE);
		
		if (!empty($where_segmentos)) {
			$this->db->where('EXISTS (SELECT * FROM dlv_exs
							          WHERE dlv_dlvemp_exs = dlv_emp.dlv_id_emp
                          		        AND '.$where_segmentos.')', null, FALSE);
				
		}
		$this->db->order_by('dlv_aberto_emp DESC, dlv_nome_emp');
		return $this->db->get()->result();
	}
	
	public function getQuantidadeCurtidas($dlv_id_emp) {
		$this->db->select('dlv_dlvemp_cxe');
		$this->db->from('dlv_cxe');
		$this->db->where('dlv_dlvemp_cxe', $dlv_id_emp, FALSE);
		$this->db->where('dlv_curtir_cxe', 1, FALSE);
		
		return $this->db->get()->result();
	}

	public function insert($empresa) {
		$res = $this->db->insert('dlv_emp', $empresa);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($empresa, $dlv_id_emp) {
		$this->db->where('dlv_id_emp', $dlv_id_emp, FALSE);
		$res = $this->db->update('dlv_emp', $empresa);
	
		if ($res) {
			return $dlv_id_emp;
		} else {
			return FALSE;
		}
	}
	
	public function delete($dlv_id_emp) {
		$this->db->where('dlv_id_emp', $dlv_id_emp, FALSE);
		return $this->db->delete('dlv_emp');
	}	
	
}