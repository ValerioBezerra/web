<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">
        	Pedidos        
		    <div class="pull-right">
		    	<a onclick="atualizarPedidos()" title="Atualizar" class="btn-link"><i class="glyphicon glyphicon-refresh"></i></a> 
		    </div>
	    </h2>	
    </div>    
</div>

<div class="panel panel-default">
    <div class="panel-body">
        	    <form class="form-horizontal">
        	    	<center>
			         	 <div class="form-group">	
			                <label for="dtp_input" class="col-sm-2 control-label">Data Inicial</label>
			                <div class="col-sm-3">
				                <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" data-link-field="dtp_input" data-link-format="yyyy-mm-dd">
				                    <input class="form-control" type="text" id="data_inicial" value="{DATA_INICIAL}" readonly>
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
				                </div>
			                </div>
			                
	 						<label for="dtp_input2" class="col-sm-2 control-label">Data Final</label>
	 						<div class="col-sm-3">
				                <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
				                    <input class="form-control" type="text" id="data_final" name="data_final" value="{DATA_FINAL}" readonly>
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
				                </div>		 
			                </div>               
			            </div>
		            </center>
			    </form>
        <div class="row">
			    			
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-condensed" id="tb_pedidos">
						<thead>
                            <tr>
                                <th style="display:{DISPLAY_KINGSOFT};">Empresa</th>
                            	<th width="80" class="text-right">Nro Pedido</th>
                            	<th width="100" class="text-center">Data</th>
                                <th width="100" class="text-center">Hora</th>
                                <th>Cliente</th>
                                <th width="80"><center>Status</center></th>
                                <th width="160" style="display:{N_DISPLAY_KINGSOFT};"><center>Marcar como</center></th>
                                <th class="coluna-acao" style="display:{N_DISPLAY_KINGSOFT};"></th>
                                <th class="coluna-acao" style="display:{DISPLAY_ENTREGADOR};"></th>
                                <th class="coluna-acao" style="display:{N_DISPLAY_KINGSOFT};"></th>
                             </tr>
                        </thead> 
                        <tbody id="table_pedidos" >
                        </tbody>                   
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalModificarStatus" tabindex="-1" role="dialog" aria-labelledby="labelModificarStatus" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h3 class="modal-title" id="labelModificarStatus">Encontre Delivery</h3>
                </div>
                <div class="modal-body">
                    <h4>Confirma está modificação?</h4>
                    <button type="button" class="btn btn-primary" onclick="modificarStatus()">Sim</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCancelarPedido" tabindex="-1" role="dialog" aria-labelledby="labelCancelarPedido" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h3 class="modal-title" id="labelCancelarPedido">Encontre Delivery</h3>
                </div>
                <div class="modal-body">
                    <div id="div_motivo_cancelamento">
						<label for="motivo_cancelamento" class="control-label">Motivo</label>
	                	<input class="form-control not-resize has-error" rows="4" id="motivo_cancelamento" 
							 	  name="motivo_cancelamento" maxlength="100" />
					</div>	
					
					<br/>
					<h4>Confirma o cancelamento?</h4>	
                    <button type="button" class="btn btn-primary" onclick="cancelarPedido()">Sim</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
				</div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalImprimirPedido" tabindex="-1" role="dialog" aria-labelledby="labelImprimirPedido" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h3 class="modal-title" id="labelImprimirPedido">Encontre Delivery</h3>
                </div>
                <div class="modal-body">
                    <div>
						<select class="form-control" id="opcao_imprimir" name="opcao">
							<option value="0" selected>Balcão e cozinha</option>
							<option value="1">Apenas balcão</option>
							<option value="2">Apenas cozinha</option>
						</select>
                    </div>	
					
					<br/>
                    <button type="button" class="btn btn-primary" onclick="imprimirPedido()">Imprimir</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				</div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSelecionarEntregador" tabindex="-1" role="dialog" aria-labelledby="labelSelecionarEntregador" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h3 class="modal-title" id="labelSelecionarEntregador">Encontre Delivery</h3>
                </div>
                <div class="modal-body">
                	<textarea class="form-control not-resize has-error" rows="3" id="endereco_pedido" 
							 	  name="endereco_pedido" disabled></textarea>
					<br />		 	  
                	<div id="div_opcao_entregador">
                    	<select class="form-control" id="opcao_entregador" name="opcao_entregador">
							<option value="0">Selecione o entregador</option>
							{BLC_ENTREGADORES}
								<option value="{DLV_ID_ENT}">{DLV_NOME_ENT}</option>
							{/BLC_ENTREGADORES}
						</select>
                    </div>	
					
					<br/>
                    <button type="button" class="btn btn-primary" onclick="selecionarEntregador()">Selecionar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				</div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAguarde" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Por favor, aguarde...</h3>                    
                </div>
                <div class="modal-body">
                    <div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>
                </div>
        </div>
    </div>
