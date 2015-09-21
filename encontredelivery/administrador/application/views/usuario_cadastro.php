<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Usuário - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_usu" name="dlv_id_usu" value="{dlv_id_usu}"/>
			<div class="form-group {ERRO_DLV_NOME_USU}">
				<label for="dlv_nome_usu" class="col-sm-1 control-label">Nome</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="dlv_nome_usu" 
						   name="dlv_nome_usu" value="{dlv_nome_usu}" maxlength="30" autofocus autocomplete="off"/>
				</div>
			</div>
			
			<div class="form-group">
				<div class="{ERRO_DLV_DLVPER_USU}">
					<label for="dlv_dlvcar_res" class="col-sm-1 control-label">Perfil</label>
					<div class="col-sm-4">
						<select class="form-control" id="dlv_dlvper_usu" name="dlv_dlvper_usu">
							<option value="">Selecione</option>
							{BLC_PERFIS}
								<option value="{DLV_ID_PER}" {SEL_DLV_ID_PER}>{DLV_DESCRICAO_PER}</option>
							{/BLC_PERFIS}
						</select>
					</div>
				</div>
				
				<div class="{ERRO_DLV_LOGIN_USU}">
					<label for="dlv_login_usu" class="col-sm-1 control-label">Login</label>
					<div class="col-sm-3">
						<input type="text" class="form-control" id="dlv_login_usu" name="dlv_login_usu"  
							   value="{dlv_login_usu}" maxlength="15" autofocus autocomplete="off" {DLV_LOGIN_USU_READONLY}/>
					</div>
				</div>
			</div>
			
			<div class="form-group {DIV_SENHAS}">
				<div class="{ERRO_DLV_SENHA_USU}">
					<label for="dlv_senha_usu" class="col-sm-1 control-label">Senha</label>
					<div class="col-sm-3">
						<input type="password" class="form-control" id="dlv_senha_usu" 
							   name="dlv_senha_usu" value="{dlv_senha_usu}" maxlength="8" autofocus autocomplete="off"/>
					</div>
				</div>
				
				<div class="{ERRO_DLV_SENHA_USU_CONFIRMAR}">
					<label for="dlv_senha_usu_confirmar" class="col-sm-2 control-label">Confirmar senha</label>
					<div class="col-sm-3">
						<input type="password" class="form-control" id="dlv_senha_usu_confirmar" 
							   name="dlv_senha_usu_confirmar" value="{dlv_senha_usu_confirmar}" maxlength="8" autofocus autocomplete="off"/>
					</div>
				</div>
			</div>
			
							
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-2">
					<label>
						<input type="checkbox" name="dlv_ativo_usu" id="dlv_ativo_usu" value="1" {dlv_ativo_usu}> Ativo</input>
					</label>
				</div>								
			</div>	
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_usuario" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_USUARIO}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
