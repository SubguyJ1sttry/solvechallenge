<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/html_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/helper_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/mysql_functions.php";
start_session();

$challenge = $_SESSION["master_challenge"];
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
echo $result;