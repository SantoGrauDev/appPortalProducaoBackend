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

    $query_produto = "INSERT INTO TB_CADPROMO_ACOES (NOME_CAMPANHA, TIPO_CAMPANHA, REGRAS_CAMPANHA, DATA_INICIO, DATA_TERMINO, MIDIA, LINK_MIDIA, STATUS_CAMPANHA) VALUES (:NOME_CAMPANHA,:TIPO_CAMPANHA, :REGRAS_CAMPANHA,current_timestamp,:DATA_TERMINO, :MIDIA, :LINK_MIDIA, 'ATIVO' )";
    $cad_produto = $conn->prepare($query_produto);

    $cad_produto->bindParam(':NOME_CAMPANHA', $dados['produto']['NOME_CAMPANHA'], PDO::PARAM_STR);
    $cad_produto->bindParam(':TIPO_CAMPANHA', $dados['produto']['TIPO_CAMPANHA'], PDO::PARAM_STR);
    $cad_produto->bindParam(':REGRAS_CAMPANHA', $dados['produto']['REGRAS_CAMPANHA'], PDO::PARAM_STR);
    $cad_produto->bindParam(':DATA_TERMINO', $dados['produto']['DATA_TERMINO'], PDO::PARAM_STR);
    $cad_produto->bindParam(':MIDIA', $dados['produto']['MIDIA'], PDO::PARAM_STR);
    $cad_produto->bindParam(':LINK_MIDIA', $dados['produto']['LINK_MIDIA'], PDO::PARAM_STR);

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