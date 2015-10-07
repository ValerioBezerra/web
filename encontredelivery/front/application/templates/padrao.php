<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<link rel="shortcut icon" href="assets/images/favicon.ico">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<title>Encontre Delivery</title>
	
    <link href="assets/css/bootstrap.css" rel="stylesheet">
	<link href="assets/css/plugins/social-buttons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet" >
	<link href="assets/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">	
	<link href="assets/css/select2.css" rel="stylesheet" >
	<link href="assets/css/plugins/multiselect/bootstrap-multiselect.css" rel="stylesheet">
	<link href="assets/css/plugins/popbox/popbox.css" rel="stylesheet">
	<link href="assets/css/plugins/jquery-editable/jquery-editable.css" rel="stylesheet">
	<link href="assets/css/plugins/popModal/popModal.css" rel="stylesheet">
</head>
<body>
    <div class="navbar navbar-default section_nav">
    	<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo site_url('')?>"><img height="20" alt="Brand" src="assets\images\front\ED3.png"> Encontre Delivery</a>									
		</div>
		
        <div class="collapse navbar-collapse navbar-color navbar-right" id="bs-example-navbar-collapse-1">
        	<ul class="nav navbar-nav">
            	<li class="dropdown">
                	<a class="dropdown-toggle" data-toggle="dropdown">
                		Sobre
                		<i class="fa fa-caret-down"></i>
                	</a>
					<ul class="dropdown-menu dropdown-user">
						<li>
							<a href="<?php echo site_url('meus_pedidos')?>">Termos e condições de uso</a>
						</li>
						<li>
							<a href="<?php echo site_url('meus_endereços')?>">Privacidade</a>
						</li>
						<li>
							<a href="<?php echo site_url('meus_vouchers')?>">Quem somos</a>
						</li>
					</ul>
                </li>
                <li>
               		<a style="{DISPLAY_CLIENTE_DESLOGADO}" onclick="abrirLogin()">Login</a>
                </li>
                <li>
                	<a style="{DISPLAY_CLIENTE_DESLOGADO}" onclick="abrirCadastro()">Cadastrar</a>
                </li>
				<li class="dropdown">
					<a style="{DISPLAY_CLIENTE_LOGADO}" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-user fa-fw"></i>
							{DLV_NOME_CLI}		
						<i class="fa fa-caret-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-user">
						<li>
							<a href="<?php echo site_url('meus_pedidos')?>"><i class="glyphicon glyphicon-list-alt"></i> Meus pedidos</a>
						</li>
						<li>
							<a href="<?php echo site_url('meus_endereços')?>"><i class="glyphicon glyphicon-map-marker"></i> Meus endereços</a>
						</li>
						<li>
							<a href="<?php echo site_url('meus_vouchers')?>"><i class="glyphicon glyphicon-usd"></i> Meus vouchers</a>
						</li>
						<li>
							<a href="<?php echo site_url('meus_dados')?>"><i class="glyphicon glyphicon-user"></i> Meus dados</a>
						</li>
						<li class="divider"></li>
						<li>
							<a  href="<?php echo site_url('inicio/sair')?>"><i class="fa fa-sign-out fa-fw"></i> Sair</a>
						</li>
					</ul>
				</li>                
            </ul>
    	</div>      	      
    </div>
     
	<div class="section ">
    	<div class="container">
        	<div class="fade modal modal-footer" id="login_modal">
            	<div class="modal-dialog">
                	<div class="modal-content">
                    	<div class="modal-header">
                        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 class="modal-title text-center">Login</h3>
                      	</div>
                      	<div class="modal-body">
                        	<form role="form" class="form-horizontal">
                                <div id="div_login_email" class="form-group">
                                	<input class="form-control" type="email" id="login_email" name="login_email" placeholder="Email" maxlength="50">
									<label class="control-label" id="label_login_email" for="login_email" hidden></label>
                                </div>
                                
                                <div id="div_login_senha" class="form-group">
                                	<input class="form-control" type="password" id="login_senha" name="login_senha" placeholder="Senha" maxlength="12">
									<label class="control-label" id="label_login_senha" for="login_senha" hidden></label>
                                </div>
                        	
                              	<div class="form-group text-left">
                                	<a href="#" class="col-xs=6"><span class="text ">Esqueci minha senha</span></a>
                                </div>
                       		</form>
                                
                            <div class="form-group">
                            	<button onclick="login()" class="btn btn-block btn-lg btn-primary">Entrar</button>
                            </div>
                            
                       		<div class="form-group">
	                        	<button onclick="loginFacebook()" class="btn btn-block btn-lg btn-primary btn-facebook">
	                            	<i class="fa fa-facebook fa-fw fa-lg pull-left"></i>Entrar com Facebook
	                            </button>
                            </div>
                    	</div>        
                   	</div>
               	</div>
           	</div>
        </div>
	</div> 
	
 	<div class="section">
		<div class="container">
        	<div class="fade modal modal-footer" id="cadastro_modal">
            	<div class="modal-dialog">
                	<div class="modal-content">
                    	<div class="modal-header">
                        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 class="modal-title text-center">Cadastre-se</h3>
                        </div>
                        
                        <div class="modal-body">
                    		<form role="form" class="form-horizontal" id="form_cadastro">
                        		<div id="div_cadastro_nome" class="form-group text-left">
                        			<input class="form-control" type="text" id="cadastro_nome" name="cadastro_nome" placeholder="Nome" maxlength="50">
									<label class="control-label" id="label_cadastro_nome" for="cadastro_nome" hidden></label>
                        		</div>
                                
                                <div id="div_cadastro_email" class="form-group">
                                	<input class="form-control" type="email" id="cadastro_email" name="cadastro_email" placeholder="Email" maxlength="50">
									<label class="control-label" id="label_cadastro_email" for="cadastro_email" hidden></label>
                                </div>
                                
                                <div id="div_cadastro_fone" class="form-group">
                                	<input class="form-control mask-fone" type="fone" id="cadastro_fone" name="cadastro_fone" placeholder="Celular ou Telefone">
									<label class="control-label" id="label_cadastro_fone" for="cadastro_fone" hidden></label>
                                </div>
                                
                                <div id="div_cadastro_senha" class="form-group">
                                	<input class="form-control" type="password" id="cadastro_senha" name="cadastro_senha" placeholder="Senha" maxlength="12">
									<label class="control-label" id="label_cadastro_senha" for="cadastro_senha" hidden></label>
                                </div>
                               	
                                <div id="div_cadastro_confirmar_senha" class="form-group">
                                	<input class="form-control" type="password" id="cadastro_confirmar_senha" name="cadastro_confirmar_senha" placeholder="Confirmar senha" maxlength="12">
									<label class="control-label" id="label_cadastro_confirmar_senha" for="cadastro_confirmar_senha" hidden></label>
                                </div>
                               	
                               	<div id="div_cadastro_termo_politica" class="form-group text-left">
	                                <label class="control-label"> 
	                                	<input type="checkbox" id="cadastro_termo_politica" name="cadastro_termo_politica"> Li e aceito os <a href="#">termos de uso e as políticas de privacidade.</a>
	                               	</label>
                               	</div>
                        	</form>
                        
                            <div class="form-group">
                            	<button onclick="cadastrar()" class="btn btn-block btn-lg btn-primary">Cadastrar</button>
                            </div>
                            
                            <div class="form-group">
	                        	<button onclick="cadastrarFacebook()" type="submit" class="btn btn-block btn-lg btn-primary btn-facebook">
	                            	<i class="fa fa-facebook fa-fw fa-lg pull-left"></i>Cadastrar com Facebook
	                            </button>
	                        </div> 
	                	</div>         	      
                        
                    </div>
                </div>
            </div>
        </div>
    </div>     

	<div id="page-wrapper">
		{MENSAGEM_SISTEMA_ERRO}
		{MENSAGEM_SISTEMA_SUCESSO}
		{CONTEUDO}
	</div>
	
	<script src="assets/js/jquery-1.11.0.js"></script>
	<script src="assets/js/jquery.maskedinput.min.js"></script>
	<script src="assets/js/jquery.validate.js"></script>
	<script src="assets/js/mask-fields.js"></script>
    <script src="assets/js/plugins/fileinput/fileinput.js"></script>
    <script src="assets/js/plugins/fileinput/fileinput.js"></script>	
	<script src="assets/js/bootstrap.min.js"></script>		
	<script src="assets/js/plugins/metisMenu/metisMenu.min.js"></script>
	<script src="assets/js/sb-admin-2.js"></script>
	<script src="assets/js/jasny-bootstrap.min.js"></script>
	<script src="assets/js/plugins/dataTables/jquery.dataTables.js"></script>
	<script src="assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
	<script src="assets/js/jquery.alphanumeric.js"></script>
	<script src="assets/js/jquery.maskMoney.js"></script>
	<script src="assets/js/select2.full.js"></script>
	<script src="assets/js/plugins/multiselect/bootstrap-multiselect.js"></script>
	<script src="assets/js/plugins/popbox/popbox.js"></script>
	<script src="assets/js/plugins/jquery-editable/jquery-editable-poshytip.js"></script>
	<script src="assets/js/plugins/popModal/popModal.js"></script>
