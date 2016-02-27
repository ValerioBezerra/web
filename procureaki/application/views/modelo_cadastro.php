<div class="row">
	<div class="col-lg-12">
        <h2 class="page-header">Modelo - {ACAO}</h2>
    </div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<form action="{ACAO_FORM}" method="post" class="form-horizontal">
			<div class="form-group">
				<input type="hidden" id="vei_id_mod" name="vei_id_mod" value="{vei_id_mod}"/>
				<div class="form-group {ERRO_VEI_NOME_MOD} col-sm-12">
					<label for="vei_nome_mod" class="col-sm-1 control-label">Nome</label>
					<div class="col-sm-11">
						<input type="text" class="form-control" id="vei_nome_mod"
							   name="vei_nome_mod" value="{vei_nome_mod}" maxlength="25" autofocus autocomplete="off"/>
					</div>
				</div>

				<div class="{ERRO_VEI_VEIMAR_MOD}">
					<label for="vei_veimar_mod" class="col-sm-1 control-label">Marca</label>
					<div class="col-sm-4">
						<select class="form-control" id="vei_veimar_mod" name="vei_veimar_mod">
							<option value="">Selecione</option>
							{BLC_MARCAS}
							<option value="{VEI_ID_MAR}" {SEL_VEI_ID_MAR}>{VEI_NOME_MAR}</option>
							{/BLC_MARCAS}
						</select>
					</div>
				</div>

				<div class="{ERRO_VEI_VEITIP_MOD}">
					<label for="vei_veitip_mod" class="col-sm-1">Tipo</label>
					<div class="col-sm-4">
						<select class="form-control" id="vei_veitip_mod" name="vei_veitip_mod">
							<option value="">Selecione</option>
							{BLC_TIPOS}
							<option value="{VEI_ID_TIP}" {SEL_VEI_ID_TIP}>{VEI_DESCRICAO_TIP}</option>
							{/BLC_TIPOS}
						</select>
					</div>
				</div>
			</div>
		
			<div class="form-group">
				<div class="col-sm-offset-1 col-sm-11">
					<button type="submit" name="salvar_modo" class="btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Salvar</button>
		        	<a type="button" href="{CONSULTA_MODELO}" class="btn btn-default">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
</div>		
