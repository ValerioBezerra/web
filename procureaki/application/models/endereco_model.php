<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Endereco_Model extends CI_Model {
	
	public function getEnderecoCompletoId($glo_id_end) {
		$this->db->from('glo_end');
		$this->db->join('glo_bai', 'glo_id_bai = glo_globai_end');
		$this->db->join('glo_cid', 'glo_id_cid = glo_glocid_bai');
		$this->db->join('glo_est', 'glo_id_est = glo_gloest_cid');
		$this->db->where('glo_id_end', $glo_id_end, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getEndereco($glo_cep_end) {
		$this->db->from('glo_end');
		$this->db->where('glo_cep_end', $glo_cep_end);
		return $this->db->get()->first_row();
	}
	
	public function getEnderecoCompleto($glo_cep_end) {
		$this->db->from('glo_end');
		$this->db->join('glo_bai', 'glo_id_bai = glo_globai_end');
		$this->db->join('glo_cid', 'glo_id_cid = glo_glocid_bai');
		$this->db->join('glo_est', 'glo_id_est = glo_gloest_cid');
		$this->db->where('glo_cep_end', $glo_cep_end);
		return $this->db->get()->first_row();
	}
	
	public function getEnderecoCompletoLogradouro($glo_glocid_bai, $glo_globai_end, $glo_logradouro_end) {
		$this->db->from('glo_end');
		$this->db->join('glo_bai', 'glo_id_bai = glo_globai_end');
		$this->db->join('glo_cid', 'glo_id_cid = glo_glocid_bai');
		$this->db->join('glo_est', 'glo_id_est = glo_gloest_cid');
		$this->db->where('glo_glocid_bai', $glo_glocid_bai, FALSE);
		$this->db->where('glo_globai_end', $glo_globai_end, FALSE);
		$this->db->like('glo_logradouro_end', $glo_logradouro_end);
		return $this->db->get()->result();
	}

	public function getEnderecoCompletoCliente($bus_buscli_ecl) {
		$this->db->from('bus_ecl');
		$this->db->join('glo_end', 'glo_id_end = bus_gloend_ecl');
		$this->db->join('glo_bai', 'glo_id_bai = glo_globai_end');
		$this->db->join('glo_cid', 'glo_id_cid = glo_glocid_bai');
		$this->db->join('glo_est', 'glo_id_est = glo_gloest_cid');
		$this->db->where('bus_buscli_ecl', $bus_buscli_ecl, FALSE);
		return $this->db->get()->result();
	}	
	
	public function getEstados() {
		$this->db->from('glo_est');
		$this->db->where('glo_glopai_est', 1, FALSE);
		$this->db->where('glo_visivel_est', 1, FALSE);
		$this->db->order_by('glo_nome_est');
		return $this->db->get()->result();
	}
	
	public function getCidades($glo_gloest_cid) {
		$this->db->from('glo_cid');
		$this->db->where('glo_gloest_cid', $glo_gloest_cid, FALSE);
		$this->db->where('glo_visivel_cid', 1, FALSE);
		$this->db->order_by('glo_nome_cid');
		return $this->db->get()->result();
	}
	
	public function getCidadesVisiveis() {
		$this->db->from('glo_cid');
		$this->db->join('glo_est', 'glo_id_est = glo_gloest_cid');
		$this->db->where('glo_visivel_cid', 1, FALSE);
		$this->db->order_by('glo_nome_cid');
		return $this->db->get()->result();
	}
	
	public function getCidade($glo_id_cid) {
		$this->db->from('glo_cid');
		$this->db->join('glo_est', 'glo_id_est = glo_gloest_cid');
		$this->db->where('glo_id_cid', $glo_id_cid, FALSE);
		return $this->db->get()->first_row();
	}
	
	public function getBairros($glo_glocid_bai) {
		$this->db->from('glo_bai');
		$this->db->where('glo_glocid_bai', $glo_glocid_bai, FALSE);
		$this->db->order_by('glo_nome_bai');
		return $this->db->get()->result();
	}
	
	public function getBairrosVisiveis($glo_glocid_bai) {
		$this->db->from('glo_bai');
		$this->db->where('glo_glocid_bai', $glo_glocid_bai, FALSE);
		$this->db->order_by('glo_nome_bai');
		return $this->db->get()->result();
	}
	
	public function getDistanciaTempoEnderecos($cep_origem, $cep_destino) {
		$dados = array();
		
		$dados["distancia"] = "";
		$dados["tempo"]     = "";
		
// 		$cep_origem  = mascara($cep_origem, MASCARA_CEP);
// 		$cep_destino = mascara($cep_destino, MASCARA_CEP);
		
// 		$json     = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=".$cep_origem."&destinations=".$cep_destino."&mode=driving&language=pt-BR&sensor=true");
// 		$jsonObj  = json_decode($json);
// 		$elements = $jsonObj->rows[0]->elements[0];

// 		if ($elements->status == "OK") {
// 			$dados["distancia"] = $elements->distance->text;
// 			$dados["tempo"]     = $elements->duration->text;
// 		} 
		
		return $dados;
	}
	
	public function insertEnderecoCliente($endereco_cliente) {
		$res = $this->db->insert('bus_ecl', $endereco_cliente);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}

    public function updateEnderecoCliente($endereco_cliente, $bus_id_ecl) {
        $this->db->where('bus_id_ecl', $bus_id_ecl, FALSE);
        $res = $this->db->update('bus_ecl', $endereco_cliente);

        if ($res) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    public function verificarEnderecoCliente($bus_buscli_ecl, $bus_gloend_ecl, $bus_numero_ecl) {
		$this->db->where('bus_buscli_ecl', $bus_buscli_ecl, FALSE);
		$this->db->where('bus_gloend_ecl', $bus_gloend_ecl, FALSE);
		$this->db->where('bus_numero_ecl', $bus_numero_ecl);
		$this->db->from('bus_ecl');
		return $this->db->get()->result();
	}
	
	public function deleteEnderecoCliente($bus_id_ecl) {
		$this->db->where('bus_id_ecl', $bus_id_ecl, FALSE);
		return $this->db->delete('bus_ecl');
	}
	
	
}