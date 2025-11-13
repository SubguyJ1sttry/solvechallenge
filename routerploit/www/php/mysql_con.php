<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/../config/database.php";

try {
    $pdo = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8', $db_user, $db_passwd);
} catch (PDOException $e) {
    echo "SQL Error: " . $e->getMessage();
}
