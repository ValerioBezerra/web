<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Empresa - {ACAO}
        
	        <div class="pull-right">
		        <a type="button" href="{URL_TIPO}" class="btn btn-primary" {DISABLE_TIPO}>
		        	<i class="glyphicon glyphicon-list-alt"></i> Segmentos
		        </a>
		    </div>  
	    </h2>  
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="bus_id_emp" name="bus_id_emp" value="{bus_id_emp}"/>
			<div class="form-group {ERRO_BUS_NOME_EMP}">
				<label for="bus_nome_emp" class="col-sm-1 control-label">Nome</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="bus_nome_emp" 
						   name="bus_nome_emp" value="{bus_nome_emp}" maxlength="50d" autofocus autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="bus_detalhamento_emp" class="col-sm-1 control-label">Detalham.</label>
				<div class="col-sm-11">
					<textarea class="form-control not-resize" rows="3" id="bus_detalhamento_emp" 
						   name="bus_detalhamento_emp" maxlength="350">{bus_detalhamento_emp}</textarea>
				</div>
			</div>
			
			<div class="form-group">	
				<div>		
					<label class="col-sm-1 control-label" for="bus_tipopessoa_emp">Pessoa</label>
					<div class="col-sm-2">	
						<select class="form-control" id="bus_tipopessoa_emp" name="bus_tipopessoa_emp" onchange="alterarCpfCnpj(this)">
							<option value="f" {bus_tipopessoa_emp_f}>Física</option>
							<option value="j" {bus_tipopessoa_emp_j}>Jurídica</option>
						</select>
					</div>	
				</div>
				
				<div id="div_bus_cpfcnpj_emp_f" class="{CLASS_DIV_BUS_CPFCNPJ_EMP_F} {ERRO_BUS_CPFCNPJ_EMP}">
					<label class="col-sm-1 control-label" for="bus_cpfcnpj_emp_f">CPF</label>	
					<div class="col-sm-3">
						<input type="text" class="form-control" id="bus_cpfcnpj_emp_f" data-mask="{MASCARA_CPF}"
							   name="bus_cpfcnpj_emp" value="{bus_cpfcnpj_emp}" {DIV_BUS_CPFCNPJ_EMP_F} autocomplete="off"/>
					</div>
				</div>		
							
				<div id="div_bus_cpfcnpj_emp_j" class="{CLASS_DIV_BUS_CPFCNPJ_EMP_J} {ERRO_BUS_CPFCNPJ_EMP}">
					<label class="col-sm-1 control-label" for="bus_cpfcnpj_emp_j">CNPJ</label>	
					<div class="col-sm-3">
						<input type="text" class="form-control" id="bus_cpfcnpj_emp_j" data-mask="{MASCARA_CNPJ}"
							   name="bus_cpfcnpj_emp" value="{bus_cpfcnpj_emp}" {DIV_BUS_CPFCNPJ_EMP_J} autocomplete="off"/>
					</div>
				</div>

				<div class="form-group {ERRO_BUS_BUSSEG_EMP}">
					<label for="bus_busseg_emp" class="col-sm-1 control-label">Segmento</label>
					<div class="col-sm-3">
						<select class="form-control" id="bus_busseg_emp" name="bus_busseg_emp">
							<option value="">Selecione</option>
							{BLC_SEGMENTO}
							<option value="{BUS_ID_SEG}" {SEL_BUS_ID_SEG}>{BUS_DESCRICAO_SEG}</option>
							{/BLC_SEGMENTO}
						</select>
					</div>
				</div>

	    	</div>
	    	
			<div class="panel panel-default"></div>	
			
	    	<div class="form-group {ERRO_ENDERECO}">	
				<div class="{ERRO_GLO_CEP_END}">		
					<label class="col-sm-1 control-label" for="glo_cep_end">CEP</label>
					<div class="col-sm-2">	
						<input type="text" class="form-control" id="glo_cep_end" data-mask="{MASCARA_CEP}"
							   name="glo_cep_end" value="{glo_cep_end}" autocomplete="off"/>
					</div>	
				</div>
				
				<div>		
					<label class="col-sm-1 control-label" for="glo_logradouro_end">Endereço</label>
					<div class="col-sm-8">	
						<input type="text" class="form-control" id="glo_logradouro_end"
							   name="glo_logradouro_end" value="{glo_logradouro_end}" readonly/>
					</div>	
				</div>				
	    	</div>
	    	
	    	<div class="form-group {ERRO_ENDERECO}">	
				<div>		
					<label class="col-sm-1 control-label" for="glo_nome_bai">Bairro</label>
					<div class="col-sm-6">	
						<input type="text" class="form-control" id="glo_nome_bai"
							   name="glo_nome_bai" value="{glo_nome_bai}" readonly/>
					</div>	
				</div>
				
				<div>		
					<label class="col-sm-1 control-label" for="glo_nome_cid">Cidade</label>
					<div class="col-sm-4">	
						<input type="text" class="form-control" id="glo_nome_cid"
							   name="glo_nome_cid" value="{glo_nome_cid}" readonly/>
					</div>	
				</div>						    	
	    	</div>
	    	
	    	<div class="form-group">
				<div class="{ERRO_BUS_NUMERO_EMP}">		
					<label class="col-sm-1 control-label" for="bus_numero_emp">Número</label>
					<div class="col-sm-2">	
						<input type="text" class="form-control" id="bus_numero_emp"
							   name="bus_numero_emp" value="{bus_numero_emp}" maxlength="5" autocomplete="off"/>
					</div>	
				</div>
				
				<div>		
					<label class="col-sm-1 control-label" for="bus_complemento_emp">Complem.</label>
					<div class="col-sm-8">	
						<input type="text" class="form-control" id="bus_complemento_emp"
							   name="bus_complemento_emp" value="{bus_complemento_emp}" maxlength="50" autocomplete="off"/>
					</div>	
				</div>				    	
	    	</div>
	    	
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-2">
					<label>
						<input type="checkbox" name="bus_ativo_emp" id="bus_ativo_emp" value="1" {bus_ativo_emp}> Ativa</input>						
					</label>	
				</div>

			</div>		    		
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-10">
					<button type="submit" name="salvar_empresa" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_EMPRESA}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>	

