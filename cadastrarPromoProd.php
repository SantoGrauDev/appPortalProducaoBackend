<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");
//header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE");

//Incluir a conexao
include_once 'conexao.php';

$response_json = file_get_contents("php://input");
$dados = json_decode($response_json, true);

if($dados){

    $query_produto = "INSERT INTO TB_CADPROMO_PROD (MATFANTASIA, DATA_INICIO, DATA_TERMINO, VALOR_PROMO, STATUS_CAMPANHA, REGRAS) VALUES (:MATFANTASIA, current_timestamp, :DATA_TERMINO, :VALOR_PROMO, 'ATIVO', :REGRAS  )";
    $cad_produto = $conn->prepare($query_produto);

    $cad_produto->bindParam(':MATFANTASIA', $dados['produto']['MATFANTASIA'], PDO::PARAM_STR);
    $cad_produto->bindParam(':DATA_TERMINO', $dados['produto']['DATA_TERMINO'], PDO::PARAM_STR);
    $cad_produto->bindParam(':VALOR_PROMO', $dados['produto']['VALOR_PROMO'], PDO::PARAM_STR);
    $cad_produto->bindParam(':REGRAS', $dados['produto']['REGRAS'], PDO::PARAM_STR);

    $cad_produto->execute();

    if($cad_produto->rowCount()){
        $response = [
            "erro" => false,
            "mensagem" => "Promo enviado com sucesso!"
        ];
    }else{
        $response = [
            "erro" => true,
            "mensagem" => "Ops! Alguma coisa deu errado =("
        ];
    }
    
    
}else{
    $response = [
        "erro" => true,
        "mensagem" => "Ops! Alguma coisa deu errado =("
    ];
}

http_response_code(200);
echo json_encode($response);