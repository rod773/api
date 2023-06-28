<?php



class Database
{
    public function __construct(
        private $host,
        private $name,
        private $user,
        private $password
    ) {
    }


    public function getConnection()
    {

        // $conn = new PDO(
        //     "mysql:host=$this->host;dbname=$this->name",
        //     $this->user, $this->password
        // );

        // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        //************************ */

        // $conn = new mysqli($this->host, $this->user, $this->password);

        // // Check connection
        // if ($conn->connect_error) {
        //     die("Connection failed: " . $conn->connect_error);
        // }
        // echo "Connected successfully\n";


        //******************************* */

        try {
            $conn = new PDO(
                "mysql:host=$this->host;dbname=$this->name",
                $this->user, $this->password
            );
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $conn->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
            //echo "Connected successfully\n";
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage() . "\n";
        }



    }

}