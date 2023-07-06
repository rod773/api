<?php

class RefreshTokenGateway
{

    private PDO $conn;
    private string $key;

    public function __construct(Database $database, string $key)
    {
        $this->conn = $database->getConnection();

        $this->key = $key;
    }

    public function create($token, $expiry): bool
    {
        $hash = hash_hmac("sha256", $token, $this->key);

        $sql = "insert into refresh_token (token_hash, expires_at)" .
            "values ( :token_hash, :expires_at)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":token_hash", $hash, PDO::PARAM_STR);

        $stmt->bindValue(":expires_at", $expiry, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($token): int
    {

        $hash = hash_hmac("sha256", $token, $this->key);

        $sql = "delete from refresh_token where token_hash = :token_hash";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":token_hash", $hash, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
