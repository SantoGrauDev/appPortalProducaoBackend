<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../../conexao.php';

//$id = 3;

$QTDVENDEDORES = 0;
$SCORE = 0;
$TM = 0;
$VENDAS =  0;

$scoreTotal = 0;
$TMTotal = 0;

$hoje = date('Y-m-d');

$hoje = explode('-', $hoje);

$ano = $hoje[0];
$mes = $hoje[1];
$dia = $hoje[2];

$dataDiaInicio = "'".$ano."-".$mes."-".$dia."'";
$dataDiaFim = "'".$ano."-".$mes."-".$dia."'";

$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT DISTINCT  sum(a.LCTVALOR) AS VENDAS, COUNT(a.LCTVALOR),sum(a.LCTVALOR)/COUNT(e.PEDSEQUENCIAL) AS TM, count(k.NOME) AS VITORIAS
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
               WHERE a.LCTDATALANCAMENTO >={$dataDiaInicio} AND a.LCTDATALANCAMENTO <= {$dataDiaFim}
               AND k.UID_SUPERVISOR = {$id}
               AND e.FILID_FILIAL = '5'
               AND e.MCVID IS NULL
               ORDER BY VENDAS DESC");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                   $TM = $dados["TM"];  
                   $VENDAS = $dados["VENDAS"]; 
                   $VITORIAS = $dados["VITORIAS"]; 

                      endforeach; 
                    
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $BoxVenda["BoxVenda"][] = [

                     'TM' => number_format($TM,2,",",""),
                     'VENDAS' => number_format($VENDAS,2,",","."),
                     'VITORIAS' => number_format($VITORIAS,0,",",""),
                   ];


             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($BoxVenda);  

                //echo "Total geral: {$qtd}";
