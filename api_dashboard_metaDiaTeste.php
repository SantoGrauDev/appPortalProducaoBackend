<?php



// ************ FRAGMENTANDO A DATA PARA UTILIZAR NAS QUERYS *****************
$hoje = date('Y-m-d');

$hoje = explode('-', $hoje);

$ano = $hoje[0];
$mes = $hoje[1];
$dia = $hoje[2];


//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once 'conexao.php';


$DIFTOTAL = 0;
$LIQUIDO = 0;
$METAPORDIA =0;
$META = 0;   
$DIFERENCADIA = 0;               
$METAACUMULADO = 0;

$DiasRestantes = 30 - $dia;


//$id = 3;


for ($dia = '01' ; $dia <= '31'; $dia++) {

$diaQueryQuatro = "'".$ano."-".$mes."-".$dia."'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-".$dia." 23:59'";


  $id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT first 1 sum(a.PEDVALORLIQUIDO) AS LIQUIDO, f.MTVVALORMETA AS META, f.MTVVALORMETA/24 AS METAPORDIA
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
                   $METAPORDIA = $dados["METAPORDIA"];    
                   $META = $dados["META"];        
                   $DIFERENCADIA = $dados["METAPORDIA"] - $dados["LIQUIDO"];                    
                                     
                     if($DIFERENCADIA < $METAPORDIA ){
                    $DIFTOTAL += $DIFERENCADIA;
                   }

                    $METAACUMULADO = $METAPORDIA + $DIFTOTAL;
                    
                    

                endforeach;  
}

                    
                    if($DiasRestantes == 0){
                       
                       $METADILUIDA = $DIFTOTAL/1;

                    } else {

                       $METADILUIDA = $DIFTOTAL/$DiasRestantes;
                    }

                    $Dif = 'Sim';

                    if($DIFTOTAL > 0) {
                      $Dif = 'Sim';
                    } else {
                     $Dif = 'Nao';
                    }

                    $METADIADIULUIDA = $METAPORDIA + $METADILUIDA;

                   $MetaDiasUltimo["MetaDiasUltimo"][] = [

                     'LIQUIDO' => number_format($LIQUIDO,2,",","."),  
                     'META' => number_format($META,2,",","."),  
                     'METAPORDIA' => number_format($METAPORDIA,2,",","."),
                     'DIFERENCADIA' => number_format($DIFERENCADIA,2,",","."),                     
                     'DIFTOTAL' => number_format($DIFTOTAL,2,",","."),                     
                     'METAACUMULADO' => number_format($METAACUMULADO,2,",","."),                           
                     'METADIADIULUIDA' => number_format($METADIADIULUIDA,2,",","."),                       
                     'DIF' => $Dif, 
                   ];

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($MetaDiasUltimo);  

                //echo "Total geral: {$qtd}";
           
                   ?> 
