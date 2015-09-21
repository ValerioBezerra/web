<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">
        	Área de Entrega       
		    <div class="pull-right">
		    	<a href="{ATUALIZAR}" title="Atualizar" class="btn-link"><i class="glyphicon glyphicon-refresh"></i></a> 
		    </div>
	    </h2>	
    </div>    
</div>

<div class="panel panel-default">

	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_aen" name="dlv_id_aen" value="{dlv_id_aen}"/>
			<div class="form-group">
				<div class="{ERRO_GLO_ID_EST}">
					<label for="glo_id_est" class="col-sm-1 control-label">Estado</label>
					<div class="col-sm-3">
						<select class="form-control" id="glo_id_est" name="glo_id_est">
							<option value="">Selecione</option>
							{BLC_ESTADO}
								<option value="{GLO_ID_EST}" {SEL_GLO_ID_EST}>{GLO_NOME_EST}</option>
							{/BLC_ESTADO}
						</select>
					</div>
				</div>
				
				<div class="{ERRO_DLV_GLOCID_AEN}">
					<label for="dlv_glocid_aen" class="col-sm-1 control-label">Cidade</label>
					<div class="col-sm-4">
						<select class="form-control" id="dlv_glocid_aen" name="dlv_glocid_aen" {DIS_DLV_GLOCID_AEN}>
							<option value="">Selecione</option>
							{BLC_CIDADE}
								<option value="{GLO_ID_CID}" {SEL_GLO_ID_CID}>{GLO_NOME_CID}</option>
							{/BLC_CIDADE}
						</select>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-10">
					<button type="submit" class="btn btn-primary"> <i class="glyphicon glyphicon-plus"></i> Adicionar</button>
				</div>
			</div>
						
		
		</form>
	</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-condensed" id="tb_area_entrega">
						<thead>
                            <tr>
                                <th>Cidade</th>
                                <th width="200">Estado</th>
                                <th class="coluna-acao"></th>
                                <th class="coluna-acao"></th>
                                <th class="coluna-acao"></th>
                            </tr>
                        </thead> 
                        <tbody>
                        	{BLC_DADOS}
	                            <tr>
	                                <td>{GLO_NOME_CID}</td>
	                                <td>{GLO_NOME_EST}</td>
	                                <td class="text-center"><a href="{AREA_NAO_ENTREGA}" class="btn-link" title="Área de não entrega"><i class="glyphicon glyphicon-ban-circle"></i></a></td>
	                                <td class="text-center"><a href="{TAXA_BAIRRO}" class="btn-link" title="Taxa de entrega por bairro"><i class="glyphicon glyphicon glyphicon-usd"></i></a></td>
	                                <td class="text-center"><a onclick="{APAGAR_AREA_ENTREGA}" class="btn-link" title="Remover"><i class="glyphicon glyphicon-trash"></i></a></td>
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
                    <h4>Deseja realmente remover esta área de entrega ?</h4>
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
        location.href = 'area_entrega/remover/' + idExclusao;
    }
</script>

  <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.0.js')?>"></script>
  <script type="text/javascript">
  	$('#glo_id_est').change(function(){
  	  	var glo_id_est = $('#glo_id_est').val();

  	  	if (glo_id_est == '') {
  	  		glo_id_est = 0;
  	  	}

 		$('#dlv_glocid_aen').html('<option value="">Carregando...</option>');	
  		$('#dlv_glocid_aen').attr("disabled", true);
  		
	  	$.ajax({
		  url: '{URL_ENDERECO}/' + glo_id_est,  
		  dataType : "json",
		  success: function(data) { 
			  if (data == "") {
				  $('#dlv_glocid_aen').html('<option value="">Selecione</option>');	
			  	  $('#dlv_glocid_aen').attr("disabled", true);	
			  } else {
				  var options = '<option value="">Selecione</option>';	

				  for (var i = 0; i < data.length; i++) {
			      	options += '<option value="' + data[i].glo_id_cid + '">' + data[i].glo_nome_cid + '</option>';
				  }

				  $('#dlv_glocid_aen').html(options).show();
				  $('#dlv_glocid_aen').removeAttr("disabled");
			  } 
			}
		});
  	});
  </script>


