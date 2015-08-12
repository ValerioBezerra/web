<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">
        	Vendas por Período       
		    <div class="pull-right">
		    	<a href="{ATUALIZAR}" title="Atualizar" class="btn-link"><i class="glyphicon glyphicon-refresh"></i></a> 
		    </div>
	    </h2>	
    </div>    
</div>

<div class="panel panel-default">
    <div class="panel-body">
    	<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<div class="form-group">	
		    	<label for="data_inicial" class="col-sm-2 control-label">Data Inicial</label>
		        <div class="col-sm-2">
			    	<div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" data-link-field="dtp_input" data-link-format="yyyy-mm-dd">
			        	<input class="form-control" type="text" id="data_inicial" name="data_inicial" value="{DATA_INICIAL}" readonly>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			        </div>
		        </div>
		                
 				<label for="data_final" class="col-sm-2 control-label">Data Final</label>
 				<div class="col-sm-2">
			    	<div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			        	<input class="form-control" type="text" id="data_final" name="data_final" value="{DATA_FINAL}" readonly>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			      	</div>		 
		      	</div> 
		                
				<div class="col-sm-4">
					<button type="submit" formtarget="_blank" name="imprimir" class="btn btn-block btn-primary"> <i class="glyphicon glyphicon-print"></i> Imprimir</button>
				</div>
			</div>
		</form>
    </div>
</div>

<script src="<?php echo base_url('assets/js/jquery-1.11.0.js')?>"></script>	
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datetimepicker.js')?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/locales/bootstrap-datetimepicker.pt-BR.js')?>" charset="UTF-8"></script>
<script type="text/javascript">
	$('.form_date').datetimepicker({
	    language:  'pt-BR',
	    weekStart: 1,
	    todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
	});
</script>
