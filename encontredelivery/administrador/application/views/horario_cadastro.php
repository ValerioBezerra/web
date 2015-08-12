<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Horário - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_exh" name="dlv_id_exh" value="{dlv_id_exh}"/>
			<div class="form-group">
				<div class="{ERRO_DLV_DIA_EXT}">
					<label for="dlv_tipo_exh" class="col-sm-1 control-label">Dia</label>
					<div class="col-sm-3">
						<select class="form-control" id="dlv_dia_exh" name="dlv_dia_exh" autofocus>
							<option value="" {dlv_dia_exh}>Selecione</option>
							<option value="1" {dlv_dia_exh_1}>Domingo</option>
							<option value="2" {dlv_dia_exh_2}>Segunda-Feira</option>
							<option value="3" {dlv_dia_exh_3}>Terça-Feira</option>
							<option value="4" {dlv_dia_exh_4}>Quarta-Feira</option>
							<option value="5" {dlv_dia_exh_5}>Quinta-Feira</option>
							<option value="6" {dlv_dia_exh_6}>Sexta-Feira</option>
							<option value="7" {dlv_dia_exh_7}>Sábado</option>
						</select>
					</div>
				</div>
				
				<div class="{ERRO_DLV_HORAINI_EXT}">
					<label for="dlv_horaini_exh" class="col-sm-2 control-label">Hora inicial</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="dlv_horaini_exh" data-mask="{MASCARA_HORA}" 
							   name="dlv_horaini_exh" value="{dlv_horaini_exh}" maxlength="20" autocomplete="off"/>
					</div>
				</div>
				
				<div class="{ERRO_DLV_HORAFIN_EXT}">
					<label for="dlv_horafin_exh" class="col-sm-2 control-label">Hora final</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="dlv_horafin_exh" data-mask="{MASCARA_HORA}" 
							   name="dlv_horafin_exh" value="{dlv_horafin_exh}" maxlength="20" autocomplete="off"/>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_horario" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_HORARIO}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
