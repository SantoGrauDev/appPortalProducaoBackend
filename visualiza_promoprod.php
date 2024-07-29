<?php 

//Cabeçalho obrigatório
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");


//incluindo conexão
include_once 'conexao.php';

// QUERY ESTOQUE

			   
            
            $read = $conn->prepare("SELECT e.MATFANTASIA AS MATFANTASIA, e.NOME_APELIDO as NOME, e.COR AS COR, f.DATA_INICIO AS DATA_INICIO, f.DATA_TERMINO AS DATA_TERMINO, f.VALOR_PROMO AS VALOR_PROMO, sum(a.MECQUANTIDADE1) AS QTD, f.ID_PROMO_PROD AS IDPROMOPROD, f.REGRAS AS REGRAS
                FROM TB_MEC_MATESTCONTROLE a 
                INNER JOIN TB_MAT_MATERIAL b ON b.MATID = a.MATID 
                INNER JOIN TB_FIL_FILIAL c ON c.FILID = a.FILID 
                INNER JOIN TB_PES_PESSOA d ON d.PESID = c.PESID 
                INNER JOIN TB_DRIP_APELIDO e ON e.MATFANTASIA = b.MATFANTASIA 
                INNER JOIN TB_CADPROMO_PROD f ON f.MATFANTASIA = e.MATFANTASIA
                WHERE STATUS_CAMPANHA = 'ATIVO' 
                AND a.MECQUANTIDADE1 > '0'
                AND c.PESID = '31823'
                AND NOT b.NCMID = '56' 
                AND NOT b.NCMID = '65'
                AND NOT b.NCMID = '66'
                AND NOT b.NCMID = '68'
                AND NOT b.NCMID = '71'
                AND NOT b.NCMID = '75'
                GROUP BY e.NOME_APELIDO, e.COR, e.MATFANTASIA, e.ARMDESCRICAO, e.SUB_LINHA1 ,f.DATA_INICIO, f.DATA_TERMINO, f.VALOR_PROMO,f.ID_PROMO_PROD, f.REGRAS
                ORDER BY f.DATA_INICIO DESC");

            $read->setFetchMode(PDO::FETCH_ASSOC);            
            $read->execute();   
            $array = $read->fetchAll();       

                foreach ($array as $dados): 

                   $matfantasia = $dados["MATFANTASIA"]; 
                   $regras = $dados["REGRAS"]; 
                   $idpromoprod = $dados["IDPROMOPROD"]; 
                   $nome_apelido = $dados["NOME"]; 
                   $cor = $dados["COR"]; 
                   $data_inicio = date("d/m/Y",strtotime($dados["DATA_INICIO"])); 
                   $data_termino = $dados["DATA_TERMINO"]; 
                   $valor_promo = $dados["VALOR_PROMO"];
                   $qtd = number_format($dados["QTD"],0,",",".");
                   $imagem = "{$dados["MATFANTASIA"]}.jpg";


                   ?> 

                            <?php 
                            
                   extract($dados);

                   $lista_produtos["records"][] = [
                     'MATFANTASIA' => $matfantasia,
                     'REGRAS' => $regras,
                     'IDPROMOPROD' => $idpromoprod,
                     'NOME' => $nome_apelido,
                     'DATA_INICIO' => $data_inicio,
                     'data_termino' => $data_termino,
                     'valor_promo' => $valor_promo,
                     'COR' => $cor,
                     'IMAGEM'=> $imagem, 
                     'QTD' => $qtd
                   ];


                endforeach; 

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  

                //echo "Total geral: {$qtd}";
                ?>
           