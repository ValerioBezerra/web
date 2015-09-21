<section>
	<div class="navbar navbar_cinza">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu_endereco">
			<span class="glyphicon glyphicon-map-marker"></span> 
		</button>

		<div class="collapse navbar-collapse col-lg-offset-5" id="menu_endereco">
			<ul class="nav navbar-nav">
				<center>
				<h4>{ENDERECO}</h4>
				<h5>{BAIRRO_CIDADE}</h5>
				<a href="<?php echo site_url('')?>">Mudar endereço</a>
				</center>
			</ul>
		</div>
		
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu_filtro">
			<span class="glyphicon glyphicon-filter"></span> 
		</button>

		<div class="collapse navbar-collapse col-lg-offset-3" id="menu_filtro">
			<ul class="nav navbar-nav">
				<li class="btn_filtro">
					<select id="cozinha" multiple="multiple">
						{BLC_SEGMENTOS}
							<option value="{DLV_ID_SEG}">{DLV_DESCRICAO_SEG}</option>
						{/BLC_SEGMENTOS}				
					</select>
				</li>
				<li class="btn_filtro">
					<select id="pagamento" class="" multiple="multiple">
						{BLC_FORMAS_PAGAMENTO}
							<option value="{DLV_ID_FPG}">{DLV_DESCRICAO_FPG}</option>
						{/BLC_FORMAS_PAGAMENTO}
					</select>
				</li>
				<li class="btn_filtro">
					<select id="entrega" class="" multiple="multiple">
						<option value="cheese">Até 30 Minutos</option>
						<option value="tomatoes">De 31 a 45 Minutos</option>
						<option value="mozarella">de 46 a 60 Minutos</option>
						<option value="mushrooms">Acima de 60 Minutos</option>
					</select>
				</li>
			</ul>
		</div>
	</div>
	
	<div class="col-lg-offset-3">
		<form action="{ACAO_FILTRO}" method="post" class="form-horizontal">
			<div class="form-group">
				<div class="col-sm-7">
					<input type="text" class="form-control" id="filtro_nome" name="filtro_nome" 
					       placeholder="Filtrar nome da empresa" maxlength="50" autofocus autocomplete="off">
				</div>
				
				<div class="col-sm-2">
					<a na class="btn btn-primary btn-block" role="button">Filtrar</a>
				</div>
			</div>	
		</form>
	</div>	
	
	<div class="col-lg-10 col-lg-offset-1">
		<h3>Estabelecimentos Abertos({QUANTIDADE_ABERTAS})</h3>
		<div class="row ">
			{BLC_EMPRESAS_ABERTAS}
			<div class="col-lg-4">
				<div class="thumbnail fundo_estabelecimentos">
					<a class="thumbnail"> 
						<img src="{IMAGEM}" style="min-height:160px;height:160px;alt="..."/>
					</a>
					<div class="caption">
						<h4 class="text-center">{NOME}</h4>
						<h5 class="text-center">{SEGMENTOS}</h5>
						
						<p>
							<i>
								<img alt="" src="<?php echo base_url('/assets/images/front/bola_verde.gif')?>">
							</i>
							<b>Aberto</b> 
							<span class="col-lg-offset-1">
								<b>Entrega:</b> R$ {VALOR_ENTREGA} / <i class="fa fa-clock-o"> {TEMPO_ENTREGA}</i>
							</span>
						</p>
						
						<p>
							<a href="{URL}" class="btn btn-primary btn-block" role="button">Pedir</a>
						</p>
					</div>
				</div>
			</div>
			{/BLC_EMPRESAS_ABERTAS}
		</div>
	</div>
	
	<div class="col-lg-10 col-lg-offset-1">
		<h3>Estabelecimentos Abertos({QUANTIDADE_FECHADAS})</h3>
		<div class="row ">
			{BLC_EMPRESAS_FECHADAS}
			<div class="col-lg-4">
				<div class="thumbnail fundo_estabelecimentos">
					<a class="thumbnail"> 
						<img src="{IMAGEM}" style="min-height:160px;height:160px;alt="..."/>
					</a>
					<div class="caption">
						<h4 class="text-center">{NOME}</h4>
						<h5 class="text-center">{SEGMENTOS}</h5>
						
						<p>
							<i>
								<img alt="" src="<?php echo base_url('/assets/images/front/bola_vermelha.gif')?>">
							</i>
							<b>Fechado</b> 
							<span class="col-lg-offset-1">
								<b>Entrega:</b> R$ {VALOR_ENTREGA} / <i class="fa fa-clock-o"> {TEMPO_ENTREGA}</i>
							</span>
						</p>
						
						<p>
							<a href="{URL}" class="btn btn-primary btn-block" role="button">Pedir</a>
						</p>
					</div>
				</div>
			</div>
			{/BLC_EMPRESAS_FECHADAS}
		</div>
	</div>
	
</section>


