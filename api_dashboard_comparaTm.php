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

$mesAnterior = $hoje[1] - 1;
$anoAnterior = $hoje[0] - 1;


if($mesAnterior == 0) {
  $mesAnterior = 12;
}

if($mesAnterior == 0) {
  $anoAnterior = 2022;
}




$diaInicioMesAnterior = "'".$anoAnterior."-".$mesAnterior."-01'";

$diaFimMesAnterior = "'".$anoAnterior."-".$mesAnterior."-".$dia." 23:59'";



// QUERY MÊS ANTERIOR

$id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT count(c.PESNOME) AS QTDTRANSACOES, sum(a.PEDVALORLIQUIDO) AS LIQUIDO, sum(a.PEDVALORLIQUIDO)/count(c.PESNOME) AS TM
                        FROM TB_PED_PEDIDO a
                        INNER JOIN TB_VND_VENDEDOR b ON b.VNDID = a.VNDID_PRIMEIRO
                        INNER JOIN TB_PES_PESSOA c ON c.PESID = b.PESID 
                        INNER JOIN TB_NIVEL_ACESSO d ON d.PESID = c.PESID 
                        WHERE a.PEDDATADIGITACAO >= {$diaInicioMesAnterior} AND a.PEDDATADIGITACAO <= {$diaQueryQuatroFim} 
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
                   $TMANTIGO = $dados["TM"]; 


                   endforeach; 

                    // QUERY MÊS ATUAL
//$id = 3;
$id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT count(c.PESNOME) AS QTDTRANSACOES, sum(a.PEDVALORLIQUIDO) AS LIQUIDO, sum(a.PEDVALORLIQUIDO)/count(c.PESNOME) AS TM
                        FROM TB_PED_PEDIDO a
                        INNER JOIN TB_VND_VENDEDOR b ON b.VNDID = a.VNDID_PRIMEIRO
                        INNER JOIN TB_PES_PESSOA c ON c.PESID = b.PESID 
                        INNER JOIN TB_NIVEL_ACESSO d ON d.PESID = c.PESID 
                        WHERE a.PEDDATADIGITACAO >= {$diaQueryQuatro} AND a.PEDDATADIGITACAO <= {$diaQueryQuatroFim} 
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
