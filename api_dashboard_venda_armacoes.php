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

$read = $conn->prepare("SELECT n.PESNOME, sum(l.PICQTDE) AS TOTAL 
                        FROM TB_VEN_VENDA a
                        INNER JOIN TB_VPE_VENDAPEDIDOS b ON b.VENID_VENDA = a.VENID 
                        INNER JOIN TB_IPD_ITEMPEDIDO c ON c.PEDID_PEDIDO = b.PEDID_PEDIDO 
                        INNER JOIN TB_MAT_MATERIAL d ON c.MATID_PRODUTO = d.MATID 
                        INNER JOIN TB_NCM_NCM e ON e.NCMID = d.NCMID 
                        INNER JOIN TB_AAT_ATRIBUTOS f ON f.MATID = d.MATID  
                        INNER JOIN TB_DRIP_APELIDO h ON h.MATFANTASIA  = d.MATFANTASIA
                        INNER JOIN TB_PED_PEDIDO i ON i.PEDID = c.PEDID_PEDIDO
                        INNER JOIN TB_TVN_TIPOVENDA j ON j.TVNID = i.TVNID
                        INNER JOIN TB_PIC_PEDIDOITEMCLIENTE l ON l.IPDID  = c.IPDID
                        INNER JOIN TB_VND_VENDEDOR m ON m.VNDID = i.VNDID_PRIMEIRO 
                        INNER JOIN TB_PES_PESSOA n ON n.PESID = m.PESID 
                        INNER JOIN TB_NIVEL_ACESSO o ON o.PESID = n.PESID 
                        WHERE a.VENDATAHORAFATURAMENTO  >= $subData AND a.VENDATAHORAFATURAMENTO <= $hoje
                        AND i.PEDDATACANCELAMENTO IS NULL
                        AND o.UID = {$id}
                        AND NOT e.NCMID = '56' 
                        AND NOT e.NCMID = '65'
                        AND NOT e.NCMID = '66'
                        AND NOT e.NCMID = '68'
                        AND NOT e.NCMID = '71'
                        AND NOT e.NCMID = '73'  
                        AND NOT e.NCMID = '75'
                        GROUP BY  l.PICQTDE, n.PESNOME 
                        ORDER BY TOTAL desc ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
           
                   $TOTAL = $dados["TOTAL"];       
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $lista_produtos["records"][] = [

                     'TOTAL' => number_format($TOTAL,0,",","")
                   ];


                endforeach; 

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  

                //echo "Total geral: {$qtd}";
