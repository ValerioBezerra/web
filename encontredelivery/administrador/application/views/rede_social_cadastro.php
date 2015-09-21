<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Rede Social - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_red" name="dlv_id_red" value="{dlv_id_red}"/>
			<div class="form-group {ERRO_DLV_DESCRICAO_RED}">
				<label for="dlv_descricao_red" class="col-sm-1 control-label">Descrição</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="dlv_descricao_red" 
						   name="dlv_descricao_red" value="{dlv_descricao_red}" maxlength="25" autofocus autocomplete="off"/>
				</div>
			</div>
			
			<div>
				<div class="form-group {ERRO_DLV_CLASSE_RED}">
					<label for="dlv_classe_red" class="col-sm-1 control-label">Classe</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="dlv_classe_red" 
							   name="dlv_classe_red" value="{dlv_classe_red}" maxlength="15" autofocus autocomplete="off"/>
					</div>
			</div>	
			
		
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_rede_social" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_REDE_SOCIAL}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
			
		</form>
	</div>
</div>		
