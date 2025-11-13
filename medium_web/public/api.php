<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    http_response_code(200);
    echo json_encode(['status' => 'authenticated', 'user' => $_SESSION['username'] ?? 'unknown']);
    exit();
}

$xml = file_get_contents('php://input');

if (empty($xml)) {
    http_response_code(400);
    echo json_encode(['error' => 'No XML data provided']);
    exit();
}

$dom = new DOMDocument();
$dom->resolveExternals = true;
$dom->substituteEntities = true;

if (!@$dom->loadXML($xml, LIBXML_DTDLOAD | LIBXML_NOENT)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid XML format']);
    exit();
}

// Return the processed XML
header('Content-Type: application/xml');
echo $dom->saveXML();

?>