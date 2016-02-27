<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">
        	Empresas       
		    <div class="pull-right">
		    	<a href="{ATUALIZAR}" title="Atualizar" class="btn-link"><i class="glyphicon glyphicon-refresh"></i></a> 
		    </div>
	    </h2>	
    </div>    
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-condensed" id="tb_empresa">
						<thead>
                            <tr>
                                <th></th>
                                <th>Nome</th>
                                <th width="100" class="text-center">Ativa</th>
                                <th class="coluna-acao"></th>
                                <th class="coluna-acao"></th>
                            </tr>
                        </thead> 
                        <tbody>
                        	{BLC_DADOS}
	                            <tr>
	                                <td  class="text-right">{BUS_ID_EMP}</td>
	                                <td>{BUS_NOME_EMP}</td>
	                                <td class="text-center">
	                                	<input type="checkbox" value="1" {BUS_ATIVO_EMP} disabled/>
	                                </td>
	                                <td class="text-center"><a href="{EDITAR_EMPRESA}" class="btn-link" title="Editar" {VALLESOFT}><i class="glyphicon glyphicon-pencil"></i></a></td>
	                                <td class="text-center"><a onclick="{APAGAR_EMPRESA}" class="btn-link" title="Apagar" {VALLESOFT}><i class="glyphicon glyphicon-trash"></i></a></td>
	                            </tr>
                        	{/BLC_DADOS}
                        </tbody>                   
                    </table>
                </div>
                	

                <div>
                    <a href="{NOVA_EMPRESA}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Nova empresa</a>
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
                    <h4>Deseja realmente apagar esta empresa ?</h4>
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
        location.href = 'empresa/apagar/' + idExclusao;
    }
</script>

