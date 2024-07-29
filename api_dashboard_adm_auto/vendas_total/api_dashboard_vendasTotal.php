<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '................./../../../conexao.php';

//$id = 3;

$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT DISTINCT sum(a.LCTVALOR) AS VENDAS, COUNT(a.LCTVALOR) AS OSCOUNT,sum(a.LCTVALOR)/COUNT(e.PEDSEQUENCIAL) AS TM
                    FROM TB_LCT_LANCAMENTOS a
                    INNER JOIN TB_LTV_LANCAMENTOVENDA b ON b.LCTID = a.LCTID 
                    INNER JOIN TB_VEN_VENDA c ON c.VENID = b.VENID 
                    INNER JOIN TB_VPE_VENDAPEDIDOS d ON d.VENID_VENDA = c.VENID 
                    INNER JOIN TB_PED_PEDIDO e ON e.PEDID = d.PEDID_PEDIDO 
                    INNER JOIN TB_CLI_CLIENTE f ON f.CLIID = c.CLIID_PAGADOR 
                    INNER JOIN TB_PES_PESSOA g ON g.PESID = f.PESID 
                    INNER JOIN TB_USU_USUARIO h ON h.USUID = a.USUID
                    WHERE a.LCTDATALANCAMENTO >= '2023-04-01' AND a.LCTDATALANCAMENTO <= '2023-04-24'
                    AND e.FILID_FILIAL = '5'
                    AND e.MCVID IS NULL
                 ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                   $TM = $dados["TM"];  
                   $VENDAS = $dados["VENDAS"]; 
                   $OSCOUNT = $dados["OSCOUNT"];   
  
                    endforeach; 
                    
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $BoxVenda["BoxVenda"][] = [

                     'TM' => number_format($TM,2,",",""),
                     'VENDAS' => number_format($VENDAS,2,",","."),
                     'OSCOUNT' => number_format($OSCOUNT,0,",",""),
                   ];


             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($BoxVenda);  

                //echo "Total geral: {$qtd}";
