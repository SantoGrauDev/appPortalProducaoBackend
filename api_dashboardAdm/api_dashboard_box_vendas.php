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

               $read = $conn->prepare("SELECT  SUM(TOTAL_DE_VENDAS) AS VENDAS, SUM(TOTAL_DE_VENDAS)/SUM(TOTAL_VITORIAS) AS TM, SUM(TOTAL_VITORIAS) AS VITORIAS, sum(TOTAL_DE_ATENDIMENTOS) AS ATENDIMENTOS
                                        FROM TB_VENDEDORES_CONVERSAO v 
                                        INNER JOIN TB_NIVEL_ACESSO a ON a.UID = v.UID
                                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                   $TM = $dados["TM"];  
                   $VENDAS = $dados["VENDAS"]; 
                   $VITORIAS = $dados["VITORIAS"]; 
                   $ATENDIMENTOS = $dados["ATENDIMENTOS"]; 
  
  

                   $CONVERSAO = $VITORIAS * 100 / $ATENDIMENTOS;

                   $SCORE = $CONVERSAO * $TM;

                    endforeach; 
                    
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $BoxVenda["BoxVenda"][] = [

                     'TM' => number_format($TM,2,",",""),
                     'VENDAS' => number_format($VENDAS,2,",","."),
                     'SCORE' => number_format($SCORE,0,",",""),
                   ];


             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($BoxVenda);  

                //echo "Total geral: {$qtd}";
