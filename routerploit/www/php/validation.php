<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/helper_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../php/mysql_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/../config/security.php";

function validate_guid($guid, $goto = null)
{
    $valid = preg_match("/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i", $guid);

    if (!$valid && $goto != null) {
        goto_page($goto);
    }

    if (!$valid) return "Invalid GUID";
}

function validate_name($name, $goto = null)
{
    $valid = preg_match("/^[a-zA-Z\- ]{1,64}$/", $name);

    if (!$valid && $goto != null) {
        goto_page($goto);
    }

    if (!$valid) return "First name must only contain letters (A-Z or a-z), hyphens (-), or spaces, and be between 1 and 64 characters long";
}

function validate_username($username, $goto = null, $allow_exists = false)
{
    $valid = preg_match("/^[a-zA-Z0-9\_\-]{1,64}$/", $username);

    if (!$valid && $goto != null) {
        goto_page($goto);
    }

    if (exists_username($username) && !$allow_exists && $username != get_username(get_user_guid())) return "Username is already taken. Please choose another";

    if (!$valid) return "Username must only contain letters (A-Z or a-z), numbers (0-9), underscores (_), or hyphens (-), and be between 1 and 64 characters long";
}

function validate_password($password, $goto = null)
{
    $valid = preg_match("/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", $password);

    if (!$valid && $goto != null) {
        goto_page($goto);
    }

    if (!$valid) return "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character (#?!@$%^&*-)";
}

function validate_street($street, $goto = null)
{
    $valid = preg_match("/^[a-zA-Z0-9\-\. ]{0,64}$/", $street);

    if (!$valid && $goto != null) {
        goto_page($goto);
    }

    if (!$valid) return "Street must only contain letters (A-Z or a-z), numbers (0-9), hyphens (-), dots (.) or spaces, and be between 0 and 64 characters long";
}

function validate_postal_code($postal_code, $goto = null)
{
    $valid = preg_match("/^[a-zA-Z0-9\-\. ]{0,64}$/", $postal_code);

    if (!$valid && $goto != null) {
        goto_page($goto);
    }

    if (!$valid) return "Postal code must only contain numbers (0-9), and be between 0 and 64 characters long";
}

function validate_city($city, $goto = null)
{
    $valid = preg_match("/^[a-zA-Z\- ]{0,64}$/", $city);

    if (!$valid && $goto != null) {
        goto_page($goto);
    }

    if (!$valid) return "City must only contain letters (A-Z or a-z), hyphens (-), or spaces, and be between 0 and 64 characters long";
}

function validate_country($country, $goto = null)
{
    $valid = preg_match("/^[a-zA-Z\- ]{0,64}$/", $country);

    if (!$valid && $goto != null) {
        goto_page($goto);
    }

    if (!$valid) return "Country must only contain letters (A-Z or a-z), hyphens (-), or spaces, and be between 0 and 64 characters long";
}

function validate_text($text, $goto = null)
{
    $valid = preg_match("/^.+$/u", $text);

    if (!$valid && $goto != null) {
        goto_page($goto);
    }

    if (!$valid) return "Textfields must not be empty";
}

function validate_account_type($type, $goto = null)
{
    $valid = strpos($type, "s") !== false && strpos($type, "n") !== false;

    if (!$valid && $goto != null) {
        goto_page($goto);
    }

    if (!$valid) return "Unknown account type";
}
