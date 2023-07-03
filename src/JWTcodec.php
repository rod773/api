<?php

class JWTcodec
{

    public function encode($payload)
    {
        $header = json_encode([
            "typ" => "JWT",
            "alg" => "HS256"
        ]);

        $header - $this->base64urlEncode($header);

        $payload = json_encode($payload);

        $payload = $this->base64urlEncode($payload);


        $signature = hash_hmac("sha256", "$header.$payload.833F91ECA375974CBE23EE9C9AF49", true);

        $signature = $this->base64urlEncode($signature);

        return "$header.$payload.$signature";
    }

    private function base64urlEncode($text)
    {
        return str_replace(
            ["+", "/", "="],
            ["-", "_", ""],
            base64_encode($text)
        );
    }
}
