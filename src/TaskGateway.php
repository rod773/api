<?php


class TaskGateway
{
    private  $conn;

    public function __construct(private $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(){
        
        $sql = "select * from task order by name";

        $data = [];

        $stmt = $this->conn->query($sql);

       while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['is_completed'] = (bool)$row['is_completed'];
        $data[] = $row;
       }

       return $data;
    }

    public function get($id){

        $sql = "select * from task where id= :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id",$id,PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($data){
            $data['is_completed'] = (bool)$data['is_completed'];
            
        }

        

        return $data;
    }
}