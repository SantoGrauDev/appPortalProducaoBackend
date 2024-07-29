<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

//$id = 3;


$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT VENDEDOR AS VENDEDOR, TOTAL_LINKS AS LINKSTOTAL, TAXA_DE_LINKVENDA AS TAXAVENDA
                                        FROM TB_VENDEDORES_CONVERSAO v 
                                        INNER JOIN TB_NIVEL_ACESSO a ON a.UID = v.UID
                                        WHERE v.UID_SUPERVISOR = {$id}
                                        AND v.SCORE > '0'
                                        ORDER BY TAXA_DE_LINKVENDA DESC
                                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                   $VENDEDOR = $dados["VENDEDOR"];    
                   $LINKSTOTAL = $dados["LINKSTOTAL"];     
                   $TAXAVENDA = $dados["TAXAVENDA"];                 
                    
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $LinksPagos["LinksPagos"][] = [

                     'VENDEDOR' =>$VENDEDOR,
                     'LINKSTOTAL' => number_format($LINKSTOTAL,0,",",""),
                     'TAXAVENDA' => $TAXAVENDA,
                   ];

    endforeach; 
             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($LinksPagos);  

                //echo "Total geral: {$qtd}";
