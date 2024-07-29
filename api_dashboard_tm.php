<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once 'conexao.php';

//Automatizar data atual
$hoje ="'".date('Y-m-d 23:59')."'";

//subtraindo dias da data atual
$subHoje = date('Y-m-d');

$subData = "'".date('Y-m-d', strtotime('-7 days', strtotime($subHoje)))."'";

//$id = 3;
$id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT count(c.PESNOME) AS QTDTRANSACOES, sum(a.PEDVALORLIQUIDO) AS LIQUIDO, sum(a.PEDVALORLIQUIDO)/count(c.PESNOME) AS TM
                        FROM TB_PED_PEDIDO a
                        INNER JOIN TB_VND_VENDEDOR b ON b.VNDID = a.VNDID_PRIMEIRO
                        INNER JOIN TB_PES_PESSOA c ON c.PESID = b.PESID 
                        INNER JOIN TB_NIVEL_ACESSO d ON d.PESID = c.PESID 
                        WHERE a.PEDDATADIGITACAO >= {$subData} AND a.PEDDATADIGITACAO <= {$hoje} 
                        AND d.UID = {$id}
                        AND a.FILID_FILIAL = '5'
                        AND a.PEDDATACANCELAMENTO IS NULL
                        GROUP BY c.PESNOME,d.EMAIL, d.PESID, d.VNDID, d.NOME, d.NIVEL_ACESSO , d.SUPERVISOR, d.UID
                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
           
                   $LIQUIDO = $dados["LIQUIDO"];                
                   $QTDTRANSACOES = $dados["QTDTRANSACOES"];  
                   $TM = $dados["TM"]; 
                

                   ?> 

                    
                   <?php 
                   extract($dados);

                   $lista_produtos["records"][] = [

                     'LIQUIDO' => number_format($LIQUIDO,2,",",""),
                     'QTDTRANSACOES' => number_format($QTDTRANSACOES,2,",",""),
                     'TM' => number_format($TM,2,",","")
                   ];


                endforeach; 

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  

                //echo "Total geral: {$qtd}";
