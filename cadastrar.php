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

if ($dados) {

    
    $query_produto = "INSERT INTO TB_POST_PAGE (TITULO_POST, AUTOR_POST, POST_POST, DATA_POST) VALUES (:TITULO_POST, :AUTOR_POST, :POST_POST,CURRENT_TIMESTAMP)";
    $cad_produto = $conn->prepare($query_produto);

    $cad_produto->bindParam(':TITULO_POST', $dados['produto']['TITULO_POST'], PDO::PARAM_STR);
    $cad_produto->bindParam(':AUTOR_POST', $dados['produto']['AUTOR_POST'], PDO::PARAM_STR);
    $cad_produto->bindParam(':POST_POST', $dados['produto']['POST_POST'], PDO::PARAM_STR);
    


    $cad_produto->execute();

    if ($cad_produto->rowCount()) {
        $response = [
            "erro" => false,
            "mensagem" => "Post enviado com sucesso!"
        ];
    } else {
        $response = [
            "erro" => true,
            "mensagem" => "Ops! Alguma coisa deu errado =("
        ];
    }
} else {
    $response = [
        "erro" => true,
        "mensagem" => "Ops! Alguma coisa deu errado =("
    ];
}

http_response_code(200);


echo json_encode($response);