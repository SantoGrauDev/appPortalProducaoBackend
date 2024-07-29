
<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Incluir a conexao
include_once '................./../../../conexao.php';

//$id = 3;

$QTDVENDEDORES = 0;
$SCORE = 0;
$TM = 0;
$META = 0;
$VENDAS =  0;

$scoreTotal = 0;
$TMTotal = 0;

$diasMes =  $dias = date ("t");
$diasMesEfe = $diasMes - 6;


$id = filter_input(INPUT_GET, 'id');

               $read = $conn->prepare("SELECT DISTINCT  COUNT(a.LCTVALOR) AS VENDAS
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
               ORDER BY VENDAS DESC");



$read->setFetchMode(PDO::FETCH_ASSOC);            
$read->execute();   
$array = $read->fetchAll();   

    foreach ($array as $dados): 
                
                     
                   $VND = $dados["VENDAS"];  
                   $ATENDIMENTOS = 17 * 32 * $diasMesEfe;
                    endforeach; 

                    $CONVERSAO =  $VND * 100 / $ATENDIMENTOS;
                   
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $BoxOportunidade["BoxOportunidade"][] = [

                     
                     'VENDAS' =>$VND,
                     'CONVERSAO' => number_format($CONVERSAO),
                    
                   ];


             

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($BoxOportunidade);  

                //echo "Total geral: {$qtd}";
