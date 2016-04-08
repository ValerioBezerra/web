<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Tipo - {ACAO}</h2>

<!--    <div class="pull-right">-->
<!--     <a type="button" href="{URL_METRICAS}" class="btn btn-primary" {DISABLE_METRICAS}>-->
<!--      <i class="glyphicon glyphicon-list-alt"></i> Metricas-->
<!--     </a>-->
<!--    </div>-->


    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="bus_id_tip" name="bus_id_tip" value="{bus_id_tip}"/>
			<div class="form-group {ERRO_BUS_DESCRICAO_TIP}">
				<label for="bus_descricao_tip" class="col-sm-1 control-label">Descrição</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="bus_descricao_tip"
					   name="bus_descricao_tip" value="{bus_descricao_tip}" maxlength="25" autofocus autocomplete="off"/>
				</div>
			</div>
			<div class="form-group {ERRO_BUS_BUSSEG_TIP}">
				<label for="bus_busseg_tip" class="col-sm-1 control-label">Segmento</label>
				<div class="col-sm-3">
					<select class="form-control" id="bus_busseg_tip" name="bus_busseg_tip">
						<option value="">Selecione</option>
						{BLC_SEGMENTO}
						<option value="{BUS_ID_SEG}" {SEL_BUS_ID_SEG}>{BUS_DESCRICAO_SEG}</option>
						{/BLC_SEGMENTO}
					</select>
				</div>
			</div>
		
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_tipo" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_TIPO}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
