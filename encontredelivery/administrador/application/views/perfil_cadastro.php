<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Perfil - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_per" name="dlv_id_per" value="{dlv_id_per}"/>
			<div class="form-group {ERRO_DLV_DESCRICAO_PER}">
				<label for="dlv_descricao_per" class="col-sm-1 control-label">Descrição</label>
				<div class="col-sm-11">
					<input type="text" class="form-control" id="dlv_descricao_per" 
						   name="dlv_descricao_per" value="{dlv_descricao_per}" maxlength="20" autofocus autocomplete="off"/>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-3">
					<label>
						<input type="checkbox" name="dlv_alttelefone_per" id="dlv_alttelefone_per" value="1" {dlv_alttelefone_per}> Altera telefones</input>
					</label>
				</div>
				
				<div class="col-sm-3">
					<label>
						<input type="checkbox" name="dlv_althorario_per" id="dlv_althorario_per" value="1" {dlv_althorario_per}> Altera horários</input>
					</label>
				</div>
				
				<div class="col-sm-3">
					<label>
						<input type="checkbox" name="dlv_alttaxa_per" id="dlv_alttaxa_per" value="1" {dlv_alttaxa_per}> Altera taxas</input>
					</label>
				</div>
			</div>	
				
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-3">
					<label>
						<input type="checkbox" name="dlv_altfpg_per" id="dlv_altfpg_per" value="1" {dlv_altfpg_per}> Altera formas de pgto</input>
					</label>
				</div>								
				
				<div class="col-sm-3">
					<label>
						<input type="checkbox" name="dlv_altrede_per" id="dlv_altrede_per" value="1" {dlv_altrede_per}> Altera r. sociais</input>
					</label>
				</div>
				
				<div class="col-sm-3">
					<label>
						<input type="checkbox" name="dlv_altarea_per" id="dlv_altarea_per" value="1" {dlv_altarea_per}> Altera áreas de entrega</input>
					</label>
				</div>				
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-3">
					<label>
						<input type="checkbox" name="dlv_cadtamanho_per" id="dlv_cadtamanho_per" value="1" {dlv_cadtamanho_per}> Cadastra tamanhos</input>
					</label>
				</div>
				
				<div class="col-sm-3">
					<label>
						<input type="checkbox" name="dlv_cadadicional_per" id="dlv_cadadicional_per" value="1" {dlv_cadadicional_per}> Cadastro adicionais</input>
					</label>
				</div>								
				
				<div class="col-sm-3">
					<label>
						<input type="checkbox" name="dlv_cadcategoria_per" id="dlv_allhorario_per" value="1" {dlv_cadcategoria_per}> Cadastra categorias</input>
					</label>
				</div>
			</div>	
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-3">
					<label>
						<input type="checkbox" name="dlv_cadproduto_per" id="dlv_cadproduto_per" value="1" {dlv_cadproduto_per}> Cadastra produtos</input>
					</label>
				</div>	
				
				<div class="col-sm-3">
					<label>
						<input type="checkbox" name="dlv_altstatusped_per" id="dlv_altstatusped_per" value="1" {dlv_altstatusped_per}> Alterar status do pedido</input>
					</label>
				</div>							
				
			</div>								
		
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_perfil" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_PERFIL}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
