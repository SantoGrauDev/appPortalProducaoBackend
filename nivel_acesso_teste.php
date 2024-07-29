<?php 

//Cabeçalho obrigatório
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");


//incluindo conexão
include_once 'conexao.php';

// QUERY ESTOQUE

               
            
            $read = $conn->prepare("SELECT EMAIL AS EMAIL, PESID AS PESID, VNDID AS VNDID, NOME AS NOME, NIVEL_ACESSO AS NIVEL, SUPERVISOR AS SUPERVISOR, UID AS UID
                                    FROM TB_NIVEL_ACESSO ");

            $read->setFetchMode(PDO::FETCH_ASSOC);            
            $read->execute();   
            $array = $read->fetchAll();       

            $qtd = 0;

                foreach ($array as $dados): 
           
                   $EMAIL = $dados["EMAIL"];
                   $PESID = $dados["PESID"];
                   $VNDID = $dados["VNDID"]; 
                   $NOME = $dados["NOME"];  
                   $NIVEL = $dados["NIVEL"]; 
                   $SUPERVISOR = $dados["SUPERVISOR"]; 
                   $UID = $dados["UID"]; 
                
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $lista_produtos["records"][] = [
                     'EMAIL'=> $EMAIL, 
                     'PESID'=> $PESID, 
                     'VNDID' => $VNDID,
                     'NOME' => $NOME,
                     'NIVEL' => $NIVEL,
                     'SUPERVISOR' => $SUPERVISOR,
                     'UID' => $UID
                   ];


                endforeach; 

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  

                //echo "Total geral: {$qtd}";
                ?>