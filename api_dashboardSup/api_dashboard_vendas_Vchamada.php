<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

//$id = 3;



$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT DISTINCT VENDEDOR AS VENDEDOR, VALOR_VIDEO_CHAMADA AS VENDAVIDEO, VITORIA_DA_VIDEO AS VITORIA, CONVERSAO_VIDEO_CHAMADA AS CONVERSAO, ENCAM_DA_VIDEO AS ENCAMINHAMENTO
                                        FROM TB_VENDEDORES_CONVERSAO v 
                                        INNER JOIN TB_NIVEL_ACESSO a ON a.UID = v.UID
                                        WHERE v.UID_SUPERVISOR = {$id}
                                        AND VALOR_VIDEO_CHAMADA > '0'
                                        AND CONVERSAO_VIDEO_CHAMADA IS NOT NULL
                                        AND VITORIA_DA_VIDEO IS NOT NULL
                                        AND VALOR_VIDEO_CHAMADA IS NOT NULL
                                        ORDER BY TOTAL_DE_VENDAS DESC
                                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                   $VENDEDOR = $dados["VENDEDOR"];    
                   $VENDAVIDEO = $dados["VENDAVIDEO"];    
                   $VITORIA = $dados["VITORIA"]; 
                   $CONVERSAO = $dados["CONVERSAO"]; 
                   $ENCAMINHAMENTO = $dados["ENCAMINHAMENTO"];     

                   $TM = $VENDAVIDEO / $VITORIA;
                    
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $VENDAVIDEOCHAMADA["VendaVideoChamada"][] = [


                     'VENDEDOR' =>$VENDEDOR,
                     'CONVERSAO' =>$CONVERSAO,
                     'ENCAMINHAMENTO' =>$ENCAMINHAMENTO,
                     'VENDAVIDEO' => number_format($VENDAVIDEO,2,",","."),
                     'TM' => number_format($TM,2,",","."),
                   ];

    endforeach; 
             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($VENDAVIDEOCHAMADA);  

                //echo "Total geral: {$qtd}";
