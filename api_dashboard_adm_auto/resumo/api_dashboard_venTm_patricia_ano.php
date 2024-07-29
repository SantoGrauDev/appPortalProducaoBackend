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

$qtsDiasMes = date("t") - 6 ;

$dataDiaInicio = "'".$ano."-01-01'";
$dataDiaFim = "'".$ano."-".$mes."-".$dia."'";

                    
                   ?> 

                    
                   <?php 
                 

// ********************** Query vendas *******************************


$id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT DISTINCT sum(a.LCTVALOR) AS VENDAS, sum(a.LCTVALOR)/count(k.NOME) AS TM
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
WHERE a.LCTDATALANCAMENTO >= {$dataDiaInicio} AND a.LCTDATALANCAMENTO <= {$dataDiaFim}
AND k.SUPERVISOR = 'PATRICIA'
AND e.FILID_FILIAL = '5'
AND e.MCVID IS NULL ");

$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   


    foreach ($array as $dados): 
           
                   $VENDAS = $dados["VENDAS"];  
                   $TM = $dados["TM"];  

                        endforeach;   

                   ?> 

                    
                   <?php 

                   extract($dados);


                   $venda["VENDAS"][] = [

                     'VENDAS' => number_format($VENDAS,0,",",""), 
                     'TM' => number_format($TM,0,",",""),  
 

                   ];

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($venda);  

                //echo "Total geral: {$qtd}";
