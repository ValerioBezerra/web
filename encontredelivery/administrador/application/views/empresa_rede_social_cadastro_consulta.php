<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">
        	Rede Social        
		    <div class="pull-right">
		    	<a href="{ATUALIZAR}" title="Atualizar" class="btn-link"><i class="glyphicon glyphicon-refresh"></i></a> 
		    </div>
	    </h2>	
    </div>    
</div>

<div class="panel panel-default">

	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_exr" name="dlv_id_exr" value="{dlv_id_exr}"/>
			<div class="form-group">
				<div class="{ERRO_DLV_DLVRED_EXR}">
					<label for="dlv_dlvred_exr" class="col-sm-2 control-label">Rede Social</label>
					<div class="col-sm-4">
						<select class="form-control" id="dlv_dlvred_exr" name="dlv_dlvred_exr">
							<option value="">Selecione</option>
							{BLC_REDE_SOCIAL}
								<option value="{DLV_ID_RED}" {SEL_DLV_ID_RED}>{DLV_DESCRICAO_RED}</option>
							{/BLC_REDE_SOCIAL}
						</select>
					</div>
				</div>
				<div class="{ERRO_DLV_LINK_RED}">
					<label for="dlv_link_red" class="col-sm-1 control-label">Link</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="dlv_link_red"  
							   name="dlv_link_red" value="{dlv_link_red}" maxlength="50" autocomplete="off" autofocus/>

					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" name="adicionar_empresa_rede_social" class="btn btn-primary"> <i class="glyphicon glyphicon-plus"></i> Adicionar / <i class="glyphicon glyphicon-pencil"></i> Editar</button>
				</div>
			</div>
						
		
		</form>
	</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-condensed" id="tb_empresa_rede_social">
						<thead>
                            <tr>
                                <th>Rede Social</th>
                                <th>Link</th>
                                <th></th>
                                <th class="coluna-acao"></th>
                            </tr>
                        </thead> 
                        <tbody>
                        	{BLC_DADOS}
	                            <tr>
	                                <td>{DLV_DESCRICAO_RED}</td>
	                                <td>{DLV_LINK_RED}</td>
	                                <td class="text-center"><a class="btn btn-xs btn-social-icon btn-{DLV_CLASSE_RED}"><i class="fa fa-{DLV_CLASSE_RED}"></i></a></td>
	                                <td class="text-center"><a onclick="{APAGAR_REDE_SOCIAL_EMPRESA}" class="btn-link" title="Remover"><i class="glyphicon glyphicon-trash"></i></a></td>
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
                    <h4>Deseja realmente remover esta rede social ?</h4>
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
        location.href = 'empresa_rede_social/remover/' + idExclusao;
    }
</script>


  <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.0.js')?>"></script>
  <script type="text/javascript">
  	$('#dlv_dlvred_exr').change(function(){
  	  	var dlv_dlvred_exr = $('#dlv_dlvred_exr').val();

  	  	if (dlv_dlvred_exr == '') {
  	  		dlv_dlvred_exr = 0;
  	  	}
  	  	
  	  	$.ajax({
		  url: '{URL_REDE_SOCIAL_EMPRESA}/' + dlv_dlvred_exr,  
		  dataType : "json",
		  success: function(data) {
			  $("#dlv_id_exr").attr("value", "");
			  $("#dlv_link_red").attr("value", "");
		  	   
			  if (data != "") {
				  $("#dlv_id_exr").attr("value", data.dlv_id_exr);
				  $("#dlv_link_red").attr("value", data.dlv_link_red);
			  } 
			}
		});
  	});
  </script>


