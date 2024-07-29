<?php 

//Cabeçalho obrigatório
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");


//incluindo conexão
include_once 'conexao.php';

// QUERY ESTOQUE

			   
            
            $read = $conn->prepare("SELECT e.NOME_APELIDO as APELIDO, SUM(a.MECQUANTIDADE1) AS QTD, e.COR AS COR, e.MATFANTASIA AS MATFAN, f.MPVPRECOVENDA AS PRECO, e.ARMDESCRICAO AS MODELO, e.SUB_LINHA1 AS SUBLINHA1  "
                                    . "FROM TB_MEC_MATESTCONTROLE a "
                                    . "INNER JOIN TB_MAT_MATERIAL b ON b.MATID = a.MATID "
                                    . "INNER JOIN TB_FIL_FILIAL c ON c.FILID = a.FILID "
                                    . "INNER JOIN TB_PES_PESSOA d ON d.PESID = c.PESID "
                                    . "INNER JOIN TB_DRIP_APELIDO e ON e.MATFANTASIA = b.MATFANTASIA "
                                    . "INNER JOIN TB_MPV_MATPRECOVENDA f ON f.MATID = b.MATID "
                                    . "WHERE a.MECDATALOTE  >= '2018-01-01' AND a.MECDATALOTE <=  '2024-12-31' "
                                    . "AND e.GRIFE = 'SUNGLASS'
                                       AND a.MECQUANTIDADE1 > '0'
                                       AND c.PESID = '31823'
                                       AND f.FILID = '1'
                                       AND NOT b.NCMID = '56' 
                                       AND NOT b.NCMID = '65'
                                       AND NOT b.NCMID = '66'
                                       AND NOT b.NCMID = '68'
                                       AND NOT b.NCMID = '71'
                                       AND NOT b.NCMID = '75'"
                                    . "GROUP BY e.NOME_APELIDO, e.COR, e.MATFANTASIA, f.MPVPRECOVENDA, e.ARMDESCRICAO, e.SUB_LINHA1 " 
                                    . "ORDER BY QTD desc ");

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
                     'SUBLINHA1' => $sublinha1,
                     'QTD' => $qtd,
                     'COR' => $cor,
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