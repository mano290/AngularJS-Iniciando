<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Content-type: application/json");

$post = json_decode(file_get_contents("php://input"));

function conn(){
    $conn = new PDO("mysql:host=localhost;dbname=test_angular;charset=utf8", "root", "");
    return $conn;
}

function listAll(){
    $db = conn();
    $qery = "SELECT * FROM client ORDER BY id DESC";
    $stmt = $db->prepare($qery);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function save($dados) {
    $db = conn();
    $query = "INSERT INTO client (name, tel, address) VALUES (:name, :tel, :address)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":name", $dados->name);
    $stmt->bindValue(":tel", $dados->tel);
    $stmt->bindValue(":address", $dados->address);
    $stmt->execute();
    return $db->lastInsertId();
}

function find($id) {
    $db = conn();
    $query = "SELECT * FROM client WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function update($dados) {
    $db = conn();
    $query = "UPDATE client SET name = :name, tel = :tel, address = :address WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $dados->id);
    $stmt->bindValue(":name", $dados->name);
    $stmt->bindValue(":tel", $dados->tel);
    $stmt->bindValue(":address", $dados->address);
    return $stmt->execute();
}

function delete($id) {
    $db = conn();
    $qery = "DELETE FROM client WHERE id = :id";
    $stmt = $db->prepare($qery);
    $stmt->bindValue(":id", $id);
    return $stmt->execute();
}

if($post) {

    if($post->delete) {
        $rest = delete($post->id);
        if($rest) {
            $data['status'] = true;
            $data['msg'] = "Success!";
            echo json_encode($data);
            exit;
        } else {
            $post['status'] = false;
            $post['msg'] = "Error!";
            echo json_encode($post);
            exit;
        }
    }

    if($post->id) {
        $rest = update($post);
        if($rest) {
            $data['status'] = true;
            $data['msg'] = "Success! ID: $post->id";
            $data['client'] = find($post->id);
            echo json_encode($data);
            exit;
        } else {
            $post['status'] = false;
            $post['msg'] = "Error!";
            echo json_encode($post);
            exit;
        }
    }

    $id = save($post);

    if($id) {
        $data['status'] = true;
        $data['msg'] = "Success! ID: $id";
        $data['client'] = find($id);
        echo json_encode($data);
        exit;
    } else {
        $post['status'] = false;
        $post['msg'] = "Error!";
        echo json_encode($post);
        exit;
    }
}

$clients = listAll();

echo json_encode($clients);
