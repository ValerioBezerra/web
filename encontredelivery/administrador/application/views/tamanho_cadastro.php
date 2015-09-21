<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Tamanho - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_tam" name="dlv_id_tam" value="{dlv_id_tam}"/>				
			<div class="form-group">	
				<div class="{ERRO_DLV_DESCRICAO_TAM}">
					<label for="dlv_descricao_tam" class="col-sm-1 control-label">Descrição</label>
					<div class="col-sm-11">
						<input type="text" class="form-control" id="dlv_descricao_tam"  
							   name="dlv_descricao_tam" value="{dlv_descricao_tam}" maxlength="25" autocomplete="off" autofocus/>
					</div>
				</div>
			</div>
			
		  	<div class="form-group">	
				<div>
					<label for="dlv_quantidade_tam" class="col-sm-1 control-label">Quant.</label>
					<div class="col-sm-1">
						<input type="text" style="text-align: right;" class="form-control numero" id="dlv_quantidade_tam"  
							   name="dlv_quantidade_tam" maxlength="2" value="{dlv_quantidade_tam}"/>
					</div>
				</div>
				
		  		<div class="{ERRO_DLV_ORDEM_TAM}">
					<label for="dlv_ordem_tam" class="col-sm-1 control-label">Ordem</label>
					<div class="col-sm-1">
						<input type="text" style="text-align: right;" class="form-control numero" id="dlv_quantidade_tam"  
							   name="dlv_ordem_tam" maxlength="2" value="{dlv_ordem_tam}"/>
					</div>
				</div>
				
				<div>
					<label class="col-sm-1 control-label">
						<input type="checkbox" name="dlv_ativo_tam" id="dlv_ativo_tam" value="1" {dlv_ativo_tam}> Ativo</input>
					</label>
				</div>	
			</div>	
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_tamanho" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_TAMANHO}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		

