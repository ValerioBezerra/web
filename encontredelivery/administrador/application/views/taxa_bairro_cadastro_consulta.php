<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">
        	Taxa de entrega por bairro({GLO_NOME_CID} - {GLO_UF_EST})      
		    <div class="pull-right">
		    	<a href="{ATUALIZAR}" title="Atualizar" class="btn-link"><i class="glyphicon glyphicon-refresh"></i></a> 
		    </div>
	    </h2>
    </div>    
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_txb" name="dlv_id_txb" value="{dlv_id_txb}"/>
			<input type="hidden" id="dlv_dlvaen_txb" name="dlv_dlvaen_txb" value="{dlv_dlvaen_txb}"/>
			<input type="hidden" id="glo_id_cid" name="glo_id_cid" value="{glo_id_cid}"/>
			<div class="form-group">
				<div class="{ERRO_DLV_GLOBAI_TXB}">
					<label for="dlv_globai_txb" class="col-sm-1 control-label">Bairro</label>
					<div class="col-sm-4">
						<select class="form-control" id="dlv_globai_txb" name="dlv_globai_txb">
							<option value="">Selecione</option>
							{BLC_BAIRROS}
								<option value="{GLO_ID_BAI}" {SEL_GLO_ID_BAI}>{GLO_NOME_BAI}</option>
							{/BLC_BAIRROS}
						</select>
					</div>
				</div>	
					
				<div class="{ERRO_DLV_TAXAENTREGA_TXB}">
					<label for="dlv_taxaentrega_txb" class="col-sm-1 control-label">Taxa</label>
					<div class="col-sm-3">
						<input type="text" class="form-control dinheiro" id="dlv_taxaentrega_txb"  
							   style="text-align: right;" name="dlv_taxaentrega_txb" value="{dlv_taxaentrega_txb}" maxlength="15"/>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-10">
					<button type="submit" class="btn btn-primary"> <i class="glyphicon glyphicon-plus"></i> Adicionar / <i class="glyphicon glyphicon-pencil"></i> Editar</button>
				</div>
			</div>
						
		
		</form>
	</div>
	
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-condensed" id="tb_area_nao_entrega">
						<thead>
                            <tr>
                                <th>Bairro</th>
                                <th width="120" class="text-right">Taxa</th>
                                <th class="coluna-acao"></th>
                            </tr>
                        </thead> 
                        <tbody>
                        	{BLC_DADOS}
	                            <tr>
	                                <td>{GLO_NOME_BAI}</td>
	                                 <td class="text-right">{DLV_TAXAENTREGA_TXB}</td>
	                                <td class="text-center"><a onclick="{APAGAR_TAXA_BAIRRO}" class="btn-link" title="Remover"><i class="glyphicon glyphicon-trash"></i></a></td>
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
                    <h4>Deseja realmente remover esta taxa de entrega por bairro?</h4>
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
        location.href = '{URL_APAGAR_TAXA_BAIRRO}/' + idExclusao;
    }
</script>

<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.0.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/mask.js')?>"></script>
<script type="text/javascript">
  	$('#dlv_globai_txb').change(function(){
  	  	var dlv_dlvaen_txb = $('#dlv_dlvaen_txb').val();
  		var dlv_globai_txb = $('#dlv_globai_txb').val();

  	  	if (dlv_globai_txb == '') {
  	  		dlv_globai_txb = 0;
  	  	}
  	  	
  	  	$.ajax({
		  url: '{URL_TAXA_BAIRRO}/' + dlv_dlvaen_txb + '/' + dlv_globai_txb,  
		  dataType : "json",
		  success: function(data) {
			  $("#dlv_taxaentrega_txb").attr("value", 'R$ 0,00');
		  	   
			  if (data != "") {
				  $("#dlv_id_txb").attr("value", data.dlv_id_txb);
				  $("#dlv_taxaentrega_txb").attr("value", 'R$ ' + mascaraMonetaria(data.dlv_taxaentrega_txb));
			  } 
			}
		});
  	});
</script>


