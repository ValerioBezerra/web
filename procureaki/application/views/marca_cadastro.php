<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Marca - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="vei_id_mar" name="vei_id_mar" value="{vei_id_mar}"/>
			<div class="form-group {ERRO_VEI_NOME_MAR}">
				<label for="vei_nome_mar" class="col-sm-1 control-label">Nome</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="vei_nome_mar"
						   name="vei_nome_mar" value="{vei_nome_mar}" maxlength="25" autofocus autocomplete="off"/>
				</div>
			</div>
		
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_maro" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_MARO}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
