<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Data', 'Venda por dia' ],

          <?php 
          
         include "../conexao.php";
$hoje = date('Y-m-d');

$hoje = explode('-', $hoje);

$ano = $hoje[0];
$mes = $hoje[1];
$dia = $hoje[2];


for ($dia = '01' ; $dia <= '30'; $dia++) {


$diaQueryQuatro = "'".$ano."-".$mes."-".$dia."'";

  $id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT sum(a.PEDVALORLIQUIDO) AS LIQUIDO, a.PEDDATAENTRADA AS DATAVENDA
                        FROM TB_PED_PEDIDO a
                        INNER JOIN TB_VND_VENDEDOR b ON b.VNDID = a.VNDID_PRIMEIRO
                        INNER JOIN TB_PES_PESSOA c ON c.PESID = b.PESID 
                        INNER JOIN TB_NIVEL_ACESSO d ON d.PESID = c.PESID 
                        WHERE a.PEDDATAENTRADA >= {$diaQueryQuatro} AND a.PEDDATAENTRADA <= {$diaQueryQuatro}
                        AND d.UID =  {$id}
                        AND a.FILID_FILIAL = '5'
                        AND a.PEDDATACANCELAMENTO IS NULL                        
                        GROUP BY a.PEDDATAENTRADA
                        ");
                   

                    $read->setFetchMode(PDO::FETCH_ASSOC);            
                    $read->execute();   
                    $array = $read->fetchAll();   

                  foreach ($array as $dados): { ?>

               ['<?= date("d/m/Y",strtotime($dados["DATAVENDA"]));?>', <?= $dados["LIQUIDO"] ?> ],             

        <?php } endforeach;
         } ?>
        ]);

         var options = {
          title: '',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }

    </script>
</head> 

  <body>
     <div >                                   
       <div id="curve_chart" style="width: 1000px; height: 500px; "></div>                                                      
    </div>
  </body>  
</html>