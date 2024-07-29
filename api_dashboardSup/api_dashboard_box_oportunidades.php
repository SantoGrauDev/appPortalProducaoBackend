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

               $read = $conn->prepare("SELECT  sum(TOTAL_DE_ATENDIMENTOS) AS ATENDIMENTOS, SUM(TOTAL_VITORIAS) AS VITORIAS,sum(VITORIA_DA_VIDEO) AS VITORIAVID ,sum(ENCAM_DA_VIDEO) AS ENCAMINHAMENTOVID
                                        FROM TB_VENDEDORES_CONVERSAO v 
                                        INNER JOIN TB_NIVEL_ACESSO a ON a.UID = v.UID
                                        INNER JOIN TB_DMV_DETALHEMETAVEND b ON b.VNDID = a.VNDID 
                                        INNER JOIN TB_MTV_METASVENDEDOR c ON c.MTVID = b.MTVID 
                                        WHERE v.UID_SUPERVISOR ={$id}
                                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                   $ATENDIMENTOS = $dados["ATENDIMENTOS"];    
                   $VITORIAS = $dados["VITORIAS"];   
                   $VITORIAVID = $dados["VITORIAVID"];     
                   $ENCAMINHAMENTOVID = $dados["ENCAMINHAMENTOVID"];    
  
                    endforeach; 

                    $CONVERSAO = $VITORIAS * 100 / $ATENDIMENTOS;
                    $CONVERSAOVIDEO = $VITORIAVID * 100 / $ENCAMINHAMENTOVID;
                    
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $BoxOportunidade["BoxOportunidade"][] = [

                     'ATENDIMENTOS' => $ATENDIMENTOS,
                     'VITORIAS' =>$VITORIAS,
                     'CONVERSAO' => number_format($CONVERSAO,2,",","."),
                     'CONVERSAOVIDEO' => number_format($CONVERSAOVIDEO,2,",","."),
                   ];


             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($BoxOportunidade);  

                //echo "Total geral: {$qtd}";
