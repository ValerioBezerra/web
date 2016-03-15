<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Empresa - {ACAO}
        
	        <div class="pull-right">
		        <a type="button" href="{URL_SEGMENTOS}" class="btn btn-primary" {DISABLE_SEGMENTO}>
		        	<i class="glyphicon glyphicon-list-alt"></i> Segmentos
		        </a>
		    </div>  
	    </h2>  
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_emp" name="dlv_id_emp" value="{dlv_id_emp}"/>
			<div class="form-group {ERRO_DLV_NOME_EMP}">
				<label for="dlv_nome_emp" class="col-sm-1 control-label">Nome</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="dlv_nome_emp" 
						   name="dlv_nome_emp" value="{dlv_nome_emp}" maxlength="50d" autofocus autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="dlv_detalhamento_emp" class="col-sm-1 control-label">Detalham.</label>
				<div class="col-sm-11">
					<textarea class="form-control not-resize" rows="3" id="dlv_detalhamento_emp" 
						   name="dlv_detalhamento_emp" maxlength="350">{dlv_detalhamento_emp}</textarea>
				</div>
			</div>
			
			<div class="form-group">	
				<div>		
					<label class="col-sm-1 control-label" for="dlv_tipopessoa_emp">Pessoa</label>
					<div class="col-sm-2">	
						<select class="form-control" id="dlv_tipopessoa_emp" name="dlv_tipopessoa_emp" onchange="alterarCpfCnpj(this)">
							<option value="f" {dlv_tipopessoa_emp_f}>Física</option>
							<option value="j" {dlv_tipopessoa_emp_j}>Jurídica</option>
						</select>
					</div>	
				</div>
				
				<div id="div_dlv_cpfcnpj_emp_f" class="{CLASS_DIV_DLV_CPFCNPJ_EMP_F} {ERRO_DLV_CPFCNPJ_EMP}">
					<label class="col-sm-1 control-label" for="dlv_cpfcnpj_emp_f">CPF</label>	
					<div class="col-sm-3">
						<input type="text" class="form-control" id="dlv_cpfcnpj_emp_f" data-mask="{MASCARA_CPF}"
							   name="dlv_cpfcnpj_emp" value="{dlv_cpfcnpj_emp}" {DIV_DLV_CPFCNPJ_EMP_F} autocomplete="off"/>
					</div>
				</div>		
							
				<div id="div_dlv_cpfcnpj_emp_j" class="{CLASS_DIV_DLV_CPFCNPJ_EMP_J} {ERRO_DLV_CPFCNPJ_EMP}">
					<label class="col-sm-1 control-label" for="dlv_cpfcnpj_emp_j">CNPJ</label>	
					<div class="col-sm-3">
						<input type="text" class="form-control" id="dlv_cpfcnpj_emp_j" data-mask="{MASCARA_CNPJ}"
							   name="dlv_cpfcnpj_emp" value="{dlv_cpfcnpj_emp}" {DIV_DLV_CPFCNPJ_EMP_J} autocomplete="off"/>
					</div>
				</div>	
				
				<div class="{ERRO_DLV_DLVRES_EMP}">
					<label for="dlv_dlvres_emp" class="col-sm-1 control-label">Resp.</label>
					<div class="col-sm-4">
						<select class="form-control" id="dlv_dlvres_emp" name="dlv_dlvres_emp">
							<option value="">Selecione</option>
							{BLC_RESPONSAVEIS}
								<option value="{DLV_ID_RES}" {SEL_DLV_ID_RES}>{DLV_NOME_RES}</option>
							{/BLC_RESPONSAVEIS}
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
				<div class="{ERRO_DLV_NUMERO_EMP}">		
					<label class="col-sm-1 control-label" for="dlv_numero_emp">Número</label>
					<div class="col-sm-2">	
						<input type="text" class="form-control" id="dlv_numero_emp"
							   name="dlv_numero_emp" value="{dlv_numero_emp}" maxlength="5" autocomplete="off"/>
					</div>	
				</div>
				
				<div>		
					<label class="col-sm-1 control-label" for="dlv_complemento_emp">Complem.</label>
					<div class="col-sm-8">	
						<input type="text" class="form-control" id="dlv_complemento_emp"
							   name="dlv_complemento_emp" value="{dlv_complemento_emp}" maxlength="50" autocomplete="off"/>
					</div>	
				</div>				    	
	    	</div>
	    	
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-2">
					<label>
						<input type="checkbox" name="dlv_ativo_emp" id="dlv_ativo_emp" value="1" {dlv_ativo_emp}> Ativa</input>						
					</label>	
				</div>
				<div class="col-sm-2">
					<label>
						<input type="checkbox" name="dlv_escolheproduto_emp" id="dlv_escolheproduto_emp" value="1" {dlv_escolheproduto_emp}> Escolhe produto</input>
					</label>
				</div>
				<div class="col-sm-2">
					<label>
						<input type="checkbox" name="dlv_usaadicionais_emp" id="dlv_usaadicionais_emp" value="1" {dlv_usaadicionais_emp}> Usa adicionais</input>
					</label>
				</div>
				<div class="col-sm-2">
					<label>
						<input type="checkbox" name="dlv_usatamanhos_emp" id="dlv_usatamanhos_emp" value="1" {dlv_usatamanhos_emp}> Usa tamanhos</input>
					</label>
				</div>
				<div class="col-sm-2">
					<label>
						<input type="checkbox" name="dlv_controlaentregador_emp" id="dlv_controlaentregador_emp" value="1" {dlv_controlaentregador_emp}> Controla Entregador</input>
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
			$("#dlv_cpfcnpj_emp_f").attr("value", "");
			$("#dlv_cpfcnpj_emp_j").attr("value", "");
			
			$("#div_dlv_cpfcnpj_emp_f").attr("class", "transp");
			$("#div_dlv_cpfcnpj_emp_j").attr("class", "");
	
			$('#div_dlv_cpfcnpj_emp_f :input').attr("disabled", true); 
			$('#div_dlv_cpfcnpj_emp_j :input').removeAttr("disabled");	
		} 
	
		if (tipopessoa == 'f') {
			$("#dlv_cpfcnpj_emp_f").attr("value", "");
			$("#dlv_cpfcnpj_emp_j").attr("value", "");
				
			$("#div_dlv_cpfcnpj_emp_f").attr("class", "");
			$("#div_dlv_cpfcnpj_emp_j").attr("class", "transp");
	
			$('#div_dlv_cpfcnpj_emp_f :input').removeAttr("disabled"); 
			$('#div_dlv_cpfcnpj_emp_j :input').attr("disabled", true);			
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
