<html lang="pt-BR">
<head>
	<link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.ico')?>">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<title>Procure Aki | Painel Administrativo</title>
	
	<link href="<?php echo base_url('assets/css/bootstrap.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/plugins/metisMenu/metisMenu.min.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/plugins/timeline.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/plugins/dataTables.bootstrap.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/plugins/social-buttons.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/sb-admin-2.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/font-awesome-4.3.0/css/font-awesome.min.css')?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('assets/css/dashboard.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/plugins/fileinput/fileinput.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/plugins/file_upload/jquery.fileupload.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/plugins/file_upload/jquery.fileupload-ui.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/bootstrap-datetimepicker.min.css')?>" rel="stylesheet" media="screen">

	</head>
<body>
<!--	<audio id="som">-->
<!--    	<source src="assets/sounds/alerta.mp3"/>-->
<!--	</audio>-->

	<div id="wrapper">
		<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo site_url('')?>">ProkureAki | {BUS_NOME_EMP}</a>
			</div>
		
			<ul class="nav navbar-top-links navbar-right">
<!--				<li>-->
<!--					<a href="--><?php //echo site_url('pedido')?><!--">Pedidos em aberto-->
<!--						<span id="badge_pedidos" class="badge">0</span>-->
<!--					</a>-->
<!--				</li>			-->
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="fa fa-user fa-fw"></i>
							{BUS_NOME_USU}
						<i class="fa fa-caret-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-user">
						<li>
							<a style="{DISPLAY_VALLESOFT_C}" href="<?php echo site_url('configuracoes')?>"><i class="fa fa-gear fa-fw"></i> Configurações</a>
						</li>
						<li style="{DISPLAY_VALLESOFT_C}" class="divider"></li>
						<li>
							<a  href="<?php echo site_url('login')?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
						</li>
					</ul>
					<!-- /.dropdown-user -->
				</li>
			</ul>
		
			<div class="navbar sidebar" role="navigation">
				<div class="sidebar-nav navbar-collapse">
					<ul class="nav" id="side-menu">                        
						<li>
							<a class="active" href="<?php echo site_url('')?>"><i class="fa fa-dashboard fa-fw"></i> <strong>Principal</strong></a>
						</li>
						<li>
							<a  href="#"><i class="fa fa-pencil-square-o fa-fw"></i> CADASTROS<span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">
								<li>
									<a  style="{DISPLAY_VALLESOFT}" href="<?php echo site_url('segmento')?>">Segmento</a>
								</li>
                               <li>
                                   <a style="{DISPLAY_VALLESOFT}" href="<?php echo site_url('plano')?>">Planos</a>
                                  </li>

                               <li>
                                   <a style="{DISPLAY_VALLESOFT}" href="<?php echo site_url('metrica')?>">Metricas</a>
                               </li>

								<li>
									<a style="{DISPLAY_VALLESOFT}" href="<?php echo site_url('tipo')?>">Tipo</a>
								<li>
									<a style="{DISPLAY_VALLESOFT}" href="<?php echo site_url('empresa')?>">Empresas</a>
								</li>
								
								
								<li>
									<a style="{dlv_cadperfil_per}" href="<?php echo site_url('perfil')?>">Perfil</a>
								</li>
								<li>
									<a style="{dlv_cadusuario_per}" href="<?php echo site_url('usuario')?>">Usuários</a>
								</li>
								<li>
									<a style="{dlv_alttelefone_per}" href="<?php echo site_url('telefone')?>">Fones</a>
								</li>



