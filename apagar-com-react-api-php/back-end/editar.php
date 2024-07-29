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
    $query_produto = "UPDATE produtos SET titulo=:titulo, descricao=:descricao WHERE id=:id";
    $edit_produto = $conn->prepare($query_produto);

    $edit_produto->bindParam(':titulo', $dados['titulo'], PDO::PARAM_STR);
    $edit_produto->bindParam(':descricao', $dados['descricao'], PDO::PARAM_STR);
    $edit_produto->bindParam(':id', $dados['id'], PDO::PARAM_INT);

    $edit_produto->execute();

    if($edit_produto->rowCount()){
        $response = [
            "erro" => false,
            "mensagem" => "Produto editado com sucesso!"
        ];
    }else{
        $response = [
            "erro" => false,
            "mensagem" => "Produto não editado com sucesso!"
        ];
    }
}else{
    $response = [
        "erro" => false,
        "mensagem" => "Produto não editado com sucesso!"
    ];
}

http_response_code(200);
echo json_encode($response);