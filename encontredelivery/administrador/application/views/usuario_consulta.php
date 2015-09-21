<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">
        	Usuários        
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
                    <table class="table table-striped table-bordered table-hover table-condensed" id="tb_usuario">
						<thead>
                            <tr>
                                <th>Nome</th>
                                <th>Perfil</th>
                                <th width="80" class="text-center">Ativo</th>
                                <th class="coluna-acao"></th>
                                <th class="coluna-acao"></th>
                            </tr>
                        </thead> 
                        <tbody>
                        	{BLC_DADOS}
	                            <tr>
	                                <td>{DLV_NOME_USU}</td>
	                                <td>{DLV_DESCRICAO_PER}</td>
	                                <td class="text-center">
	                                	<input type="checkbox" value="1" {DLV_ATIVO_USU} disabled/>
	                                </td>
	                                <td class="text-center"><a href="{EDITAR_USUARIO}" class="btn-link" title="Editar"><i class="glyphicon glyphicon-pencil"></i></a></td>
	                                <td class="text-center"><a onclick="{APAGAR_USUARIO}" class="btn-link" title="Apagar"><i class="glyphicon glyphicon-trash"></i></a></td>
	                            </tr>
                        	{/BLC_DADOS}
                        </tbody>                   
                    </table>
                </div>
                	

                <div>
                    <a href="{NOVO_USUARIO}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Novo usuario</a>
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
                    <h4>Deseja realmente apagar este usuario ?</h4>
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
        location.href = 'usuario/apagar/' + idExclusao;
    }
</script>