<script>
	function alterarCpfCnpj(objeto) {
		var tipopessoa = objeto.value;
		 
		if (tipopessoa == 'j') {
			$("#bus_cpfcnpj_emp_f").attr("value", "");
			$("#bus_cpfcnpj_emp_j").attr("value", "");
			
			$("#div_bus_cpfcnpj_emp_f").attr("class", "transp");
			$("#div_bus_cpfcnpj_emp_j").attr("class", "");
	
			$('#div_bus_cpfcnpj_emp_f :input').attr("disabled", true); 
			$('#div_bus_cpfcnpj_emp_j :input').removeAttr("disabled");	
		} 
	
		if (tipopessoa == 'f') {
			$("#bus_cpfcnpj_emp_f").attr("value", "");
			$("#bus_cpfcnpj_emp_j").attr("value", "");
				
			$("#div_bus_cpfcnpj_emp_f").attr("class", "");
			$("#div_bus_cpfcnpj_emp_j").attr("class", "transp");
	
			$('#div_bus_cpfcnpj_emp_f :input').removeAttr("disabled"); 
			$('#div_bus_cpfcnpj_emp_j :input').attr("disabled", true);			
		}
	}
</script>

  <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.0.js')?>"></script>
  <script type="text/javascript">
  	$('#glo_cep_end').blur(function(){
	  	$.ajax({
		  url: '{URL_ENDERECO}/' + $('#glo_cep_end').val(),  
		  dataType : "json",
		  success: function(data) { 
			  if (data == "") {
				  $("#glo_logradouro_end").attr("value", ""); 
				  $("#glo_nome_bai").attr("value", ""); 
				  $("#glo_nome_cid").attr("value", "");
			  } else {
				  $("#glo_logradouro_end").attr("value", data.glo_logradouro_end); 
				  $("#glo_nome_bai").attr("value", data.glo_nome_bai); 
				  $("#glo_nome_cid").attr("value", data.glo_nome_cid + ' - ' + data.glo_uf_est);
			  } 
			}
		});
  	});
  </script>
