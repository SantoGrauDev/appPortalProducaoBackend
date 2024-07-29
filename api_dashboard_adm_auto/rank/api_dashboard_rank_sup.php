<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

//$id = 3;


$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT DISTINCT  COUNT(a.LCTVALOR) AS VENDAS, k.SUPERVISOR AS SUPERVISOR, sum(a.LCTVALOR)/COUNT(e.PEDSEQUENCIAL) AS tm
               FROM TB_LCT_LANCAMENTOS a
               INNER JOIN TB_LTV_LANCAMENTOVENDA b ON b.LCTID = a.LCTID 
               INNER JOIN TB_VEN_VENDA c ON c.VENID = b.VENID 
               INNER JOIN TB_VPE_VENDAPEDIDOS d ON d.VENID_VENDA = c.VENID 
               INNER JOIN TB_PED_PEDIDO e ON e.PEDID = d.PEDID_PEDIDO 
               INNER JOIN TB_CLI_CLIENTE f ON f.CLIID = c.CLIID_PAGADOR 
               INNER JOIN TB_PES_PESSOA g ON g.PESID = f.PESID 
               INNER JOIN TB_USU_USUARIO h ON h.USUID = a.USUID
               INNER JOIN TB_VND_VENDEDOR i ON i.VNDID = e.VNDID_PRIMEIRO  
               INNER JOIN TB_NIVEL_ACESSO k ON k.PESID = i.PESID 
               INNER JOIN TB_DMV_DETALHEMETAVEND l ON l.VNDID = i.VNDID 
               INNER JOIN TB_MTV_METASVENDEDOR m ON m.MTVID = l.MTVID
               WHERE e.FILID_FILIAL = '5' 
               AND a.LCTDATALANCAMENTO >= '2023-05-01' AND a.LCTDATALANCAMENTO <= '2023-05-30'
               AND e.MCVID IS NULL
               GROUP BY supervisor
               ORDER BY VENDAS DESC
                                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                   $VENDAS = $dados["VENDAS"];  
                   $SUPERVISOR = $dados["SUPERVISOR"];  
                   $TM = $dados["TM"];
                
                    
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $RankVendasPodiumSup["RankVendasPodiumSup"][] = [

                     'SUPERVISOR' =>$SUPERVISOR,
                     'VENDAS' => number_format($VENDAS,2,",","."),
                     'TM' => number_format($TM,2,",","."),
                   ];

    endforeach; 
             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($RankVendasPodiumSup);  

                //echo "Total geral: {$qtd}";
