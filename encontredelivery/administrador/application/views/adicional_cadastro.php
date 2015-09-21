<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Adicional - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_adi" name="dlv_id_adi" value="{dlv_id_adi}"/>				
			  <div class="form-group">	
				<div class="{ERRO_DLV_DESCRICAO_ADI}">
					<label for="dlv_descricao_adi" class="col-sm-1 control-label">Descrição</label>
					<div class="col-sm-11">
						<input type="text" class="form-control" id="dlv_descricao_adi"  
							   name="dlv_descricao_adi" value="{dlv_descricao_adi}" maxlength="25" autocomplete="off" autofocus/>
					</div>
				</div>
							
			</div>
			  <div class="form-group">	
				<div class="{ERRO_DLV_VALOR_ADI}">
					<label for="dlv_valor_adi" class="col-sm-1 control-label">Valor</label>
					<div class="col-sm-2">
						<input type="text" class="form-control dinheiro" id="dlv_valor_adi"  
							   style="text-align: right;" name="dlv_valor_adi" value="{dlv_valor_adi}" maxlength="16"/>
					</div>
				</div>
				<div class="col-sm-1">
					<label class="control-label">
						<input type="checkbox" name="dlv_ativo_adi" id="dlv_ativo_adi" value="1" {dlv_ativo_adi}> Ativo</input>
					</label>
				</div>	
			 </div>
						
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_adicional" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_ADICIONAL}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
