<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">
        	Categorias       
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
                    <table class="table table-striped table-bordered table-hover table-condensed" id="tb_categoria">
						<thead>
                            <tr>
                                <th>Descrição</th>
                                <th width="80"  class="text-right">Ordem</th>
                                <th width="80" class="text-center" />
                                <th class="coluna-acao"></th>
                                <th class="coluna-acao"></th>
                            </tr>
                        </thead> 
                        <tbody>
                        	{BLC_DADOS}
	                            <tr>
	                                <td>{BUS_DESCRICAO_CAT}</td>
	                                <td class="text-right">{BUS_ORDEM_CAT}</td>
	                                <td class="text-center">
	                                    <a onclick="{ATIVAR_CATEGORIA}" style="display:{DISPLAY_ATIVAR};" class="btn btn-success btn-xs">Ativar</a>
	                                    <a onclick="{DESATIVAR_CATEGORIA}" style="display:{DISPLAY_DESATIVAR};" class="btn btn-danger btn-xs">Desativar</a>
	                                </td>	                                	                                
	                                <td class="text-center"><a href="{EDITAR_CATEGORIA}" class="btn-link" title="Editar"><i class="glyphicon glyphicon-pencil"></i></a></td>
	                                <td class="text-center"><a onclick="{APAGAR_CATEGORIA}" class="btn-link" title="Apagar"><i class="glyphicon glyphicon-trash"></i></a></td>
	                            </tr>
                        	{/BLC_DADOS}
                        </tbody>                   
                    </table>
                </div>
                	

                <div>
                    <a href="{NOVO_CATEGORIA}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Nova categoria</a>
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
                    <h3 class="modal-title" id="myModalLabel">Procureaki</h3>
                </div>
                <div class="modal-body">
                    <h4>Deseja realmente apagar esta categoria ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="apagar()">Sim</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ativarCategoriaModal" tabindex="-1" role="dialog" aria-labelledby="ativarCategoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h3 class="modal-title" id="ativarCategoriaModalLabel">Procureaki</h3>
                </div>
                <div class="modal-body">
                    <h4>Deseja realmente ativar esta categoria ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="ativarDesativar(1)">Sim</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="desativarCategoriaModal" tabindex="-1" role="dialog" aria-labelledby="desativarCategoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h3 class="modal-title" id="desativarCategoriaModalLabel">Procureaki</h3>
                </div>
                <div class="modal-body">
                    <h4>Deseja realmente desativar esta categoria ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="ativarDesativar(0)">Sim</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAguarde" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Por favor, aguarde...</h3>                    
                </div>
                <div class="modal-body">
                    <div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>
                </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var idExclusao        = "";
    var idAtivarDesativar = "";
    
    function abrirConfirmacao(id){
        idExclusao = id;
        $('#myModal').modal('show');
    }

    function apagar(){
        $('#myModal').modal('hide');
        location.href = 'categoria/apagar/' + idExclusao;
    }

    function ativarCategoria(id){
    	idAtivarDesativar = id;
        $('#ativarCategoriaModal').modal('show');
    }

    function desativarCategoria(id){
    	idAtivarDesativar = id;
        $('#desativarCategoriaModal').modal('show');
    }

    function ativarDesativar(opcao){
    	$('#ativarCategoriaModal').modal('hide');
    	$('#desativarCategoriaModal').modal('hide');
    	$('#modalAguarde').modal('show');
    	
    	$.ajax({
  		  url: '{URL_ATIVAR_DESATIVAR}/' + idAtivarDesativar + '/' + opcao,  
  		  dataType : "json",
  		  success: function(data) {
  	    	$('#modalAguarde').modal('hide');
  		  	location.reload();  		  
  		  }
  		});        
    }
    
</script>

