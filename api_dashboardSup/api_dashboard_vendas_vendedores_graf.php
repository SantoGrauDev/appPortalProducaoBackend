<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

//$id = 3;


$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT VENDEDOR AS VENDEDOR, TOTAL_DE_VENDAS AS VENDAS
                                        FROM TB_VENDEDORES_CONVERSAO v 
                                        INNER JOIN TB_NIVEL_ACESSO a ON a.UID = v.UID
                                        INNER JOIN TB_DMV_DETALHEMETAVEND b ON b.VNDID = a.VNDID 
                                        INNER JOIN TB_MTV_METASVENDEDOR c ON c.MTVID = b.MTVID 
                                        WHERE v.UID_SUPERVISOR = {$id}
                                        ORDER BY TOTAL_DE_VENDAS DESC
                                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                   $VENDEDOR = $dados["VENDEDOR"];    
                   $VENDAS = $dados["VENDAS"];    


                
                    
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $VendaVendedoresGraf["VendaVendedoresGraf"][] = [

                     'VENDEDOR' =>$VENDEDOR,
                     'VENDAS' => number_format($VENDAS,2,",","."),
                   ];

    endforeach; 
             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($VendaVendedoresGraf);  

                //echo "Total geral: {$qtd}";