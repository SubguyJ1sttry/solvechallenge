<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/helper_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/mysql_con.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/validation.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../config/security.php";

function exists_user($user_guid)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE guid = :_user_guid");
        $stmt->execute([
            '_user_guid' => $user_guid,
        ]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function exists_product($product_guid)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE guid = :_product_guid");
        $stmt->execute([
            '_product_guid' => $product_guid,
        ]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function exists_invoice($invoice_guid)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM invoices WHERE guid = :_invoice_guid");
        $stmt->execute([
            '_invoice_guid' => $invoice_guid,
        ]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function exists_username($username)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :_username");
        $stmt->execute([
            '_username' => $username,
        ]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function get_username($user_guid)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT username FROM users WHERE guid = :_user_guid");
        $stmt->execute([
            '_user_guid' => $user_guid,
        ]);
        $user_data = $stmt->fetch();

        return $user_data["username"];
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function get_user_data($user_guid)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT first_name, last_name, username, account_type FROM users WHERE guid = :_user_guid");
        $stmt->execute([
            '_user_guid' => $user_guid,
        ]);
        $user_data = $stmt->fetch();

        return $user_data;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function get_user_address($user_guid)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT street, postal_code, city, country FROM addresses WHERE user_guid = :_user_guid");
        $stmt->execute([
            '_user_guid' => $user_guid,
        ]);
        $address = $stmt->fetch();

        return $address;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function get_product_name($product_guid)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT name FROM products WHERE guid = :_product_guid");
        $stmt->execute([
            '_product_guid' => $product_guid,
        ]);
        $product_data = $stmt->fetch();

        return $product_data["name"];
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function get_products()
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM products ORDER BY id ASC");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function get_product_data($product_guid)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE guid = :_product_guid");
        $stmt->execute([
            '_product_guid' => $product_guid,
        ]);
        $product_data = $stmt->fetch();

        return $product_data;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function get_product_comments($product_guid)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM comments WHERE product_guid = :_product_guid ORDER BY id DESC");
        $stmt->execute([
            '_product_guid' => $product_guid,
        ]);
        $product_comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $product_comments;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function get_user_comments($user_guid)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM comments WHERE user_guid = :_user_guid ORDER BY id DESC");
        $stmt->execute([
            '_user_guid' => $user_guid,
        ]);
        $product_comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $product_comments;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function get_user_notes($user_guid)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_guid = :_user_guid ORDER BY id ASC");
        $stmt->execute([
            '_user_guid' => $user_guid,
        ]);
        $user_notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $user_notes;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function get_request_invoices()
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM invoices WHERE request IS NOT NULL ORDER BY id DESC");
        $stmt->execute();        
        $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $invoices;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function get_invoices($user_guid, $account_type = "", $limit = 3, $only_empty_request = false)
{
    global $pdo;

    try {
        if (strpos($account_type, "admin") !== false && is_local_ip()) {
            $stmt = $pdo->prepare("SELECT * FROM invoices ORDER BY id DESC");
            $stmt->execute();
        } else if (!$only_empty_request) {
            $stmt = $pdo->prepare("SELECT * FROM invoices WHERE user_guid = :_user_guid ORDER BY id DESC");
            $stmt->execute([
                '_user_guid' => $user_guid,
            ]);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM invoices WHERE user_guid = :_user_guid AND request IS NULL ORDER BY id DESC");
            $stmt->execute([
                '_user_guid' => $user_guid,
            ]);
        }
        $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($limit) {
            $count_gt_limit = count($invoices) > $limit;
            $invoices = array_slice($invoices, 0, $limit);
        } else {
            $count_gt_limit = false;
        }

        return [$invoices, $count_gt_limit];
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function get_invoice_data($invoice_guid)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM invoices WHERE guid = :_invoice_guid");
        $stmt->execute([
            '_invoice_guid' => $invoice_guid,
        ]);
        $invoice_data = $stmt->fetch();

        return $invoice_data;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function signup()
{
    global $pdo, $salt_length, $pepper;

    $first_name = isset($_POST["first_name"]) ? sanitize_input($_POST["first_name"]) : null;
    $last_name = isset($_POST["last_name"]) ? sanitize_input($_POST["last_name"]) : null;
    $username = isset($_POST["username"]) ? sanitize_input($_POST["username"]) : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;
    $account_type = isset($_POST["account_type"]) ? $_POST["account_type"] : null;

    if ($error = validate_name($first_name)) return $error;
    if ($error = validate_name($last_name)) return $error;
    if ($error = validate_username($username)) return $error;
    if ($error = validate_password($password)) return $error;
    if ($error = validate_account_type($account_type)) return $error;

    $salt = get_random_string($salt_length);
    $password_hash = password_hash($pepper . $password . $salt, PASSWORD_BCRYPT, array(
        "cost" => 4,
    ));

    if (exists_username($username)) return "Username is already taken. Please choose another";

    try {
        do {
            $fresh_guid = get_fresh_guid();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE guid = :_guid");
            $stmt->execute([
                '_guid' => $fresh_guid,
            ]);
            $count = $stmt->fetchColumn();
        } while ($count > 0);

        $stmt = $pdo->prepare("INSERT INTO users (guid, first_name, last_name, username, password_hash, salt, account_type) VALUES (:_guid, :_first_name, :_last_name, :_username, :_password_hash, :_salt, :_account_type)");
        $stmt->execute([
            '_guid' => $fresh_guid,
            '_first_name' => $first_name,
            '_last_name' => $last_name,
            '_username' => $username,
            '_password_hash' => $password_hash,
            '_salt' => $salt,
            '_account_type' => $account_type,
        ]);

        $stmt = $pdo->prepare("INSERT INTO addresses (user_guid) VALUES (:_guid)");
        $stmt->execute([
            '_guid' => $fresh_guid,
        ]);

        goto_page("/login.php");
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function login()
{
    global $pdo, $pepper;

    $username = isset($_POST["username"]) ? sanitize_input($_POST["username"]) : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;

    if ($error = validate_username($username, null, true)) return $error;
    if ($error = validate_password($password)) return $error;

    try {
        $stmt = $pdo->prepare("SELECT guid, password_hash, salt FROM users WHERE username = :_username");
        $stmt->execute([
            '_username' => $username,
        ]);
        $result = $stmt->fetch();

        if (!$result || !password_verify($pepper . $password . $result['salt'], $result['password_hash'])) {
            return "Username or password is incorrect";
        }

        session_regenerate_id(true);
        $_SESSION['user_guid'] = $result['guid'];
        $_SESSION['username'] = $username;

        goto_page();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function save_changes()
{
    global $pdo;

    $username = isset($_POST["username"]) ? sanitize_input($_POST["username"]) : null;
    $account_type = isset($_POST["account_type"]) ? $_POST["account_type"] : null;
    $user_guid = isset($_POST["user_guid"]) ? sanitize_input($_POST["user_guid"]) : null;
    $first_name = isset($_POST["first_name"]) ? sanitize_input($_POST["first_name"]) : null;
    $last_name = isset($_POST["last_name"]) ? sanitize_input($_POST["last_name"]) : null;
    $street = isset($_POST["street"]) ? sanitize_input($_POST["street"]) : null;
    $postal_code = isset($_POST["postal_code"]) ? sanitize_input($_POST["postal_code"]) : null;
    $city = isset($_POST["city"]) ? sanitize_input($_POST["city"]) : null;
    $country = isset($_POST["country"]) ? sanitize_input($_POST["country"]) : null;

    validate_guid($user_guid, "/");
    if ($error = validate_username($username)) return $error;
    if ($error = validate_account_type($account_type)) return $error;
    if ($error = validate_name($first_name)) return $error;
    if ($error = validate_name($last_name)) return $error;
    if ($error = validate_street($street)) return $error;
    if ($error = validate_postal_code($postal_code)) return $error;
    if ($error = validate_city($city)) return $error;
    if ($error = validate_country($country)) return $error;

    if (!exists_user($user_guid)) return "Invalid user";

    try {
        $stmt = $pdo->prepare("UPDATE users SET first_name = :_first_name, last_name = :_last_name, username = :_username, account_type = :_account_type WHERE guid = :_user_guid");
        $stmt->execute([
            '_user_guid' => $user_guid,
            '_first_name' => $first_name,
            '_last_name' => $last_name,
            '_username' => $username,
            '_account_type' => $account_type,
        ]);

        $stmt = $pdo->prepare("UPDATE addresses SET street = :_street, postal_code = :_postal_code, city = :_city, country = :_country WHERE user_guid = :_user_guid");
        $stmt->execute([
            '_user_guid' => $user_guid,
            '_street' => $street,
            '_postal_code' => $postal_code,
            '_city' => $city,
            '_country' => $country,
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function save_post()
{
    global $pdo;

    $user_guid = isset($_POST["user_guid"]) ? sanitize_input($_POST["user_guid"]) : null;
    $product_guid = isset($_POST["product_guid"]) ? sanitize_input($_POST["product_guid"]) : null;
    $title = isset($_POST["title"]) ? sanitize_input($_POST["title"]) : null;
    $comment = isset($_POST["comment"]) ? sanitize_input($_POST["comment"]) : null;

    validate_guid($user_guid, "/");
    validate_guid($product_guid, "/");
    if ($error = validate_text($title)) return $error;
    if ($error = validate_text($comment)) return $error;

    if (!exists_user($user_guid)) return "Invalid user";
    if (!exists_product($product_guid)) return "Invalid product";

    try {
        do {
            $fresh_guid = get_fresh_guid();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM comments WHERE guid = :_guid");
            $stmt->execute([
                '_guid' => $fresh_guid,
            ]);
            $count = $stmt->fetchColumn();
        } while ($count > 0);

        $stmt = $pdo->prepare("INSERT INTO comments (guid, user_guid, product_guid, title, comment) VALUES (:_guid, :_user_guid, :_product_guid, :_title, :_comment)");
        $stmt->execute([
            '_guid' => $fresh_guid,
            '_user_guid' => $user_guid,
            '_product_guid' => $product_guid,
            '_title' => $title,
            '_comment' => $comment,
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function save_note()
{
    global $pdo;

    $user_guid = isset($_POST["user_guid"]) ? sanitize_input($_POST["user_guid"]) : null;
    $note = isset($_POST["note"]) ? sanitize_input($_POST["note"]) : null;

    validate_guid($user_guid, "/");
    if ($error = validate_text($note)) return $error;

    if (!exists_user($user_guid)) return "Invalid user";

    try {
        do {
            $fresh_guid = get_fresh_guid();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM notes WHERE guid = :_guid");
            $stmt->execute([
                '_guid' => $fresh_guid,
            ]);
            $count = $stmt->fetchColumn();
        } while ($count > 0);

        $stmt = $pdo->prepare("INSERT INTO notes (guid, user_guid, note) VALUES (:_guid, :_user_guid, :_note)");
        $stmt->execute([
            '_guid' => $fresh_guid,
            '_user_guid' => $user_guid,
            '_note' => $note,
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function purchase()
{
    global $pdo;

    $user_guid = isset($_POST["user_guid"]) ? sanitize_input($_POST["user_guid"]) : null;
    $product_guid = isset($_POST["product_guid"]) ? sanitize_input($_POST["product_guid"]) : null;
    $purchase_note = isset($_POST["purchase_note"]) ? sanitize_input($_POST["purchase_note"]) : null;

    validate_guid($user_guid, "/");
    validate_guid($product_guid, "/");

    if (!exists_user($user_guid)) return "Invalid user";
    if (!exists_product($product_guid)) return "Invalid product";

    try {
        do {
            $fresh_guid = get_fresh_guid();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM invoices WHERE guid = :_guid");
            $stmt->execute([
                '_guid' => $fresh_guid,
            ]);
            $count = $stmt->fetchColumn();
        } while ($count > 0);

        $stmt = $pdo->prepare("INSERT INTO invoices (guid, user_guid, product_guid, note) VALUES (:_guid, :_user_guid, :_product_guid, :_note)");
        $stmt->execute([
            '_guid' => $fresh_guid,
            '_user_guid' => $user_guid,
            '_product_guid' => $product_guid,
            '_note' => $purchase_note,
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}

function create_invoice_request()
{
    global $pdo;

    $user_guid = isset($_POST["user_guid"]) ? sanitize_input($_POST["user_guid"]) : null;
    $invoice_guid = isset($_POST["invoice_guid"]) ? sanitize_input($_POST["invoice_guid"]) : null;
    $request = isset($_POST["request"]) ? sanitize_input($_POST["request"]) : null;

    validate_guid($user_guid, "/");
    validate_guid($invoice_guid);

    if (!exists_user($user_guid)) return "Invalid user";
    if (!exists_invoice($invoice_guid)) return "Invalid invoice";
    if ($error = validate_text($request)) return "Invalid request: " . $error;

    try {
        $stmt = $pdo->prepare("UPDATE invoices SET request = :_request WHERE guid = :_invoice_guid");
        $stmt->execute([
            '_invoice_guid' => $invoice_guid,
            '_request' => $request,
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        goto_page("/notification.php?code=1000");
    }
}
