<?php 

//Cabeçalho obrigatório
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");


//incluindo conexão
include_once 'conexao.php';

// QUERY ESTOQUE

			   
            
            $read = $conn->prepare(" SELECT e.NOME as NOME, e.MATFANTASIA as MATFAN, SUM(a.MECQUANTIDADE1) AS QTD, f.MPVPRECOVENDA as PRECO
               FROM TB_MEC_MATESTCONTROLE a 
               INNER JOIN TB_MAT_MATERIAL b ON b.MATID = a.MATID 
               INNER JOIN TB_FIL_FILIAL c ON c.FILID = a.FILID 
               INNER JOIN TB_PES_PESSOA d ON d.PESID = c.PESID 
               INNER JOIN TB_ACESSORIO e ON e.MATFANTASIA = b.MATFANTASIA 
               INNER JOIN TB_MPV_MATPRECOVENDA f ON f.MATID = b.MATID 
               WHERE a.MECDATALOTE  >= '2018-01-01' AND a.MECDATALOTE <=  '2024-12-31'
               AND b.NCMID = '56' 
               AND a.MECQUANTIDADE1 > '0'
               GROUP BY e.NOME, e.MATFANTASIA,f.MPVPRECOVENDA 
               ORDER BY QTD desc  ");

            $read->bindParam(':buscar', $buscar, PDO::PARAM_STR);
            $read->setFetchMode(PDO::FETCH_ASSOC);            
            $read->execute();   
            $array = $read->fetchAll();       

            $qtd = 0;

                foreach ($array as $dados): 
                $qtd += $dados["QTD"];
                                           
                   $nome = $dados["NOME"];
                   $matfan = $dados["MATFAN"]; 
                   $qtd = number_format($dados["QTD"],0,",",".");
                   $preco = number_format($dados["PRECO"],2,",",".");
                   $imagem = "{$dados["MATFAN"]}.jpg";
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $lista_produtos["records"][] = [
                     'IMAGEM'=> $imagem, 
                     'NOME' => $nome,
                     'QTD' => $qtd,
                     'PRECO' => $preco,
                     'MATFANTASIA' => $matfan
                   ];


                endforeach; 

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  

                //echo "Total geral: {$qtd}";
                ?>