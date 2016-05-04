<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">
        	Produtos       
		    <div class="pull-right">
		    	<a href="{ATUALIZAR}" title="Atualizar" class="btn-link"><i class="glyphicon glyphicon-refresh"></i></a> 
		    </div>
	    </h2>	
    </div>    
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<div class="{ERRO_BUS_BUSCAT_PRO}">
				<label for="bus_buscat_pro" class="col-sm-1 control-label">Categoria</label>
				<div class="col-sm-4">
					<select class="form-control" id="bus_buscat_pro" name="bus_buscat_pro">
						<option value="0">Selecione</option>
						{BLC_CATEGORIAS}
							<option value="{BUS_ID_CAT}" {SEL_bus_ID_CAT}>{bus_DESCRICAO_CAT}</option>
						{/BLC_CATEGORIAS}
					</select>
				</div>
			</div>
			<div class="col-sm-2">
            	<select class="form-control" id="opcao_status" name="opcao_status"  autofocus>
					<option value="1" {SEL_OPCAO_STATUS_1}>Ativos</option>
					<option value="0" {SEL_OPCAO_STATUS_0}>Inativos</option>
					<option value="2" {SEL_OPCAO_STATUS_2}>Todos</option>					
				</select>
			</div>
			<div class="">
				<button type="submit" class="btn btn-primary"> <i class="glyphicon glyphicon-search"></i> Pesquisar</button>
			</div>
		</form>
	</div>
	
    <div class="panel-body">
        <div class="row">        		   	
            <div class="col-lg-12">            	
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-condensed" id="tb_produto">
						<thead>
                            <tr>
                                <th>Descrição</th>
                                <th width="180">Categoria</th>
                                <th width="80" class="text-center" />
                                <th class="coluna-acao" style="display:{DISPLAY_PRODUTOS};"></th>
                                <th class="coluna-acao" style="display:{DISPLAY_ADICIONAIS};"></th>
                                <th class="coluna-acao" style="display:{DISPLAY_TAMANHOS};"></th>
                                <th class="coluna-acao"></th>
                                <th class="coluna-acao"></th>
                            </tr>
                        </thead> 
                        <tbody>
                        	{BLC_DADOS}
	                            <tr>
	                                <td>{bus_DESCRICAO_PRO}</td>	                                
	                                <td>{bus_DESCRICAO_CAT}</td>
	                                <td class="text-center">
	                                    <a onclick="{ATIVAR_PRODUTO}" style="display:{DISPLAY_ATIVAR};" class="btn btn-success btn-xs">Ativar</a>
	                                    <a onclick="{DESATIVAR_PRODUTO}" style="display:{DISPLAY_DESATIVAR};" class="btn btn-danger btn-xs">Desativar</a>
	                                </td>
	                                <td class="text-center" style="display:{DISPLAY_PRODUTOS};"><a href="{PRODUTO_PRODUTOS}" class="btn-link" title="Sabores/Complementos" {DIS_PRODUTO_PRODUTOS}><i class="glyphicon glyphicon-cutlery"></i></a></td>
	                                <td class="text-center" style="display:{DISPLAY_ADICIONAIS};"><a href="{PRODUTO_ADICIONAIS}" class="btn-link" title="Adicionais"><i class="glyphicon glyphicon-expand"></i></a></td>
	                                <td class="text-center" style="display:{DISPLAY_TAMANHOS};"><a href="{PRODUTO_TAMANHOS}" class="btn-link" title="Tamanhos"><i class="glyphicon glyphicon-fullscreen"></i></a></td>
	                                <td class="text-center"><a href="{EDITAR_PRODUTO}" class="btn-link" title="Editar"><i class="glyphicon glyphicon-pencil"></i></a></td>
	                                <td class="text-center"><a onclick="{APAGAR_PRODUTO}" class="btn-link" title="Apagar"><i class="glyphicon glyphicon-trash"></i></a></td>
	                            </tr>
                        	{/BLC_DADOS}
                        </tbody>                   
                    </table>
                </div>
                	
                <div>
                    <a href="{NOVO_PRODUTO}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Novo produto</a>
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
                    <h4>Deseja realmente apagar esta produto ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="apagar()">Sim</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ativarProdutoModal" tabindex="-1" role="dialog" aria-labelledby="ativarProdutoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h3 class="modal-title" id="ativarProdutoModalLabel">Encontre Delivery</h3>
                </div>
                <div class="modal-body">
                    <h4>Deseja realmente ativar este produto ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="ativarDesativar(1)">Sim</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="desativarProdutoModal" tabindex="-1" role="dialog" aria-labelledby="desativarProdutoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h3 class="modal-title" id="desativarProdutoModalLabel">Encontre Delivery</h3>
                </div>
                <div class="modal-body">
                    <h4>Deseja realmente desativar este produto ?</h4>
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
        location.href = '{URL_APAGAR_PRODUTO}/' + idExclusao;
    }

    function ativarProduto(id){
    	idAtivarDesativar = id;
        $('#ativarProdutoModal').modal('show');
    }

    function desativarProduto(id){
    	idAtivarDesativar = id;
        $('#desativarProdutoModal').modal('show');
    }

    function ativarDesativar(opcao){
    	$('#ativarProdutoModal').modal('hide');
    	$('#desativarProdutoModal').modal('hide');
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

