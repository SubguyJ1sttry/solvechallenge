<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/html_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/helper_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/mysql_functions.php";
start_session();

requireLogin();

$user_guid = get_user_guid();

validate_guid($user_guid, "/");
require_user_exists($user_guid);

try {
    $stmt = $pdo->prepare("DELETE FROM addresses WHERE user_guid = :_user_guid");
    $stmt->execute([
        '_user_guid' => $user_guid,
    ]);
    $stmt = $pdo->prepare("DELETE FROM comments WHERE user_guid = :_user_guid");
    $stmt->execute([
        '_user_guid' => $user_guid,
    ]);
    $stmt = $pdo->prepare("DELETE FROM invoices WHERE user_guid = :_user_guid");
    $stmt->execute([
        '_user_guid' => $user_guid,
    ]);
    $stmt = $pdo->prepare("DELETE FROM notes WHERE user_guid = :_user_guid");
    $stmt->execute([
        '_user_guid' => $user_guid,
    ]);
    $stmt = $pdo->prepare("DELETE FROM users WHERE guid = :_user_guid");
    $stmt->execute([
        '_user_guid' => $user_guid,
    ]);
    goto_page("/logout.php");
} catch (PDOException $e) {
    error_log($e->getMessage());
    goto_page("/notification.php?code=1000");
}