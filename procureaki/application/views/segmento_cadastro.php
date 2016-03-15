<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Segmento - {ACAO}</h2>

    <div class="pull-right">
     <a type="button" href="{URL_METRICAS}" class="btn btn-primary" {DISABLE_METRICAS}>
      <i class="glyphicon glyphicon-list-alt"></i> Metricas
     </a>
    </div>


    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="bus_id_seg" name="bus_id_seg" value="{bus_id_seg}"/>
			<div class="form-group {ERRO_BUS_DESCRICAO_SEG}">
				<label for="bus_descricao_seg" class="col-sm-1 control-label">Descrição</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="bus_descricao_seg"
					   name="bus_descricao_seg" value="{bus_descricao_seg}" maxlength="25" autofocus autocomplete="off"/>
				</div>
			</div>
		
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_segmento" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_SEGMENTO}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
