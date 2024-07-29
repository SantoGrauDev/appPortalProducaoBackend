<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

//$id = 3;
$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT NOME
                                        FROM TB_NIVEL_ACESSO 
                                        WHERE UID = {$id}
                                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                   $NOME = $dados["NOME"];  
                
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $infoSup["info"][] = [

                     'NOME' => $NOME,
                   ];


                endforeach; 

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($infoSup);  

                //echo "Total geral: {$qtd}";
