<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Endereco_Json extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Endereco_Model', 'EnderecoModel');
	}
	
	public function cadastrar_endereco_cliente($chave) {
		$json             = $this->input->post('json');
		$endereco_cliente = json_decode($json,true);
		
		$verificarEndereco = $this->EnderecoModel->verificarEnderecoCliente($endereco_cliente['dlv_dlvcli_ecl'],
																			$endereco_cliente['dlv_gloend_ecl'],
																			$endereco_cliente['dlv_numero_ecl']);
		
		$msgErros = "";
		$erros    = FALSE;
		
		if ($chave != CHAVE_MD5) {
			$msgErros .= "- Chave de acesso inválida.\n";
			$erros     = TRUE;
		} else {
			if ($verificarEndereco) {
				$erros = TRUE;
			}
		}	

		if (!$erros) {
			$dlv_id_ecl = $this->EnderecoModel->insertEnderecoCliente($endereco_cliente);
			
			if (is_numeric($dlv_id_ecl)) {
				echo "s".$dlv_id_ecl;
			} else {
				echo "n";
			}
		} else {
			echo $msgErros;
		}
	}

    public function alterar_endereco_cliente($chave) {
        $json             = $this->input->post('json');
        $endereco_cliente = json_decode($json,true);

        $msgErros = "";
        $erros    = FALSE;

        if ($chave != CHAVE_MD5) {
            $msgErros .= "- Chave de acesso inválida.\n";
            $erros     = TRUE;
        }

        if (!$erros) {
            $dlv_id_ecl = $this->EnderecoModel->updateEnderecoCliente($endereco_cliente, $endereco_cliente['dlv_id_ecl']);

            if (is_numeric($dlv_id_ecl)) {
                echo "s".$dlv_id_ecl;
            } else {
                echo "n";
            }
        } else {
            echo $msgErros;
        }
    }

	public function retornar_enderecos_gps($chave, $latitude, $longitude) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$json    = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=true");
			$jsonObj = json_decode($json);
			$results = $jsonObj->results;
				
			foreach ($results as $r) {
				foreach ($r->address_components as $a_c) {
						
					if ($a_c->types[0] == "postal_code") {
						$cep       = str_replace("-", null, $a_c->long_name);
						$resultado = $this->EnderecoModel->getEnderecoCompleto($cep);
	
						if ($resultado) {
							$dados[] = array(
								"glo_id_end"         => $resultado->glo_id_end,
								"glo_cep_end"        => $resultado->glo_cep_end,
								"glo_logradouro_end" => $resultado->glo_logradouro_end,
								"glo_latitude_end"   => $resultado->glo_latitude_end,
								"glo_latitude_end"   => $resultado->glo_latitude_end,
								"glo_id_bai"         => $resultado->glo_id_bai,
								"glo_nome_bai"       => $resultado->glo_nome_bai,
								"glo_id_cid"         => $resultado->glo_id_cid,
								"glo_nome_cid"       => $resultado->glo_nome_cid,
								"glo_uf_est"         => $resultado->glo_uf_est,
								"glo_uf_est"         => $resultado->glo_uf_est
							);
						}
					}
						
				}
			}
		}
	
		echo json_encode(array("enderecos" => $dados));
	}
	
	public function retornar_cidades_visiveis($chave) {
		$dados = array();
		
		if ($chave == CHAVE_MD5) {			
			$resultado = $this->EnderecoModel->getCidadesVisiveis();
	
			foreach ($resultado as $registro) {
				$dados[] = array(
					"glo_id_cid"   => $registro->glo_id_cid,	
					"glo_nome_cid" => $registro->glo_nome_cid,	
					"glo_uf_est"   => $registro->glo_uf_est	
				);
			}
		}
	
		echo json_encode(array("cidades" => $dados));
	}
	
	public function retornar_bairros_visiveis($chave, $glo_glocid_bai) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->EnderecoModel->getBairrosVisiveis($glo_glocid_bai);
	
			foreach ($resultado as $registro) {
				$dados[] = array(
					"glo_id_bai"     => $registro->glo_id_bai,
					"glo_nome_bai"   => $registro->glo_nome_bai
				);
			}
		}
	
		echo json_encode(array("bairros" => $dados));
	}	
	
	public function retornar_enderecos_logradouro($chave, $glo_glocid_bai, $glo_globai_end, $glo_logradouro_end) {
		$dados = array();
		
		$glo_logradouro_end = str_ireplace("+", " ", $glo_logradouro_end);
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->EnderecoModel->getEnderecoCompletoLogradouro($glo_glocid_bai, $glo_globai_end, $glo_logradouro_end);
	
			foreach ($resultado as $registro) {
				$dados[] = array(
					"glo_id_end"         => $registro->glo_id_end,
					"glo_cep_end"        => $registro->glo_cep_end,
					"glo_logradouro_end" => $registro->glo_logradouro_end,
					"glo_latitude_end"   => $registro->glo_latitude_end,
					"glo_longitude_end"  => $registro->glo_longitude_end,
					"glo_id_bai"         => $registro->glo_id_bai,
					"glo_nome_bai"       => $registro->glo_nome_bai,
					"glo_id_cid"         => $registro->glo_id_cid,
					"glo_nome_cid"       => $registro->glo_nome_cid,
					"glo_uf_est"         => $registro->glo_uf_est
				);
			}
		}
	
		echo json_encode(array("enderecos" => $dados));
	}	
	
	public function retornar_endereco_cep($chave, $cep) {
		$dados = array();
		$cep   = str_replace("-", null, $cep);
		
		if ($chave == CHAVE_MD5) {
			$resultado = $this->EnderecoModel->getEnderecoCompleto($cep);
			
			if ($resultado) {
				$dados['glo_id_end']         = $resultado->glo_id_end;
				$dados['glo_cep_end']        = $resultado->glo_cep_end;
				$dados['glo_logradouro_end'] = $resultado->glo_logradouro_end;
				$dados['glo_latitude_end']   = $resultado->glo_latitude_end;
				$dados['glo_longitude_end']  = $resultado->glo_longitude_end;
				$dados['glo_id_bai']         = $resultado->glo_id_bai;
				$dados['glo_nome_bai']       = $resultado->glo_nome_bai;
				$dados['glo_id_cid']         = $resultado->glo_id_cid;
				$dados['glo_nome_cid']       = $resultado->glo_nome_cid;
				$dados['glo_uf_est']         = $resultado->glo_uf_est;
			}
		}
	
		echo json_encode($dados);
	}

	public function retornar_enderecos_cliente($chave, $dlv_dlvcli_ecl) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->EnderecoModel->getEnderecoCompletoCliente($dlv_dlvcli_ecl);
	
			foreach ($resultado as $registro) {
				$dados[] = array(
					"glo_id_end"          => $registro->glo_id_end,
					"glo_cep_end"         => $registro->glo_cep_end,
					"glo_logradouro_end"  => $registro->glo_logradouro_end,
					"glo_id_bai"          => $registro->glo_id_bai,
					"glo_nome_bai"        => $registro->glo_nome_bai,
					"glo_id_cid"          => $registro->glo_id_cid,
					"glo_nome_cid"        => $registro->glo_nome_cid,
					"glo_uf_est"          => $registro->glo_uf_est,
					"dlv_numero_ecl"	  => $registro->dlv_numero_ecl,
					"dlv_complemento_ecl" => $registro->dlv_complemento_ecl,
					"dlv_id_ecl"          => $registro->dlv_id_ecl
				);
			}
		}
	
		echo json_encode(array("enderecos" => $dados));
	}

	public function retornar_distancia_tempo_enderecos($chave, $cep_origem, $cep_destino) {
		$dados = array();
	
		if ($chave != CHAVE_MD5) {
			$json     = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=".$cep_origem."&destinations=".$cep_destino."&mode=driving&language=pt-BR&sensor=true");
			$jsonObj  = json_decode($json);
			$elements = $jsonObj->rows[0]->elements[0];
			
			if ($elements->status == "OK") {
				$dados[] = array(
					"distancia" => $elements->distance->text,
					"tempo"     => $elements->duration->text	
				);
			} else {
				$dados[] = array(
					"distancia" => "",
					"tempo"     => ""	
				);
			}
		}
		
		echo json_encode($dados);
	}

	public function apagar_endereco_cliente($chave) {
		$json             = $this->input->post('json');
		$endereco_cliente = json_decode($json,true);
		
		$msgErros = "";
		$erros    = FALSE;
		
		if ($chave != CHAVE_MD5) {
			$msgErros .= "- Chave de acesso inválida.\n";
			$erros     = TRUE;
		} 
		
		if (!$erros) {
			$res = $this->EnderecoModel->deleteEnderecoCliente($endereco_cliente['dlv_id_ecl']);
				
			if ($res) {
				echo "s";
			} else {
				echo "n";
			}
		} else {
			echo $msgErros;
		}		
	}
	
}