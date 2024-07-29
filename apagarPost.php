<?php

//Cabecalhos obrigatorios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");

//Incluir a conexao
include_once 'conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

$response = "";

$query_produto = "DELETE FROM TB_POST_PAGE WHERE ID_POST LIKE :id ";
$delete_produto = $conn->prepare($query_produto);
$delete_produto->bindParam(':id', $id, PDO::PARAM_INT);

if($delete_produto->execute()){
    $response = [
        "erro" => false,
        "mensagem" => "Post apagado com sucesso!"
    ];
}else{
    $response = [
        "erro" => true,
        "mensagem" => "Oops, alguma coisa deu errado ): "
    ];
}

http_response_code(200);
echo json_encode($response);
