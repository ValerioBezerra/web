<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Notificação</h2>  
    </div>
</div>

<form action="{ACAO_FORM}" method="post" class="form-horizontal">
	<div class="form-group">
		<div class="col-sm-12 {ERRO_MENSAGEM}">
			<textarea class="form-control not-resize" rows="3" id="mensagem" placeholder="Mensagem"
				   name="mensagem" maxlength="350"></textarea>
		</div>
	</div>	
	
	<div class="form-group">
		<div class="col-sm-10">
			<button type="submit" name="enviar_noficacao" class="btn btn-primary"> <i class="glyphicon glyphicon-envelope"></i> Enviar</button>
		</div>
	</div>
</form>