<!-- 	<script src="assets/js/plugins/simpler-sidebar/simpler-sidebar.js"></script> -->

	<script type="text/javascript">
		window.fbAsyncInit = function() {
			FB.init({
				appId      : '1385834838396217',
				xfbml      : true,
				version    : 'v2.4'
			});
		};

		(function(d, s, id){
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "http://connect.facebook.net/pt_BR/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>

	<script type="text/javascript">

		$('#username').editable({
			url: '/post',
			title: 'Enter username'
		});

		$('#elem').popModal({ html :'teste' });
		
		$('.hintModal').hintModal();

		$('.titleModal').titleModal();


		$(".js-example-basic-multiple").select2({
			language: "pt-BR"
		});

		$("#segmento").select2({
			placeholder: "Selecione um tipo de cozinha"
		});

		$('#cozinha').multiselect({
			buttonWidth: '240px',
			nonSelectedText: 'Segmento: Todos'
			}
			  );
		$('#pagamento').multiselect({
			buttonWidth: '240px',
			nonSelectedText: 'Pagamento: Todos'
		})

		$('#entrega').multiselect({
			buttonWidth: '240px',
			nonSelectedText: 'Entrega: Todos'
		});

		function abrirLogin() {
   			$('#login_email').val(""); 
			$("#div_login_email").attr("class", "form-group text-left");
   			$("#label_login_email").attr("hidden", "true");
   			 
			$('#login_senha').val(""); 
			$("#div_login_senha").attr("class", "form-group text-left");
   			$("#label_login_senha").attr("hidden", "true");
					
			$('#login_modal').modal('show');
		}

		function login() {
			var login_email = $('#login_email').val();
			var login_senha = $('#login_senha').val();
			var erros       = false;
			var icone_error = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> ';

			if (login_email.trim() == '') {
				 $("#div_login_email").attr("class", "has-error form-group text-left"); 
				 document.getElementById('label_login_email').innerHTML = icone_error + 'Informe seu email.';
				 $('#label_login_email').removeAttr("hidden");
				 erros = true;
			} else {
				 if (!validacaoEmail(login_email.trim())) {
					 $("#div_login_email").attr("class", "has-error form-group text-left"); 
					 document.getElementById('label_login_email').innerHTML = icone_error + 'Email inválido.';
					 $('#label_login_email').removeAttr("hidden");
					 erros = true;
				 } else {
				 	 $("#div_login_email").attr("class", "form-group text-left"); 
					 $("#label_login_email").attr("hidden", "true");
				 } 
			}

			if (login_senha.trim() == '') {
				 $("#div_login_senha").attr("class", "has-error form-group text-left"); 
				 document.getElementById('label_login_senha').innerHTML = icone_error + 'Informe sua senha.';
				 $('#label_login_senha').removeAttr("hidden");
				 erros = true;
			} else {
				 $("#div_login_senha").attr("class", "form-group text-left"); 
				 $("#label_login_senha").attr("hidden", "true"); 
			}

			if (!erros) {
				var dados = 'dlv_email_cli=' + login_email + '&dlv_senha_cli=' + login_senha;
				$.ajax({
					type: 'post',
					url: '{URL_LOGIN_CLIENTE}', 
					data: dados,
					dataType : "json",
					success: function(data) { 
						if (data.resposta) {
							location.reload();
						} else {
 							$("#div_login_email").attr("class", "has-error form-group text-left"); 
							$("#div_login_senha").attr("class", "has-error form-group text-left"); 
							document.getElementById('label_login_senha').innerHTML = icone_error + 'Email e/ou senha incorretos.';
							$('#label_login_senha').removeAttr("hidden");
						}
					}
				});								
			}
			
		}
		
		function abrirCadastro() {
			$('#cadastro_nome').val(""); 
			$("#div_cadastro_nome").attr("class", "form-group text-left");
			$("#label_cadastro_nome").attr("hidden", "true"); 
			 
   			$('#cadastro_email').val(""); 
			$("#div_cadastro_email").attr("class", "form-group text-left");
   			$("#label_cadastro_email").attr("hidden", "true");
   			 
			$('#cadastro_fone').val(""); 
			$("#div_cadastro_fone").attr("class", "form-group text-left");
			$("#label_cadastro_fone").attr("hidden", "true");
			 
			$('#cadastro_senha').val(""); 
			$("#div_cadastro_senha").attr("class", "form-group text-left");
   			$("#label_cadastro_senha").attr("hidden", "true");
   			 
			$('#cadastro_confirmar_senha').val(""); 
			$("#div_cadastro_confirmar_senha").attr("class", "form-group text-left");
			$("#label_cadastro_confirmar_senha").attr("hidden", "true");

      		$("#cadastro_termo_politica").removeAttr("checked"); 
			$("#div_cadastro_termo_politica").attr("class", "form-group text-left");
      		
			$('#cadastro_modal').modal('show');
		}

		function cadastrar() {
			var cadastro_nome  			  = $('#cadastro_nome').val();
			var cadastro_email  		  = $('#cadastro_email').val();
			var cadastro_fone   		  = $('#cadastro_fone').val();
			var cadastro_senha  		  = $('#cadastro_senha').val();
			var cadastro_confirmar_senha  = $('#cadastro_confirmar_senha').val();
			var cadastro_termo_politica   = document.getElementById('cadastro_termo_politica');
			var erros                     = false;
			var icone_error               = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> '; 

			if (cadastro_nome.trim() == '') {
				 $("#div_cadastro_nome").attr("class", "has-error form-group text-left"); 
				 document.getElementById('label_cadastro_nome').innerHTML = icone_error + 'Informe seu nome.';
				 $('#label_cadastro_nome').removeAttr("hidden");
				 erros = true;
			} else {
				 $("#div_cadastro_nome").attr("class", "form-group text-left"); 
				 $("#label_cadastro_nome").attr("hidden", "true"); 
			}

			if (cadastro_email.trim() == '') {
				 $("#div_cadastro_email").attr("class", "has-error form-group text-left"); 
				 document.getElementById('label_cadastro_email').innerHTML = icone_error + 'Informe seu email.';
				 $('#label_cadastro_email').removeAttr("hidden");
				 erros = true;
			} else {
				 if (!validacaoEmail(cadastro_email.trim())) {
					 $("#div_cadastro_email").attr("class", "has-error form-group text-left"); 
					 document.getElementById('label_cadastro_email').innerHTML = icone_error + 'Email inválido.';
					 $('#label_cadastro_email').removeAttr("hidden");
					 erros = true;
				 } else {
				 	 $("#div_cadastro_email").attr("class", "form-group text-left"); 
					 $("#label_cadastro_email").attr("hidden", "true");
				 } 
			}

			if (cadastro_fone.trim() == '') {
				 $("#div_cadastro_fone").attr("class", "has-error form-group text-left"); 
				 document.getElementById('label_cadastro_fone').innerHTML = icone_error + 'Informe seu celular ou telefone.';
				 $('#label_cadastro_fone').removeAttr("hidden");
				 erros = true;
			} else {
				 $("#div_cadastro_fone").attr("class", "form-group text-left"); 
				 $("#label_cadastro_fone").attr("hidden", "true"); 
			}

			if (cadastro_senha.trim() == '') {
				 $("#div_cadastro_senha").attr("class", "has-error form-group text-left"); 
				 document.getElementById('label_cadastro_senha').innerHTML = icone_error + 'Informe sua senha.';
				 $('#label_cadastro_senha').removeAttr("hidden");
				 erros = true;
			} else {
				 $("#div_cadastro_senha").attr("class", "form-group text-left"); 
				 $("#label_cadastro_senha").attr("hidden", "true"); 
			}
			
			if (cadastro_confirmar_senha.trim() == '') {

				 $("#div_cadastro_confirmar_senha").attr("class", "has-error form-group text-left"); 
				 document.getElementById('label_cadastro_confirmar_senha').innerHTML = icone_error + 'Confirme sua senha.';
				 $('#label_cadastro_confirmar_senha').removeAttr("hidden");
				 erros = true;
			} else {
				 $("#div_cadastro_confirmar_senha").attr("class", "form-group text-left"); 
				 $("#label_cadastro_confirmar_senha").attr("hidden", "true"); 
			}

			if ((cadastro_senha.trim() != '') && (cadastro_confirmar_senha.trim() != '')) {
				if (cadastro_senha.trim() != cadastro_confirmar_senha.trim()) {
					 $("#div_cadastro_senha").attr("class", "has-error form-group text-left"); 
					 $("#div_cadastro_confirmar_senha").attr("class", "has-error form-group text-left"); 
					 document.getElementById('label_cadastro_confirmar_senha').innerHTML = icone_error + 'Senhas diferentes.';
					 $('#label_cadastro_confirmar_senha').removeAttr("hidden");
					 erros = true;
				} else {
					 $("#div_cadastro_senha").attr("class", "form-group text-left"); 
					 $("#label_cadastro_senha").attr("hidden", "true"); 
					 $("#div_cadastro_confirmar_senha").attr("class", "form-group text-left"); 
					 $("#label_cadastro_confirmar_senha").attr("hidden", "true"); 
				}
			}

			if (!cadastro_termo_politica.checked) {
				$("#div_cadastro_termo_politica").attr("class", "has-error form-group text-left");
				erros = true;
			} else {
				$("#div_cadastro_termo_politica").attr("class", "form-group text-left");
			}

			if (!erros) {
		  		$.ajax({
					url: '{URL_VERIFICAR_EMAIL}/' + cadastro_email.trim(), 
					async: true, 
					dataType : "json",
					success: function(data) { 
						if (data != "") {
							if (!data.resposta) {
								$("#div_cadastro_email").attr("class", "has-error form-group text-left"); 
								document.getElementById('label_cadastro_email').innerHTML = icone_error + 'Email já cadastrado.';
								$('#label_cadastro_email').removeAttr("hidden");
							} else {
								var dados = 'dlv_nome_cli=' + cadastro_nome + '&dlv_email_cli=' + cadastro_email + 
								            '&dlv_fone_cli=' + cadastro_fone + '&dlv_senha_cli=' + cadastro_senha;
					             
								$.ajax({
									type: 'post',
									url: '{URL_CADASTRAR_CLIENTE}', 
									data: dados,
									dataType : "json",
									success: function(data) { 
										if (data.resposta) {
											location.reload();
										} else {
											alert("Erro ao cadastrar");
										}
									}
								});																
 							}
						} 
					}
				});
			}
		}

		function loginFacebook() {
			FB.login(function(response) {
				if (response.authResponse) {
			    	access_token = response.authResponse.accessToken;
			        user_id      = response.authResponse.userID;

			        var dados = 'dlv_idfacebook_cli=' + user_id;
             
					$.ajax({
						type: 'post',
						url: '{URL_LOGIN_CLIENTE_FACEBOOK}', 
						data: dados,
						dataType : "json",
						success: function(data) { 
							if (data.resposta) {
								location.reload();
							} else {
								document.getElementById('label_login_senha').innerHTML = icone_error + 'Usuário do facebook não cadastrado no Encontre Delivery.';
								$('#label_login_senha').removeAttr("hidden");
							}
						}
					});				            
			    }
			    }, {
			        scope: 'public_profile,email'
				});
		}
		

		function cadastrarFacebook() {
			var cadastro_fone   		  = $('#cadastro_fone').val();
			var cadastro_termo_politica   = document.getElementById('cadastro_termo_politica');
			var erros                     = false;
			var icone_error               = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> ';

			$("#div_cadastro_nome").attr("class", "form-group text-left");
			$("#label_cadastro_nome").attr("hidden", "true"); 
			 
			$("#div_cadastro_email").attr("class", "form-group text-left");
   			$("#label_cadastro_email").attr("hidden", "true");

   			$("#div_cadastro_senha").attr("class", "form-group text-left");
   			$("#label_cadastro_senha").attr("hidden", "true");
   			 
			$("#div_cadastro_confirmar_senha").attr("class", "form-group text-left");
			$("#label_cadastro_confirmar_senha").attr("hidden", "true");

			if (cadastro_fone.trim() == '') {
				 $("#div_cadastro_fone").attr("class", "has-error form-group text-left"); 
				 document.getElementById('label_cadastro_fone').innerHTML = icone_error + 'Informe seu celular ou telefone.';
				 $('#label_cadastro_fone').removeAttr("hidden");
				 erros = true;
			} else {
				 $("#div_cadastro_fone").attr("class", "form-group text-left"); 
				 $("#label_cadastro_fone").attr("hidden", "true"); 
			}

			if (!cadastro_termo_politica.checked) {
				$("#div_cadastro_termo_politica").attr("class", "has-error form-group text-left");
				erros = true;
			} else {
				$("#div_cadastro_termo_politica").attr("class", "form-group text-left");
			}	

			if (!erros) {
				FB.login(function(response) {
			        if (response.authResponse) {
			            access_token = response.authResponse.accessToken;
			            id_facebook  = response.authResponse.userID;

			            FB.api('/me', function(response) {
			            	nome      = response.name; //get user email
				            var dados = 'dlv_nome_cli=' + nome + '&dlv_idfacebook_cli=' + id_facebook + '&dlv_fone_cli=' + cadastro_fone;
				             
							$.ajax({
								type: 'post',
								url: '{URL_CADASTRAR_CLIENTE_FACEBOOK}', 
								data: dados,
								dataType : "json",
								success: function(data) { 
									if (data.resposta) {
										location.reload();
									} else {
										alert("Erro ao cadastrar");
									}
								}
							});				            
			            });

			        }
			    }, {
			        scope: 'public_profile,email'
			    });
			}
		}

		function validacaoEmail(email) { 
			usuario = email.substring(0, email.indexOf("@")); 
			dominio = email.substring(email.indexOf("@")+ 1, email.length); 

			if ((usuario.length >=1) && 
			    (dominio.length >=3) && 
			    (usuario.search("@")==-1) && 
			    (dominio.search("@")==-1) && 
			    (usuario.search(" ")==-1) && 
			    (dominio.search(" ")==-1) && 
			    (dominio.search(".")!=-1) && 
			    (dominio.indexOf(".") >=1)&& 
			    (dominio.lastIndexOf(".") < dominio.length - 1)) { 
				return true; 
			} else { 
				return false;
			} 
		}

	</script>
</body>		
</html>