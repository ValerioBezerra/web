<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Câmbio - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="vei_id_cam" name="vei_id_cam" value="{vei_id_cam}"/>
			<div class="form-group {ERRO_VEI_DESCRICAO_CAM}">
				<label for="vei_descricao_cam" class="col-sm-1 control-label">Descrição</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="vei_descricao_cam"
						   name="vei_descricao_cam" value="{vei_descricao_cam}" maxlength="25" autofocus autocamplete="off"/>
				</div>
			</div>
		
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_cambio" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_CAMBIO}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
