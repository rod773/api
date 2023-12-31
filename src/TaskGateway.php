<?php


class TaskGateway
{
    private  $conn;

    public function __construct(private $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAllforUser($user_id)
    {

        $sql = "select * from task where user_id = :user_id order by name";

        $data = [];

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['is_completed'] = (bool)$row['is_completed'];
            $data[] = $row;
        }

        return $data;
    }

    public function getForUser($user_id, $id)
    {

        $sql = "select * from task where id= :id and user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $data['is_completed'] = (bool)$data['is_completed'];
        }



        return $data;
    }


    public function createForUser($user_id, $data)
    {

        $sql = "insert into task (name, priority, is_completed, user_id) 
        values (:name , :priority, :is_completed, :user_id)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":name", $data['name'], PDO::PARAM_STR);

        if (empty($data['priority'])) {
            $stmt->bindValue(":priority", null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(":priority", $data['priority'], PDO::PARAM_INT);
        }

        $stmt->bindValue(":is_completed", $data['is_completed'] ?? false, PDO::PARAM_BOOL);

        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);


        $stmt->execute();

        return $this->conn->lastInsertId();
    }


    public function updateForUser($user_id, $id, $data)
    {

        $fields = [];

        if (!empty($data['name'])) {

            $fields['name'] = [
                $data['name'],
                PDO::PARAM_STR
            ];
        }


        if (array_key_exists('priority', $data)) {

            $fields['priority'] = [
                $data['priority'],
                $data['priority'] === null ? PDO::PARAM_NULL : PDO::PARAM_INT
            ];
        }



        if (array_key_exists('is_completed', $data)) {

            $fields['is_completed'] = [
                $data['is_completed'],
                PDO::PARAM_BOOL
            ];
        }

        if (empty($fields)) {

            return 0;
        } else {
            $sets = array_map(function ($value) {
                return "$value = :$value";
            }, array_keys($fields));

            $sql = "update task set " . implode(', ', $sets) .
                " where id = :id and user_id = :user_id";


            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);


            foreach ($fields as $name => $values) {

                $stmt->bindValue(":$name", $values[0], $values[1]);
            }

            $stmt->execute();

            return $stmt->rowCount();
        }
    }


    public function deleteForUser($user_id, $id)
    {

        $sql = "delete from task where id = :id and user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
