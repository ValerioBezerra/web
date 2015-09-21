<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">
        	Sabores/Complementos do produto({DLV_DESCRICAO_PRO_PAI})      
		    <div class="pull-right">
		    	<a href="{ATUALIZAR}" title="Atualizar" class="btn-link"><i class="glyphicon glyphicon-refresh"></i></a> 
		    </div>
	    </h2>
    </div>    
</div>

<div class="panel panel-default">

	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_pxp" name="dlv_id_pxp" value="{dlv_id_pxp}"/>
			<input type="hidden" id="dlv_dlvproprincipal_pxp" name="dlv_dlvproprincipal_pxp" value="{dlv_dlvproprincipal_pxp}"/>
			<div class="form-group">
				<div class="{ERRO_DLV_DLVPRO_PXP}">
					<label for="dlv_dlvpro_pxp" class="col-sm-2 control-label">Sabor/Complemento</label>
					<div class="col-sm-4">
						<select class="form-control" id="dlv_dlvpro_pxp" name="dlv_dlvpro_pxp">
							<option value="">Selecione</option>
							{BLC_PRODUTOS}
								<option value="{DLV_ID_PRO}" {SEL_DLV_ID_PRO} {DISABLED}>{DLV_DESCRICAO_PRO}</option>
							{/BLC_PRODUTOS}
						</select>
					</div>
				</div>				
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary"> <i class="glyphicon glyphicon-plus"></i> Adicionar</button>
				</div>
			</div>
						
		
		</form>
	</div>
	
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-condensed" id="tb_produto_produto">
						<thead>
                            <tr>
                                <th>Sabor/Complemento</th>
                                <th class="coluna-acao"></th>
                            </tr>
                        </thead> 
                        <tbody>
                        	{BLC_DADOS}
	                            <tr>
	                                <td>{DLV_DESCRICAO_PRO}</td>
	                                <td class="text-center"><a onclick="{APAGAR_PRODUTO_PRODUTO}" class="btn-link" title="Remover"><i class="glyphicon glyphicon-trash"></i></a></td>
	                            </tr>
                        	{/BLC_DADOS}
                        </tbody>                   
                    </table>
                </div>
                
                <div>
                    <a href="{URL_VOLTAR}" class="btn btn-primary"><i class="glyphicon glyphicon-chevron-left"></i> Voltar</a>
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
                    <h4>Deseja realmente remover este sabor/complemento do produto?</h4>
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
        location.href = '{URL_APAGAR_PRODUTO_PRODUTO}/' + idExclusao;
    }
</script>

