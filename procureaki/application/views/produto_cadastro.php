<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Produto - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal" enctype="multipart/form-data">
			<input type="hidden" id="bus_id_pro" name="bus_id_pro" value="{bus_id_pro}"/>
			<div class="form-group">
				<div class="fileinput fileinput-new" data-provides="fileinput"  >
				  <center>
				  
				  <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
				  	<img src="{bus_foto_pro}">
				  </div>
				  <div>
				    <span class="btn btn-default btn-file"> 
				    	<span class="fileinput-new">Selecione a imagem</span>
				    	<input type="file" name="image" accept="image/jpeg, image/png">
				    </span>
				    
				  </div>
				  </center>
				</div>
			</div>			
			
			<div class="form-group">	
				<div class="{ERRO_bus_DESCRICAO_PRO}">
					<label for="bus_descricao_pro" class="col-sm-1 control-label">Descrição</label>
					<div class="col-sm-11">
						<input type="text" class="form-control" id="bus_descricao_pro"  
							   name="bus_descricao_pro" value="{bus_descricao_pro}" maxlength="35" autocomplete="off" autofocus/>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<label for="bus_detalhamento_pro" class="col-sm-1 control-label">Detalhes</label>
				<div class="col-sm-11">
					<textarea class="form-control not-resize" rows="4" id="bus_detalhamento_pro" 
						   name="bus_detalhamento_pro" maxlength="350">{bus_detalhamento_pro}</textarea>
				</div>
				
				
<!-- 				<div class="form-group"> -->
<!-- 					<div class="col-sm-3 text-center"> -->
<!--       					<img style="height:123px" src="<?php echo base_url('assets/images/produtos/1.jpg')?>" alt="" class="img-rounded">  -->
<!-- 	  				</div> -->
<!-- 					<div class="col-sm-4 text-center "> -->
<!-- 						<button type="submit" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Carregar Imagem</button> -->
<!-- 					</div> -->
<!-- 	  			</div>	 -->
			</div>
			
			<div class="form-group">
				<div class="{ERRO_bus_busCAT_PRO}">
					<label for="bus_buscat_pro" class="col-sm-1 control-label">Categoria</label>
					<div class="col-sm-4">
						<select class="form-control" id="bus_buscat_pro" name="bus_buscat_pro">
							<option value="">Selecione</option>
							{BLC_CATEGORIAS}
								<option value="{bus_ID_CAT}" {SEL_bus_ID_CAT}>{bus_DESCRICAO_CAT}</option>
							{/BLC_CATEGORIAS}
						</select>
					</div>
				</div>
				
				
				
				
			
			
			<div class="form-group">
				<div class="{ERRO_bus_PRECO_PRO}">
					<label for="bus_preco_pro" class="col-sm-1 control-label">Preço</label>
					<div class="col-sm-3">
						<input type="text" class="form-control dinheiro" id="bus_preco_pro"  
							   style="text-align: right;" name="bus_preco_pro" value="{bus_preco_pro}" maxlength="20"/>
					</div>
				</div>
				
				<div>
					<label for="bus_promocao_pro" class="col-sm-1 control-label">Promoção</label>
					<div class="col-sm-2">	
						<select class="form-control" id="bus_promocao_pro" name="bus_promocao_pro">
							<option value="0" {bus_promocao_pro_0}>Não</option>
							<option value="1" {bus_promocao_pro_1}>Sim</option>
						</select>
					</div>						
				</div>
				

			
		  	<div class="form-group">
				<div>
					<label for="bus_ordem_pro" class="col-sm-1 control-label">Ordem</label>
					<div class="col-sm-1">
						<input type="text" style="text-align: right;" class="form-control numero" id="bus_ordem_pro"  
							   name="bus_ordem_pro" maxlength="2" value="{bus_ordem_pro}"/>
					</div>
				</div>
				
		  		
				
			
			
			<div class="form-group">
				<div>
					<label class="col-sm-offset-1 col-sm-2">
						<input type="checkbox" name="bus_ativo_pro" id="bus_ativo_pro" value="1" {bus_ativo_pro}> Ativo</input>
					</label>
				</div>
			</div>
			
			<div class="{DIV_ESCOLHE_PRODUTOS}">			
				<div class="panel panel-default"></div>	
				

					
					
				
				

						
								
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_produto" class="btn btn-primary" value="salvar"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
					<button type="submit" name="salvar_produto" class="btn btn-primary" value="salvar_novo"> <i class="glyphicon glyphicon-ok"></i> Salvar e Novo</button>
					
		        	<a type="button" href="{CONSULTA_PRODUTO}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>

  <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.0.js')?>"></script>
  <script type="text/javascript">
  	$('#bus_promocao_pro').change(function(){
  	  	if ($('#bus_promocao_pro').val() == 0) {
			$('#bus_precopromocional_pro').attr("readonly", true);
  	  	} else {
			$('#bus_precopromocional_pro').removeAttr("readonly");
  	  	}
  	});

 
  </script>




