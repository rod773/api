<?php


class Auth
{
    private $user_id;

    public function __construct(private UserGateway $user_gateway)
    {
    }

    public function authenticateAPIKey()
    {

        if (empty($_SERVER['HTTP_X_API_KEY'])) {

            http_response_code(400);

            echo json_encode([
                "message" => "Api Key is missing"
            ]);

            return false;
        }

        $api_key = $_SERVER['HTTP_X_API_KEY'];


        $user = $this->user_gateway->getByAPIKey($api_key);


        if ($user === false) {

            http_response_code(401);
            echo json_encode([
                "message" => "Invalid Api Key"
            ]);
            return false;
        };

        $this->user_id = $user['id'];
        return true;
    }


    public function getUserId()
    {
        return $this->user_id;
    }

    public function autheticateAccessToken(): bool
    {
        if (!preg_match("/^Bearer\s+(.*)$/", $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            http_response_code(404);
            echo json_encode([
                "message" => "incomplete authorization header"
            ]);
            return false;
        }

        $plain_text = base64_decode($matches[1], true);

        if ($plain_text === false) {
            http_response_code(404);
            echo json_encode([
                "message" => "invalid authorization header"
            ]);
            return false;
        }

        $data = json_decode($plain_text, true);

        if ($data === false) {
            http_response_code(404);
            json_encode([
                "message" => "invalis JSON"
            ]);
            return false;
        }

        return true;
    }
}
