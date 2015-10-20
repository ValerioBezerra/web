<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedido_JSON extends CI_Controller {
	
	public function __construct() {
		parent::__construct();

        $this->load->model('Empresa_Model', 'EmpresaModel');
		$this->load->model('Pedido_Model', 'PedidoModel');
		$this->load->model('Configuracao_Model', 'ConfiguracaoModel');
		$this->load->model('Status_Model', 'StatusModel');
		$this->load->model('Produto_Model', 'ProdutoModel');
		$this->load->model('Adicional_Model', 'AdicionalModel');
		$this->load->model('Telefone_Model', 'TelefoneModel');
	}

	public function enviar($chave) {
		$json        = $this->input->post('json');
		$pedido_temp = json_decode($json,true);
//		date_default_timezone_set("Brazil/East");
		date_default_timezone_set("America/Araguaina");
		
		$dlv_dlvvou_ped = null;
		if (array_key_exists("dlv_dlvvou_ped", $pedido_temp)) {
			$dlv_dlvvou_ped = $pedido_temp['dlv_dlvvou_ped'];
		}
		
		$pedido = array(
			"dlv_dlvemp_ped"      => $pedido_temp['dlv_dlvemp_ped'], 
			"dlv_datahora_ped"    => date('Y-m-d H:i:s'), 
			"dlv_dlvcli_ped"      => $pedido_temp['dlv_dlvcli_ped'], 
			"dlv_gloend_ped"      => $pedido_temp['dlv_gloend_ped'], 
			"dlv_numero_ped"      => $pedido_temp['dlv_numero_ped'], 
			"dlv_complemento_ped" => $pedido_temp['dlv_complemento_ped'], 
			"dlv_taxaentrega_ped" => $pedido_temp['dlv_taxaentrega_ped'], 
			"dlv_desconto_ped"    => $pedido_temp['dlv_desconto_ped'], 
			"dlv_dlvfpg_ped"      => $pedido_temp['dlv_dlvfpg_ped'], 
			"dlv_troco_ped"       => $pedido_temp['dlv_troco_ped'], 
			"dlv_origem_ped"      => $pedido_temp['dlv_origem_ped'], 
			"dlv_recebido_ped"    => 0,
			"dlv_entregue_ped"    => 0,
			"dlv_cancelado_ped"   => 0,
			"dlv_impbalcao_ped"   => 0,
			"dlv_impcozinha_ped"  => 0,
			"dlv_dlvvou_ped"      => $dlv_dlvvou_ped
		);
		
		$produtos_pedido_array = $pedido_temp['dlv_ppe'];
		
		$msgErros = "";
		$erros    = FALSE;
		
		if ($chave != CHAVE_MD5) {
			$msgErros .= "- Chave de acesso inválida.\n";
			$erros     = TRUE;
		}

        $empresa = $this->EmpresaModel->getEmpresa($pedido_temp['dlv_dlvemp_ped']);
        if (($empresa) && ($empresa->dlv_aberto_emp == 0)) {
            $msgErros .= "- Infelizmente o restaurante fechou enquanto você montava seu pedido.\n";
            $erros     = TRUE;
        }

		if (!$erros) {
			$dlv_id_ped = $this->PedidoModel->insert($pedido, $produtos_pedido_array);
			
			if (is_numeric($dlv_id_ped)) {
				echo "s".$dlv_id_ped;
			} else {
				echo "n";
			}
		} else {
			echo $msgErros;
		}
	}
	
	public function verificar_recebimento($chave, $dlv_id_ped) {
		$dados = array();
		
		$dados = $this->PedidoModel->getRecebido($dlv_id_ped);
		
		echo json_encode($dados);
	}
	
	public function cancelar_automatico($chave) {
		$json                = $this->input->post('json');
		$dlv_id_ped          = json_decode($json,true);
		$pedido              = $this->PedidoModel->getRecebido($dlv_id_ped['dlv_id_ped']);
		$configuracao        = $this->ConfiguracaoModel->get();
		date_default_timezone_set("America/Araguaina");
//		date_default_timezone_set("Brazil/East");
		
		if ($pedido->dlv_recebido_ped == 0) {
			$status_pedido = array(
				"dlv_dlvped_spe"      => $dlv_id_ped['dlv_id_ped'],
				"dlv_dlvsta_spe"      => $configuracao->dlv_dlvstacanc_cfg,
				"dlv_motivocanc_spe"  => $configuracao->dlv_motivocanc_cfg,
				"dlv_datahoramod_spe" => date('Y-m-d H:i:s')
			);
			
			$dlv_id_spe = $this->StatusModel->insertStatusPedido($status_pedido);
			
			if (is_numeric($dlv_id_spe)) {
				echo "s";
			} else {
				echo "n";
			}
		} else {
			echo "r";
		}		
	}
	
	public function retornar_quantidade_pedido($dlv_dlvemp_ped) {
		$dados = array();
	
		$dados = $this->PedidoModel->getQuantidadePedido($dlv_dlvemp_ped);;
	
		echo json_encode($dados);
	}
	
	public function retornar_pedidos_cliente($chave, $dlv_dlvcli_ped, $opcao) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->PedidoModel->getPedidosCliente($dlv_dlvcli_ped, $opcao);
				
			foreach ($resultado as $registro) {
				$status          = $this->StatusModel->getUltimoStatusPedido($registro->dlv_id_ped);
				$produtos_pedido = $this->ProdutoModel->getProdutosPedido($registro->dlv_id_ped);
				
				
				$descricao_produtos = "";
				$preco_produtos     = "";
				$separador          = "";
				$valor_total        = 0;
				
				foreach ($produtos_pedido as $produto_pedido) {
					$separador_preco    = "";
					$descricao_produtos = $descricao_produtos.$separador.$produto_pedido->dlv_quantidade_ppe."x ".substr($produto_pedido->dlv_descricao_pro, 0, 24);
					$preco_aplicado     = $produto_pedido->dlv_preco_ppe;
					
					if (!is_null($produto_pedido->dlv_descricao_tam)) {
						$descricao_produtos = $descricao_produtos."\nTamanho: ".$produto_pedido->dlv_descricao_tam;
						$separador_preco    = $separador_preco."\n";
					}
					
					$produtos_produtos = $this->ProdutoModel->getProdutosProdutoPedido($produto_pedido->dlv_id_ppe);
					foreach ($produtos_produtos as $produto_produtos) {
						if ($produto_pedido->dlv_exibirfracaoproduto_ppe == 0) {
							if ($produto_pedido->dlv_quantidadeproduto_ppe == 1) {
								$descricao_produtos = $descricao_produtos."\n • ".substr($produto_produtos->dlv_descricao_pro, 0, 23);
							} else {
								$descricao_produtos = $descricao_produtos."\n • ".$produto_produtos->dlv_quantidade_ppp."x ".substr($produto_produtos->dlv_descricao_pro, 0, 21);
							}
						} else {
							if ($produto_pedido->dlv_opcao_ppe != 3) {
								$descricao_produtos = $descricao_produtos."\n • 1";
							} else {
								if ($produto_produtos->dlv_opcao_ppp == 1) {
									$descricao_produtos = $descricao_produtos."\n • 2";
								} else {
									$descricao_produtos = $descricao_produtos."\n • 1";
								}
							}
							
							if ($produto_pedido->dlv_opcao_ppe == 2) {
								$descricao_produtos = $descricao_produtos."/2";
							} else if ($produto_pedido->dlv_opcao_ppe >= 3) {
								$descricao_produtos = $descricao_produtos."/4";								
							}
							
							$descricao_produtos = $descricao_produtos." ".substr($produto_produtos->dlv_descricao_pro, 0, 18);
						}
						
						$separador_preco = $separador_preco."\n";
					}
					
					$adicionais_produtos = $this->AdicionalModel->getAdicionaisProdutoPedido($produto_pedido->dlv_id_ppe);
					foreach ($adicionais_produtos as $adicional_produtos) {
						$descricao_produtos = $descricao_produtos."\n+".substr($adicional_produtos->dlv_descricao_adi, 0, 22);
						$preco_aplicado     = $preco_aplicado + $adicional_produtos->dlv_valor_ppa;
						$separador_preco    = $separador_preco."\n";
					}
						
					
					$preco_aplicado     = $preco_aplicado * $produto_pedido->dlv_quantidade_ppe;
					$preco_produtos     = $preco_produtos.$separador."R$ ".number_format($preco_aplicado, 2, ",", "").$separador_preco; 
					$separador          = "\n";
					$valor_total        = $valor_total + $preco_aplicado;
				}
				
                $fones = array();
                $resultado_fones = $this->TelefoneModel->getTelefonesEmpresa($registro->dlv_id_emp);
                foreach ($resultado_fones as $registro_fone) {
                    $fones[] = array(
                        "dlv_fone_ext" => $registro_fone->dlv_fone_ext
                    );
                }
				
				$dados[] = array(
						"dlv_id_ped"          => $registro->dlv_id_ped,
						"status"              => $status,
						"dlv_nome_emp"        => $registro->dlv_nome_emp,
						"fones"               => $fones,
						"descricao_produtos"  => $descricao_produtos,
						"preco_produtos"      => $preco_produtos,
						"dlv_desconto_ped"    => $registro->dlv_desconto_ped,
						"dlv_taxaentrega_ped" => $registro->dlv_taxaentrega_ped,
						"dlv_descricao_fpg"   => $registro->dlv_descricao_fpg,
						"valor_total"         => $valor_total
				);
			}
		}
	
		echo json_encode(array("pedidos" => $dados));
	}

    public function retornar_detalhes_pedido($chave, $dlv_id_ped) {
        $dados = array();

        if ($chave == CHAVE_MD5) {
            $resultado = $this->PedidoModel->getDetalhePedido($dlv_id_ped);

            if ($resultado) {
                $fones = array();
                $resultado_fones = $this->TelefoneModel->getTelefonesEmpresa($resultado->dlv_id_emp);
                foreach ($resultado_fones as $registro) {
                    $fones[] = array(
                        "dlv_fone_ext" => $registro->dlv_fone_ext
                    );
                }

                $status = array();
                $resultado_status = $this->StatusModel->getStatusPedido($dlv_id_ped);
                foreach ($resultado_status as $registro) {
                    $status[] = array(
                        "dlv_descricao_sta"   => $registro->dlv_descricao_sta,
                        "dlv_indicador_sta"   => $registro->dlv_indicador_sta,
                        "dlv_datahoramod_spe" => date('d/m/Y - H:i', strtotime($registro->dlv_datahoramod_spe)),
                        "dlv_motivocanc_spe"  => $registro->dlv_motivocanc_spe,
                    );
                }

                $dados["dlv_data_ped"]        = date('d/m/Y', strtotime($resultado->dlv_datahora_ped));
                $dados["dlv_hora_ped"]        = date('H:i', strtotime($resultado->dlv_datahora_ped));
                $dados["dlv_nome_emp"]        = $resultado->dlv_nome_emp;
                $dados["glo_cep_end"]         = $resultado->glo_cep_end;
                $dados["glo_logradouro_end"]  = $resultado->glo_logradouro_end;
                $dados["dlv_numero_ped"]	  = $resultado->dlv_numero_ped;
                $dados["glo_nome_bai"]        = $resultado->glo_nome_bai;
                $dados["glo_nome_cid"]        = $resultado->glo_nome_cid;
                $dados["glo_uf_est"]          = $resultado->glo_uf_est;
                $dados["dlv_complemento_ped"] = $resultado->dlv_complemento_ped;
                $dados["fones"] = $fones;
                $dados["status"] = $status;

            }
        }

        echo json_encode($dados);
    }

	
	public function retornar_status_pedido($chave, $dlv_dlvped_spe) {
		$dados = array();
	
		if ($chave == CHAVE_MD5) {
			$resultado = $this->StatusModel->getStatusPedido($dlv_dlvped_spe);
	
			foreach ($resultado as $registro) {	
				$dados[] = array(
					"dlv_descricao_sta"   => $registro->dlv_descricao_sta,
					"dlv_indicador_sta"   => $registro->dlv_indicador_sta,
					"dlv_datahoramod_spe" => date('d/m/Y - H:i', strtotime($registro->dlv_datahoramod_spe)),
					"dlv_motivocanc_spe"  => $registro->dlv_motivocanc_spe,
				);				
			}
		}
		
		echo json_encode(array("status" => $dados));
	}

	public function retornar_pedidos_json($chave, $opcao_status, $data_inicial, $data_final) {
		$dados = array();

		if ($chave == CHAVE_MD5) {
			$resultado = $this->PedidoModel->getPedidosEmpresa(1, $opcao_status, $data_inicial, $data_final);

			foreach ($resultado as $registro) {
				$status = $this->StatusModel->getUltimoStatusPedido($registro->dlv_id_ped);

				$status_descricao = "";
				if (!empty($status))
					$status_descricao = $status->dlv_descricao_sta;

				$dados[] = array(
					"dlv_nome_emp" => $registro->dlv_nome_emp,
					"dlv_data_ped" => date('d/m/Y', strtotime($registro->dlv_datahora_ped)),
					"dlv_hora_ped" => date('H:i:s', strtotime($registro->dlv_datahora_ped)),
					"dlv_nome_cli" => $registro->dlv_nome_cli,
					"status"       => $status_descricao,
				);
			}
		}

		echo json_encode(array("pedidos" => $dados));
	}

	public function retornar_pedidos_empresa($dlv_dlvemp_ped, $opcao_status, $data_inicial, $data_final) {		
		$resultado = $this->PedidoModel->getPedidosEmpresa($dlv_dlvemp_ped, $opcao_status, $data_inicial, $data_final);
		
		foreach ($resultado as $registro) {	
			$status_antigo = $this->StatusModel->getUltimoStatusPedido($registro->dlv_id_ped);
			
			$status_descricao = "";
			$status_ordem     = 1;
			$exibir           = TRUE;
			if (!empty($status_antigo)) {
				$status_descricao = $status_antigo->dlv_descricao_sta;
				$status_ordem     =	$status_antigo->dlv_ordem_sta + 1;
				$exibir           = (($status_antigo->dlv_indicador_sta == 0) or ($status_antigo->dlv_indicador_sta == 1));
			}
			
			$status = '';
			
			$novo_status = $this->StatusModel->getStatusOrdemAtivoNaoCancelado($status_ordem);
			
			if ($exibir) {
				$status = '<a onclick="abrirConfirmacaoModificarStatus('.$registro->dlv_id_ped.','.$novo_status->dlv_id_sta.')" class="btn btn-success btn-xs"">'.$novo_status->dlv_descricao_sta.'</a>';
				
				if ($status_ordem > 1) {
					$status_cancelado = $this->StatusModel->getStatusCanceladoAtivo();
					$status .= ' <a onclick="abrirCancelarPedido('.$registro->dlv_id_ped.','.$status_cancelado->dlv_id_sta.')" class="btn btn-danger btn-xs"">'.$status_cancelado->dlv_descricao_sta.'</a>';
				}
			} 
			
			$display_kingsoft_emp = '';
			$display_kingsoft     = '';
			$link_entregador      = '';
			$display_kingsoft_imp = '';
			$endereco             = '('.mascara($registro->glo_cep_end, MASCARA_CEP).') '.$registro->glo_logradouro_end.', Nro: '.$registro->dlv_numero_ped.'\n'.
			                      $registro->glo_nome_bai.'. '.$registro->glo_nome_cid.' - '.$registro->glo_uf_est.'\n'.
			                      'Ref: '.$registro->dlv_complemento_ped;
			
			$dlv_dlvent_ped = 0;
			if (!is_null($registro->dlv_dlvent_ped)) {
				$dlv_dlvent_ped = $registro->dlv_dlvent_ped;
			}
			
			if ($this->session->userdata('dlv_id_emp') == 1) {
				$display_kingsoft_emp = '<td>'.$registro->dlv_nome_emp.'</td>';
			} else {
				$display_kingsoft     = '<td><center>'.$status.'</center></td>		            		
		                                 <td class="text-center"><a target="_blank" href="'.site_url('pedido/detalhe/'.base64_encode($registro->dlv_id_ped)).'" class="btn-link" title="Ver pedido"><i class="glyphicon glyphicon-eye-open"></i></a></td>';
				$display_kingsoft_imp = '<td class="text-center"><a onclick="abrirImprimirPedido('.$registro->dlv_id_ped.')" class="btn-link" title="Imprimir" target="_blank"><i class="glyphicon glyphicon-print"></i></a></td>';
			}
			
			if ($this->session->userdata('dlv_controlaentregador_emp') == 1) {
				$link_entregador = '<td class="text-center"><a onclick="abrirSelecionarEntregador('.$registro->dlv_id_ped.','.$dlv_dlvent_ped.',\''.$endereco.'\')" class="btn-link" title="Entregador" target="_blank"><i class="fa fa-motorcycle"></i></a></td>';
			}
				
			
			echo '<tr>'.
					$display_kingsoft_emp.'
					<td class="text-right">'.$registro->dlv_id_ped.'</td>
	             	<td class="text-center">'.date('d/m/Y', strtotime($registro->dlv_datahora_ped)).'</td>
					<td class="text-center">'.date('H:i:s', strtotime($registro->dlv_datahora_ped)).'</td>
		            <td>'.$registro->dlv_nome_cli.'</td>
					<td><center>'.$status_descricao.'</center></td>'.	
					$display_kingsoft.
		            $link_entregador.
		            $display_kingsoft_imp.'		            
		            </tr>';
		}
		
	}

	public function modificar_status($chave, $id_usuario, $id_pedido, $id_status, $motivo = NULL) {
		date_default_timezone_set("America/Araguaina");
//		date_default_timezone_set("Brazil/East");
		
		if ($chave == CHAVE_MD5) {
			if (is_null($motivo)) {
				$motivo = '';
			} else {
				$motivo = str_replace("%20", " ", $motivo);
			}
			$status_pedido = array(
					"dlv_dlvped_spe"	  => $id_pedido,
					"dlv_dlvsta_spe"      => $id_status,
					"dlv_motivocanc_spe"  => $motivo,
					"dlv_dlvusumod_spe"   => $id_usuario,
					"dlv_datahoramod_spe" => date('Y-m-d H:i:s')
			);
			
			$dlv_id_spe = $this->StatusModel->insertStatusPedido($status_pedido);
			
			echo json_encode($dlv_id_spe);
		}
	}
	
	public function imprimir_pedido($chave, $id_pedido, $opcao) {
		if ($chave == CHAVE_MD5) {
			$dlv_impbalcao_ped  = 0;
			$dlv_impcozinha_ped = 0;
			
			if ($opcao == 0) {
				$dlv_impbalcao_ped  = 1;
				$dlv_impcozinha_ped = 1;
			} else if ($opcao == 1) {
				$dlv_impbalcao_ped  = 1;
			} else {
				$dlv_impcozinha_ped = 1;
			}
			
			$pedido = array(
				"dlv_impbalcao_ped"  => $dlv_impbalcao_ped,
				"dlv_impcozinha_ped" => $dlv_impcozinha_ped
			);
			
			$dlv_id_ped = $this->PedidoModel->update($pedido, $id_pedido);
				
			echo json_encode($dlv_id_ped);
		}
	}
	
	public function retonar_pedidos_impressora($chave, $dlv_dlvemp_ped, $opcao = NULL) {
		$dados = array();
		
		if ($chave == CHAVE_MD5) {
			$resultado = $this->PedidoModel->getPedidoEmpresaImprimir($dlv_dlvemp_ped);
				
			foreach ($resultado as $registro) {
				$cabecalho_ed  = $this->gerarCaracteresDireita(43, 'Encontre Delivery Nro '.$registro->dlv_id_ped, '%20').'||';
				$cabecalho_ed .= $this->gerarCaracteresDireita(43, $registro->dlv_nome_emp, '%20').'|';
				
				if (!empty($registro->dlv_detalhamento_emp)) {
					$cabecalho_ed .= $this->gerarCaracteresDireita(43, $registro->dlv_detalhamento_emp, '%20').'|';
				}
				
				$cabecalho_ed .= strtoupper('Data:%20').date('d/m/Y', strtotime($registro->dlv_datahora_ped)).strtoupper('%20%20Hora:%20').date('H:i:s', strtotime($registro->dlv_datahora_ped)).'|';
				$cabecalho_ed .= $this->gerarCaracteresDireita(43, 'Entregador: '.$registro->dlv_nome_ent, '%20').'||';
				
				if ($registro->dlv_cancelado_ped == 1) {
					$cabecalho_ed .= $this->gerarCaracteresDireita(10, '', '%20').strtoupper('*** Pedido Cancelado ***').'|';
				}
				
				$cabecalho  = $this->gerarCaracteresDireita(43, '', '-').'|';
				$cabecalho .= $this->gerarCaracteresDireita(18, '', '%20').strtoupper('Cliente').'|';
				$cabecalho .= $this->gerarCaracteresDireita(43, '', '-').'|';
				$cabecalho .= $this->gerarCaracteresDireita(43, $registro->dlv_nome_cli, '%20').'|';
				$cabecalho .= $this->gerarCaracteresDireita(43, '('.mascara($registro->glo_cep_end, MASCARA_CEP).') '.$registro->glo_logradouro_end, '%20').'|';
				$cabecalho .= $this->gerarCaracteresDireita(43, 'Nro: '.$registro->dlv_numero_ped.' '.$registro->glo_nome_bai, '%20').'|';
				$cabecalho .= $this->gerarCaracteresDireita(43, $registro->glo_nome_cid.' - '.$registro->glo_uf_est, '%20').'|';
				$cabecalho .= $this->gerarCaracteresDireita(43, 'Ref: '.$registro->dlv_complemento_ped, '%20').'|';
				$cabecalho .= $this->gerarCaracteresDireita(43, 'Fone: '.$registro->dlv_fone_cli, '%20').'|';
				
				$produtos  = $this->gerarCaracteresDireita(43, '', '-').'|';
				$produtos .= $this->gerarCaracteresDireita(18, '', '%20').strtoupper('Produtos').'|';
				$produtos .= $this->gerarCaracteresDireita(43, '', '-').'|';
				$produtos .= $this->gerarCaracteresDireita(4, 'QTD', '%20').$this->gerarCaracteresDireita(25, 'DESCRICAO', '%20').$this->gerarCaracteresEsquerda(6, 'UNIT', '%20').$this->gerarCaracteresEsquerda(8, 'TOTAL', '%20').'|';
				
				$produtos_pedido = $this->ProdutoModel->getProdutosPedido($registro->dlv_id_ped);
				
				$valor_total = 0;
				foreach ($produtos_pedido as $produto_pedido) {
					$complemento_produto = '';
					$preco_aplicado      = $produto_pedido->dlv_preco_ppe;
						
					if (!is_null($produto_pedido->dlv_descricao_tam)) {
						$complemento_produto .= $this->gerarCaracteresDireita(4, '', '%20').$this->gerarCaracteresDireita(39, strtoupper('Tamanho: '.$produto_pedido->dlv_descricao_tam), '%20').'|';
					}
					
					$produtos_produtos = $this->ProdutoModel->getProdutosProdutoPedido($produto_pedido->dlv_id_ppe);
					foreach ($produtos_produtos as $produto_produtos) {
						$descricao_produto   = '';
						if ($produto_pedido->dlv_exibirfracaoproduto_ppe == 0) {
							if ($produto_pedido->dlv_quantidadeproduto_ppe == 1) {
								$descricao_produto .= '>'.$produto_produtos->dlv_descricao_pro;
							} else {
								$descricao_produto .= '>'.$produto_produtos->dlv_quantidade_ppp.'x '.$produto_produtos->dlv_descricao_pro;
							}
						} else {
							if ($produto_pedido->dlv_opcao_ppe != 3) {
								$descricao_produto .= '>'.'1';
							} else {
								if ($produto_produtos->dlv_opcao_ppp == 1) {
									$descricao_produto .= '>'.'2';
								} else {
									$descricao_produto .= '>'.'1';
								}
							}
								
							if ($produto_pedido->dlv_opcao_ppe == 2) {
								$descricao_produto .= '/2';
							} else if ($produto_pedido->dlv_opcao_ppe >= 3) {
								$descricao_produto .= '/4';
							}
								
							$descricao_produto .= '%20'.$produto_produtos->dlv_descricao_pro;
						}
						
						$complemento_produto .= $this->gerarCaracteresDireita(4, '', '%20').$this->gerarCaracteresDireita(39, strtoupper($descricao_produto), '%20').'|';
					}
						
					$adicionais_produtos = $this->AdicionalModel->getAdicionaisProdutoPedido($produto_pedido->dlv_id_ppe);
					foreach ($adicionais_produtos as $adicional_produtos) {
						$complemento_produto .= $this->gerarCaracteresDireita(4, '', '%20').'+'.$this->gerarCaracteresDireita(39, strtoupper($adicional_produtos->dlv_descricao_adi), '%20').'|';
						$preco_aplicado     = $preco_aplicado + $adicional_produtos->dlv_valor_ppa;
					}
					
					$valor_total = $valor_total + ($produto_pedido->dlv_quantidade_ppe * $preco_aplicado);
					
					$produtos .= $this->gerarCaracteresEsquerda(3, $produto_pedido->dlv_quantidade_ppe, '%20').'%20'.
					             $this->gerarCaracteresDireita(25, strtoupper($produto_pedido->dlv_descricao_pro), '%20').
					             $this->gerarCaracteresEsquerda(6, number_format($preco_aplicado,  2, ',', '.'), '%20').
					             $this->gerarCaracteresEsquerda(8, number_format(($produto_pedido->dlv_quantidade_ppe * $preco_aplicado),  2, ',', '.'), '%20').'|';
					
					$restante_produto = substr($produto_pedido->dlv_descricao_pro, 25, strlen($produto_pedido->dlv_descricao_pro));
					if (!empty($restante_produto)) {
						$produtos .= $this->gerarCaracteresEsquerda(3, '', '%20').'%20'.
								     $this->gerarCaracteresDireita(25, strtoupper($restante_produto), '%20').'|';
					}
					
					if (!empty($complemento_produto)) {
						$produtos .= $complemento_produto;
					}
					
					if (!empty($produto_pedido->dlv_observacao_ppe)) {
						$produtos .= $this->gerarCaracteresDireita(4, '', '%20').$this->gerarCaracteresDireita(39, strtoupper('Observacao: '.$produto_pedido->dlv_observacao_ppe), '%20').'|';
					}
				}
				
				$rodape  = '';
				$rodape .= $this->gerarCaracteresDireita(43, '', '-').'|';
				$rodape .= strtoupper('Sub-Total:').$this->gerarCaracteresEsquerda(33, number_format($valor_total,  2, ',', '.'), '%20').'|';
				$rodape .= strtoupper('Desconto:').$this->gerarCaracteresEsquerda(34, number_format($registro->dlv_desconto_ped,  2, ',', '.'), '%20').'|';
				$rodape .= strtoupper('Taxa de Entrega:').$this->gerarCaracteresEsquerda(27, number_format($registro->dlv_taxaentrega_ped,  2, ',', '.'), '%20').'|';
				$rodape .= strtoupper('Total:').$this->gerarCaracteresEsquerda(37, number_format((($valor_total - $registro->dlv_desconto_ped) + $registro->dlv_taxaentrega_ped),  2, ',', '.'), '%20').'|';
				$rodape .= $this->gerarCaracteresDireita(43, '', '-').'|';
				$rodape .= strtoupper('Pagamento:').$this->gerarCaracteresEsquerda(33, $registro->dlv_descricao_fpg, '%20').'|';
				
				if ($registro->dlv_calculatroco_fpg == 1) {
					if ($registro->dlv_troco_ped > 0) {
						$rodape .= strtoupper('Troco:').$this->gerarCaracteresEsquerda(37, number_format(($registro->dlv_troco_ped - (($valor_total - $registro->dlv_desconto_ped) + $registro->dlv_taxaentrega_ped)),  2, ',', '.'), '%20').'|';
					} else {
						$rodape .= strtoupper('Troco:').$this->gerarCaracteresEsquerda(37, 'SEM TROCO', '%20').'|';
					}
				}
				
				$rodape .= $this->gerarCaracteresDireita(43, '', '-').'|';
				
				
				$rodape .= '||'.$this->gerarCaracteresDireita(6, '', '%20').'****%20DOCUMENTO%20NAO%20FISCAL%20****||';

                if ($opcao == null) {
                        $pedido = array(
                            "dlv_impbalcao_ped" => 0,
                            "dlv_impcozinha_ped" => 0
                        );
                } else {
                    if ($opcao == 0) {
                        $pedido = array(
                            "dlv_impbalcao_ped" => 0
                        );
                    } else {
                        $pedido = array(
                            "dlv_impcozinha_ped" => 0
                        );
                    }
                }
				
				$resultado = $this->PedidoModel->update($pedido, $registro->dlv_id_ped);
				
				$dados[] = array (
					"dlv_impbalcao_ped"  => $registro->dlv_impbalcao_ped,	
					"dlv_impcozinha_ped" => $registro->dlv_impcozinha_ped,
					"cabecalho_ed"	     => $cabecalho_ed,
					"cabecalho"          => $cabecalho,
					"produtos"           => $produtos,
					"rodape"             => $rodape,
				);				
			}
		}
		
		echo json_encode(array("pedidos" => $dados));
	}

	private function gerarCaracteresDireita($quantidade, $texto, $char) {
		$characteres = array(
				'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
				'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
				'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
				'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
				'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
				'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
				'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f'
		);
		
		$texto   = strtr($texto, $characteres);
		$texto   = substr($texto, 0, $quantidade - 1);
		$texto   = strtoupper($texto);
		$retorno = $texto;
		for ($i = 1; $i <= ($quantidade - strlen($texto)); $i++) {
			$retorno .= $char;
		}
		
		return $retorno;
	}
	
	private function gerarCaracteresEsquerda($quantidade, $texto, $char) {
		$characteres = array(
				'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
				'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
				'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
				'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
				'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
				'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
				'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f'
		);
		
		$texto   = strtr($texto, $characteres);
		$texto   = substr($texto, 0, $quantidade - 1);
		$texto   = strtoupper($texto);
		$retorno = '';
		for ($i = 1; $i <= ($quantidade - strlen($texto)); $i++) {
			$retorno .= $char;
		}
	
		return $retorno .= $texto;
	}
	
	public function modificar_entregador($chave, $id_pedido, $id_entregador) {
		date_default_timezone_set("America/Araguaina");
//		date_default_timezone_set("Brazil/East");
	
		if ($chave == CHAVE_MD5) {
			$pedido = array(
				"dlv_dlvent_ped" => $id_entregador
			);
				
			$dlv_id_ped = $this->PedidoModel->update($pedido, $id_pedido);
				
			echo json_encode($dlv_id_ped);
		}
	}
	
	
	
}