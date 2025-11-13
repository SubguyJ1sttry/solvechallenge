<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/helper_functions.php";
start_session();

if (isset($_SESSION['user_guid'])) {
    session_destroy();
    session_start();
    session_regenerate_id(true);
}

header("Location: /");
exit();