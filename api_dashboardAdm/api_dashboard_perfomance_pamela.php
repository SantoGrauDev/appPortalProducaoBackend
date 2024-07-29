<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

//$id = 3;


$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT DISTINCT VENDEDOR AS VENDEDOR, SCORE AS SCORE, MEDIA_DE_VENDAS AS TM, TOTAL_DE_VENDAS AS VENDAS, CONVERSAO AS CONVERSAO,TICKET_SEM_VIDEO_CHAMADA AS TMSEMVID
                                        FROM TB_VENDEDORES_CONVERSAO v 
                                        INNER JOIN TB_NIVEL_ACESSO a ON a.UID = v.UID
                                        WHERE v.SUPERVISOR = 'Pamela'
                                        AND CONVERSAO > '0'
                                        ORDER BY SCORE DESC
                                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                   $VENDEDOR = $dados["VENDEDOR"];    
                   $TM = $dados["TM"];    
                   $TMSEMVID = $dados["TMSEMVID"];    
                   $CONVERSAO = $dados["CONVERSAO"];                     
                   $SCORE = $dados["SCORE"];  
                    
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $PerfomancePamela["PerfomancePamela"][] = [

                     'VENDEDOR' =>$VENDEDOR,
                     'TM' => number_format($TM,2,",","."),
                     'TMSEMVID' => number_format($TMSEMVID,2,",","."),
                     'CONVERSAO' => $CONVERSAO,
                     'SCORE' => number_format($SCORE,0,",",""),
                   ];

    endforeach; 
             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($PerfomancePamela);  

                //echo "Total geral: {$qtd}";
