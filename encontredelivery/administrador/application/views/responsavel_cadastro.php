<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Responsável - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_res" name="dlv_id_res" value="{dlv_id_res}"/>
			<div class="form-group {ERRO_DLV_NOME_RES}">
				<label for="dlv_nome_res" class="col-sm-1 control-label">Nome</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="dlv_nome_res" 
						   name="dlv_nome_res" value="{dlv_nome_res}" maxlength="45" autofocus autocomplete="off"/>
				</div>
			</div>
			
			<div class="form-group">
				<div class="{ERRO_DLV_DLVCAR_RES}">
					<label for="dlv_dlvcar_res" class="col-sm-1 control-label">Cargo</label>
					<div class="col-sm-3">
						<select class="form-control" id="dlv_dlvcar_res" name="dlv_dlvcar_res">
							<option value="">Selecione</option>
							{BLC_CARGOS}
								<option value="{DLV_ID_CAR}" {SEL_DLV_ID_CAR}>{DLV_DESCRICAO_CAR}</option>
							{/BLC_CARGOS}
						</select>
					</div>
				</div>
									
				<div class="{ERRO_DLV_TELEFONE_RES}">
					<label for="dlv_telefone_res" class="col-sm-1 control-label">Telefone</label>
					<div class="col-sm-3">
						<input type="tel" class="form-control" id="dlv_telefone_res" data-mask="{MASCARA_FONE}" 
							   name="dlv_telefone_res" value="{dlv_telefone_res}" maxlength="20" autocomplete="off"/>
					</div>
				</div>					
				
				<div class="{ERRO_DLV_CELULAR_RES}">
					<label for="dlv_celular_res" class="col-sm-1 control-label">Celular</label>
					<div class="col-sm-3">
						<input type="tel" class="form-control" id="dlv_celular_res" data-mask="{MASCARA_FONE}"
							   name="dlv_celular_res" value="{dlv_celular_res}" maxlength="20" autocomplete="off"/>
					</div>
				</div>											
			</div>
			
			<div class="form-group">
				<label for="dlv_email_res" class="col-sm-1 control-label">E-mail</label>
				<div class="col-sm-11">
					<input type="email" class="form-control" id="dlv_email_res" 
						   name="dlv_email_res" value="{dlv_email_res}" maxlength="50" autocomplete="off"/>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_responsavel" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_RESPONSAVEL}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
