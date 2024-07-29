<?php 

//Cabeçalho obrigatório
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");


//incluindo conexão
include_once '../conexao.php';

// QUERY ESTOQUE

			   
            
            $read = $conn->prepare("SELECT OS AS OS, VENDEDOR AS VENDEDOR, LENTE AS LENTE, TIPOLENTE AS TIPOLENTE, INDICE AS INDICE, 
                VALOR AS VALOR, GRADE AS GRADE, DNPOE AS DNPOE, DNPOD AS DNPOD, DP AS DP, COMENTARIO AS COMENTARIO
                FROM TB_LANCAOS_EXPED ");

            $read->setFetchMode(PDO::FETCH_ASSOC);            
            $read->execute();   
            $array = $read->fetchAll();       

                foreach ($array as $dados): 

                   $OS = $dados["OS"]; 
                   $VENDEDOR = $dados["VENDEDOR"]; 
                   $LENTE = $dados["LENTE"]; 
                   $TIPOLENTE = $dados["TIPOLENTE"]; 
                   $INDICE = $dados["INDICE"]; 
                   $VALOR = $dados["VALOR"]; 
                   $GRADE = $dados["GRADE"]; 
                   $DNPOE = $dados["DNPOE"];
                   $DNPOD = $dados["DNPOD"];
                   $DP = $dados["DP"]; 
                   $COMENTARIO = $dados["COMENTARIO"]; 


                   ?> 

                            <?php 
                            
                   extract($dados);

                   $lista_produtos["records"][] = [
                     'OS' => $OS,
                     'VENDEDOR' => $VENDEDOR,
                     'LENTE' => $LENTE,
                     'TIPOLENTE' => $TIPOLENTE,
                     'INDICE' => $INDICE,
                     'VALOR' => $VALOR,
                     'GRADE' => $GRADE,
                     'DNPOE' => $DNPOE,
                     'DNPOD'=> $DNPOD, 
                     'COMENTARIO'=> $COMENTARIO,
                     'DP' => $DP
                   ];


                endforeach; 

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  

                //echo "Total geral: {$qtd}";
                ?>
           