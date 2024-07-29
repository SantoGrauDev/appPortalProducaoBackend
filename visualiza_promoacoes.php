<?php 

//Cabeçalho obrigatório
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");


//incluindo conexão
include_once 'conexao.php';      

// QUERY ESTOQUE

			   
            
            $read = $conn->prepare("SELECT ID_PROMO_ACOES AS IDPROMOACOES, NOME_CAMPANHA AS NOME_CAMPANHA, TIPO_CAMPANHA AS TIPO_CAMPANHA, REGRAS_CAMPANHA AS REGRAS_CAMPANHA, DATA_INICIO AS DATA_INICIO, DATA_TERMINO AS DATA_TERMINO, MIDIA AS MIDIA, LINK_MIDIA AS LINKMIDIA            
                FROM TB_CADPROMO_ACOES
                WHERE STATUS_CAMPANHA = 'ATIVO'
                ORDER BY DATA_INICIO DESC");

            $read->setFetchMode(PDO::FETCH_ASSOC);            
            $read->execute();   
            $array = $read->fetchAll();       

                foreach ($array as $dados): 

                   $idpromoacoes = $dados["IDPROMOACOES"];
                   $idpromoacoesimg = "{$dados["IDPROMOACOES"]}.jpg"; 
                   $nome_campanha = $dados["NOME_CAMPANHA"]; 
                   $tipo_campanha = $dados["TIPO_CAMPANHA"]; 
                   $regras_campanha = $dados["REGRAS_CAMPANHA"]; 
                   $data_inicio = date("d/m/Y",strtotime($dados["DATA_INICIO"])); 
                   $data_termino = $dados["DATA_TERMINO"]; 
                   $midia = $dados["MIDIA"]; 
                   $linkmidia = $dados["LINKMIDIA"]; 
                   ?> 

                   <?php 
                   extract($dados);

                   $lista_produtos["records"][] = [
                     'IDPROMOACOES' => $idpromoacoes,                     
                     'IDPROMOACOESIMG' => $idpromoacoesimg,
                     'MIDIA' => $midia,
                     'NOME_CAMPANHA' => $nome_campanha,
                     'TIPO_CAMPANHA' => $tipo_campanha,
                     'DATA_INICIO' => $data_inicio,
                     'DATA_TERMINO' => $data_termino,
                     'REGRAS_CAMPANHA' => $regras_campanha,
                     'LINKMIDIA' => $linkmidia
                   ];

                endforeach; 


                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  

                //echo "Total geral: {$qtd}";
                ?>