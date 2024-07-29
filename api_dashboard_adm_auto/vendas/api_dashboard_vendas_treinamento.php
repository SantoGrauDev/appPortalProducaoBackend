<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '................./../../../conexao.php';

//$id = 3;


$id = filter_input(INPUT_GET, 'id');


//***************************************************CODIGO PARA QUANDO ENTRAR PESSOAS QUE ESTÃO EM TREINAMENTO************************************************************//


/*
               $read = $conn->prepare("SELECT DISTINCT  COUNT(a.LCTVALOR) AS VENDAS,sum(a.LCTVALOR)/COUNT(e.PEDSEQUENCIAL) AS TM, k.NOME AS VENDEDOR,n.TVNDESCRICAO,m.MTVVALORMETA AS META
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
               INNER JOIN TB_TVN_TIPOVENDA n ON n.TVNID = e.TVNID 
               WHERE e.FILID_FILIAL = '5'
               AND a.LCTDATALANCAMENTO >= '2023-04-01' AND a.LCTDATALANCAMENTO <= '2023-04-30'
               AND k.SUPERVISOR = 'TREINAMENTO' 
               AND e.MCVID IS NULL
               GROUP BY k.NOME,n.TVNDESCRICAO,m.MTVVALORMETA 
               ORDER BY VENDAS DESC
                                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                   $VENDEDOR = $dados["VENDEDOR"];    
                   $VENDAS = $dados["VENDAS"];    
                   $META = $dados["META"]; 
                   $CONVERSAO = $dados["CONVERSAO"];
                   $ATENDIMENTOS = $dados["ATENDIMENTOS"];   
                   $TM = $dados["TM"];     
                   $SCORE = $dados["SCORE"];   

                   $PERCENTUALMETA = $VENDAS * 100 / $META;

                
                    
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $treinamentoResult["treinamentoResult"][] = [

                     'VENDEDOR' =>$VENDEDOR,
                     'VENDAS' => number_format($VENDAS,2,",","."),
                     'PERCENTUALMETA' => number_format($PERCENTUALMETA,2,",",""),
                     'META' => number_format($META,2,",","."),
                     'TM' => number_format($TM,2,",","."),
                     'CONVERSAO' => $CONVERSAO,
                     'ATENDIMENTOS' => $ATENDIMENTOS,   
                     'SCORE' => number_format($SCORE,0,",",""),                  
                   ];

    endforeach; 
             
*/