<!-- 								<li> 
									<a style="{dlv_altrede_per}"  href="<?php echo site_url('empresa_rede_social')?>">Redes Sociais</a>
								</li>                                 -->


								<li>
									<hr style="{DISPLAY_VALLESOFT_C}" />  
								</li>  
								                             


								<li>
									<a style="{dlv_cadcategoria_per}" href="<?php echo site_url('categoria')?>">Categorias</a>
								</li>
								<li>
									<a style="{dlv_cadproduto_per}" href="<?php echo site_url('produto')?>">Produtos</a>
								</li>
							</ul>
						</li>
						<li>
							<a href="#"><i class="fa fa-random fa-fw"></i> MOVIMENTO<span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">
								<li>
									<a style="{dlv_altstatusped_per}" href="<?php echo site_url('pedido')?>">Pedidos</a>
								</li>
							</ul>
							<!-- /.nav-second-level -->
						</li>
						<li>
							<a href="#"><i class="fa fa-random fa-fw"></i> RELATÓRIOS<span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">
								<li>
									<a style="{DISPLAY_VALLESOFT_C}" href="<?php echo site_url('venda_diaria')?>">Venda Diária</a>
								</li>
								<li>
									<a style="{DISPLAY_VALLESOFT_C}" href="<?php echo site_url('venda_periodo')?>">Venda por Período</a>
								</li>
							</ul>
							<!-- /.nav-second-level -->
						</li>
					</ul>
				</div>
				<!-- /.sidebar-collapse -->
			</div>
		</nav>
	</div>
	
	<div id="page-wrapper">
		{MENSAGEM_SISTEMA_ERRO}
		{MENSAGEM_SISTEMA_SUCESSO}
		{CONTEUDO}
	</div>
	
	<script src="<?php echo base_url('assets/js/jquery-1.11.0.js')?>"></script>
	<script src="<?php echo base_url('assets/js/jquery.maskedinput.js')?>"></script>
	<script src="<?php echo base_url('assets/js/jquery.fileupload.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.fileupload-process.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.fileupload-image.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.fileupload-validate.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.fileupload-ui.js')?>"></script>	
    <script src="<?php echo base_url('assets/js/plugins/fileinput/fileinput.js')?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/fileinput/fileinput.js')?>"></script>	
	<script src="<?php echo base_url('assets/js/bootstrap.min.js')?>"></script>		
	<script src="<?php echo base_url('assets/js/plugins/metisMenu/metisMenu.min.js')?>"></script>
	<script src="<?php echo base_url('assets/js/sb-admin-2.js')?>"></script>
	<script src="<?php echo base_url('assets/js/jasny-bootstrap.min.js')?>"></script>
	<script src="<?php echo base_url('assets/js/plugins/dataTables/jquery.dataTables.js')?>"></script>
	<script src="<?php echo base_url('assets/js/plugins/dataTables/dataTables.bootstrap.js')?>"></script>
	<script src="<?php echo base_url('assets/js/jquery.alphanumeric.js')?>"></script>
	<script src="<?php echo base_url('assets/js/jquery.maskMoney.js')?>"></script>
	
	<script>
	    $(document).ready(function() {
	        $('#tb_segmento').dataTable({"order": [[ 1, "asc" ]]});
            $('#tb_plano').dataTable({"order": [[ 1, "asc" ]]});
	        $('#tb_cambio').dataTable({"order": [[ 1, "asc" ]]});
	        $('#tb_cargo').dataTable({"order": [[ 1, "asc" ]]});
	        $('#tb_rede_social').dataTable({"order": [[ 1, "asc" ]]});
	        $('#tb_forma_pagamento').dataTable({"order": [[ 1, "asc" ]]});
	        $('#tb_responsavel').dataTable({"order": [[ 1, "asc" ]]});
	        $('#tb_empresa').dataTable({"order": [[ 1, "asc" ]]});
	        $('#tb_empresa_segmento').dataTable({"order": [[ 1, "asc" ]]});
	        $('#tb_perfil').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_usuario').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_telefone').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_tamanho').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_adicional').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_categoria').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_horario').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_empresa_forma_pagamento').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_empresa_rede_social').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_area_entrega').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_area_nao_entrega').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_categoria_tamanho').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_categoria_adicional').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_produto').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_produto_adicional').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_produto_tamanho').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_produto_produto').dataTable({"order": [[ 0, "asc" ]]});
	        $('#tb_entregador').dataTable({"order": [[ 0, "asc" ]]});
            $('input.numero').numeric();
	        $("input.dinheiro").maskMoney({showSymbol:true, symbol:"R$ ", decimal:",", thousands:"."});
		});
	</script>
    <script type="text/javascript">
//		function somPedido() {
//			var som = document.getElementById("som");
//			som.play();
//	  	}
//
//	  	var quantidadePedidoAnterior = 0;
//	  	var quantidadePedidoAtual    = 0;
//
//		function verificarPedidos() {
//	  		$.ajax({
//	  		  url: '{URL_PEDIDO}',
//	  		  dataType : "json",
//	  		  success: function(data) {
//	  			  if (data != "") {
//	  				 quantidadePedidoAtual = data.quantidade;
//	  				 $('#badge_pedidos').html(quantidadePedidoAtual);
//
//	  				 if (quantidadePedidoAtual > 0) {
//	  					somPedido();
//	  				 }
//
//	  				 quantidadePedidoAnterior = quantidadePedidoAtual;
//	  			  }
//	  			}
//	  		});
//	  	}
//
//		window.onload = function(){
//			verificarPedidos();
//			atualizarPedidos();
//		}
//
//  		window.setInterval('verificarPedidos()', 10000);
  </script>	
  

</body>		
</html>
