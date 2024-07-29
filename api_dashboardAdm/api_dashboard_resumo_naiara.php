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
$META = 0;
$VENDAS =  0;

$scoreTotal = 0;
$TMTotal = 0;
$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT  SUM(SCORE)/COUNT(VENDEDOR) AS SCORE, SUM(MEDIA_DE_VENDAS)/COUNT(VENDEDOR) AS TM, 
SUM(TOTAL_DE_VENDAS) AS VENDAS, v.SUPERVISOR AS SUPERVISOR,sum(TOTAL_DE_ATENDIMENTOS) AS ATENDIMENTOS,
SUM(TOTAL_VITORIAS) AS VITORIAS, sum(c.MTVVALORMETA) AS META
                                        FROM TB_VENDEDORES_CONVERSAO v 
                                        INNER JOIN TB_NIVEL_ACESSO a ON a.UID = v.UID
                                        INNER JOIN TB_DMV_DETALHEMETAVEND b ON b.VNDID = a.VNDID 
                                        INNER JOIN TB_MTV_METASVENDEDOR c ON c.MTVID = b.MTVID 
                                        WHERE v.SUPERVISOR = 'Jullie'
                                        GROUP BY v.SUPERVISOR 
                                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   


    foreach ($array as $dados): 


                   $SCORE = $dados["SCORE"];    
                   $TM = $dados["TM"];  
                   $META = $dados["META"];  
                   $VENDAS = $dados["VENDAS"]; 
                   $SUPERVISOR = $dados["SUPERVISOR"];
                   $ATENDIMENTOS = $dados["ATENDIMENTOS"];    
                   $VITORIAS = $dados["VITORIAS"];     

                   $PERCENTUALMETA = $VENDAS * 100 / $META; 

                 
  
                    endforeach; 

                    $CONVERSAO = $VITORIAS * 100 / $ATENDIMENTOS
                    
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $BoxVenda["BoxVenda"][] = [

                     'SCORE' => number_format($SCORE,0,",","."),
                     'TM' => number_format($TM,2,",",""),
                     'META' => number_format($META,2,",","."),
                     'VENDAS' => number_format($VENDAS,2,",","."),
                     'SUPERVISOR' => $SUPERVISOR,
                     'ATENDIMENTOS' => $ATENDIMENTOS,
                     'VITORIAS' =>$VITORIAS,
                     'CONVERSAO' => number_format($CONVERSAO,2,",","."),
                     'PERCENTUALMETA' => number_format($PERCENTUALMETA,2,".",""),
                   ];


             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($BoxVenda);  

                //echo "Total geral: {$qtd}";
