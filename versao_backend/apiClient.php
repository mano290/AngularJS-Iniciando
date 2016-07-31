<?php

require_once "Client.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Content-type: application/json");

// Inicialização das variaveis
$client = new Client();
$response = [];
$postData = json_decode(file_get_contents("php://input"));

// Lista todos os clientes
if($_SERVER['REQUEST_METHOD'] === "GET") {
    echo json_encode($client->allClients());
    exit;
}

// Adiciona um novo cliente
if($_SERVER['REQUEST_METHOD'] === "POST") {
    $newClient = $client->addClient($postData);
    // Verifica o retorno do método que cadastra novo cliente
    if($newClient) {
        $response['status'] = true;
        $response['msg'] = "Success! Cliente cadastrado com sucesso! ID: $newClient";
        $response['client'] = $client->findClient($newClient);
    } else {
        $response['status'] = false;
        $response['msg'] = "Error! Não foi possível cadastrar novo cliente";
    }

    echo json_encode($response);
    exit;
}

// Edita um cliente
if($_SERVER['REQUEST_METHOD'] === "PUT") {
    // Verifica o retorno do método que atualiza o cliente
    if($client->editClient($postData)) {
        $response['status'] = true;
        $response['msg'] = "Success! Cliente atualizado com sucesso! ID: $postData->id";
        $response['client'] = $client->findClient($postData->id);
    } else {
        $response['status'] = false;
        $response['msg'] = "Error! Não foi atualizar este cliente";
    }

    echo json_encode($response);
    exit;
}

// Delete um cliente
if($_SERVER['REQUEST_METHOD'] === "DELETE") {
    $idClient = filter_input(INPUT_GET, 'clientId', FILTER_SANITIZE_NUMBER_INT);
    // Verifica o retorno do método que deleta o cliente
    if($client->deleteClient($idClient)) {
        $response['status'] = true;
        $response['msg'] = "Success! Cliente excluido com sucesso!";
    } else {
        $response['status'] = false;
        $response['msg'] = "Error! Não foi excluir este cliente";
    }

    echo json_encode($response);
    exit;
}
