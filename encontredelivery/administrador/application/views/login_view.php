<!DOCTYPE html>
<html lang="pt">
<head>
	<meta charset="utf-8">
	<title>Encontre Delivery</title>
	
	<link href="<?php echo base_url('assets/css/bootstrap.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/plugins/metisMenu/metisMenu.min.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/plugins/timeline.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/sb-admin-2.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/font-awesome-4.1.0/css/font-awesome.min.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/jasny-bootstrap.min.css')?>" rel="stylesheet">
</head>
<body>
	<form action="{ACAO_FORM}" role="form" method="post" class="form-horizontal">
		<div class="container">
			<div class="row">
				{MENSAGEM_LOGIN_ERRO}
				<div class="col-md-4 col-md-offset-4">
				
					<div class="login-panel panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title" align="center">
								<strong>Autenticação</strong>
							</h3>
						</div>
						<div class="panel-body">
								<div class="form-group">		
									<div class="col-sm-offset-1 col-sm-10">	
										<select class="form-control" id="dlv_tipopessoa_emp" name="dlv_tipopessoa_emp" onchange="alterarCpfCnpj(this)" autofocus>
											<option value="f" {dlv_tipopessoa_emp_f}>Física</option>
											<option value="j" {dlv_tipopessoa_emp_j}>Jurídica</option>
										</select>
									</div>	
								</div>
								
								<div class="form-group">							
									<div id="div_dlv_cpfcnpj_emp_f" class="{CLASS_DIV_DLV_CPFCNPJ_EMP_F} {ERRO_DLV_CPFCNPJ_EMP}">
										<div class="col-sm-offset-1 col-sm-10">
											<input type="text" class="form-control" id="dlv_cpfcnpj_emp_f" data-mask="{MASCARA_CPF}" placeholder="CPF"
												   name="dlv_cpfcnpj_emp" value="{dlv_cpfcnpj_emp}" {DIV_DLV_CPFCNPJ_EMP_F}/>
										</div>
									</div>		
												
									<div id="div_dlv_cpfcnpj_emp_j" class="{CLASS_DIV_DLV_CPFCNPJ_EMP_J} {ERRO_DLV_CPFCNPJ_EMP}">
										<div class="col-sm-offset-1 col-sm-10">
											<input type="text" class="form-control" id="dlv_cpfcnpj_emp_j" data-mask="{MASCARA_CNPJ}" placeholder="CNPJ"
												   name="dlv_cpfcnpj_emp" value="{dlv_cpfcnpj_emp}" {DIV_DLV_CPFCNPJ_EMP_J}/>
										</div>
									</div>	
								</div>	
								
								<hr />	
								
								<div class="form-group {ERRO_DLV_LOGIN_USU}">							
									<div class="col-sm-offset-1 col-sm-10">
										<input type="text" class="form-control" id="dlv_login_usu" placeholder="Login"
											   name="dlv_login_usu" value="{dlv_login_usu}" maxlength="15"/>
									</div>
								</div>
								
								<div class="form-group {ERRO_DLV_SENHA_USU}">
									<div class="col-sm-offset-1 col-sm-10">
										<input class="form-control" placeholder="Senha" name="dlv_senha_usu" type="password" value="" maxlength="8">
									</div>	
								</div>
								
								<div class="form-group">
									<div class="col-sm-offset-1 col-sm-10">
										<button name="entrar" type="submit" class="btn btn-lg btn-primary btn-block"> <i class="fa fa-lock"></i> Entrar</button>
									</div>
								</div>		
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	
	<script src="<?php echo base_url('assets/js/jquery-1.11.0.js')?>"></script>
	<script src="<?php echo base_url('assets/js/bootstrap.min.js')?>"></script>
	<script src="<?php echo base_url('assets/js/jasny-bootstrap.min.js')?>"></script>
	<script src="<?php echo base_url('assets/js/plugins/metisMenu/metisMenu.min.js')?>"></script>
	<script src="<?php echo base_url('assets/js/sb-admin-2.js')?>"></script>
	<script>
		function alterarCpfCnpj(objeto) {
			var tipopessoa = objeto.value;
			 
			if (tipopessoa == 'j') {
				$("#dlv_cpfcnpj_emp_f").attr("value", "");
				$("#dlv_cpfcnpj_emp_j").attr("value", "");
				
				$("#div_dlv_cpfcnpj_emp_f").attr("class", "transp");
				$("#div_dlv_cpfcnpj_emp_j").attr("class", "");
		
				$('#div_dlv_cpfcnpj_emp_f :input').attr("disabled", true); 
				$('#div_dlv_cpfcnpj_emp_j :input').removeAttr("disabled");	
			} 
		
			if (tipopessoa == 'f') {
				$("#dlv_cpfcnpj_emp_f").attr("value", "");
				$("#dlv_cpfcnpj_emp_j").attr("value", "");
					
				$("#div_dlv_cpfcnpj_emp_f").attr("class", "");
				$("#div_dlv_cpfcnpj_emp_j").attr("class", "transp");
		
				$('#div_dlv_cpfcnpj_emp_f :input').removeAttr("disabled"); 
				$('#div_dlv_cpfcnpj_emp_j :input').attr("disabled", true);			
			}
		}
	</script>
</body>
</html>
