<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Usuário - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="bus_id_usu" name="bus_id_usu" value="{bus_id_usu}"/>
			<div class="form-group {ERRO_BUS_NOME_USU}">
				<label for="bus_nome_usu" class="col-sm-1 control-label">Nome</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="bus_nome_usu" 
						   name="bus_nome_usu" value="{bus_nome_usu}" maxlength="30" autofocus autocomplete="off"/>
				</div>
			</div>
			
			<div class="form-group">
				<div class="{ERRO_BUS_BUSPER_USU}">
					<label for="bus_buscar_res" class="col-sm-1 control-label">Perfil</label>
					<div class="col-sm-4">
						<select class="form-control" id="bus_busper_usu" name="bus_busper_usu">
							<option value="">Selecione</option>
							{BLC_PERFIS}
								<option value="{BUS_ID_PER}" {SEL_BUS_ID_PER}>{BUS_DESCRICAO_PER}</option>
							{/BLC_PERFIS}
						</select>
					</div>
				</div>
				
				<div class="{ERRO_BUS_LOGIN_USU}">
					<label for="bus_login_usu" class="col-sm-1 control-label">Login</label>
					<div class="col-sm-3">
						<input type="text" class="form-control" id="bus_login_usu" name="bus_login_usu"  
							   value="{bus_login_usu}" maxlength="15" autofocus autocomplete="off" {BUS_LOGIN_USU_READONLY}/>
					</div>
				</div>
			</div>
			
			<div class="form-group {DIV_SENHAS}">
				<div class="{ERRO_BUS_SENHA_USU}">
					<label for="bus_senha_usu" class="col-sm-1 control-label">Senha</label>
					<div class="col-sm-3">
						<input type="password" class="form-control" id="bus_senha_usu" 
							   name="bus_senha_usu" value="{bus_senha_usu}" maxlength="8" autofocus autocomplete="off"/>
					</div>
				</div>
				
				<div class="{ERRO_BUS_SENHA_USU_CONFIRMAR}">
					<label for="bus_senha_usu_confirmar" class="col-sm-2 control-label">Confirmar senha</label>
					<div class="col-sm-3">
						<input type="password" class="form-control" id="bus_senha_usu_confirmar" 
							   name="bus_senha_usu_confirmar" value="{bus_senha_usu_confirmar}" maxlength="8" autofocus autocomplete="off"/>
					</div>
				</div>
			</div>
			
							
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-2">
					<label>
						<input type="checkbox" name="bus_ativo_usu" id="bus_ativo_usu" value="1" {bus_ativo_usu}> Ativo</input>
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
