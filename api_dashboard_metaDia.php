<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once 'conexao.php';

// ************ FRAGMENTANDO A DATA PARA UTILIZAR NAS QUERYS *****************
$hoje = date('Y-m-d');
$hoje = explode('-', $hoje);

$ano = $hoje[0];
$mes = $hoje[1];
$dia = $hoje[2];

$DIFTOTAL = 0;
$LIQUIDO = 0;
$METAPORDIA =0;
$META = 0;   
$DIFERENCADIA = 0;               
$METAACUMULADO = 0;

// ******************************************* PRIMEIRO DIA ********************************************
//subtraindo dias da data atual
$subHoje = '2023-01-02';
$subDataPrimeiroInicio = "'".date('Y-m-d', strtotime('-1 days', strtotime($subHoje)))."'";
$subDataPrimeiroFim = "'".date('Y-m-d', strtotime('-1 days', strtotime($subHoje)))." 23:59'";

$diaQueryUm = "'".$ano."-".$mes."-01'";
$diaQueryUmFim = "'".$ano."-".$mes."-01 23:59'";

//$id = 3;
$id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT first 1  sum(a.PEDVALORLIQUIDO) AS LIQUIDO, f.MTVVALORMETA AS META, f.MTVVALORMETA/24 AS METAPORDIA
                        FROM TB_PED_PEDIDO a
                        INNER JOIN TB_VND_VENDEDOR b ON b.VNDID = a.VNDID_PRIMEIRO
                        INNER JOIN TB_PES_PESSOA c ON c.PESID = b.PESID 
                        INNER JOIN TB_NIVEL_ACESSO d ON d.PESID = c.PESID 
                        INNER JOIN TB_DMV_DETALHEMETAVEND e ON e.VNDID = d.VNDID 
                        INNER JOIN TB_MTV_METASVENDEDOR f ON f.MTVID = e.MTVID 
                        WHERE a.PEDDATADIGITACAO >= {$diaQueryUm} AND a.PEDDATADIGITACAO <= {$diaQueryUmFim}
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
                                $DIFTOTAL += $DIFERENCADIA ;           

                               }

             endforeach; 
            


   // ******************************************* SEGUNDO DIA ********************************************
//subtraindo dias da data atual

$diaQueryDois = "'".$ano."-".$mes."-02'";
$diaQueryDoisFim = "'".$ano."-".$mes."-02 23:59'";



//$id = 3;
$id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT first 1 sum(a.PEDVALORLIQUIDO) AS LIQUIDO, f.MTVVALORMETA AS META, f.MTVVALORMETA/24 AS METAPORDIA
                        FROM TB_PED_PEDIDO a
                        INNER JOIN TB_VND_VENDEDOR b ON b.VNDID = a.VNDID_PRIMEIRO
                        INNER JOIN TB_PES_PESSOA c ON c.PESID = b.PESID 
                        INNER JOIN TB_NIVEL_ACESSO d ON d.PESID = c.PESID 
                        INNER JOIN TB_DMV_DETALHEMETAVEND e ON e.VNDID = d.VNDID 
                        INNER JOIN TB_MTV_METASVENDEDOR f ON f.MTVID = e.MTVID 
                        WHERE a.PEDDATADIGITACAO >= {$diaQueryDois} AND a.PEDDATADIGITACAO <= {$diaQueryDoisFim}
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

                    $METAACUMULADO = $dados["METAPORDIA"] + $DIFTOTAL;
    

                endforeach; 

// ******************************************* TERCEIRO DIA ********************************************
//subtraindo dias da data atual

$diaQueryTres = "'".$ano."-".$mes."-03'";
$diaQueryTresFim = "'".$ano."-".$mes."-03 23:59'";



//$id = 3;
$id = filter_input(INPUT_GET, 'id');

