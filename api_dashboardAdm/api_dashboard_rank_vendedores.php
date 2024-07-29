<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

//$id = 3;


$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT first 3 VENDEDOR AS VENDEDOR, SCORE AS SCORE, MEDIA_DE_VENDAS AS TM, c.MTVVALORMETA AS META, TOTAL_DE_VENDAS AS VENDAS, v.SUPERVISOR AS SUPERVISOR
                                        FROM TB_VENDEDORES_CONVERSAO v 
                                        INNER JOIN TB_NIVEL_ACESSO a ON a.UID = v.UID
                                        INNER JOIN TB_DMV_DETALHEMETAVEND b ON b.VNDID = a.VNDID 
                                        INNER JOIN TB_MTV_METASVENDEDOR c ON c.MTVID = b.MTVID 
                                        ORDER BY TOTAL_DE_VENDAS DESC
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

                   $RankVendasPodiumVendedores["RankVendasPodiumVendedores"][] = [

                     'VENDEDOR' =>$VENDEDOR,
                     'SUPERVISOR' =>$SUPERVISOR,
                     'VENDAS' => number_format($VENDAS,2,",","."),
                   ];

    endforeach; 
             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($RankVendasPodiumVendedores);  

                //echo "Total geral: {$qtd}";
