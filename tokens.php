<?php


$payload = [
    "sub" => $user['id'],
    "name" => $user['name'],
    "exp" => time() + 20
];


$access_token = $codec->encode($payload);


$refresh_tohen_expiry = time() + 432000;

$refresh_token = $codec->encode([
    "sub" => $user['id'],
    "exp" => $refresh_tohen_expiry
]);

echo json_encode(
    [
        "access_token" => $access_token,
        "refresh_token" => $refresh_token
    ]
);
