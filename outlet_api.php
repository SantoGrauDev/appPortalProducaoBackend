<?php 

//Cabeçalho obrigatório
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");


//incluindo conexão
include_once 'conexao.php';

// QUERY ESTOQUE

			   
            
            $read = $conn->prepare("SELECT e.NOME_APELIDO AS APELIDO, f.MPVPRECOVENDA AS PRECO, SUM(a.MECQUANTIDADE1) AS QTD, e.COR AS COR, e.MATFANTASIA AS MATFAN, e.ARMDESCRICAO AS MODELO, e.SUB_LINHA1 AS SUBLINHA1, e.SUB_LINHA2 AS GENERO
                  FROM TB_MEC_MATESTCONTROLE a 
                  INNER JOIN TB_MAT_MATERIAL b ON b.MATID = a.MATID 
                  INNER JOIN TB_FIL_FILIAL c ON c.FILID = a.FILID 
                  INNER JOIN TB_PES_PESSOA d ON d.PESID = c.PESID
                  INNER JOIN TB_DRIP_APELIDO_OUTLET e ON e.MATFANTASIA = b.MATFANTASIA 
                  INNER JOIN TB_MPV_MATPRECOVENDA f ON f.MATID = b.MATID 
                  WHERE a.MECDATALOTE  >= '2010-01-01' AND a.MECDATALOTE <=  '2024-12-31' 
                  AND a.MECQUANTIDADE1 > '0'
                  GROUP BY e.NOME_APELIDO, a.MECQUANTIDADE1, e.COR, e.MATFANTASIA, e.ARMDESCRICAO,  e.SUB_LINHA1, e.SUB_LINHA2, f.MPVPRECOVENDA 
                  ORDER BY PRECO ASC");

            $read->bindParam(':buscar', $buscar, PDO::PARAM_STR);
            $read->setFetchMode(PDO::FETCH_ASSOC);            
            $read->execute();   
            $array = $read->fetchAll();       

            $qtd = 0;

                foreach ($array as $dados): 
                $qtd += $dados["QTD"];
                                           
                   $apelido = $dados["APELIDO"];
                   $modelo = $dados["MODELO"];
                   $cor = $dados["COR"]; 
                   $matfan = $dados["MATFAN"]; 
                   $sublinha1 = $dados["SUBLINHA1"]; 
                   $genero = $dados["GENERO"];
                   $qtd = number_format($dados["QTD"],0,",",".");
                   $preco = number_format($dados["PRECO"],2,",",".");
                   $imagem = "{$dados["MATFAN"]}.jpg";
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $lista_produtos["records"][] = [
                     'IMAGEM'=> $imagem, 
                     'MODELO'=> $modelo, 
                     'APELIDO' => $apelido,
                     'QTD' => $qtd,
                     'COR' => $cor,
                     'PRECO' => $preco,
                     'SUBLINHA1' => $sublinha1,
                     'GENERO' => $genero,
                     'MATFANTASIA' => $matfan
                   ];


                endforeach; 

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  

                //echo "Total geral: {$qtd}";
                ?>