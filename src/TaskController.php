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

                $data = (array)json_decode(file_get_contents('php://input'), true);

                $errors = $this->getValidationErrors($data);

                if (!empty($errors)) {
                    $this->respondUnprocessableEntity($errors);
                    return;
                }

                $id = $this->gateway->create($data);

                $this->respondCreated($id);
            } else {
                $this->respondMethodNotAllowed("GET,POST");
            }
        } else {

            $task = $this->gateway->get($id);

            if ($task === false) {
                $this->respondNotFound($id);
                return;
            }

            switch ($method) {

                case "GET":
                    echo
                    json_encode($task);
                    break;

                case "PATCH":

                    $data = (array)json_decode(file_get_contents('php://input'), true);

                    $errors = $this->getValidationErrors($data, false);

                    if (!empty($errors)) {
                        $this->respondUnprocessableEntity($errors);
                        return;
                    }

                    $this->gateway->update($id, $data);

                    break;

                case "DELETE":
                    echo "delete $id";
                    break;

                default:
                    $this->respondMethodNotAllowed("GET, PATCH, DELETE");
            }
        }
    }

    private function respondUnprocessableEntity($errors)
    {
        http_response_code(422);
        echo json_encode([
            "errors" => $errors
        ]);
    }

    private function respondMethodNotAllowed($allowed_methods)
    {
        http_response_code(405);
        header("Allow: $allowed_methods");
    }


    private function respondNotFound($id)
    {
        http_response_code((404));

        echo json_encode([
            "message" => "task with id $id not found"
        ]);
    }

    private function respondCreated($id)
    {

        http_response_code(201);
        echo json_encode([
            "message" => "task created, id => $id"
        ]);
    }

    private function getValidationErrors($data, $is_new = true)
    {

        $errors = [];

        if ($is_new && empty($data['name'])) {

            $errors[] = "name is required";
        }


        if (!empty($data['priority'])) {

            if (filter_var($data['priority'], FILTER_VALIDATE_INT) == FALSE) {
                $errors[] = "priority must be an integer";
            }
        }

        return $errors;
    }
}
