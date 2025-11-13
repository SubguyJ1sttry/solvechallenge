<?php

function start_session()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 3600,
            'path' => '/',
            'domain' => '', // Set to your domain if needed
            'secure' => true, // Enable only if using HTTPS
            'httponly' => true,
            'samesite' => 'Strict' // or 'Lax' if needed
        ]);
        session_start();
    }
}

function get_fresh_guid()
{
    $data = random_bytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function get_user_guid()
{
    return isset($_GET["user_guid"]) ? $_GET["user_guid"] : $_SESSION['user_guid'];
}

function get_product_guid()
{
    return $_GET["guid"];
}

function get_random_string($len)
{
    if (!function_exists('random_int')) {
        die("random_int() function is not available. Upgrade your PHP version.");
    }

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $len; $i++) {
        $index = random_int(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

function get_auth_code($auth_code_seed) {
    // mangle the seed with some crypto magic
    $x64 = 0x7dd19c78091e7550;
    $x64 ^= ($x64 << 13) & ((1 << 64) - 1);
    $x64 ^= ($x64 >> 7) & ((1 << 64) - 1);
    $x64 ^= ($x64 << 17) & ((1 << 64) - 1);
    $x64 ^= $auth_code_seed & ((1 << 64) - 1);
    // generate a printable token
    $token = sprintf("%02x", $x64 >> 56);
    for ($i = 0; $i < 8; $i++) {
        $token .= chr(((($x64 >> ($i * 8)) & 0xff) % 0xa) + 0x30);
    }
    return $token;
}

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function ip_in_subnet($ip, $subnet, $mask)
{
    if (empty($ip)) return false;
    $ipLong = ip2long($ip);
    $subnetLong = ip2long($subnet);
    $maskLong = ~((1 << (32 - $mask)) - 1);

    return ($ipLong & $maskLong) == ($subnetLong & $maskLong);
}

function value_of($data)
{
    return !empty($data) ? $data : "n.a.";
}

function is_logged_in()
{
    return isset($_SESSION['user_guid']);
}

function requireLogin()
{
    if (!isset($_SESSION['user_guid'])) goto_page();
}

function requireLogout()
{
    if (isset($_SESSION['user_guid'])) goto_page();
}

function require_user_exists($user_guid)
{
    if (!exists_user($user_guid)) goto_page();
}

function is_local_ip()
{
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $forwarded_ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $forwarded_ips = array_map('trim', $forwarded_ips);
        $ip = $forwarded_ips[0];
    }

    return ip_in_subnet($ip, "192.168.0.0", 24);
}

function goto_page($goto = "/")
{
    if (empty($goto)) {
        $goto = "/";
    }

    if (filter_var($goto, FILTER_VALIDATE_URL) || str_starts_with($goto, "/")) {
        header("Location: " . $goto);
    } else {
        header("Location: /");
    }

    exit();
}

function master_login()
{
    if (isset($_GET["master_response"]) && isset($_SESSION['master_challenge'])) {
        # $_SERVER['SERVER_ADDR'] is used to uniquely identify the server
        # Its neccessary to prohibit using the game master as signing oracle
        $challenge = $_SESSION["master_challenge"] . $_SERVER['SERVER_ADDR'];
        $response = $_GET["master_response"];
        $response = base64_decode($response);

        $publicKey = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAskGw5lFawglQNsKj8eiF
/YrZyZSEkBNbOt1/XdDupBoEorEdvRS+DHeL1l1vuR86wMT9hb7ABS5PuG6cTXqX
P7iW0s9yg5DVgK9hSg0ndbo37k3VqTCDMmOUUuk9DXkukgRUSYSef6IFtBa47k1o
kdn5Re45vTmZ3NIhKURTRVg2CTSg8HLgxqfIzq2KO1C087N1+iyooYlcE31HMRVr
kbmbm7wMufLnrrob5GDJeqAgNkKTN40iu0w+grqrXEYRsxanmgu4pJbgQF3hWi1i
2CkG9WFZRUvpiMNVAHfN7o16KXAWPp+u/KGXiBDl14Fj3Oiu5qzpbDCcGbUycGZG
WwIDAQAB
-----END PUBLIC KEY-----";

        $result = openssl_verify($challenge, $response, $publicKey, OPENSSL_ALGO_SHA256);
        return $result === 1;
    }

    return false;
}
