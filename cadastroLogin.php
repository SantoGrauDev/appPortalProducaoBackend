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

    $query_produto = "INSERT INTO TB_USUARIOS_PORTAL (NOME, SOBRENOME, SUPERVISOR) VALUES (:NOME, :SOBRENOME, :SUPERVISOR )";
    $cad_produto = $conn->prepare($query_produto);

    $cad_produto->bindParam(':NOME', $dados['produto']['NOME'], PDO::PARAM_STR);
    $cad_produto->bindParam(':SOBRENOME', $dados['produto']['SOBRENOME'], PDO::PARAM_STR);
    $cad_produto->bindParam(':SUPERVISOR', $dados['produto']['SUPERVISOR'], PDO::PARAM_STR);

    $cad_produto->execute();

    if($cad_produto->rowCount()){
        $response = [
            "erro" => false,
            "mensagem" => "Post enviado com sucesso!"
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