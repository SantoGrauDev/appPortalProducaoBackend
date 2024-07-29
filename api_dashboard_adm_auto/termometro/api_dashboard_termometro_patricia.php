<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

$hoje = date('Y-m-d');

$hoje = explode('-', $hoje);

$ano = $hoje[0];
$mes = $hoje[1];
$dia = $hoje[2];

$qtsDiasMes = date("t") - 2 ;

$dataDiaInicio = "'".$ano."-".$mes."-".$dia."'";
$dataDiaFim = "'".$ano."-".$mes."-".$dia." 23:59'";

// ********************** Query para determinar meta diária *************************


$read = $conn->prepare("SELECT SUM(a.MTVVALORMETA) AS META
FROM TB_MTV_METASVENDEDOR a
INNER JOIN TB_DMV_DETALHEMETAVEND b ON b.MTVID = a.MTVID 
INNER JOIN TB_NIVEL_ACESSO c ON c.VNDID = b.VNDID 
WHERE c.SUPERVISOR = 'PATRICIA'");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   


    foreach ($array as $dados): 


                   $META = $dados["META"];     

                 
  
                    endforeach; 

                  $metaDia = $META/$qtsDiasMes;

                  echo $META;
                  echo '<br/>';
                    
                   ?> 

                    
                   <?php 
                 

// ********************** Query vendas *******************************


$id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT sum(a.PEDVALORLIQUIDO) AS VENDADIA
FROM TB_PED_PEDIDO a
INNER JOIN TB_TVN_TIPOVENDA b ON b.TVNID = a.TVNID 
INNER JOIN TB_VND_VENDEDOR c ON c.VNDID = a.VNDID_PRIMEIRO
INNER JOIN TB_PES_PESSOA d ON c.PESID = d.PESID 
INNER JOIN TB_NIVEL_ACESSO e ON d.PESID = e.PESID 
WHERE a.PEDDATADIGITACAO >= {$dataDiaInicio} AND a.PEDDATADIGITACAO <= {$dataDiaFim}
AND a.FILID_FILIAL = '5'
AND e.SUPERVISOR = 'PATRICIA'
AND a.PEDDATACANCELAMENTO IS NULL
AND NOT b.TVNID = '48'
AND NOT b.TVNID = '49'
AND NOT b.TVNID = '50'
AND NOT b.TVNID = '51'
AND NOT b.TVNID = '52'
AND NOT b.TVNID = '54'
AND NOT b.TVNID = '55'   ");

$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
           
                   $VENDADIA = $dados["VENDADIA"];   

                        endforeach;   

                   ?> 

                    
                   <?php 

                   extract($dados);

                   echo $VENDADIA;
                   echo $metaDia;

                   $PERCENTUALDIABATIDO = $VENDADIA * 100 / $metaDia;


                   $venda["VENDAS"][] = [

                     'VENDADIA' => number_format($VENDADIA,2,",",""),  
                     'PERCENTUALDIABATIDO' => number_format($PERCENTUALDIABATIDO,0,",",""), 

                   ];

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($venda);  

                //echo "Total geral: {$qtd}";
