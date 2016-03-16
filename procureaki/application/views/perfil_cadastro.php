<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Perfil - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="bus_id_per" name="bus_id_per" value="{bus_id_per}"/>
			<div class="form-group {ERRO_BUS_DESCRICAO_PER}">
				<label for="bus_descricao_per" class="col-sm-1 control-label">Descrição</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="bus_descricao_per" 
						   name="bus_descricao_per" value="{bus_descricao_per}" maxlength="20" autofocus autocomplete="off"/>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-3">
					<label>
						<input type="checkbox" name="bus_alttelefone_per" id="bus_alttelefone_per" value="1" {bus_alttelefone_per}> Altera telefones</input>
					</label>
				</div>
				
				<div class="col-sm-3">
					<label>
						<input type="checkbox" name="bus_althorario_per" id="bus_althorario_per" value="1" {bus_althorario_per}> Altera horários</input>
					</label>
				</div>

			</div>	
				

			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-3">
					<label>
						<input type="checkbox" name="bus_cadcategoria_per" id="bus_allhorario_per" value="1" {bus_cadcategoria_per}> Cadastra categorias</input>
					</label>
				</div>

				<div class="col-sm-3">
					<label>
						<input type="checkbox" name="bus_cadproduto_per" id="bus_cadproduto_per" value="1" {bus_cadproduto_per}> Cadastra produtos</input>
					</label>
				</div>

			</div>	
			

		
			<div class="form-group">
				<div class="col-sm-11">
					<button type="submit" name="salvar_perfil" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_PERFIL}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
