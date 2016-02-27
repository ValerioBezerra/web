<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa_Model extends CI_Model {
	
	public function get() {
		$this->db->from('bus_emp');
		return $this->db->get()->result();
	}
	
	public function getEmpresa($bus_id_emp) {
		$this->db->from('bus_emp');
		$this->db->where('bus_id_emp', $bus_id_emp, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getEmpresaCpfCnpj($bus_id_emp, $bus_cpfcnpj_emp) {
		$this->db->from('bus_emp');
		$this->db->where('bus_id_emp !=', $bus_id_emp, FALSE);
		$this->db->where('bus_cpfcnpj_emp', $bus_cpfcnpj_emp);
		return $this->db->get()->first_row();
	}
	
	public function getCpfCnpj($bus_tipopessoa_emp, $bus_cpfcnpj_emp) {
		$this->db->from('bus_emp');
		$this->db->where('bus_tipopessoa_emp', $bus_tipopessoa_emp);
		$this->db->where('bus_cpfcnpj_emp', $bus_cpfcnpj_emp);
		return $this->db->get()->first_row();
	}
	
	public function getEmpresasResponsabuss($bus_busres_emp) {
		$this->db->where('bus_busres_emp', $bus_busres_emp, FALSE);
		$this->db->from('bus_emp');
		return $this->db->get()->result();
	}
	
	public function getSegmentos($bus_id_emp) {
		$this->db->from('bus_exs');
		$this->db->join('bus_seg', 'bus_id_seg = bus_busseg_exs');
		$this->db->where('bus_busemp_exs', $bus_id_emp, FALSE);
		return $this->db->get()->result();
	}
	
	public function getEmpresaEndereco($bus_nome_emp, $bus_glocid_aen, $bus_globai_ane, $where_segmentos) {
		$this->db->from('bus_emp');
		$this->db->join('glo_end', 'glo_id_end = bus_gloend_emp');
		$this->db->join('glo_bai', 'glo_id_bai = glo_globai_end');
		$this->db->join('glo_cid', 'glo_id_cid = glo_glocid_bai');
		$this->db->join('glo_est', 'glo_id_est = glo_gloest_cid');
		$this->db->where('bus_ativo_emp', 1, FALSE);
		$this->db->like('bus_nome_emp', $bus_nome_emp);
		$this->db->where('EXISTS (SELECT * FROM bus_aen
                          		  WHERE bus_busemp_aen = bus_emp.bus_id_emp
                                    AND bus_glocid_aen ='.$bus_glocid_aen.')', null, FALSE);
		$this->db->where('NOT EXISTS (SELECT * FROM bus_ane
                  					  WHERE bus_busemp_ane = bus_emp.bus_id_emp
                    					AND bus_globai_ane ='.$bus_globai_ane.')', null, FALSE);
		
		if (!empty($where_segmentos)) {
			$this->db->where('EXISTS (SELECT * FROM bus_exs
							          WHERE bus_busemp_exs = bus_emp.bus_id_emp
                          		        AND '.$where_segmentos.')', null, FALSE);
				
		}
		$this->db->order_by('bus_aberto_emp DESC, bus_nome_emp');
		return $this->db->get()->result();
	}
	
	public function getQuantidadeCurtidas($bus_id_emp) {
		$this->db->select('bus_busemp_cxe');
		$this->db->from('bus_cxe');
		$this->db->where('bus_busemp_cxe', $bus_id_emp, FALSE);
		$this->db->where('bus_curtir_cxe', 1, FALSE);
		
		return $this->db->get()->result();
	}

	public function insert($empresa) {
		$res = $this->db->insert('bus_emp', $empresa);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($empresa, $bus_id_emp) {
		$this->db->where('bus_id_emp', $bus_id_emp, FALSE);
		$res = $this->db->update('bus_emp', $empresa);
	
		if ($res) {
			return $bus_id_emp;
		} else {
			return FALSE;
		}
	}
	
	public function delete($bus_id_emp) {
		$this->db->where('bus_id_emp', $bus_id_emp, FALSE);
		return $this->db->delete('bus_emp');
	}	
	
}