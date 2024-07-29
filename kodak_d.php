<?php 

//Cabeçalho obrigatório
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");


//incluindo conexão
include_once 'conexao.php';

// QUERY ESTOQUE

			   
            
           $read = $conn->prepare(" SELECT NOME AS NOMED, MATERIAL AS MATERIALD, ESPESSURA AS ESPESSURAD, BLUE_UV AS BLUED,
                                                   TRANSITIONS AS TRANSITIONSD, CRIZAL_PREVENCIA_PRECO AS CRIZALPREVENCIAD,
                                                   CRIZAL_SAPPHIRE_HR_PRECO AS CRIZALSAPPHIRED, CRIZAL_ROCK_PRECO AS CRIZALROCKD,
                                                   CRIZAL_EASY_PRO_PRECO AS CRIZALEASYD, OPTIFOG_PRECO AS OPTIFOGD,
                                                   NO_REFLEX_PRECO AS NOREFLEXD, TRIO_EASY_CLEAN_PRECO AS TRIOD,
                                                   VERNIZ_HC_INC_PRECO AS VERNIZD,
                                                   ESFERICO AS ESFERICOD,
                                                   CILINDRICO_MAXIMO AS CILINDRICOD, ADICAO AS ADICAOD, ALTURA_MINIMA AS ALTURAMIND,
                                                   MARCA AS MARCAD , TIPO_LENTE AS TIPOD
                                                   FROM TB_KODAK_D_SG  ");

            $read->bindParam(':buscar', $buscar, PDO::PARAM_STR);
            $read->setFetchMode(PDO::FETCH_ASSOC);            
            $read->execute();   
            $array = $read->fetchAll();       

            $qtd = 0;

                foreach ($array as $dados): 
                                           
                   $nomed = $dados["NOMED"];
                   $materiald = $dados["MATERIALD"];
                   $espessurad = $dados["ESPESSURAD"];
                   $blued = $dados["BLUED"];
                   $transitionsd = $dados["TRANSITIONSD"];
                   $crizalPrevenciad = number_format($dados["CRIZALPREVENCIAD"],2,",",".");
                   $crizalSapphired = number_format($dados["CRIZALSAPPHIRED"],2,",",".");
                   $crizalRockd = number_format($dados["CRIZALROCKD"],2,",",".");
                   $crizalEasyd = number_format($dados["CRIZALEASYD"],2,",",".");
                   $optifogd = number_format($dados["OPTIFOGD"],2,",",".");
                   $noReflexd = number_format($dados["NOREFLEXD"],2,",",".");
                   $triod = number_format($dados["TRIOD"],2,",",".");
                   $vernizd = number_format($dados["VERNIZD"],2,",",".");
                   $esfericod = $dados["ESFERICOD"];
                   $cilindricod = $dados["CILINDRICOD"];
                   $adicaod = $dados["ADICAOD"];
                   $alturaMind = $dados["ALTURAMIND"];
                   $marcad = $dados["MARCAD"];
                   $tipod = $dados["TIPOD"];
                   ?> 

                    
                   <?php 
                   extract($dados);

                   $lista_produtos["recordsKodakD"][] = [
                     'NOMED'=> $nomed,
                     'MATERIALD'=> $materiald,
                     'ESPESSURAD'=> $espessurad,
                     'BLUED'=> $blued,
                     'TRANSITIONSD'=> $transitionsd,
                     'CRIZALPREVENCIAD'=> $crizalPrevenciad,
                     'CRIZALSAPPHIRED'=> $crizalSapphired,
                     'CRIZALROCKD'=> $crizalRockd,
                     'CRIZALEASYD'=> $crizalEasyd,
                     'OPTIFOGD'=> $optifogd,
                     'NOREFLEXD'=> $noReflexd,
                     'TRIOD'=> $triod,
                     'VERNIZD'=> $vernizd,
                     'ESFERICOD'=> $esfericod,
                     'CILINDRICOD'=> $cilindricod,
                     'ADICAOD'=> $adicaod,
                     'ALTURAMIND'=> $alturaMind,
                     'MARCAD'=> $marcad,
                     'TIPOD'=> $tipod

                     
                   ];


                endforeach; 

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  

                //echo "Total geral: {$qtd}";
                ?>