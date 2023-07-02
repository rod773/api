<?php



class Database
{
    private ?PDO $conn = null;

    public function __construct(
        private $host,
        private $name,
        private $user,
        private $password
    ) {
    }


    public function getConnection()
    {

        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host=$this->host;dbname=$this->name",
                    $this->user,
                    $this->password
                );
                // set the PDO error mode to exception
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $this->conn->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
                //echo "Connected successfully\n";

            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage() . "\n";
            }
        }

        return $this->conn;
    }
}
