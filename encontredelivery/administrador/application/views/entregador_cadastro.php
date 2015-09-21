<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Entregador - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_ent" name="dlv_id_ent" value="{dlv_id_ent}"/>				
			<div class="form-group">	
				<div class="{ERRO_DLV_NOME_ENT}">
					<label for="dlv_nome_ent" class="col-sm-1 control-label">Nome</label>
					<div class="col-sm-11">
						<input type="text" class="form-control" id="dlv_nome_ent"  
							   name="dlv_nome_ent" value="{dlv_nome_ent}" maxlength="35" autocomplete="off" autofocus/>
					</div>
				</div>
			</div>
			 <div>
					<label class="col-sm-2">
						<input type="checkbox" name="dlv_ativo_ent" id="dlv_ativo_ent" value="1" {dlv_ativo_ent}> Ativo</input>
					</label>
				</div>	
			
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_entregador" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_ENTREGADOR}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		