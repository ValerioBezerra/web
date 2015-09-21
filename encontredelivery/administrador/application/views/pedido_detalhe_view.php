<div class="row">
	<div class="col-lg-12">
		<h2 class="page-header">Detalhes do Pedido - Nro {dlv_id_ped}</h2>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form class="form-horizontal">
			<div class="form-group">
				<label for="dlv_data_ped" class="col-sm-1 control-label">Data</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="dlv_data_ped"
						   name="dlv_data_ped" value="{dlv_data_ped}" disabled>
				</div>

				<label for="dlv_hora_ped" class="col-sm-1 control-label">Hora</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="dlv_hora_ped"
						   name="dlv_hora_ped" value="{dlv_hora_ped}" disabled>
				</div>

				<label for="dlv_nome_cli" class="col-sm-1 control-label">Cliente</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" id="dlv_nome_cli"
						   name="dlv_nome_cli" value="{dlv_nome_cli}" disabled>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-1 control-label" for="glo_cep_end">CEP</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="glo_cep_end"  data-mask="{MASCARA_CEP}" name="glo_cep_end" value="{glo_cep_end}" disabled />
				</div>

				<label class="col-sm-1 control-label" for="glo_logradouro_end">Endereço</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="glo_logradouro_end" name="glo_logradouro_end" value="{glo_logradouro_end}" disabled />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-1 control-label" for="glo_nome_bai">Bairro</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="glo_nome_bai"
						   name="glo_nome_bai" value="{glo_nome_bai}" disabled />
				</div>

				<label class="col-sm-1 control-label" for="glo_nome_cid">Cidade</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="glo_nome_cid"
						   name="glo_nome_cid" value="{glo_nome_cid}" disabled />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-1 control-label" for="dlv_numero_ped">Número</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="dlv_numero_ped"
						   name="dlv_numero_ped" value="{dlv_numero_ped}" disabled />
				</div>

				<label class="col-sm-1 control-label" for="dlv_complemento_ped">Complem.</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="dlv_complemento_ped"
						   name="dlv_complemento_ped" value="{dlv_complemento_ped}" disabled />
				</div>

				<label class="col-sm-1 control-label" for="dlv_fone_cli">Fone.</label>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="dlv_fone_cli"
						   name="dlv_fone_cli" value="{dlv_fone_cli}" disabled />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-1 control-label" for="dlv_totalprodutos_ped">Valor Produtos</label>
				<div class="col-sm-3">
					<input type="text" class="form-control dinheiro" id="dlv_totalprodutos_ped" style="text-align: right;"
						   name="dlv_totalprodutos_ped" value="{dlv_totalprodutos_ped}" disabled />
				</div>

				<label class="col-sm-1 control-label" for="dlv_taxaentrega_ped">Taxa de Entrega</label>
				<div class="col-sm-3">
					<input type="text" class="form-control dinheiro" id="dlv_taxaentrega_ped" style="text-align: right;" 
					       name="dlv_taxaentrega_ped" value="{dlv_taxaentrega_ped}" disabled />
				</div>
				
				<label class="col-sm-1 control-label" for="dlv_desconto_ped">Desconto</label>
				<div class="col-sm-3">
					<input type="text" class="form-control dinheiro" id="dlv_desconto_ped" style="text-align: right;" 
					       name="dlv_desconto_ped" value="{dlv_desconto_ped}" disabled />
				</div>

			</div>

			<div class="form-group">
				<label class="col-sm-1 control-label" for="dlv_total_ped">Valor Total</label>
				<div class="col-sm-3">
					<input type="text" class="form-control dinheiro" id="dlv_total_ped" style="text-align: right;" 
						   name="dlv_total_ped" value="{dlv_total_ped}" disabled />
				</div>
			
				<label class="col-sm-1 control-label" for="dlv_descricao_fpg">Pagamento</label>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="dlv_descricao_fpg"
						   name="dlv_descricao_fpg" value="{dlv_descricao_fpg}" disabled />
				</div>
				
				<div class="{CLASS_DIV_DLV_TROCO_PED}">
					<label class="col-sm-1 control-label" for="dlv_troco_ped">Troco</label>
					<div class="col-sm-3">
						<input type="text" class="form-control dinheiro" id="dlv_troco_ped" style="text-align: right;" 
							   name="dlv_troco_ped" value="{dlv_troco_ped}" disabled />
					</div>
				</div>
			</div>
		</form>
	</div>
</div>


