<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Produto - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal" enctype="multipart/form-data">
			<input type="hidden" id="dlv_id_pro" name="dlv_id_pro" value="{dlv_id_pro}"/>
			<div class="form-group">
				<div class="fileinput fileinput-new" data-provides="fileinput"  >
				  <center>
				  
				  <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
				  	<img src="{dlv_foto_pro}">
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
				<div class="{ERRO_DLV_DESCRICAO_PRO}">
					<label for="dlv_descricao_pro" class="col-sm-1 control-label">Descrição</label>
					<div class="col-sm-11">
						<input type="text" class="form-control" id="dlv_descricao_pro"  
							   name="dlv_descricao_pro" value="{dlv_descricao_pro}" maxlength="35" autocomplete="off" autofocus/>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<label for="dlv_detalhamento_pro" class="col-sm-1 control-label">Detalhes</label>
				<div class="col-sm-11">
					<textarea class="form-control not-resize" rows="4" id="dlv_detalhamento_pro" 
						   name="dlv_detalhamento_pro" maxlength="350">{dlv_detalhamento_pro}</textarea>
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
				<div class="{ERRO_DLV_DLVCAT_PRO}">
					<label for="dlv_dlvcat_pro" class="col-sm-1 control-label">Categoria</label>
					<div class="col-sm-4">
						<select class="form-control" id="dlv_dlvcat_pro" name="dlv_dlvcat_pro">
							<option value="">Selecione</option>
							{BLC_CATEGORIAS}
								<option value="{DLV_ID_CAT}" {SEL_DLV_ID_CAT}>{DLV_DESCRICAO_CAT}</option>
							{/BLC_CATEGORIAS}
						</select>
					</div>
				</div>
				
				<div class="{ERRO_DLV_TEMPOPREPARO_PRO} transp">
					<label for="dlv_tempopreparo_pro" class="col-sm-2 control-label">Tempo de Preparo</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="dlv_tempopreparo_pro" data-mask="{MASCARA_HORA}" 
							   name="dlv_tempopreparo_pro" value="{dlv_tempopreparo_pro}" maxlength="20" autocomplete="off"/>
					</div>
				</div>
				
				<div class="transp">
					<label for="dlv_destaque_pro" class="col-sm-1 control-label">Destaque</label>
					<div class="col-sm-2">	
						<select class="form-control" id="dlv_destaque_pro" name="dlv_destaque_pro">
							<option value="0" {dlv_destaque_pro_0}>Não</option>
							<option value="1" {dlv_destaque_pro_1}>Sim</option>
						</select>
					</div>						
				</div>
			</div>	
			
			
			<div class="form-group">
				<div class="{ERRO_DLV_PRECO_PRO}">
					<label for="dlv_preco_pro" class="col-sm-1 control-label">Preço</label>
					<div class="col-sm-3">
						<input type="text" class="form-control dinheiro" id="dlv_preco_pro"  
							   style="text-align: right;" name="dlv_preco_pro" value="{dlv_preco_pro}" maxlength="20"/>
					</div>
				</div>
				
				<div>
					<label for="dlv_promocao_pro" class="col-sm-1 control-label">Promoção</label>
					<div class="col-sm-2">	
						<select class="form-control" id="dlv_promocao_pro" name="dlv_promocao_pro">
							<option value="0" {dlv_promocao_pro_0}>Não</option>
							<option value="1" {dlv_promocao_pro_1}>Sim</option>
						</select>
					</div>						
				</div>
				
				<div class="{ERRO_DLV_PRECOPROMOCINAL_PRO}">
					<label for="dlv_precopromocional_pro" class="col-sm-2 control-label">Preço Promocional</label>
					<div class="col-sm-3">
						<input type="text" class="form-control dinheiro" id="dlv_precopromocional_pro"  
							   style="text-align: right;" name="dlv_precopromocional_pro" value="{dlv_precopromocional_pro}" maxlength="20" {DIS_DLV_PRECOPROMOCINAL_PRO}/>
					</div>
				</div>
			</div>
			
		  	<div class="form-group">
				<div>
					<label for="dlv_ordem_pro" class="col-sm-1 control-label">Ordem</label>
					<div class="col-sm-1">
						<input type="text" style="text-align: right;" class="form-control numero" id="dlv_ordem_pro"  
							   name="dlv_ordem_pro" maxlength="2" value="{dlv_ordem_pro}"/>
					</div>
				</div>
				
		  		<div class="{DIV_USA_ADICIONAIS}">
					<label for="dlv_umadicional_pro" class="col-sm-3 control-label">Somente um adicional</label>
					<div class="col-sm-2">	
						<select class="form-control" id="dlv_umadicional_pro" name="dlv_umadicional_pro">
							<option value="0" {dlv_umadicional_pro_0}>Não</option>
							<option value="1" {dlv_umadicional_pro_1}>Sim</option>
						</select>
					</div>						
				</div>
				
				<div>
					<label for="dlv_principal_pro" class="col-sm-2 control-label">Tela principal</label>
					<div class="col-sm-2">	
						<select class="form-control" id="dlv_principal_pro" name="dlv_principal_pro">
							<option value="0" {dlv_principal_pro_0}>Não</option>
							<option value="1" {dlv_principal_pro_1}>Sim</option>
						</select>
					</div>						
				</div>				
			</div>	
			
			<div class="form-group">
				<div>
					<label class="col-sm-offset-1 col-sm-2">
						<input type="checkbox" name="dlv_ativo_pro" id="dlv_ativo_pro" value="1" {dlv_ativo_pro}> Ativo</input>
					</label>
				</div>
			</div>
			
			<div class="{DIV_ESCOLHE_PRODUTOS}">			
				<div class="panel panel-default"></div>	
				
				<div class="form-group">
					<div>
						<label for="dlv_escolheproduto_pro" class="col-sm-2 control-label">Escolhe produtos</label>
						<div class="col-sm-2">	
							<select class="form-control" id="dlv_escolheproduto_pro" name="dlv_escolheproduto_pro">
								<option value="0" {dlv_escolheproduto_pro_0}>Não</option>
								<option value="1" {dlv_escolheproduto_pro_1}>Sim</option>
							</select>
						</div>						
					</div>
					
					<div class="{ERRO_DLV_QUANTIDADE_PRO}">
						<label for="dlv_quantidade_pro" class="col-sm-1 control-label">Quant.</label>
						<div class="col-sm-1">
							<input type="text" style="text-align: right;" class="form-control numero" id="dlv_quantidade_pro"  
								   name="dlv_quantidade_pro" maxlength="2" value="{dlv_quantidade_pro}" {DIS_DLV_QUANTIDADE_PRO}/>
						</div>
					</div>	
					
					<div class="{ERRO_DLV_UNIDADE_PRO}">
						<label for="dlv_unidade_pro" class="col-sm-1 control-label">Unidade</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="dlv_unidade_pro" {DIS_DLV_UNIDADE_PRO} 
								   name="dlv_unidade_pro" value="{dlv_unidade_pro}" maxlength="20" autocomplete="off" autofocus/>
						</div>
					</div>											
				</div>
				
				<div class="form-group">
					<div class="{ERRO_DLV_PRECOMAIORPRODUTO_PRO}">
						<label for="dlv_precomaiorproduto_pro" class="col-sm-2 control-label">Preço do maior produto</label>
						<div class="col-sm-2">	
							<select class="form-control" id="dlv_precomaiorproduto_pro" name="dlv_precomaiorproduto_pro" {DIS_DLV_PRECOMAIORPRODUTO_PRO}>
								<option value="0" {dlv_precomaiorproduto_pro_0}>Não</option>
								<option value="1" {dlv_precomaiorproduto_pro_1}>Sim</option>
							</select>
						</div>						
					</div>
					
					<div class="{ERRO_DLV_EXIBIRFRACAO_PRO}">
						<label for="dlv_exibirfracao_pro" class="col-sm-2 control-label">Exibir por fração</label>
						<div class="col-sm-2">	
							<select class="form-control" id="dlv_exibirfracao_pro" name="dlv_exibirfracao_pro" {DIS_DLV_EXIBIRFRACAO_PRO}>
								<option value="0" {dlv_exibirfracao_pro_0}>Não</option>
								<option value="1" {dlv_exibirfracao_pro_1}>Sim</option>
							</select>
						</div>						
					</div>				
				</div>
				
				<div class="form-group">
					<div class="{ERRO_DLV_MINPRODUTO_PRO}">
						<label for="dlv_minproduto_pro" class="col-sm-2 control-label">Quant. mínima por item</label>
						<div class="col-sm-2">
							<input type="text" style="text-align: right;" class="form-control numero" id="dlv_minproduto_pro"  
								   name="dlv_minproduto_pro" maxlength="2" value="{dlv_minproduto_pro}" {DIS_DLV_MINPRODUTO_PRO}/>
						</div>
					</div>
						
					<div class="{ERRO_DLV_MAXPRODUTO_PRO}">	
						<label for="dlv_maxproduto_pro" class="col-sm-2 control-label">Quant. máxima por item</label>
						<div class="col-sm-2">
							<input type="text" style="text-align: right;" class="form-control numero" id="dlv_maxproduto_pro"  
								   name="dlv_maxproduto_pro" maxlength="2" value="{dlv_maxproduto_pro}" {DIS_DLV_MAXPRODUTO_PRO}/>
						</div>
					</div>					
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
  	$('#dlv_promocao_pro').change(function(){
  	  	if ($('#dlv_promocao_pro').val() == 0) {
			$('#dlv_precopromocional_pro').attr("readonly", true);
  	  	} else {
			$('#dlv_precopromocional_pro').removeAttr("readonly");
  	  	}
  	});

  	$('#dlv_escolheproduto_pro').change(function(){
  	  	if ($('#dlv_escolheproduto_pro').val() == 0) {
			$('#dlv_quantidade_pro').attr("readonly", true);
			$('#dlv_unidade_pro').attr("readonly", true);
			$('#dlv_precomaiorproduto_pro').attr("readonly", true);
			$('#dlv_exibirfracao_pro').attr("readonly", true);
			$('#dlv_minproduto_pro').attr("readonly", true);
			$('#dlv_maxproduto_pro').attr("readonly", true);
		} else {
			$('#dlv_quantidade_pro').removeAttr("readonly");
			$('#dlv_unidade_pro').removeAttr("readonly");
			$('#dlv_precomaiorproduto_pro').removeAttr("readonly");
			$('#dlv_exibirfracao_pro').removeAttr("readonly");
			$('#dlv_minproduto_pro').removeAttr("readonly");
			$('#dlv_maxproduto_pro').removeAttr("readonly");
		}
  	});  	
  </script>




