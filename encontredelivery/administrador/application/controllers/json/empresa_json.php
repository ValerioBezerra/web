<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Empresa_Model', 'EmpresaModel');
		$this->load->model('Endereco_Model', 'EnderecoModel');
		$this->load->model('Empresa_Segmento_Model', 'EmpresaSegmentoModel');
		$this->load->model('Telefone_Model', 'TelefoneModel');
		$this->load->model('Taxa_Bairro_Model', 'TaxaBairroModel');
	}
	
	public function retornar_empresas_endereco($chave, $cep_cliente, $dlv_glocid_aen, $dlv_globai_ane, $segmentos, $dlv_nome_emp = NULL) {
		$dados = array();
		
		if ($chave == CHAVE_MD5) {
			$where_segmentos = "";
			if ($segmentos != "+") {
				$array_segmentos = explode("+", $segmentos);
				$where_segmentos = "(";
				$separador       = "";
				foreach ($array_segmentos as $registro) {
					$where_segmentos = $where_segmentos.$separador."dlv_dlvseg_exs = ".$registro;
					$separador       = " OR ";
				}
				$where_segmentos = $where_segmentos.")";
			}
				
			$resultado = $this->EmpresaModel->getEmpresaEndereco($dlv_nome_emp, $dlv_glocid_aen, $dlv_globai_ane, $where_segmentos);
	
			foreach ($resultado as $registro) {
				$tempoDistancia  = $this->EnderecoModel->getDistanciaTempoEnderecos($registro->glo_cep_end, $cep_cliente);

                $fones = array();
                $resultado_fones = $this->TelefoneModel->getTelefonesEmpresa($registro->dlv_id_emp);
                foreach ($resultado_fones as $registro_fone) {
                    $fones[] = array(
                        "dlv_fone_ext" => $registro_fone->dlv_fone_ext
                    );
                }

				$taxa_entrega   = $registro->dlv_taxaentrega_emp;
				$resultado_taxa = $this->TaxaBairroModel->getEmpresaTaxaBairro($dlv_globai_ane, $registro->dlv_id_emp);
				if (!empty($resultado_taxa)) {
					$taxa_entrega = $resultado_taxa->dlv_taxaentrega_txb;
				}
				
				$hora        = date("H", strtotime($registro->dlv_tempomedio_emp));
				$minuto      = date("i", strtotime($registro->dlv_tempomedio_emp));
				$tempo_medio = "";
					
				if ($hora != 0) {
					$tempo_medio = $tempo_medio.$hora.'h';
				}
					
				if ($minuto != 0) {
					$tempo_medio = $tempo_medio.$minuto.'min.';
				}				

				$dados[] = array(
					"dlv_id_emp"           => $registro->dlv_id_emp,
					"dlv_nome_emp"         => $registro->dlv_nome_emp,
					"dlv_detalhamento_emp" => $registro->dlv_detalhamento_emp,
					"dlv_taxaentrega_emp"  => $taxa_entrega,
					"dlv_valorminimo_emp"  => $registro->dlv_valorminimo_emp,
					"dlv_tempomedio_emp"   => $tempo_medio,
					"fones"                => $fones,
					"dlv_aberto_emp"       => $registro->dlv_aberto_emp,
					"glo_id_end"           => $registro->glo_id_end,
					"glo_cep_end"          => $registro->glo_cep_end,
					"glo_logradouro_end"   => $registro->glo_logradouro_end,
					"glo_id_bai"           => $registro->glo_id_bai,
					"glo_nome_bai"         => $registro->glo_nome_bai,
					"glo_id_cid"           => $registro->glo_id_cid,
					"glo_nome_cid"         => $registro->glo_nome_cid,
					"glo_uf_est"           => $registro->glo_uf_est,
					"dlv_numero_emp"	   => $registro->dlv_numero_emp,
					"dlv_complemento_emp"  => $registro->dlv_complemento_emp,
					"distancia_enderecos"  => $tempoDistancia["distancia"],							
					"tempo_enderecos"      => $tempoDistancia["tempo"],	
					"segmentos"            => array("lista" => $this->EmpresaSegmentoModel->get($registro->dlv_id_emp)),
					"quantidade_curtidas"  => count($this->EmpresaModel->getQuantidadeCurtidas($registro->dlv_id_emp)),
					"url_imagem"           => base_url('assets/images/empresas/'.$registro->dlv_id_emp.".png")
				);
			}
		}
	
		echo json_encode(array("empresas" => $dados));
	}

    public function verificar_empresas($chave) {
        $dados['resposta'] = false;

        if ($chave == CHAVE_MD5) {
            $this->db->query("CALL sp_dlv_emp_verificar()")->result();
            $dados['resposta'] = true;
        }

        echo json_encode($dados);
    }

}