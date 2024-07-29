<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

//$id = 3;

$hoje = date('Y-m-d');

$hoje = explode('-', $hoje);

$ano = $hoje[0];
$mes = $hoje[1];
$dia = $hoje[2];

$dataDiaInicio = "'".$ano."-".$mes."-".$dia."'";
$dataDiaFim = "'".$ano."-".$mes."-".$dia."'";


$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT FIRST 3 DISTINCT k.NOME  AS VENDEDOR, SUM(a.LCTVALOR) AS VENDAS, k.SUPERVISOR AS SUPERVISOR, sum(a.LCTVALOR)/COUNT(e.PEDSEQUENCIAL) AS tm
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
               WHERE e.FILID_FILIAL = '5' 
               AND a.LCTDATALANCAMENTO >= {$dataDiaInicio} AND a.LCTDATALANCAMENTO <= {$dataDiaFim}
               AND e.MCVID IS NULL
               GROUP BY SUPERVISOR, VENDEDOR
               ORDER BY VENDAS DESC
                                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                   $VENDEDOR = $dados["VENDEDOR"];    
                   $VENDAS = $dados["VENDAS"];  
                   $SUPERVISOR = $dados["SUPERVISOR"];  
                
                    
                   ?> 

                    
                   <?php 

                   extract($dados);

                   $Rank["RANK"][] = [

                     'VENDEDOR' =>$VENDEDOR,
                     'SUPERVISOR' =>$SUPERVISOR,
                     'VENDAS' => number_format($VENDAS,2,",","."),
                   ];

    endforeach; 
             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($Rank);  

                //echo "Total geral: {$qtd}";
