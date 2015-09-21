<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Cargo - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_car" name="dlv_id_car" value="{dlv_id_car}"/>
			<div class="form-group {ERRO_DLV_DESCRICAO_CAR}">
				<label for="dlv_descricao_car" class="col-sm-1 control-label">Descrição</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="dlv_descricao_car" 
						   name="dlv_descricao_car" value="{dlv_descricao_car}" maxlength="25" autofocus autocomplete="off"/>
				</div>
			</div>
		
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_cargo" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_CARGO}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
