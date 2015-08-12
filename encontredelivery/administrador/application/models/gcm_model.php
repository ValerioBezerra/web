<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GCM_Model extends CI_Model {
	public function insert($gcm) {
		$res = $this->db->insert('dlv_gcm', $gcm);
	
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($gcm, $dlv_id_gcm) {
		$this->db->where('dlv_id_gcm', $dlv_id_gcm, FALSE);
		$res = $this->db->update('dlv_gcm', $gcm);
	
		if ($res) {
			return $dlv_id_gcm;
		} else {
			return FALSE;
		}
	}
	
	public function getRegId($dlv_regid_gcm) {
		$this->db->from('dlv_gcm');
		$this->db->where('dlv_regid_gcm', $dlv_regid_gcm);
		return $this->db->get()->first_row();
	}	
	
	public function getTodos() {
		$this->db->from('dlv_gcm');
		$this->db->order_by('dlv_id_gcm');
		return $this->db->get()->result();
	}
	
	public function getTodosLimit($inicio_limit, $final_limit) {
		$this->db->from('dlv_gcm');
		$this->db->order_by('dlv_id_gcm');
		$this->db->limit($final_limit, $inicio_limit);
		return $this->db->get()->result();
	}
	
	public function getCliente($dlv_dlvcli_gcm) {
		$this->db->from('dlv_gcm');
		$this->db->where('dlv_dlvcli_gcm', $dlv_dlvcli_gcm, FALSE);
		return $this->db->get()->result();
	}
	
	public function send_notification($registatoin_ids, $message) {
		$url = 'https://android.googleapis.com/gcm/send';
	
		$fields = array(
			'registration_ids' => $registatoin_ids,
			'data'             => $message,
		);
	
		$headers = array(
			'Authorization: key='.GOOGLE_API_KEY,
			'Content-Type: application/json'
		);
	
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	
		$result = curl_exec($ch);
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
	
		curl_close($ch);
		sleep(10);
	}
	
	public function notificacao_mudanca_status($dlv_dlvped_spe, $dlv_dlvsta_spe) {
		$this->load->model('Pedido_Model', 'PedidoModel');
		$this->load->model('Status_Model', 'StatusModel');
		$this->load->model('Telefone_Model', 'TelefoneModel');
		
		$pedido = $this->PedidoModel->get($dlv_dlvped_spe);
		$status = $this->StatusModel->getStatus($dlv_dlvsta_spe); 
		
		$resultado = $this->getCliente($pedido->dlv_dlvcli_ped);
		
		$notificacoes = array();
		foreach ($resultado as $registro) {
			$notificacoes[] = $registro->dlv_regid_gcm;
		}
		
		$mensagem = 'Pedido Nro '.$pedido->dlv_id_ped.'. Novo status: '.$status->dlv_descricao_sta.'!';
		
		$resultado_fone  = $this->TelefoneModel->getTelefonesEmpresa($pedido->dlv_id_emp);
		$fone            = "";
		$separador_fone  = "";
		foreach ($resultado_fone as $registro_fone) {
			$fone            = $fone.$separador_fone.$registro_fone->dlv_fone_ext;
			$separador_fone  = " / ";
		}
			
		$this->GCMModel->send_notification($notificacoes, array("msg"         => $mensagem, 
				                                                "idPedido"    => $pedido->dlv_id_ped, 
																"nomeEmpresa" => $pedido->dlv_nome_emp, 
																"foneEmpresa" => $fone));	
	}	
}