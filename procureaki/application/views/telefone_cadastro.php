<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Fone - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="bus_id_ext" name="bus_id_ext" value="{bus_id_ext}"/>
			<div class="form-group">
				<div class="{ERRO_BUS_TIPO_EXT}">
					<label for="bus_tipo_ext" class="col-sm-1 control-label">Tipo</label>
					<div class="col-sm-2">
						<select class="form-control" id="bus_tipo_ext" name="bus_tipo_ext" autofocus>
							<option value="" {bus_tipo_ext}>Selecione</option>
							<option value="t" {bus_tipo_ext_t}>Telefone</option>
							<option value="c" {bus_tipo_ext_c}>Celular</option>
							<option value="f" {bus_tipo_ext_f}>Fax</option>
						</select>
					</div>
				</div>
				
				<div class="{ERRO_BUS_FONE_EXT}">
					<label for="bus_fone_ext" class="col-sm-1 control-label">Fone</label>
					<div class="col-sm-3">
						<input type="text" class="form-control" id="bus_fone_ext" data-mask="{MASCARA_FONE}"
							   name="bus_fone_ext" value="{bus_fone_ext}" maxlength="20" autocomplete="off"/>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_telefone" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_TELEFONE}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
