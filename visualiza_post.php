<?php 

//Cabeçalho obrigatório
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");


//incluindo conexão
include_once 'conexao.php';



// QUERY ESTOQUE

			   
            
            $read = $conn->prepare("SELECT AUTOR_POST AS AUTOR, TITULO_POST AS TITULO, DATA_POST AS TEMPO, POST_POST AS POST, ID_POST AS ID "
                                    . "FROM TB_POST_PAGE "
                                    . "ORDER BY DATA_POST DESC" );

            $read->setFetchMode(PDO::FETCH_ASSOC);            
            $read->execute();   
            $array = $read->fetchAll();
            
            $lista_produtos["records"] = []; // Inicializa o array

                foreach ($array as $dados): 

                   $autorPost = $dados["AUTOR"]; 
                   $tituloPost = $dados["TITULO"]; 
                   $dataPost = date('d/m/Y H:i', strtotime($dados["TEMPO"]));
                   $post = $dados["POST"]; 
                   $id = $dados["ID"]; 
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $lista_produtos["records"][] = [
                     'AUTOR_POST' => $autorPost,
                     'TITULO_POST' => $tituloPost,
                     'DATA_POST' => $dataPost,
                     'ID_POST' => $id,
                     'POST' => $POST
                   ];


                endforeach; 

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  

                //echo "Total geral: {$qtd}";
                ?>