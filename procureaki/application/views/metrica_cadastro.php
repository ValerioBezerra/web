<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Metrica - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="bus_id_met" name="bus_id_met" value="{bus_id_met}"/>
			<div class="form-group {ERRO_BUS_DESCRICAO_MET}">
				<label for="bus_descricao_met" class="col-sm-1 control-label">Descrição</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="bus_descricao_met"
					   name="bus_descricao_met" value="{bus_descricao_met}" maxlength="25" autofocus autocomplete="off"/>
				</div>
			</div>
		
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_metrica" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_METRICA}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
