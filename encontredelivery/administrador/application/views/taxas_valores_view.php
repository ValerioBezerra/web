<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Taxas e Valores</h2>
    </div>
</div>

<div id="content">
	<div class="panel panel-default">
		<div class="panel-body">
	        <form action="{ACAO_TAXAS_VALORES}" role="form" method="post" class="form-horizontal">
				<div class="form-group">
					<div class="{ERRO_DLV_TAXAENTREGA_EMP}">
						<label for="dlv_taxaentrega_emp" class="col-sm-2 control-label">Taxa de entrega</label>
						<div class="col-sm-2">
							<input type="text" class="form-control dinheiro" id="dlv_taxaentrega_emp"  autofocus
								   style="text-align: right;" name="dlv_taxaentrega_emp" value="{dlv_taxaentrega_emp}" maxlength="16"/>
						</div>
					</div>
					
					<div class="{ERRO_DLV_TAXAENTREGA_EMP}">
						<label for="dlv_valorminimo_emp" class="col-sm-2 control-label">Valor mínimo</label>
						<div class="col-sm-2">
							<input type="text" class="form-control dinheiro" id="dlv_valorminimo_emp"  
								   style="text-align: right;" name="dlv_valorminimo_emp" value="{dlv_valorminimo_emp}" maxlength="16"/>
						</div>
					</div>
					
					<div class="{ERRO_DLV_TAXAENTREGA_EMP}">
						<label for="dlv_tempomedio_emp" class="col-sm-2 control-label">Tempo médio</label>
						<div class="col-sm-2">
							<input type="text" class="form-control" id="dlv_tempomedio_emp" data-mask="{MASCARA_HORA}"  
								   name="dlv_tempomedio_emp" value="{dlv_tempomedio_emp}"/>
						</div>
					</div>

                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-11">
                        <label>
                            <input type="checkbox" name="dlv_fechamentoaut_emp" id="dlv_fechamentoaut_emp" {dlv_fechamentoaut_emp}> Fechamento automático</input>
                        </label>
                    </div>
                </div>
				
 				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-11">
						<button type="submit" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Modificar</button>
			        	<a type="button" href="<?php echo site_url('')?>" class="btn btn-default">Cancelar</a>
					</div>
				</div>
	        </form>
		</div>
	</div>	
</div>    
 
 
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#tabs').tab();
    });
</script>    
	
