<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">
        	Empresa({bus_nome_emp}) - Tipos      
		    <div class="pull-right">
		    	<a href="{ATUALIZAR_TIPO}" title="Atualizar" class="btn-link"><i class="glyphicon glyphicon-refresh"></i></a> 
		    </div>
	    </h2>
    </div>    
</div>

<div class="panel panel-default">

	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="bus_id_ext" name="bus_id_ext" value="{bus_id_ext}"/>
			<input type="hidden" id="bus_busemp_ext" name="bus_busemp_ext" value="{bus_busemp_ext}"/>
			<div class="form-group">
				<div class="{ERRO_BUS_BUSTIP_EXT}">
					<label for="bus_bustip_ext" class="col-sm-1 control-label">Tipo</label>
					<div class="col-sm-4">
						<select class="form-control" id="bus_bustip_ext" name="bus_bustip_ext">
							<option value="">Selecione</option>
							{BLC_TIPO}
								<option value="{BUS_ID_TIP}" {SEL_BUS_ID_TIP}>{BUS_DESCRICAO_TIP}</option>
							{/BLC_TIPO}
						</select>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-10">
					<button type="submit" name="adicionar_empresa_tipo" class="btn btn-primary"> <i class="glyphicon glyphicon-plus"></i> Adicionar</button>
				</div>
			</div>
						
		
		</form>
	</div>
	
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-condensed" id="tb_empresa_tipo">
						<thead>
                            <tr>
                                <th></th>
                                <th>Tipo</th>
                                <th class="coluna-acao"></th>
                            </tr>
                        </thead> 
                        <tbody>
                        	{BLC_DADOS}
	                            <tr>
	                                <td  class="text-right">{BUS_ID_TIP}</td>
	                                <td>{BUS_DESCRICAO_TIP}</td>
	                                <td class="text-center"><a onclick="{REMOVER_TIPO}" class="btn-link" title="Remover"><i class="glyphicon glyphicon-trash"></i></a></td>
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
                    <h3 class="modal-title" id="myModalLabel">Procure Aki</h3>
                </div>
                <div class="modal-body">
                    <h4>Deseja realmente remover este tipo da empresa ?</h4>
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
    var idsegmento = "";

    function abrirConfirmacao(id, segmento){
        idExclusao = id;
        idSegmento = segmento
        $('#myModal').modal('show');
    }

    function apagar(){
        $('#myModal').modal('hide');
        location.href = '{URL_APAGAR_TIPO}/' + idExclusao + '/' + idSegmento ;
    }
</script>

