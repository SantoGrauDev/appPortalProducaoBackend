<?php



// ************ FRAGMENTANDO A DATA PARA UTILIZAR NAS QUERYS *****************
$hoje = date('Y-m-d');

$hoje = explode('-', $hoje);

$ano = $hoje[0];
$mes = $hoje[1];
$dia = $hoje[2];


//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once 'conexao.php';


$DiasRestantes = 30 - $dia;

//$id = 3;

//Automatizar data atual
$hoje ="'".date('Y-m-d 23:59')."'";

//subtraindo dias da data atual
//$subHoje = date('Y-m-d');

//$subData = "'".date('Y-m-d', strtotime('-3 days', strtotime($subHoje)))."'";


for ($dia = '01' ; $dia <= '30'; $dia++) {


$diaQueryQuatro = "'".$ano."-".$mes."-".$dia."'";
//$diaQueryQuatroFim = "'".$ano."-".$mes."-".$dia." 23:59'";


  $id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT sum(a.PEDVALORLIQUIDO) AS LIQUIDO
                        FROM TB_PED_PEDIDO a
                        INNER JOIN TB_VND_VENDEDOR b ON b.VNDID = a.VNDID_PRIMEIRO
                        INNER JOIN TB_PES_PESSOA c ON c.PESID = b.PESID 
                        INNER JOIN TB_NIVEL_ACESSO d ON d.PESID = c.PESID 
                        WHERE a.PEDDATAENTRADA >= {$diaQueryQuatro} AND a.PEDDATAENTRADA <= {$diaQueryQuatro}
                        AND d.UID =  {$id}
                        AND a.FILID_FILIAL = '5'
                        AND a.PEDDATACANCELAMENTO IS NULL
                        ");
                   

                    $read->setFetchMode(PDO::FETCH_ASSOC);            
                    $read->execute();   
                    $array = $read->fetchAll();   

                  foreach ($array as $dados):
                    
                  $LIQUIDO = $dados["LIQUIDO"];  
                  $DATA = $diaQueryQuatro;

                    extract($dados); 


                  

                   $Grafico["GraficoVendas"][] = [

                     'LIQUIDO' => number_format($LIQUIDO,2,",",""),
                     'DATA'=> $DATA, 

                   ];
                      endforeach; 
              
                         }

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($Grafico);  

                //echo "Total geral: {$qtd}";
           
                   ?> 
