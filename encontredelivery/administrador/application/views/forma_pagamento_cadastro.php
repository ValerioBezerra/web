<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Forma de Pagamento - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_fpg" name="dlv_id_fpg" value="{dlv_id_fpg}"/>
			<div class="form-group {ERRO_DLV_DESCRICAO_FPG}">
				<label for="dlv_descricao_fpg" class="col-sm-1 control-label">Descrição</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="dlv_descricao_fpg" 
						   name="dlv_descricao_fpg" value="{dlv_descricao_fpg}" maxlength="25" autofocus autocomplete="off"/>
				</div>
			</div>
		
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<label>
						<input type="checkbox" name="dlv_calculatroco_fpg" id="dlv_calculatroco_fpg" value="1" {dlv_calculatroco_fpg}> Calcula troco</input>
					</label>
				</div>
			</div>		
				
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_forma_pagamento" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_FORMA_PAGAMENTO}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
			
		</form>
	</div>
</div>		
