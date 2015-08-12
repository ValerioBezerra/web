<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Status - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_sta" name="dlv_id_sta" value="{dlv_id_sta}"/>
			<div class="form-group {ERRO_DLV_DESCRICAO_STA}">
				<label for="dlv_descricao_sta" class="col-sm-1 control-label">Descrição</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="dlv_descricao_sta" 
						   name="dlv_descricao_sta" value="{dlv_descricao_sta}" maxlength="25" autofocus autocomplete="off"/>
				</div>
			</div>
			
			<div class="form-group">	
				<div>		
					<label class="col-sm-1 control-label" for="dlv_indicador_sta">Indicador</label>
					<div class="col-sm-2">	
						<select class="form-control" id="dlv_indicador_sta" name="dlv_indicador_sta" onchange="alterarCpfCnpj(this)">
							<option value="0" {dlv_indicador_sta_0}>Recebido</option>
							<option value="1" {dlv_indicador_sta_1}>Em processo</option>
							<option value="2" {dlv_indicador_sta_2}>Entregue</option>
							<option value="3" {dlv_indicador_sta_3}>Cancelado</option>
						</select>
					</div>	
				</div>
				
				
				
				<div class="form-group {ERRO_DLV_ORDEM_STA}">
				 <label class="col-sm-1 control-label" for="dlv_ordem_sta">Ordem</label>
					<div class="col-sm-2">	
						<input type="text" class="form-control" id="dlv_ordem_sta"
							   name="dlv_ordem_sta" value={dlv_ordem_sta} maxlength="1" autocomplete="off"/>
					</div>	
				</div>
			
				
				<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<label>
						<input type="checkbox" name="dlv_ativo_sta" id="dlv_ativo_sta" value="1" {dlv_ativo_sta}> Ativo</input>
					</label>
				</div>
			</div>		    		
			
			
		
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_status" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_STATUS}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