<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Produtos do pedido</h3>
		<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover table-condensed" id="tb_pedidos">
				<thead>
					<tr>
						<th width="450">Produto</th>
						<th width="60" class="text-right">Quantidade</th>
						<th width="110" class="text-right">Preço</th>
						<th width="110" class="text-right">Total</th>
						<th>Observação</th>
					</tr>
				</thead>
				<tbody>
					{BLC_DADOS}
					<tr>
						<td>{DLV_DESCRICAO_PRO}</td>
						<td class="text-right">{DLV_QUANTIDADE_PPE}</td>
						<td class="text-right">{DLV_PRECO_PPE}</td>
						<td class="text-right">{DLV_TOTAL_PPE}</td>
						<td>{DLV_OBSERVACAO_PPE}</td>
					</tr>
					{/BLC_DADOS}
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Status do pedido</h3>
		<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
	</div>
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<input type="hidden" id="dlv_id_ped" name="dlv_id_ped" value="{dlv_id_ped}" />
		
			<div class="form-group">
				<div class="{ERRO_DLV_DLVSTA_SPE}">
					<label for="dlv_dlvsta_spe" class="col-sm-1 control-label">Status</label>
					<div class="col-sm-4">
						<select class="form-control" id="dlv_dlvsta_spe" name="dlv_dlvsta_spe">
							<option value="">Selecione</option>
							{BLC_STATUS}
								<option value="{DLV_ID_STA}" {SEL_DLV_ID_STA}>{DLV_DESCRICAO_STA}</option>
							{/BLC_STATUS}
						</select>
					</div>
				</div>
				
				<div class="{ERRO_DLV_MOTIVOCANC_SPE}">
					<label class="col-sm-1 control-label" for="dlv_motivocanc_spe">Motivo</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="dlv_motivocanc_spe" name="dlv_motivocanc_spe" value="{dlv_motivocanc_spe}" 
						       maxlength="100" autocomplete="off"/>
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
                    <table class="table table-striped table-bordered table-hover table-condensed">
						<thead>
                            <tr>
                                <th>Status</th>
                            	<th width="100" class="text-center">Data</th>
                                <th width="100" class="text-center">Hora</th>
                                <th class="coluna-acao"></th>
                            </tr>
                        </thead> 
                        <tbody>
                        	{BLC_DADOS_STATUS}
	                            <tr>
	                                <td>{DLV_DESCRICAO_STA}</td>
                            		<td class="text-center">{DLV_DATA_STA}</td>
                                	<td class="text-center">{DLV_HORA_STA}</td>
	                                <td class="text-center"><a onclick="{APAGAR_STATUS_PEDIDO}" class="btn-link" title="Remover" {DIS_APAGAR_STATUS_PEDIDO}><i class="glyphicon glyphicon-trash"></i></a></td>
	                            </tr>
                        	{/BLC_DADOS_STATUS}
                        </tbody>                   
                    </table>
                </div>
            </div>
        </div>
    </div>	
</div>

<div>
	<a href="{URL_VOLTAR}" class="btn btn-primary"><i class="glyphicon glyphicon-chevron-left"></i> Voltar</a>
</div>  

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h3 class="modal-title" id="myModalLabel">Encontre Delivery</h3>
                </div>
                <div class="modal-body">
                    <h4>Deseja realmente remover este status do pedido?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="apagar()">Sim</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                </div>
        </div>
    </div>
</div>

<style>
.panel-heading span {
	margin-top: -20px;
	font-size: 15px;
}

.clickable {
	cursor: pointer;
}
</style>

<script src="<?php echo base_url('assets/js/jquery-1.11.0.js')?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js')?>"></script>
<script type="text/javascript">
    jQuery(function ($) {
        $('.panel-heading span.clickable').on("click", function (e) {
            if ($(this).hasClass('panel-collapsed')) {
                // expand the panel
                $(this).parents('.panel').find('.panel-body').slideDown();
                $(this).removeClass('panel-collapsed');
                $(this).find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
            }
            else {
                // collapse the panel
                $(this).parents('.panel').find('.panel-body').slideUp();
                $(this).addClass('panel-collapsed');
                $(this).find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
            }
        });
    });
</script>

<script type="text/javascript">
    var idExclusao = "";

    function abrirConfirmacao(id){
        idExclusao = id;
        $('#myModal').modal('show');
    }

    function apagar(){
        $('#myModal').modal('hide');
        location.href = '{URL_APAGAR_STATUS_PEDIDO}/' + idExclusao;
    }
</script>




