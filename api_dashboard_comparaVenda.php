<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once 'conexao.php';
$LIQUIDOANTIGO = 0;
$LIQUIDOATUAL = 0 ;
$COMPARAVENDAPERCENTUAL = 0;


$hoje = date('Y-m-d');

$hoje = explode('-', $hoje);

$ano = $hoje[0];
$mes = $hoje[1];
$dia = $hoje[2];

$diaQueryQuatro = "'".$ano."-".$mes."-01'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-".$dia." 23:59'";

$mesAnterior = $hoje[1] - 1;

if($mesAnterior == 0) {
  $mesAnterior = 12;
}

if($mesAnterior == 02){
  if($dia == 29 || $dia == 30 ) {
    $dia = 28;
    };
};




$diaInicioMesAnterior = "'".$ano."-".$mesAnterior."-01'";

$diaFimMesAnterior = "'".$ano."-".$mesAnterior."-".$dia." 23:59'";

// QUERY MES ANTERIOR

$id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT first 1 sum(a.PEDVALORLIQUIDO) AS LIQUIDO
                        FROM TB_PED_PEDIDO a
                        INNER JOIN TB_VND_VENDEDOR b ON b.VNDID = a.VNDID_PRIMEIRO
                        INNER JOIN TB_PES_PESSOA c ON c.PESID = b.PESID 
                        INNER JOIN TB_NIVEL_ACESSO d ON d.PESID = c.PESID 
                        WHERE a.PEDDATADIGITACAO >= {$diaInicioMesAnterior} AND a.PEDDATADIGITACAO <= {$diaFimMesAnterior}
                        AND d.UID = {$id}
                        AND a.FILID_FILIAL = '5'
                        AND a.PEDDATACANCELAMENTO IS NULL
                        GROUP BY c.PESNOME,d.EMAIL, d.PESID, d.VNDID, d.NOME, d.NIVEL_ACESSO , d.SUPERVISOR, d.UID
                        ");

$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
           
                   $LIQUIDOANTIGO = $dados["LIQUIDO"];  

                   endforeach;   

                   ?> 


                    
                   <?php 

// QUERY MES ATUAL

//$id = 3;
$id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT first 1 sum(a.PEDVALORLIQUIDO) AS LIQUIDO
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
           
                   $LIQUIDOATUAL = $dados["LIQUIDO"];   
                   $LIQUIDOATUALPORCENTAGEM = $dados["LIQUIDO"];   

                        endforeach;   

                   ?> 

                    
                   <?php 


                  $COMPARAVENDAPERCENTUAL =  $LIQUIDOATUALPORCENTAGEM - $LIQUIDOANTIGO;


                  $COMPARAVENDAPERCENTUAL  = $COMPARAVENDAPERCENTUAL  / $LIQUIDOANTIGO * 100;


                  $BATEUMETA = false;

                  if($LIQUIDOATUAL > $LIQUIDOANTIGO){
                    $BATEUMETA = true;
                    $responsetxt = 'Melhorou em: ';
                  }else {
                    $responsetxt = 'Menor em:  ';
                  }

                  if($LIQUIDOATUAL == 0 && $BATEUMETA == false) {
                    $responsetxt = 'Não possui vendas no momento';
                  } 

                     if($LIQUIDOATUAL == 0) {
                     $comparaVenda["COMPARAVENDA"][] = [
                        'erro' => true,
                        'responsetxt' => $responsetxt,
                      ];
                  } else {
                      $comparaVenda["COMPARAVENDA"][] = [
                      'erro' => false,  
                      'responsetxt' => $responsetxt,
                      'BATEUMETA' => $BATEUMETA,
                      'LIQUIDOATUAL' => number_format($LIQUIDOATUAL,2,",","."),
                      'LIQUIDOANTIGO' => number_format($LIQUIDOANTIGO,2,",","."),
                       'COMPARAVENDAPERCENTUAL' => number_format($COMPARAVENDAPERCENTUAL,2,",","").'%',
                        ];
                  }

                  
           

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($comparaVenda);  

                //echo "Total geral: {$qtd}";
