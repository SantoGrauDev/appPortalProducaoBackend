<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

//$id = 3;


$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT v.SUPERVISOR AS SUPERVISOR,  SUM(TOTAL_DE_VENDAS) AS VENDAS,  SUM(MEDIA_DE_VENDAS) AS TM
                                        FROM TB_VENDEDORES_CONVERSAO v 
                                        INNER JOIN TB_NIVEL_ACESSO a ON a.UID = v.UID
                                        GROUP BY v.SUPERVISOR, MEDIA_DE_VENDAS
                                        ORDER BY VENDAS,TM DESC
                                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                   $VENDAS = $dados["VENDAS"];  
                   $SUPERVISOR = $dados["SUPERVISOR"];  
                   $TM = $dados["TM"];
                
                    
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $RankVendasPodiumSup["RankVendasPodiumSup"][] = [

                     'SUPERVISOR' =>$SUPERVISOR,
                     'VENDAS' => number_format($VENDAS,2,",","."),
                     'TM' => number_format($TM,2,",","."),
                   ];

    endforeach; 
             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($RankVendasPodiumSup);  

                //echo "Total geral: {$qtd}";
