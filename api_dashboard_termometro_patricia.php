<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once 'conexao.php';

$hoje = date('Y-m-d');

$hoje = explode('-', $hoje);

$ano = $hoje[0];
$mes = $hoje[1];
$dia = $hoje[2];

$qtsDiasMes = date("t") - 2 ;

$dataDiaInicio = "'".$ano."-".$mes."-".$dia."'";
$dataDiaFim = "'".$ano."-".$mes."-".$dia." 23:59'";

// ********************** Query para determinar meta diária *************************


$read = $conn->prepare("SELECT DISTINCT sum(m.MTVVALORMETA) AS META
FROM TB_LCT_LANCAMENTOS a
INNER JOIN TB_LTV_LANCAMENTOVENDA b ON b.LCTID = a.LCTID 
INNER JOIN TB_VEN_VENDA c ON c.VENID = b.VENID 
INNER JOIN TB_VPE_VENDAPEDIDOS d ON d.VENID_VENDA = c.VENID 
INNER JOIN TB_PED_PEDIDO e ON e.PEDID = d.PEDID_PEDIDO 
INNER JOIN TB_CLI_CLIENTE f ON f.CLIID = c.CLIID_PAGADOR 
INNER JOIN TB_PES_PESSOA g ON g.PESID = f.PESID 
INNER JOIN TB_USU_USUARIO h ON h.USUID = a.USUID
INNER JOIN TB_VND_VENDEDOR i ON i.VNDID = e.VNDID_PRIMEIRO  
INNER JOIN TB_NIVEL_ACESSO k ON k.PESID = i.PESID 
INNER JOIN TB_DMV_DETALHEMETAVEND l ON l.VNDID = i.VNDID 
INNER JOIN TB_MTV_METASVENDEDOR m ON m.MTVID = l.MTVID
WHERE k.SUPERVISOR = 'PATRICIA'
AND e.FILID_FILIAL = '5'
AND e.MCVID IS NULL
GROUP BY k.NOME,m.MTVVALORMETA
ORDER BY VENDAS DESC");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   


    foreach ($array as $dados): 


                   $META = $dados["META"];     

                 
  
                    endforeach; 

                  $metaDia = $META/$qtsDiasMes;

                    
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
