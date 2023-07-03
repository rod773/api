<?php


class Auth
{
    private $user_id;

    public function __construct(
        private UserGateway $user_gateway,
        private JWTcodec $codec
    ) {
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

        try {
            $data = $this->codec->decode($matches[1]);
        } catch (InvalidSignatureException) {

            http_response_code(401);
            echo json_encode([
                "message" => "Invalid Signature"
            ]);
            return false;
        } catch (Exception $e) {

            http_response_code(400);

            echo json_encode([
                "message" => $e->getMessage()
            ]);
            return false;
        }

        $this->user_id = $data['sub'];

        return true;
    }
}
