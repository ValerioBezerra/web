<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">
        	Empresa({dlv_nome_emp}) - Segmentos      
		    <div class="pull-right">
		    	<a href="{ATUALIZAR_SEGMENTOS}" title="Atualizar" class="btn-link"><i class="glyphicon glyphicon-refresh"></i></a> 
		    </div>
	    </h2>
    </div>    
</div>

<div class="panel panel-default">

	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_exs" name="dlv_id_exs" value="{dlv_id_exs}"/>
			<input type="hidden" id="dlv_dlvemp_exs" name="dlv_dlvemp_exs" value="{dlv_dlvemp_exs}"/>
			<div class="form-group">
				<div class="{ERRO_DLV_DLVSEG_EXS}">
					<label for="dlv_dlvseg_exs" class="col-sm-1 control-label">Segmento</label>
					<div class="col-sm-4">
						<select class="form-control" id="dlv_dlvseg_exs" name="dlv_dlvseg_exs">
							<option value="">Selecione</option>
							{BLC_SEGMENTOS}
								<option value="{DLV_ID_SEG}" {SEL_DLV_ID_SEG}>{DLV_DESCRICAO_SEG}</option>
							{/BLC_SEGMENTOS}
						</select>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-10">
					<button type="submit" name="adicionar_empresa_segmento" class="btn btn-primary"> <i class="glyphicon glyphicon-plus"></i> Adicionar</button>
				</div>
			</div>
						
		
		</form>
	</div>
	
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-condensed" id="tb_empresa_segmento">
						<thead>
                            <tr>
                                <th></th>
                                <th>Segmento</th>
                                <th class="coluna-acao"></th>
                            </tr>
                        </thead> 
                        <tbody>
                        	{BLC_DADOS}
	                            <tr>
	                                <td  class="text-right">{DLV_ID_SEG}</td>
	                                <td>{DLV_DESCRICAO_SEG}</td>
	                                <td class="text-center"><a onclick="{REMOVER_SEGMENTO}" class="btn-link" title="Remover"><i class="glyphicon glyphicon-trash"></i></a></td>
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
                    <h4>Deseja realmente remover este segmento da empresa ?</h4>
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
        location.href = '{URL_APAGAR_SEGMENTO}/' + idExclusao;
    }
</script>

