<?php

class UserGateway
{


    private  $conn;

    public function __construct(private $database)
    {
        $this->conn = $database->getConnection();
    }


    public function getByAPIKey($key): array | false
    {


        $sql = "select * from user where api_key = :api_key";


        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":api_key", $key, PDO::PARAM_STR);


        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getByUserName($username): array | false
    {

        $sql = "select * from user where username = :username";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":username", $username, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
