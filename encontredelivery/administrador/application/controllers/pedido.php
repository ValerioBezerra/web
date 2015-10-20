<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedido extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		$this->layout = LAYOUT_DASHBOARD_ADMINISTRATIVO;
		
		$this->load->model('Pedido_Model', 'PedidoModel');
		$this->load->model('Produto_Model', 'ProdutoModel');
		$this->load->model('Adicional_Model', 'AdicionalModel');
		$this->load->model('Status_Model', 'StatusModel');
		$this->load->model('Entregador_Model', 'EntregadorModel');
	}
	
	public function index() {
		if (($this->session->userdata('dlv_id_emp') != 1) && 
			($this->session->userdata('dlv_altstatusped_per') != 1)) {redirect('');}
		
//		date_default_timezone_set("Brazil/East");
		date_default_timezone_set("America/Araguaina");

		$dados = array();
		$dados['URL_PEDIDO']               = site_url('/json/pedido_json/retornar_pedidos_empresa/'.$this->session->userdata('dlv_id_emp'));
		$dados['URL_MODIFICAR_STATUS']     = site_url('/json/pedido_json/modificar_status/'.CHAVE_MD5.'/'.$this->session->userdata('dlv_id_usu'));
		$dados['URL_IMPRIMIR_PEDIDO']      = site_url('/json/pedido_json/imprimir_pedido/'.CHAVE_MD5);
		$dados['URL_MODIFICAR_ENTREGADOR'] = site_url('/json/pedido_json/modificar_entregador/'.CHAVE_MD5);
		
		$dados['DATA_INICIAL'] = date('d/m/Y');
		$dados['DATA_FINAL']   = date('d/m/Y', strtotime("+1 day"));
		$dados['ATUALIZAR']    = site_url('pedido');
		
		if ($this->session->userdata('dlv_controlaentregador_emp') == 0) {
			$dados['DISPLAY_ENTREGADOR'] = 'none';
		} else {
			$dados['DISPLAY_ENTREGADOR'] = '';
			$this->carregarEntregador($dados);
		}
		
		if ($this->session->userdata('dlv_id_emp') == 1) {
			$dados['N_DISPLAY_KINGSOFT'] = 'none';
			$dados['DISPLAY_KINGSOFT']   = '';
		} else {
			$dados['N_DISPLAY_KINGSOFT'] = '';
			$dados['DISPLAY_KINGSOFT']   = 'none';
		}
		
		
		$this->parser->parse('pedido_view', $dados);
	}
	
	public function detalhe($dlv_id_ped) {
		$dlv_id_ped = base64_decode($dlv_id_ped);
		$dados = array();
		$dados['ACAO_FORM']                 = site_url('pedido/adicionar_status');
		$dados['URL_APAGAR_STATUS_PEDIDO']  = site_url('pedido/remover_status');
		$dados['URL_VOLTAR']                = site_url('pedido');
		
		$this->carregarPedido($dlv_id_ped, $dados);
		$this->carregarStatus($dados);
		
		$this->parser->parse('pedido_detalhe_view', $dados);
	}
	
	private function carregarPedido($dlv_id_ped, &$dados) {
		$resultado = $this->PedidoModel->get($dlv_id_ped);
	
		if ($resultado) {
			$dados['dlv_id_ped']   		  = $dlv_id_ped;
			$dados['dlv_data_ped'] 		  = date('d/m/Y', strtotime($resultado->dlv_datahora_ped));
			$dados['dlv_hora_ped'] 		  = date('H:i:s', strtotime($resultado->dlv_datahora_ped));
			$dados['dlv_nome_cli']        = $resultado->dlv_nome_cli;
			$dados['glo_cep_end']         = mascara($resultado->glo_cep_end, MASCARA_CEP);
			$dados['glo_logradouro_end']  = $resultado->glo_logradouro_end;
			$dados['glo_nome_bai']        = $resultado->glo_nome_bai;
			$dados['glo_nome_cid']        = $resultado->glo_nome_cid.' - '.$resultado->glo_uf_est;
			$dados['dlv_numero_ped']      = $resultado->dlv_numero_ped;
			$dados['dlv_complemento_ped'] = $resultado->dlv_complemento_ped;
			$dados['dlv_fone_cli']        = $resultado->dlv_fone_cli;
			$dados['dlv_descricao_fpg']   = $resultado->dlv_descricao_fpg;			
			
			$produtos_pedido    = $this->ProdutoModel->getProdutosPedido($resultado->dlv_id_ped);
			$total_produtos     = 0;
			$dados['BLC_DADOS'] = array();
			
			foreach ($produtos_pedido as $produto_pedido) {
				$descricao_produtos = $produto_pedido->dlv_descricao_pro;
				$preco_aplicado     = $produto_pedido->dlv_preco_ppe;
				
				if (!is_null($produto_pedido->dlv_descricao_tam)) {
					$descricao_produtos = $descricao_produtos."<br><i>Tamanho: ".$produto_pedido->dlv_descricao_tam."</i>";
				}		

				$produtos_produtos = $this->ProdutoModel->getProdutosProdutoPedido($produto_pedido->dlv_id_ppe);
				foreach ($produtos_produtos as $produto_produtos) {
					if ($produto_pedido->dlv_exibirfracaoproduto_ppe == 0) {
						if ($produto_pedido->dlv_quantidadeproduto_ppe == 1) {
							$descricao_produtos = $descricao_produtos."<br> • ".$produto_produtos->dlv_descricao_pro;
						} else {
							$descricao_produtos = $descricao_produtos."<br> • ".$produto_produtos->dlv_quantidade_ppp."x ".$produto_produtos->dlv_descricao_pro;
						}
					} else {
						if ($produto_pedido->dlv_opcao_ppe != 3) {
							$descricao_produtos = $descricao_produtos."<br> • 1";
						} else {
							if ($produto_produtos->dlv_opcao_ppp == 1) {
								$descricao_produtos = $descricao_produtos."<br> • 2";
							} else {
								$descricao_produtos = $descricao_produtos."<br> • 1";
							}
						}
							
						if ($produto_pedido->dlv_opcao_ppe == 2) {
							$descricao_produtos = $descricao_produtos."/2";
						} else if ($produto_pedido->dlv_opcao_ppe >= 3) {
							$descricao_produtos = $descricao_produtos."/4";
						}
							
						$descricao_produtos = $descricao_produtos." ".$produto_produtos->dlv_descricao_pro;
					}
				}				
				
				$adicionais_produtos = $this->AdicionalModel->getAdicionaisProdutoPedido($produto_pedido->dlv_id_ppe);
				foreach ($adicionais_produtos as $adicional_produtos) {
					$descricao_produtos = $descricao_produtos."<br>+".substr($adicional_produtos->dlv_descricao_adi, 0, 22);
					$preco_aplicado     = $preco_aplicado + $adicional_produtos->dlv_valor_ppa;
				}

				$total_produtos = $total_produtos + ($preco_aplicado * $produto_pedido->dlv_quantidade_ppe);
				
				$dados['BLC_DADOS'][] = array(
						"DLV_DESCRICAO_PRO"  => $descricao_produtos,
						"DLV_QUANTIDADE_PPE" => $produto_pedido->dlv_quantidade_ppe,
						"DLV_PRECO_PPE"      => 'R$ '.number_format($preco_aplicado,  2, ',', '.'),
						"DLV_TOTAL_PPE"      => 'R$ '.number_format(($preco_aplicado * $produto_pedido->dlv_quantidade_ppe),  2, ',', '.'),
						"DLV_OBSERVACAO_PPE"  => $produto_pedido->dlv_observacao_ppe
				);
			}
			
			$dados['dlv_totalprodutos_ped'] = 'R$ '.number_format($total_produtos,  2, ',', '.');
			$dados['dlv_taxaentrega_ped']   = 'R$ '.number_format($resultado->dlv_taxaentrega_ped,  2, ',', '.');
			$dados['dlv_desconto_ped']      = 'R$ '.number_format($resultado->dlv_desconto_ped,  2, ',', '.');
			$dados['dlv_total_ped']         = 'R$ '.number_format((($total_produtos - $resultado->dlv_desconto_ped) + $resultado->dlv_taxaentrega_ped),  2, ',', '.');
			
			if ($resultado->dlv_troco_ped > 0) {
				$dados['dlv_troco_ped'] = 'R$ '.number_format(($resultado->dlv_troco_ped - (($total_produtos - $resultado->dlv_desconto_ped) + $resultado->dlv_taxaentrega_ped)),  2, ',', '.');
			} else {
				$dados['CLASS_DIV_DLV_TROCO_PED'] = 'transp';
			}
			
			
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado');
		}
	}

	private function carregarStatus(&$dados) {
		$status    = $this->StatusModel->getUltimoStatusPedido($dados['dlv_id_ped']);
		$resultado = array();
		
		
		$dlv_id_sta_ultimo = 0;	
		if (!empty($status)) {
			$dlv_id_sta_ultimo = $status->dlv_id_sta;
			if ($status->dlv_indicador_sta == 0) {
				$resultado = $this->StatusModel->getStatusAtivo(1);
			}
			
			if ($status->dlv_indicador_sta == 1) {
				$resultado = $this->StatusModel->getStatusAtivo(2);
			}			
		} else {		
			$resultado = $this->StatusModel->getStatusAtivo(0);
		}
		
		$dados['dlv_dlvsta_spe']     = '';
		$dados['dlv_motivocanc_spe'] = '';
		$this->carregarDadosFlash($dados);
	
		$dados['BLC_STATUS'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_STATUS'][] = array(
					"DLV_ID_STA"        => $registro->dlv_id_sta,
					"DLV_DESCRICAO_STA" => $registro->dlv_descricao_sta,
					"SEL_DLV_ID_STA"    => ($dados['dlv_dlvsta_spe'] == $registro->dlv_id_sta)?'selected':''
			
			);
		}
		
		$this->carregarStatusPedido($dlv_id_sta_ultimo, $dados);
	}
	
	private function carregarStatusPedido($dlv_id_sta_ultimo, &$dados) {
		$dados['BLC_DADOS_STATUS'] = array();
		
		$resultado = $this->StatusModel->getStatusPedido($dados['dlv_id_ped']);
	
		foreach ($resultado as $registro) {
			$dis_apagar_status_pedido = 'hidden';
			
			if ($registro->dlv_id_sta == $dlv_id_sta_ultimo) {
				if ($registro->dlv_ativo_sta == 1) {
					$dis_apagar_status_pedido = '';
				}
			}
			
			$dados['BLC_DADOS_STATUS'][] = array(
					"DLV_DESCRICAO_STA"        => $registro->dlv_descricao_sta,
					"DLV_DATA_STA"             => date('d/m/Y', strtotime($registro->dlv_datahoramod_spe)),
					"DLV_HORA_STA"         	   => date('H:i:s', strtotime($registro->dlv_datahoramod_spe)),
					"DIS_APAGAR_STATUS_PEDIDO" => $dis_apagar_status_pedido,
					"APAGAR_STATUS_PEDIDO"     => "abrirConfirmacao('".base64_encode($registro->dlv_id_spe)."')"
			);
		}
	}
	
	public function adicionar_status() {
		global $dlv_id_ped;
		global $dlv_dlvsta_spe;
		global $dlv_motivocanc_spe;
	
		$dlv_id_ped         = $this->input->post('dlv_id_ped');
		$dlv_dlvsta_spe     = $this->input->post('dlv_dlvsta_spe');
		$dlv_motivocanc_spe = $this->input->post('dlv_motivocanc_spe');
		
//		date_default_timezone_set("Brazil/East");
		date_default_timezone_set("America/Araguaina");

		if ($this->testarDadosStatus()) {
			$status_pedido = array(
					"dlv_dlvped_spe"	  => $dlv_id_ped,
					"dlv_dlvsta_spe"      => $dlv_dlvsta_spe,
					"dlv_motivocanc_spe"  => $dlv_motivocanc_spe,
					"dlv_dlvusumod_spe"   => $this->session->userdata('dlv_id_usu'),
					"dlv_datahoramod_spe" => date('Y-m-d H:i:s')
			);
				
			$dlv_id_spe = $this->StatusModel->insertStatusPedido($status_pedido);
	
			if (is_numeric($dlv_id_spe)) {
				$this->session->set_flashdata('sucesso', 'Status adicionado com sucesso.');
				redirect('pedido/detalhe/'.base64_encode($dlv_id_ped));
			} else {
				$this->session->set_flashdata('erro', $dlv_id_spe);
				redirect('pedido/detalhe/'.base64_encode($dlv_id_ped));
			}
		} else {
			redirect('pedido/detalhe/'.base64_encode($dlv_id_ped));
		}
	}
	
	public function remover_status($dlv_id_spe) {
		$resultado = $this->StatusModel->getStatusPedidoChave(base64_decode($dlv_id_spe));
		$res       = $this->StatusModel->deleteStatusPedido(base64_decode($dlv_id_spe));
	
		if ($res) {
			$this->session->set_flashdata('sucesso', 'Status do pedido removido com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Não foi possível remover status do pedido.');
				
		}
		
		redirect('pedido/detalhe/'.base64_encode($resultado->dlv_dlvped_spe));
	}
	
	
	
	private function testarDadosStatus() {
		global $dlv_id_ped;
		global $dlv_dlvsta_spe;
		global $dlv_motivocanc_spe;
	
		$erros    = FALSE;
		$mensagem = null;
	
		if (empty($dlv_dlvsta_spe)) {
			$erros    = TRUE;
			$mensagem .= "- Selecione um status.\n";
			$this->session->set_flashdata('ERRO_DLV_DLVSTA_SPE', 'has-error');
		} else {
			$resultado = $this->StatusModel->getStatus($dlv_dlvsta_spe);
				
			if (!$resultado) {
				$erros    = TRUE;
				$mensagem .= "- Status não cadastrado.\n";
				$this->session->set_flashdata('ERRO_DLV_DLVSTA_SPE', 'has-error');
			} else {
				if ($resultado->dlv_indicador_sta == 3) {
					if (empty($dlv_motivocanc_spe)) {
						$erros    = TRUE;
						$mensagem .= "- Motivo não preenchido.\n";
						$this->session->set_flashdata('ERRO_DLV_MOTIVOCANC_SPE', 'has-error');
					}
				}
								
				$resultado = $this->StatusModel->getStatusPedidoUnico($dlv_id_ped, $dlv_dlvsta_spe);
	
				if ($resultado) {
					$erros    = TRUE;
					$mensagem .= "- Status já adicionado.\n";
					$this->session->set_flashdata('ERRO_DLV_DLVSTA_SPE', 'has-error');
				} 
			}
		}
	
		if ($erros) {
			$this->session->set_flashdata('titulo_erro', 'Para continuar corrija os seguintes erros:');
			$this->session->set_flashdata('erro', nl2br($mensagem));
				
			$this->session->set_flashdata('ERRO_DLV_SPE', TRUE);
			$this->session->set_flashdata('dlv_dlvsta_spe', $dlv_dlvsta_spe);
			$this->session->set_flashdata('dlv_motivocanc_spe', $dlv_motivocanc_spe);
		}
	
		return !$erros;
	}
	
	private function carregarDadosFlash(&$dados) {
		$ERRO_DLV_SPE             = $this->session->flashdata('ERRO_DLV_SPE');
		$ERRO_DLV_DLVSTA_SPE      = $this->session->flashdata('ERRO_DLV_DLVSTA_SPE');
		$ERRO_DLV_MOTIVOCANC_SPE  = $this->session->flashdata('ERRO_DLV_MOTIVOCANC_SPE');
	
		$dlv_dlvsta_spe       = $this->session->flashdata('dlv_dlvsta_spe');
		$dlv_motivocanc_spe   = $this->session->flashdata('dlv_motivocanc_spe');
	
		if ($ERRO_DLV_SPE) {
			$dados['dlv_dlvsta_spe']      = $dlv_dlvsta_spe;
			$dados['dlv_motivocanc_spe']  = $dlv_motivocanc_spe;
	
			$dados['ERRO_DLV_DLVSTA_SPE']     = $ERRO_DLV_DLVSTA_SPE;
			$dados['ERRO_DLV_MOTIVOCANC_SPE'] = $ERRO_DLV_MOTIVOCANC_SPE;
		}
	}
	
	private function carregarEntregador(&$dados) {
		$resultado = $this->EntregadorModel->getEntregadorEmpresaAtivo($this->session->userdata('dlv_id_emp'));
	
		$dados['BLC_ENTREGADORES'] = array();
	
		foreach ($resultado as $registro) {
			$dados['BLC_ENTREGADORES'][] = array(
					"DLV_ID_ENT"   => $registro->dlv_id_ent,
					"DLV_NOME_ENT" => $registro->dlv_nome_ent
			);
		}
	}
	
	
	
}