$read = $conn->prepare("SELECT first 1 sum(a.PEDVALORLIQUIDO) AS LIQUIDO, f.MTVVALORMETA AS META, f.MTVVALORMETA/24 AS METAPORDIA
                        FROM TB_PED_PEDIDO a
                        INNER JOIN TB_VND_VENDEDOR b ON b.VNDID = a.VNDID_PRIMEIRO
                        INNER JOIN TB_PES_PESSOA c ON c.PESID = b.PESID 
                        INNER JOIN TB_NIVEL_ACESSO d ON d.PESID = c.PESID 
                        INNER JOIN TB_DMV_DETALHEMETAVEND e ON e.VNDID = d.VNDID 
                        INNER JOIN TB_MTV_METASVENDEDOR f ON f.MTVID = e.MTVID 
                        WHERE a.PEDDATADIGITACAO >= {$diaQueryTres} AND a.PEDDATADIGITACAO <= {$diaQueryTresFim}
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


                    $METAACUMULADO = $METAPORDIA + $DIFTOTAL;  

// ******************************************* QUARTO DIA ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-04'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-04 23:59'";



//$id = 3;
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

// *******************************************  DIA 5 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-05'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-05 23:59'";



//$id = 3;
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

                // *******************************************  DIA 6 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-06'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-06 23:59'";



//$id = 3;
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

                // *******************************************  DIA 7 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-07'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-07 23:59'";



//$id = 3;
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


// *******************************************  DIA 8 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-08'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-08 23:59'";



//$id = 3;
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


// *******************************************  DIA 9 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-09'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-09 23:59'";



//$id = 3;
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

                // *******************************************  DIA 10 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-10'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-10 23:59'";



//$id = 3;
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


                // *******************************************  DIA 11 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-11'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-11 23:59'";



//$id = 3;
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

                // *******************************************  DIA 12 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-12'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-12 23:59'";



//$id = 3;
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

                // *******************************************  DIA 13 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-13'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-13 23:59'";



//$id = 3;
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

                // *******************************************  DIA 14 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-14'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-14 23:59'";



//$id = 3;
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

                 // *******************************************  DIA 15 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-15'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-15 23:59'";



//$id = 3;
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

             // *******************************************  DIA 16 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-16'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-16 23:59'";



//$id = 3;
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

                // *******************************************  DIA 17 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-17'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-17 23:59'";



//$id = 3;
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

        // *******************************************  DIA 18 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-18'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-18 23:59'";



//$id = 3;
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

                // *******************************************  DIA 19 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-19'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-19 23:59'";



//$id = 3;
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

                // *******************************************  DIA 20 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-20'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-20 23:59'";



//$id = 3;
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

// *******************************************  DIA 21 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-21'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-21 23:59'";



//$id = 3;
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

                // *******************************************  DIA 22 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-22'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-22 23:59'";



//$id = 3;
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

// *******************************************  DIA 23 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-23'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-23 23:59'";



//$id = 3;
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

                // *******************************************  DIA 24 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-24'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-24 23:59'";



//$id = 3;
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

                 // *******************************************  DIA 25 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-25'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-25 23:59'";



//$id = 3;
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

                // *******************************************  DIA 26 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-26'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-26 23:59'";



//$id = 3;
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

                // *******************************************  DIA 27 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-27'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-27 23:59'";



//$id = 3;
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

                // *******************************************  DIA 28 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-28'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-28 23:59'";



//$id = 3;
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

        // *******************************************  DIA 29 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-29'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-29 23:59'";



//$id = 3;
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

                // *******************************************  DIA 30 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-30'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-30 23:59'";



//$id = 3;
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

                 // *******************************************  DIA 31 ********************************************
//subtraindo dias da data atual

$diaQueryQuatro = "'".$ano."-".$mes."-31'";
$diaQueryQuatroFim = "'".$ano."-".$mes."-31 23:59'";



//$id = 3;
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

                   $MetaDiasUltimo["MetaDiasUltimo"][] = [

                     'LIQUIDO' => number_format($LIQUIDO,2,",",""),  
                     'META' => number_format($META,2,",","."),  
                     'METAPORDIA' => number_format($METAPORDIA,2,",","."),
                     'DIFERENCADIA' => number_format($DIFERENCADIA,2,",","."),                     
                     'DIFTOTAL' => number_format($DIFTOTAL,2,",","."),                     
                     'METAACUMULADO' => number_format($METAACUMULADO,2,",","."),  
                   ];

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($MetaDiasUltimo);  

                //echo "Total geral: {$qtd}";
           
                   ?> 

                