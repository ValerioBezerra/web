        <section class="divcolor">
            <div class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-6">
                            <div class="container-fluid"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"></div>
                    </div>
                    <div class="row ">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="panel panel-default panel-transparent text-center">
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <h3 class="text-center texto-branco">Os melhores restaurantes pertinho de você em apenas um clique</h3>
                                        <form onsubmit="encontrarCEP();return false">
                                            <div id="div_encontrar_cep" class="form-group">
                                                <input class="form-control mask-cep input-lg" type="text" id="encontrar_cep" name="encontrar_cep" placeholder="Informe seu CEP">
												<div id="div_error_encontrar_cep" class="alert alert-danger" role="alert" hidden>                                                
													<label class="control-label" id="label_encontrar_cep" for="encontrar_cep"></label>
												</div>
                                            </div>
                                        </form>
                                        <a class="btn btn-block btn-lg btn-primary" onclick="encontrarCEP()"><b>Encontrar restaurantes</b><i class="fa fa-flip-horizontal fa-fw fa-lg fa-search"></i></a>
                                        <h4 class="text-inverse">
                                        	<a onclick="abrirEncontrarEnderecos()"><span class="text texto-branco">Não sabe seu CEP?</span></a>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="fade modal modal-footer" id="encontrar_endereco_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title text-center">Complete seu endereço de entrega</h3>
                    </div>
                    
                    <div class="modal-body">
                    	<form onsubmit="encontrarEnderecos();return false">
                            <div id="div_estado_endereco" class="form-group col-xs-4">
                                <select class="form-control" id="estado_endereco">
                                	<option value="">UF</option>
                                	{BLC_ESTADOS}
	                                    <option value="{GLO_ID_EST}" >{GLO_UF_EST}</option>
                                	{/BLC_ESTADOS}
                            	</select>
                            	
                            	<label class="control-label" id="label_estado_endereco" for="estado_endereco" hidden></label>
                            </div>
                            <div id="div_cidade_endereco" class="form-group col-xs-8">
								<select class="form-control" id="cidade_endereco" disabled>
									<option value="">Cidade</option>
								</select>
								
                            	<label class="control-label" id="label_cidade_endereco" for="cidade_endereco" hidden></label>
                            </div>
                            <div id="div_bairro_endereco" class="form-group col-xs-12">
								<select class="form-control" id="bairro_endereco" disabled>
									<option value="">Bairro</option>
								</select>
								
                            	<label class="control-label" id="label_bairro_endereco" for="bairro_endereco" hidden></label>
                            </div>
                            
                            <div id="div_logradouro_endereco" class="form-group col-xs-12">
                                <input class="form-control" type="text" id="logradouro_endereco" placeholder="Logradouro(rua, avenida, etc.)">
                            	<label class="control-label" id="label_logradouro_endereco" for="logradouro_endereco" hidden></label>                                
                            </div>
                        </form>
                        
                        <button onclick="encontrarEnderecos()" class="btn btn-block btn-lg btn-primary">Encontrar</button>
                        
                        <br/>
                        
	                    <table class="table table-striped table-bordered table-hover table-condensed" id="tb_enderecos">
	                        <tbody id="table_enderecos" >
	                        </tbody>                   
	                    </table>
                 	</div>
                        
                </div>
            </div>
        </div>  
        
        <div class="fade modal modal-footer" id="cep_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title text-center">Confirma este endereço?</h4>
                    </div>
                    <div class="modal-body">
                      	<div class="form-group col-xs-12 text-left">
                        	<h4 id="cep_logradouro_modal"></h4>
                            <h4 id="bairro_modal"></h4>
                            <h5 id="cidade_uf_modal"></h5>
                       	</div>
                            
	                    <button id="btnConfirmarEndereco" type="button" class="btn btn-lg btn-primary">Sim</button>
	                    <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Não</button>
	                </div>                            
                </div>
            </div>
        </div>  
        
        
		<script type="text/javascript" src="assets/js/jquery-1.11.0.js"></script>
        <script type="text/javascript">     
	function encontrarCEP() {
		var encontrar_cep  			  = $('#encontrar_cep').val();
		var erros                     = false;
		var icone_error               = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> '; 

		if (encontrar_cep.trim() == '') {
			 $("#div_encontrar_cep").attr("class", "has-error form-group text-left"); 
			 document.getElementById('label_encontrar_cep').innerHTML = icone_error + 'Informe o CEP.';
			 $('#div_error_encontrar_cep').removeAttr("hidden");
			 erros = true;
		} else {
			 $("#div_encontrar_cep").attr("class", "form-group text-left"); 
			 $("#div_error_encontrar_cep").attr("hidden", "true"); 
		}


		if (!erros) {
			carregarCEP(encontrar_cep.trim(), 1);
		}
	}

  	$('#estado_endereco').change(function(){
  	  	var glo_id_est = $('#estado_endereco').val();

  	  	if (glo_id_est == '') {
  	  		glo_id_est = 0;
  	  	}

		$("#div_estado_endereco").attr("class", "form-group col-xs-4"); 
		$("#label_estado_endereco").attr("hidden", "true"); 
  	  	$('#cidade_endereco').html('<option value="">Cidade</option>');	
  		$('#cidade_endereco').attr("disabled", true);
  		$('#bairro_endereco').attr("disabled", true);
  		
	  	$.ajax({
		  url: '{URL_ENDERECO_ESTADO}/' + glo_id_est,  
		  dataType : "json",
		  success: function(data) { 
			  if (data == "") {
				  $('#cidade_endereco').html('<option value="">Cidade</option>');	
			  	  $('#cidade_endereco').attr("disabled", true);	
			  	  $('#bairro_endereco').attr("disabled", true);	
			  } else {
				  var options = '<option value="">Selecione a cidade</option>';	

				  for (var i = 0; i < data.length; i++) {
			      	options += '<option value="' + data[i].glo_id_cid + '">' + data[i].glo_nome_cid + '</option>';
				  }

				  $('#cidade_endereco').html(options).show();
				  $('#cidade_endereco').removeAttr("disabled");
			  } 
			}
		});
  	});

  	$('#cidade_endereco').change(function(){
  	  	var glo_id_cid = $('#cidade_endereco').val();

  	  	if (glo_id_cid == '') {
  	  		glo_id_cid = 0;
  	  	}

		$("#div_cidade_endereco").attr("class", "form-group col-xs-8"); 
		$("#label_cidade_endereco").attr("hidden", "true"); 
  	  	$('#bairro_endereco').html('<option value="">Bairro</option>');	
  		$('#bairro_endereco').attr("disabled", true);
  		
	  	$.ajax({
		  url: '{URL_ENDERECO_CIDADE}/' + glo_id_cid,  
		  dataType : "json",
		  success: function(data) { 
			  if (data == "") {
				  $('#bairro_endereco').html('<option value="">Bairro</option>');	
			  	  $('#bairro_endereco').attr("disabled", true);	
			  } else {
				  var options = '<option value="">Selecione o bairro</option>';	

				  for (var i = 0; i < data.length; i++) {
			      	options += '<option value="' + data[i].glo_id_bai + '">' + data[i].glo_nome_bai + '</option>';
				  }

				  $('#bairro_endereco').html(options).show();
				  $('#bairro_endereco').removeAttr("disabled");
			  } 
			}
		});
  	});

  	$('#bairro_endereco').change(function(){
		$("#div_bairro_endereco").attr("class", "form-group col-xs-12"); 
		$("#label_bairro_endereco").attr("hidden", "true"); 
  	});

  	function abrirEncontrarEnderecos() {
		$("#div_estado_endereco").attr("class", "form-group col-xs-4"); 
		$("#label_estado_endereco").attr("hidden", "true"); 
		$("#div_cidade_endereco").attr("class", "form-group col-xs-8"); 
		$("#label_cidade_endereco").attr("hidden", "true"); 
		$("#div_bairro_endereco").attr("class", "form-group col-xs-12"); 
		$("#label_bairro_endereco").attr("hidden", "true"); 
		$("#div_logradouro_endereco").attr("class", "form-group col-xs-12"); 
		$("#label_logradouro_endereco").attr("hidden", "true"); 
		$('#table_enderecos').html('').show();
  	

  		$('#encontrar_endereco_modal').modal('show');
  	}

  	function encontrarEnderecos() {
  		var estado_endereco = $('#estado_endereco').val();
  		var cidade_endereco = $('#cidade_endereco').val();
  		var bairro_endereco = $('#bairro_endereco').val();
  		var logradouro_endereco = $('#logradouro_endereco').val();
		var erros           = false;
		var icone_error     = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> '; 

		$("#div_estado_endereco").attr("class", "form-group col-xs-4"); 
		$("#label_estado_endereco").attr("hidden", "true"); 
		$("#div_cidade_endereco").attr("class", "form-group col-xs-8"); 
		$("#label_cidade_endereco").attr("hidden", "true"); 
		$("#div_bairro_endereco").attr("class", "form-group col-xs-12"); 
		$("#label_bairro_endereco").attr("hidden", "true"); 
		$("#div_logradouro_endereco").attr("class", "form-group col-xs-12"); 
		$("#label_logradouro_endereco").attr("hidden", "true"); 
		$('#table_enderecos').html('').show();

		if (estado_endereco.trim() == '') {
			 $("#div_estado_endereco").attr("class", "has-error form-group col-xs-4 text-left"); 
			 document.getElementById('label_estado_endereco').innerHTML = icone_error + 'Informe a UF.';
			 $('#label_estado_endereco').removeAttr("hidden");
			 erros = true;
		}

		if (cidade_endereco.trim() == '') {
			 $("#div_cidade_endereco").attr("class", "has-error form-group col-xs-8 text-left"); 
			 document.getElementById('label_cidade_endereco').innerHTML = icone_error + 'Informe a cidade.';
			 $('#label_cidade_endereco').removeAttr("hidden");
			 erros = true;
		}
		
		if (bairro_endereco.trim() == '') {
			 $("#div_bairro_endereco").attr("class", "has-error form-group col-xs-12 text-left"); 
			 document.getElementById('label_bairro_endereco').innerHTML = icone_error + 'Informe o bairro.';
			 $('#label_bairro_endereco').removeAttr("hidden");
			 erros = true;
		}

		if (logradouro_endereco.trim() == '') {
			 $("#div_logradouro_endereco").attr("class", "has-error form-group col-xs-12 text-left"); 
			 document.getElementById('label_logradouro_endereco').innerHTML = icone_error + 'Informe o logradouro.';
			 $('#label_logradouro_endereco').removeAttr("hidden");
			 erros = true;
		}
		
		if (!erros) {
			$.ajax({
				url: '{URL_VERIFICAR_ENDERECO}/' + cidade_endereco + '/' + bairro_endereco + '/' + logradouro_endereco, 
				async: true, 
				dataType : "json",
				success: function(data) { 
					if (data != "") {
						var enderecos = '';
						for (var i = 0; i < data.length; i++) {
							enderecos += '<tr><td class="text-left"><h4>(' + data[i].glo_cep_end_mascara + ') ' + data[i].glo_logradouro_end + '</h4>';
							enderecos += '<h4>' + data[i].glo_nome_bai + '</h4>';
							enderecos += '<h5>' + data[i].glo_nome_cid + ' - ' + data[i].glo_uf_est; + '</h5>';
							enderecos += '</td>';
							enderecos += '<td class="text-center"><div class="vertical-center"><a onclick="carregarCEP(' + data[i].glo_cep_end + ', 0)" class="btn btn-block btn-lg btn-primary">Escolher</a></div></td>'
							enderecos +=  '</tr>';
							
						}		

						$('#table_enderecos').html(enderecos);				
					}
				}
			});

		}
	}	

	function carregarCEP(cep, buscandoPorCep) {
		var icone_error = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> '; 
	
		$.ajax({
			url: '{URL_VERIFICAR_CEP}/' + cep, 
			async: true, 
			dataType : "json",
			success: function(data) { 
				if (data == "") {
					 if (buscandoPorCep == 1) {
						 $("#div_encontrar_cep").attr("class", "has-error form-group text-left"); 
						 document.getElementById('label_encontrar_cep').innerHTML = icone_error + 'Nenhum endereço encontrado com este CEP.';
						 $('#div_error_encontrar_cep').removeAttr("hidden");
					 }
				} else {
					document.getElementById('cep_logradouro_modal').innerHTML = "(" + data.glo_cep_end_mascara + ") " + data.glo_logradouro_end;
					document.getElementById('bairro_modal').innerHTML         = data.glo_nome_bai;
					document.getElementById('cidade_uf_modal').innerHTML      = data.glo_nome_cid + " - " + data.glo_uf_est;
					document.getElementById('btnConfirmarEndereco').setAttribute('onclick', 'confirmarEndereco(' + data.glo_cep_end + ')')
					$('#cep_modal').modal('show');							
				}
			}
		});
	}

	function confirmarEndereco(cep) {
		$('#cep_modal').modal('hide');
			
		$.ajax({
			url: '{URL_CONFIRMAR_ENDERECO}/' + cep, 
			async: true, 
			dataType : "json",
			success: function(data) { 
				if (data != "") {
					if (data.resposta) {
						location.href = 'estabelecimentos';
					} else {
						alert('Erro ao confirmar endereço');
					}
				}
			}
		});
		
		
	}	  	
		  	
</script>	
        