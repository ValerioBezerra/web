<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Configurações</h2>
    </div>
</div>

<div id="content">
    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li class="active"><a href="#senha" data-toggle="tab">Senha</a></li>
    </ul>
    <div id="my-tab-content" class="tab-content">
        <div class="tab-pane active" id="senha">
			<div class="panel panel-default">
				<div class="panel-body">
			        <form action="{ACAO_SENHA}" role="form" method="post" class="form-horizontal">
					<div class="form-group">
							<label for="dlv_nome_usu" class="col-sm-1 control-label">Nome</label>
							<div class="col-sm-11">
								<input type="text" class="form-control" id="dlv_nome_usu" 
									   name="dlv_nome_usu" value="{dlv_nome_usu}" disabled/>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-offset-1 col-sm-3 {ERRO_DLV_SENHA_USU_ATUAL}">
								<input type="password" class="form-control" id="dlv_senha_usu_atual" 
									   name="dlv_senha_usu_atual" value="{dlv_senha_usu_atual}" placeholder="Senha atual" maxlength="8" autofocus/>
							</div>
							
							<div class="col-sm-3 {ERRO_DLV_SENHA_USU_NOVA}">
								<input type="password" class="form-control" id="dlv_senha_usu_nova" 
									   name="dlv_senha_usu_nova" value="{dlv_senha_usu_nova}" placeholder="Nova senha" maxlength="8"/>
							</div>
							
							<div class="col-sm-3 {ERRO_DLV_SENHA_USU_CONFIRMAR}">
								<input type="password" class="form-control" id="dlv_senha_usu_confirmar" 
									   name="dlv_senha_usu_confirmar" value="{dlv_senha_usu_confirmar}" placeholder="Confirmar senha" maxlength="8"/>
							</div>							
						</div>
						
						
						<div class="form-group">
							<div class="col-sm-offset-1 col-sm-11">
								<button type="submit" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Modificar</button>
					        	<a type="button" href="<?php echo site_url('')?>" class="btn btn-default">Cancelar</a>
							</div>
						</div>
			        </form>
				</div>
			</div>	
        </div>
    </div>
</div>    
 
 
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#tabs').tab();
    });
</script>    
	
