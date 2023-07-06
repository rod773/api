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
        $hash = hash("sha256", $token, $this->key);

        $sql = "insert into refresh_token (token_hash, expires_at)" .
            "values ( :token_hash, :expires_at)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":token_hash", $hash, PDO::PARAM_STR);

        $stmt->bindValue(":expires_at", $expiry, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
