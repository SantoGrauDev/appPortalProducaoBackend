<?php 

//Cabeçalho obrigatório
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");


//incluindo conexão
include_once 'conexao.php';

// QUERY ESTOQUE

			   
            
            $read = $conn->prepare(" SELECT NOME AS NOME, MATERIAL AS MATERIAL, ESPESSURA AS ESPESSURA, BLUE_UV AS BLUE,
                                            TRANSITIONS AS TRANSITIONS, CRIZAL_PREVENCIA_PRECO AS CRIZALPREVENCIA,
                                            CRIZAL_SAPPHIRE_HR_PRECO AS CRIZALSAPPHIRE, CRIZAL_ROCK_PRECO AS CRIZALROCK,
                                            CRIZAL_EASY_PRO_PRECO AS CRIZALEASY, OPTIFOG_PRECO AS OPTIFOG,
                                            NO_REFLEX_PRECO AS NOREFLEX, TRIO_EASY_CLEAN_PRECO AS TRIO,
                                            VERNIZ_HC_INC_PRECO AS VERNIZ,
                                            ESFERICO AS ESFERICO,
                                            CILINDRICO_MAXIMO AS CILINDRICO,
                                            MARCA AS MARCA , TIPO_LENTE AS TIPO
                                    FROM TB_KODAK_VS_SG ");

            $read->bindParam(':buscar', $buscar, PDO::PARAM_STR);
            $read->setFetchMode(PDO::FETCH_ASSOC);            
            $read->execute();   
            $array = $read->fetchAll();       

            $qtd = 0;

                foreach ($array as $dados): 
                                           
                   $nome = $dados["NOME"];
                   $material = $dados["MATERIAL"];
                   $espessura = $dados["ESPESSURA"];
                   $blue = $dados["BLUE"];
                   $transitions = $dados["TRANSITIONS"];
                   $crizalPrevencia = number_format($dados["CRIZALPREVENCIA"],2,",",".");
                   $crizalSapphire = number_format($dados["CRIZALSAPPHIRE"],2,",",".");
                   $crizalRock = number_format($dados["CRIZALROCK"],2,",",".");
                   $crizalEasy = number_format($dados["CRIZALEASY"],2,",",".");
                   $optifog = number_format($dados["OPTIFOG"],2,",",".");
                   $noReflex = number_format($dados["NOREFLEX"],2,",",".");
                   $trio = number_format($dados["TRIO"],2,",",".");
                   $verniz = number_format($dados["VERNIZ"],2,",",".");
                   $esferico = $dados["ESFERICO"];
                   $cilindrico = $dados["CILINDRICO"];
                   $marca = $dados["MARCA"];
                   $tipo = $dados["TIPO"];
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $lista_produtos["recordsKodakVs"][] = [
                     'NOME'=> $nome,
                     'MATERIAL'=> $material,
                     'ESPESSURA'=> $espessura,
                     'BLUE'=> $blue,
                     'TRANSITIONS'=> $transitions,
                     'CRIZALPREVENCIA'=> $crizalPrevencia,
                     'CRIZALSAPPHIRE'=> $crizalSapphire,
                     'CRIZALROCK'=> $crizalRock,
                     'CRIZALEASY'=> $crizalEasy,
                     'OPTIFOG'=> $optifog,
                     'NOREFLEX'=> $noReflex,
                     'TRIO'=> $trio,
                     'VERNIZ'=> $verniz,
                     'ESFERICO'=> $esferico,
                     'CILINDRICO'=> $cilindrico,
                     'MARCA'=> $marca,
                     'TIPO'=> $tipo

                     
                   ];


                endforeach; 

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  

                //echo "Total geral: {$qtd}";
                ?>