<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

//$id = 3;
$QTDVENDEDORES = 0;
$SCORE = 0;
$TM = 0;
$VENDAS =  0;

$scoreTotal = 0;
$TMTotal = 0;
$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT  VENDEDOR AS VENDEDOR, VALOR_VIDEO_CHAMADA AS VENDAVIDEO, VITORIA_DA_VIDEO AS VITORIA, CONVERSAO_VIDEO_CHAMADA AS CONVERSAO, ENCAM_DA_VIDEO AS ENCAMINHAMENTO 
                              FROM TB_VENDEDORES_CONVERSAO v 
                              INNER JOIN TB_NIVEL_ACESSO a ON a.UID = v.UID
                              WHERE  v.SUPERVISOR = 'Patricia'
                              AND CONVERSAO > '0'
                                  ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                   $ = $dados[""];    
                    

                   
                   $SCORE = $CONVERSAO * $TM;

                    endforeach;

                   ?> 

                    
                   <?php 
                   extract($dados);

                   $BoxVenda["BoxVenda"][] = [

                     'VITORIAS' =>$VITORIAS,
                     'TM' => number_format($TM,2,",","."),
                     'VENDAS' => number_format($VENDAS,2,",","."),
                     'CONVERSAO' => $CONVERSAO,
                     'SCORE' => number_format($SCORE,0,",",""),
                     'ATENDIMENTOS' => number_format($ATENDIMENTOS,0,",",""),
                   ];
                   
             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($BoxVenda);  

                //echo "Total geral: {$qtd}";
