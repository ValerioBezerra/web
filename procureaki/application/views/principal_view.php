<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Principal</h1>
	</div>
	<!-- /.col-lg-12 -->
</div>
<!-- /.row -->



<!-- /.row -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			
				<div class="panel-heading">
					<i class="fa fa-bar-chart-o fa-fw"></i> <strong>Gráficos</strong>
				</div>
				<div class="panel-body">
					<div class="col-md-6">
						<div id="grafico_produtos_vendidos" style="height: 300px;"></div>
					</div>
					<div class="col-md-6">
						<div id="grafico_vendas_semanais" style="height: 300px;"></div>
					</div>
				</div>
		</div>
	</div>
	<!-- /.col-lg-8 -->
</div>

<div class="modal fade" id="fechar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h3 class="modal-title" id="myModalLabel">Encontre Delivery</h3>
                </div>
                <div class="modal-body">
                    <h4>Deseja realmente fechar?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="hideFechar()">Sim</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="abrir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>
                    <h3 class="modal-title" id="myModalLabel">Encontre Delivery</h3>
                </div>
                <div class="modal-body">
                    <h4>Deseja realmente abrir?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="hideAbrir()">Sim</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    function showFechar(){
        $('#fechar').modal('show');
    }

    function hideFechar(){
        $('#fechar').modal('hide');
        location.href = 'index.php/principal/fechar';
    }

    function showAbrir(){
        $('#abrir').modal('show');
    }

    function hideAbrir(){
        $('#abrir').modal('hide');
        location.href = 'index.php/principal/abrir';
    }
    
	google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
    	var data = google.visualization.arrayToDataTable([
        	['Produto', 'Quantidade'],
        	{GRAFICO_PRODUTOS_VENDIDOS}
        	    ['{PRODUTO}', {QUANTIDADE}],
			{/GRAFICO_PRODUTOS_VENDIDOS}
        ]);

        var options = {
        	title: 'Produtos mais vendidos (Mensal)'
        };

        var chart = new google.visualization.PieChart(document.getElementById('grafico_produtos_vendidos'));
        chart.draw(data, options);

        var data = google.visualization.arrayToDataTable([
        	['Data', 'Quantidade', 'Valor'],
        	{GRAFICO_VENDAS_SEMANAIS}
                ['{DATA}', {QUANTIDADE}, {VALOR}],
			{/GRAFICO_VENDAS_SEMANAIS}
		]);

		var options = {
        	title : 'Totais de venda (Semanal)',
            hAxis: {title: "Data"},
            seriesType: "bars",
            series: {5: {type: "line"}}
    	};

        var chart = new google.visualization.ComboChart(document.getElementById('grafico_vendas_semanais'));
        chart.draw(data, options);        
    }
</script>