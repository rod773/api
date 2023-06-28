<?php

class TaskController
{

    public function __construct(private TaskGateway $gateway)
    {
    }
    public function processRequest($method, $id)
    {


        if ($id == null) {

            if ($method == "GET") {
                
                echo json_encode($this->gateway->getAll());
            } else if ($method == "POST") {
                echo "create";
            } else {
                $this->respondMethodNotAllowed("GET,POST");
            }
        } else {

            $task = $this->gateway->get($id);

            if($task===false){
                $this->respondNotFound($id);
                return;
            }

            switch ($method) {

                case "GET":
                    echo 
                    json_encode($task);
                    break;

                case "PATCH":
                    echo "update $id";
                    break;

                case "DELETE":
                    echo "delete $id";
                    break;

                default:
                    $this->respondMethodNotAllowed("GET, PATCH, DELETE");
            }
        }
    }



    private function respondMethodNotAllowed($allowed_methods)
    {
        http_response_code(405);
        header("Allow: $allowed_methods");
    }


    private function respondNotFound($id){
        http_response_code((404));

        echo json_encode([
            "message"=>"task with id $id not found"
        ]);
    }
}