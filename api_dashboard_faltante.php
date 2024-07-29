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

$read = $conn->prepare("SELECT first 1 d.NOME AS NOME, sum(a.PEDVALORLIQUIDO) AS LIQUIDO, f.MTVVALORMETA AS META 
                        FROM TB_PED_PEDIDO a
                        INNER JOIN TB_VND_VENDEDOR b ON b.VNDID = a.VNDID_PRIMEIRO
                        INNER JOIN TB_PES_PESSOA c ON c.PESID = b.PESID 
                        INNER JOIN TB_NIVEL_ACESSO d ON d.PESID = c.PESID 
                        INNER JOIN TB_DMV_DETALHEMETAVEND e ON b.VNDID = d.VNDID 
                        INNER JOIN TB_MTV_METASVENDEDOR f ON f.MTVID = e.MTVID 
                        WHERE a.PEDDATADIGITACAO >= {$subData} AND a.PEDDATADIGITACAO <= {$hoje} 
                        AND d.UID = {$id}
                        AND a.FILID_FILIAL = '5'
                        AND a.PEDDATACANCELAMENTO IS NULL
                        GROUP BY d.NOME, F.MTVVALORMETA
                        ");

$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
           
                   $LIQUIDO = $dados["LIQUIDO"];                            
                   $META = $dados["META"]; 
                   $FALTANTE = $dados["META"] - $dados["LIQUIDO"]; 
                   $METADIA =  $dados["META"] / 24;

                 

                   ?> 

                    
                   <?php 
                   extract($dados);

                   $lista_produtos["records"][] = [

                     'LIQUIDO' => number_format($LIQUIDO,2,",",""),                     
                     'NOME' =>ucwords(strtolower($NOME)),                    
                     'META' => $META,
                     'FALTANTE' => $FALTANTE,
                     'METADIA' => $METADIA
                   ];


                endforeach; 
                
                        //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  

                //echo "Total geral: {$qtd}";
