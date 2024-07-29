<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once 'conexao.php';

$hoje = date('Y-m-d');

$hoje = explode('-', $hoje);

$ano = $hoje[0];
$mes = $hoje[1];
$dia = $hoje[2];

$diaQueryQuatro = "'".$ano."-".$mes."-01'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-".$dia." 23:59'";

//$id = 3;
$id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT count(c.PESNOME) AS QTDTRANSACOES, sum(a.PEDVALORLIQUIDO) AS LIQUIDO, sum(a.PEDVALORLIQUIDO)/count(c.PESNOME) AS TM, f.MTVVALORMETA AS METAINDIVIDUAL
                        FROM TB_PED_PEDIDO a
                        INNER JOIN TB_VND_VENDEDOR b ON b.VNDID = a.VNDID_PRIMEIRO
                        INNER JOIN TB_PES_PESSOA c ON c.PESID = b.PESID 
                        INNER JOIN TB_NIVEL_ACESSO d ON d.PESID = c.PESID 
                        INNER JOIN TB_DMV_DETALHEMETAVEND e ON e.VNDID = d.VNDID 
                        INNER JOIN TB_MTV_METASVENDEDOR f ON f.MTVID = e.MTVID 
                        WHERE a.PEDDATADIGITACAO >= {$diaQueryQuatro} AND a.PEDDATADIGITACAO <= {$diaQueryQuatroFim} 
                        AND d.UID = {$id}
                        AND a.FILID_FILIAL = '5'
                        AND a.PEDDATACANCELAMENTO IS NULL
                        GROUP BY f.MTVVALORMETA
                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
           
                   $LIQUIDO = $dados["LIQUIDO"];  
                   $METAINDIVIDUAL = $dados["METAINDIVIDUAL"];                
                   $QTDTRANSACOES = $dados["QTDTRANSACOES"];  
                   $TM = $dados["TM"]; 
                   $OBJETIVOMETA = $dados["METAINDIVIDUAL"] - $dados["LIQUIDO"]; 
                

                   ?> 

                    
                   <?php 
                   extract($dados);

                   $lista_produtos["records"][] = [

                     'LIQUIDO' => number_format($LIQUIDO,2,",","."),
                     'METAINDIVIDUAL' => number_format($METAINDIVIDUAL,2,",","."),
                     'QTDTRANSACOES' => number_format($QTDTRANSACOES,2,",",""),
                     'TM' => number_format($TM,2,",",""),
                     'OBJETIVOMETA' => number_format($OBJETIVOMETA,2,",",".")
                   ];


                endforeach; 

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  

                //echo "Total geral: {$qtd}";
