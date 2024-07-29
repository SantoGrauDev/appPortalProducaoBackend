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

               $read = $conn->prepare("SELECT  SUM(SCORE)/COUNT(VENDEDOR) AS SCORE, SUM(MEDIA_DE_VENDAS)/COUNT(VENDEDOR) AS TM, SUM(c.MTVVALORMETA) AS META, SUM(TOTAL_DE_VENDAS) AS VENDAS, v.SUPERVISOR AS SUPERVISOR
                                        FROM TB_VENDEDORES_CONVERSAO v 
                                        INNER JOIN TB_NIVEL_ACESSO a ON a.UID = v.UID
                                        INNER JOIN TB_DMV_DETALHEMETAVEND b ON b.VNDID = a.VNDID 
                                        INNER JOIN TB_MTV_METASVENDEDOR c ON c.MTVID = b.MTVID 
                                        WHERE v.UID_SUPERVISOR = {$id}
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
  
                    endforeach; 
                    
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $BoxVenda["BoxVenda"][] = [

                     'SCORE' => number_format($SCORE,0,",","."),
                     'TM' => number_format($TM,2,",",""),
                     'META' => number_format($META,2,",","."),
                     'VENDAS' => number_format($VENDAS,2,",","."),
                     'SUPERVISOR' => $SUPERVISOR,
                   ];


             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($BoxVenda);  

                //echo "Total geral: {$qtd}";
