<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">
        	Formas de Pagamento        
		    <div class="pull-right">
		    	<a href="{ATUALIZAR}" title="Atualizar" class="btn-link"><i class="glyphicon glyphicon-refresh"></i></a> 
		    </div>
	    </h2>	
    </div>    
</div>

<div class="panel panel-default">

	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_exf" name="dlv_id_exf" value="{dlv_id_exf}"/>
			<div class="form-group">
				<div class="{ERRO_DLV_DLVFPG_EXF}">
					<label for="dlv_dlvfpg_exf" class="col-sm-3 control-label">Forma de Pagamento</label>
					<div class="col-sm-4">
						<select class="form-control" id="dlv_dlvfpg_exf" name="dlv_dlvfpg_exf">
							<option value="">Selecione</option>
							{BLC_FORMAS_PAGAMENTO}
								<option value="{DLV_ID_FPG}" {SEL_DLV_ID_FPG}>{DLV_DESCRICAO_FPG}</option>
							{/BLC_FORMAS_PAGAMENTO}
						</select>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-10">
					<button type="submit" name="adicionar_empresa_segmento" class="btn btn-primary"> <i class="glyphicon glyphicon-plus"></i> Adicionar</button>
				</div>
			</div>
						
		
		</form>
	</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-condensed" id="tb_empresa_forma_pagamento">
						<thead>
                            <tr>
                                <th>Forma de Pagamento</th>
                                <th class="coluna-acao"></th>
                            </tr>
                        </thead> 
                        <tbody>
                        	{BLC_DADOS}
	                            <tr>
	                                <td>{DLV_DESCRICAO_FPG}</td>
	                                <td class="text-center"><a onclick="{APAGAR_FORMA_PAGAMENTO_EMPRESA}" class="btn-link" title="Remover"><i class="glyphicon glyphicon-trash"></i></a></td>
	                            </tr>
                        	{/BLC_DADOS}
                        </tbody>                   
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h3 class="modal-title" id="myModalLabel">Encontre Delivery</h3>
                </div>
                <div class="modal-body">
                    <h4>Deseja realmente remover esta forma de pagamento ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="apagar()">Sim</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var idExclusao = "";

    function abrirConfirmacao(id){
        idExclusao = id;
        $('#myModal').modal('show');
    }

    function apagar(){
        $('#myModal').modal('hide');
        location.href = 'empresa_forma_pagamento/remover/' + idExclusao;
    }
</script>

