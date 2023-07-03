<?php

class JWTcodec
{

    public function __construct(private string $key)
    {
    }

    public function encode($payload)
    {
        $header = json_encode([
            "typ" => "JWT",
            "alg" => "HS256"
        ]);

        $header = $this->base64urlEncode($header);

        $payload = json_encode($payload);

        $payload = $this->base64urlEncode($payload);


        $signature = hash_hmac(
            "sha256",
            $header . "." . $payload,
            $this->key,
            true
        );

        $signature = $this->base64urlEncode($signature);

        return
            $header . "." . $payload . "." . $signature;
    }

    public function decode($token)
    {
        if (preg_match("/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/", $token, $matches) !== 1) {
            throw new InvalidArgumentException("Invalid token format");
        };

        $signature = hash_hmac(
            "sha256",
            $matches["header"] . "." . $matches["payload"],
            $this->key,
            true
        );


        $signature_from_token = $this->base64urlDecode($matches["signature"]);

        if (!hash_equals($signature, $signature_from_token)) {
            throw new Exception("signature doesn't match");
        }

        $payload = json_decode($this->base64urlDecode($matches['payload']), true);

        return $payload;
    }

    private function base64urlEncode($text)
    {

        return str_replace(
            ["+", "/", "="],
            ["-", "_", ""],
            base64_encode($text)
        );
    }

    private function base64urlDecode($text)
    {
        return base64_decode(
            str_replace(
                ["-", "_"],
                ["+", "/"],
                $text
            )
        );
    }
}
