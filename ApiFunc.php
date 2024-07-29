<?php 

//Cabeçalho obrigatório
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");


//incluindo conexão
include_once 'conexao.php';

// QUERY ESTOQUE

			   
            
            $read = $conn->prepare("SELECT NOME AS NOME, SOBRENOME AS SOBRENOME, SUPERVISOR AS SUPERVISOR, EMAIL AS EMAIL "
                                    . "FROM TB_USUARIOS_PORTAL");

            $read->setFetchMode(PDO::FETCH_ASSOC);            
            $read->execute();   
            $array = $read->fetchAll();       

            $qtd = 0;

                foreach ($array as $dados): 
                                           
                   $NOME = $dados["NOME"];
                   $SOBRENOME = $dados["SOBRENOME"]; 
                   $SUPERVISOR = $dados["SUPERVISOR"]; 
                   $EMAIL = $dados["EMAIL"];
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $lista_produtos["records"][] = [
                     'NOME'=> $NOME, 
                     'SOBRENOME' => $SOBRENOME,
                     'SUPERVISOR' => $SUPERVISOR,
                     'EMAIL' => $EMAIL
                   ];


                endforeach; 

                

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  

                //echo "Total geral: {$qtd}";
                ?>