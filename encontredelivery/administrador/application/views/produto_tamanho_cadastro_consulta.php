<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">
        	Tamanhos do produto({DLV_DESCRICAO_PRO})      
		    <div class="pull-right">
		    	<a href="{ATUALIZAR}" title="Atualizar" class="btn-link"><i class="glyphicon glyphicon-refresh"></i></a> 
		    </div>
	    </h2>
    </div>    
</div>

<div class="panel panel-default">

	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_pxt" name="dlv_id_pxt" value="{dlv_id_pxt}"/>
			<input type="hidden" id="dlv_dlvpro_pxt" name="dlv_dlvpro_pxt" value="{dlv_dlvpro_pxt}"/>
			<div class="form-group">
				<div class="{ERRO_DLV_DLVTAM_PXT}">
					<label for="dlv_dlvtam_pxt" class="col-sm-1 control-label">Tamanho</label>
					<div class="col-sm-4">
						<select class="form-control" id="dlv_dlvtam_pxt" name="dlv_dlvtam_pxt">
							<option value="">Selecione</option>
							{BLC_TAMANHOS}
								<option value="{DLV_ID_TAM}" {SEL_DLV_ID_TAM}>{DLV_DESCRICAO_TAM}</option>
							{/BLC_TAMANHOS}
						</select>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="{ERRO_DLV_PRECO_PXT}">
					<label for="dlv_preco_pxt" class="col-sm-1 control-label">Preço</label>
					<div class="col-sm-3">
						<input type="text" class="form-control dinheiro" id="dlv_preco_pxt"  
							   style="text-align: right;" name="dlv_preco_pxt" value="{dlv_preco_pxt}" maxlength="15"/>
					</div>
				</div>
				
				<div>
					<label for="dlv_promocao_pxt" class="col-sm-1 control-label">Promoção</label>
					<div class="col-sm-2">	
						<select class="form-control" id="dlv_promocao_pxt" name="dlv_promocao_pxt">
							<option value="0" {dlv_promocao_pxt_0}>Não</option>
							<option value="1" {dlv_promocao_pxt_1}>Sim</option>
						</select>
					</div>						
				</div>
				
				<div class="{ERRO_DLV_PRECOPROMOCIONAL_PXT}">
					<label for="dlv_precopromocional_pxt" class="col-sm-2 control-label">Preço Promocional</label>
					<div class="col-sm-3">
						<input type="text" class="form-control dinheiro" id="dlv_precopromocional_pxt"  
							   style="text-align: right;" name="dlv_precopromocional_pxt" value="{dlv_precopromocional_pxt}" maxlength="20" {DIS_DLV_PRECOPROMOCIONAL_PXT}/>
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
                    <table class="table table-striped table-bordered table-hover table-condensed" id="tb_produto_adicional">
						<thead>
                            <tr>
                                <th>Tamanho</th>
                                <th width="120" class="text-right">Preço</th>
                                <th width="90" class="text-center">Promoção</th>
                                <th width="120" class="text-right">Preço Promocional</th>                                
                                <th class="coluna-acao"></th>
                            </tr>
                        </thead> 
                        <tbody>
                        	{BLC_DADOS}
	                            <tr>
	                                <td>{DLV_DESCRICAO_TAM}</td>
	                                <td class="text-right">{DLV_PRECO_PXT}</td>
	                                <td class="text-center">
	                                	<input type="checkbox" value="1" {DLV_PROMOCAO_PXT} disabled/>
	                                </td>
	                                <td class="text-right">{DLV_PRECOPROMOCIONAL_PXT}</td>
	                                <td class="text-center"><a onclick="{APAGAR_PRODUTO_TAMANHO}" class="btn-link" title="Remover"><i class="glyphicon glyphicon-trash"></i></a></td>
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
                    <h4>Deseja realmente remover este tamanho do produto?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="apagar()">Sim</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                </div>
        </div>
    </div>
</div>

 <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.0.js')?>"></script>
 <script type="text/javascript">
    var idExclusao = "";

    $('#dlv_promocao_pxt').change(function(){
  	  	if ($('#dlv_promocao_pxt').val() == 0) {
			$('#dlv_precopromocional_pxt').attr("readonly", true);			
			
  	  	} else {
			$('#dlv_precopromocional_pxt').removeAttr("readonly");
  	  	}
  	});
    
    function abrirConfirmacao(id){
        idExclusao = id;
        $('#myModal').modal('show');
    }

    function apagar(){
        $('#myModal').modal('hide');
        location.href = '{URL_APAGAR_PRODUTO_TAMANHO}/' + idExclusao;
    }
   
</script>

<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.0.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/mask.js')?>"></script>
<script type="text/javascript">
  	$('#dlv_dlvtam_pxt').change(function(){
  		var dlv_dlvpro_pxt = $('#dlv_dlvpro_pxt').val();
  	  	var dlv_dlvtam_pxt = $('#dlv_dlvtam_pxt').val();

  	  	if (dlv_dlvtam_pxt == '') {
  	  		dlv_dlvtam_pxt = 0;
  	  	}
  	  	
  	  	$.ajax({
		  url: '{URL_PRODUTO_TAMANHO}/' + dlv_dlvpro_pxt + '/' + dlv_dlvtam_pxt,  
		  dataType : "json",
		  success: function(data) {
			  $("#dlv_preco_pxt").attr("value", 'R$ 0,00');
			  $("#dlv_promocao_pxt").val(0);
			  $('#dlv_precopromocional_pxt').attr("readonly", true);
			  $("#dlv_precopromocional_pxt").attr("value", 'R$ 0,00');
		  	   
			  if (data != "") {
			  		mascaraMonetaria(data.dlv_preco_pxt);
			  
				  $("#dlv_id_pxt").attr("value", data.dlv_id_pxt);
				  $("#dlv_preco_pxt").attr("value", 'R$ ' + mascaraMonetaria(data.dlv_preco_pxt));
				  $("#dlv_promocao_pxt").val(data.dlv_promocao_pxt);

			  	  if ($('#dlv_promocao_pxt').val() == 0) {
					$('#dlv_precopromocional_pxt').attr("readonly", true);
			  	  } else {
					$('#dlv_precopromocional_pxt').removeAttr("readonly");
			  	  }

			  	$("#dlv_precopromocional_pxt").attr("value", 'R$ ' + mascaraMonetaria(data.dlv_precopromocional_pxt));
				  
			  } 
			}
		});
  	});
  </script>
