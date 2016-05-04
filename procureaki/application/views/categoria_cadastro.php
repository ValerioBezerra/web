<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Categoria - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="bus_id_cat" name="bus_id_cat" value="{bus_id_cat}"/>
			<div class="form-group">	
				<div class="{ERRO_BUS_DESCRICAO_CAT}">
					<label for="bus_descricao_cat" class="col-sm-1 control-label">Descrição</label>
					<div class="col-sm-11">
						<input type="text" class="form-control" id="bus_descricao_cat"
							   name="bus_descricao_cat" value="{bus_descricao_cat}" maxlength="35" autocomplete="off" autofocus/>
					</div>
				</div>
			</div>
			
		  	<div class="form-group">	
		  		<div class="{ERRO_BUS_ORDEM_CAT}">
					<label for="bus_ordem_cat" class="col-sm-1 control-label">Ordem</label>
					<div class="col-sm-1">
						<input type="text" style="text-align: right;" class="form-control numero" id="bus_ordem_cat"
							   name="bus_ordem_cat" maxlength="2" value="{bus_ordem_cat}"/>
					</div>
				</div>
				
				<div>
					<label class="col-sm-2">
						<input type="checkbox" name="bus_ativo_cat" id="bus_ativo_cat" value="1" {bus_ativo_cat}> Ativo</input>
					</label>
				</div>	
			</div>	
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_categoria" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_CATEGORIA}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		