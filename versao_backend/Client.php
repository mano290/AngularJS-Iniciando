<?php

class Client
{
    private function connect()
    {
        $conn = new PDO("mysql:host=localhost;dbname=test_angular;charset=utf8", "root", "");
        return $conn;
    }

    public function allClients()
    {
        $db = $this->connect();
        $qery = "SELECT * FROM client ORDER BY id DESC";
        $stmt = $db->prepare($qery);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addClient($client)
    {
        $db = $this->connect();
        $query = "INSERT INTO client (name, tel, address) VALUES (:name, :tel, :address)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(":name", $this->special_ucwords($client->name));
        $stmt->bindValue(":tel", $client->tel);
        $stmt->bindValue(":address", $this->special_ucwords($client->address));
        $stmt->execute();
        return $db->lastInsertId();
    }

    public function editClient($client)
    {
        $db = $this->connect();
        $query = "UPDATE client SET name = :name, tel = :tel, address = :address WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id", $client->id);
        $stmt->bindValue(":name", $this->special_ucwords($client->name));
        $stmt->bindValue(":tel", $client->tel);
        $stmt->bindValue(":address", $this->special_ucwords($client->address));
        return $stmt->execute();
    }

    public function deleteClient($idClient)
    {
        $db = $this->connect();
        $qery = "DELETE FROM client WHERE id = :id";
        $stmt = $db->prepare($qery);
        $stmt->bindValue(":id", $idClient);
        return $stmt->execute();
    }

    public function findClient($id)
    {
        $db = $this->connect();
        $query = "SELECT * FROM client WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function special_ucwords($string)
    {
        $words = explode(' ', strtolower(trim(preg_replace("/\s+/", ' ', $string))));
        $return[] = ucfirst($words[0]);

        unset($words[0]);

        foreach ($words as $word)
        {
            if (!preg_match("/^([dn]?[aeiou][s]?|em)$/i", $word))
            {
                $word = ucfirst($word);
            }
            $return[] = $word;
        }

        return implode(' ', $return);
    }
}