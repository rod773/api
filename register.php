<?php

require "vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

    $dotenv->load();

    $database = new Database(
        $_ENV['DB_HOST'],
        $_ENV['DB_NAME'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS']
    );


    $conn = $database->getConnection();

    $sql = "insert into user (name,username,password_hash,api_key)" .
        " values (:name , :username, :password_hash,:api_key)";


    $stmt = $conn->prepare($sql);

    $name = $_POST['name'];
    $username = $_POST['username'];
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $api_key = bin2hex(random_bytes(16));

    $stmt->bindValue(":name", $name, PDO::PARAM_STR);
    $stmt->bindValue(":username", $username, PDO::PARAM_STR);
    $stmt->bindValue(":password_hash", $password_hash, PDO::PARAM_STR);
    $stmt->bindValue(":api_key", $api_key, PDO::PARAM_STR);


    $stmt->execute();

    echo "Trank you for registering. Your api_key is: $api_key";

    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./pico.min.css">
    <title>Register</title>
</head>

<body>
    <main class="container">
        <h1>Register</h1>
        <form method="post">
            <label for="name">Name
                <input type="text" name="name">
            </label>
            <label for="username">Username
                <input type="text" name="username">
            </label>
            <label for="password">Password
                <input type="password" name="password">
            </label>
            <button>Register</button>
        </form>
    </main>
</body>

</html>