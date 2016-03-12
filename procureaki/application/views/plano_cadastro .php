<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Plano - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="bus_id_pla" name="bus_id_pla" value="{bus_id_pla}"/>
			<div class="form-group {ERRO_BUS_DESCRICAO_PLA}">
				<label for="bus_descricao_pla" class="col-sm-1 control-label">Descrição</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="bus_descricao_pla"
					   name="bus_descricao_pla" value="{bus_descricao_pla}" maxlength="25" autofocus autocomplete="off"/>
				</div>
			</div>
		
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_plano" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_PLANO}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