</div>


<script src="<?php echo base_url('assets/js/jquery-1.11.0.js')?>"></script>	
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datetimepicker.js')?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/locales/bootstrap-datetimepicker.pt-BR.js')?>" charset="UTF-8"></script>
<script type="text/javascript">
	var idPedido             = "";
	var idStatus             = "";
	var idStatusCancelamento = "";
	
	function abrirConfirmacaoModificarStatus(pedido, status){
		idPedido = pedido;
		idStatus = status;
	    $('#modalModificarStatus').modal('show');
	}
	
	function modificarStatus() {
	    $('#modalModificarStatus').modal('hide');
	    $('#modalAguarde').modal('show');
	    $.ajax({
			  url: '{URL_MODIFICAR_STATUS}/' + idPedido + '/' + idStatus,  
			  dataType : "json",
			  success: function(data) { 
				  atualizarPedidos();
				  verificarPedidos();
				}
			});	    
	}

	function abrirCancelarPedido(pedido, status){
		idPedido             = pedido;
		idStatusCancelamento = status;
	   	$("#div_motivo_cancelamento").attr("class", ""); 
		$('#motivo_cancelamento').val(""); 
		$('#modalCancelarPedido').modal('show');
	}
	
	function cancelarPedido(){
	    var motivo_cancelamento = $('#motivo_cancelamento').val();

	    if (motivo_cancelamento.trim() == '') {
	    	 $("#div_motivo_cancelamento").attr("class", "has-error"); 
	    } else {
	    	 $('#modalCancelarPedido').modal('hide');
	    	 $('#modalAguarde').modal('show');
	    	 $.ajax({
				  url: '{URL_MODIFICAR_STATUS}/' + idPedido + '/' + idStatusCancelamento + '/' + motivo_cancelamento,  
				  dataType : "json",
				  success: function(data) { 
					  atualizarPedidos();
					  verificarPedidos();
					}
			  });		    	 
	    }
	}

	function abrirImprimirPedido(pedido){
		idPedido = pedido;
		document.getElementById("opcao_imprimir").selectedIndex = "0";
		$('#modalImprimirPedido').modal('show');
	}

	function imprimirPedido() {
   	 $('#modalImprimirPedido').modal('hide');
	 $.ajax({
		  url: '{URL_IMPRIMIR_PEDIDO}/' + idPedido + '/' + $('#opcao_imprimir').val(),  
		  dataType : "json",
		  success: function(data) { 
		  }
	  });		    	 
	}

	function abrirSelecionarEntregador(pedido, value_entregador, endereco){
		idPedido = pedido;
		$("#div_opcao_entregador").attr("class", "");
		$('#endereco_pedido').val(endereco);
		document.getElementById("opcao_entregador").value = value_entregador;
		
		$('#modalSelecionarEntregador').modal('show');
	}

	function selecionarEntregador() {
		 var opcao_entregador = $('#opcao_entregador').val();

		 if (opcao_entregador == 0) {
			 $("#div_opcao_entregador").attr("class", "has-error"); 
		 } else {
			 $('#modalSelecionarEntregador').modal('hide');

	    	 $.ajax({
				  url: '{URL_MODIFICAR_ENTREGADOR}/' + idPedido + '/' + opcao_entregador,  
				  dataType : "json",
				  success: function(data) { 
				  }
			  });		    	 
			 
		 }
	}

	function atualizarPedidos() {
		splitDataInicial = $('#data_inicial').val().split('/');
		novaDataInicial  = splitDataInicial[2] + "-" + splitDataInicial[1]+ "-" + splitDataInicial[0];
		splitDataFinal   = $('#data_final').val().split('/');
		novaDataFinal    = splitDataFinal[2] + "-" + splitDataFinal[1]+ "-" + splitDataFinal[0];
  		$.ajax({
  			type: "GET",
  			url: '{URL_PEDIDO}/4/' + novaDataInicial + '/' + novaDataFinal,  
  		  	success: function(html) { 
  		  	    $('#modalAguarde').modal('hide');
  		  		$('#table_pedidos').html(html); 
  		   	}
  		});
  	}

	$('.form_date').datetimepicker({
        language:  'pt-BR',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });

	window.setInterval('atualizarPedidos()', 10000);
</script>