<?php 

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


$mysqli = new mysqli("localhost","root", "", "reactphp");

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
 
 $allproduct= mysqli_query($mysqli, "SELECT OS AS OS, VENDEDOR AS VENDEDOR, pfile AS IMG, LENTE AS LENTE, TIPO_LENTE AS TIPO_LENTE, INDICE AS INDICE, VALOR AS VALOR, GRADE AS GRADE, DNPOD AS DNPOD, DNPOE AS DNPOE, DP AS DP, COMENTARIO AS COMENTARIO  FROM tbl_product");



    foreach ($allproduct as $dados): 
           
                   $OS = $dados["OS"];                
                   $VENDEDOR = $dados["VENDEDOR"];  
                   $IMG = $dados["IMG"]; 
                   $LENTE = $dados["LENTE"];    
                   $TIPO_LENTE = $dados["TIPO_LENTE"];  
                   $INDICE = $dados["INDICE"]; 
                   $VALOR = $dados["VALOR"]; 
                   $GRADE = $dados["GRADE"]; 
                   $DNPOE = $dados["DNPOE"]; 
                   $DNPOD = $dados["DNPOD"]; 
                   $DP = $dados["DP"]; 
                   $COMENTARIO = $dados["COMENTARIO"]; 

       
                   extract($dados);

                   $lista_produtos["records"][] = [

                     'OS' => $OS, 
                     'VENDEDOR' => $VENDEDOR,                     
                     'IMG' => $IMG,                  
                     'LENTE' => $LENTE,
                     'TIPO_LENTE' => $TIPO_LENTE, 
                     'INDICE' => $INDICE,                     
                     'VALOR' => $VALOR,                  
                     'GRADE' => $GRADE,
                     'DNPOE' => $DNPOE,                     
                     'DNPOD' => $DNPOD,                  
                     'DP' => $DP,               
                     'COMENTARIO' => $COMENTARIO,
                   ];


                endforeach; 

                //Reposta com status 200
                http_response_code(200);

                //Retornar as informações em json
                echo json_encode($lista_produtos);  


?>