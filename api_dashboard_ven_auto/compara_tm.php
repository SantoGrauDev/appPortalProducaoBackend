<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '../conexao.php';

$hoje = date('Y-m-d');

$hoje = explode('-', $hoje);

$ano = $hoje[0];
$mes = $hoje[1];
$dia = $hoje[2];

$diaQueryQuatro = "'".$ano."-".$mes."-01'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-".$dia."'";

$mesAnterior = $hoje[1] - 1;
$anoAnterior = $hoje[0] - 1;


if($mesAnterior == 0) {
  $mesAnterior = 12;
}

if($mesAnterior == 0) {
  $anoAnterior = 2022;
}




$diaInicioMesAnterior = "'".$anoAnterior."-".$mesAnterior."-01'";

$diaFimMesAnterior = "'".$anoAnterior."-".$mesAnterior."-".$dia."'";



// QUERY MÊS ANTERIOR

$id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT DISTINCT  sum(a.LCTVALOR) AS LIQUIDO, COUNT(e.PEDSEQUENCIAL) AS QTDTRANSACOES,sum(a.LCTVALOR)/COUNT(e.PEDSEQUENCIAL) AS TM
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
                                   WHERE a.LCTDATALANCAMENTO >={$diaInicioMesAnterior} AND a.LCTDATALANCAMENTO <= {$diaQueryQuatroFim}
                                   AND k.UID = {$id}
                                   AND e.FILID_FILIAL = '5'
                                   AND e.MCVID IS NULL
                                   ORDER BY LIQUIDO DESC
                                            ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
           
                   $LIQUIDO = $dados["LIQUIDO"];                
                   $QTDTRANSACOES = $dados["QTDTRANSACOES"];  
                   $TMANTIGO = $dados["TM"]; 


                   endforeach; 

                    // QUERY MÊS ATUAL
//$id = 3;
$id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT DISTINCT  sum(a.LCTVALOR) AS LIQUIDO,COUNT(e.PEDSEQUENCIAL) AS QTDTRANSACOES,sum(a.LCTVALOR)/COUNT(e.PEDSEQUENCIAL) AS TM
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
                                   WHERE a.LCTDATALANCAMENTO >={$diaQueryQuatro} AND a.LCTDATALANCAMENTO <= {$diaQueryQuatroFim}
                                   AND k.UID = {$id}
                                   AND e.FILID_FILIAL = '5'
                                   AND e.MCVID IS NULL
                                   ORDER BY LIQUIDO DESC
                        ");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
           
                   $LIQUIDO = $dados["LIQUIDO"];                
                   $QTDTRANSACOES = $dados["QTDTRANSACOES"];  
                   $TMPORCENTAGEM = $dados["TM"]; 
                   $TM = $dados["TM"];


                   endforeach; 

           $COMPARATM = $TMPORCENTAGEM - $TMANTIGO;
           $COMPARATM = $COMPARATM / $TMANTIGO;   
            $COMPARATM =  $COMPARATM  * 100;


                   ?> 


                    
                   <?php 
                   $ComparativoTM["COMPARATIVOTM"][] = [

                     'LIQUIDO' => number_format($LIQUIDO,2,",","."),
                     'QTDTRANSACOES' => number_format($QTDTRANSACOES,2,",",""),
                     'TM' => number_format($TM,2,",",""),
                     'TMANTIGO' => number_format($TMANTIGO,2,",",""),
                     'COMPARATM' => number_format($COMPARATM,2,",","")
                   ];


             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($ComparativoTM);  

                //echo "Total geral: {$qtd}";
