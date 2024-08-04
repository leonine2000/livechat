
<?php

function encrypt($sData)
{
    $id = (float)$sData * 525325.24;
    return base64_encode($id);
}

function decrypt($sData)
{
    $url_id = base64_decode($sData);
    $id = (float)$url_id / 525325.24;
    return $id;
}

function sanitize_input($data)
{
    // Remove whitespace and invalid characters
    $data = trim($data);
    $data = filter_var($data, FILTER_SANITIZE_STRING);
    // Convert special characters to HTML entities
    $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    // Validate user input

    return $data;
}

function sanitize_get_variable($variable_name)
{
    if (isset($_GET[$variable_name])) {
        return filter_var($_GET[$variable_name], FILTER_SANITIZE_SPECIAL_CHARS);
    } else {
        return '';
    }
}

define('vat', 0.075);
(float)$vat = constant('vat');
(float)$total = 0;


function sanitizeInteger($value)
{
    return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
}

function sanitizeString($value)
{
    return filter_var($value, FILTER_SANITIZE_STRING,);
}

function sanitizeStringX($value)
{
    return filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
}

function sanitizeEmail($value)
{
    return filter_var($value, FILTER_SANITIZE_EMAIL);
}

function validateEmail($value)
{
    return filter_var($value, FILTER_VALIDATE_EMAIL);
}

function sanitizeFloat($value)
{
    return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
}

function validateInteger($value)
{
    return filter_var($value, FILTER_VALIDATE_INT);
}

function validateFloat($value)
{
    return filter_var($value, FILTER_VALIDATE_FLOAT);
}

function bulletify($data)
{

    $data = str_replace('<br/>', "\n", $data);

    // Explode the data into an array using newline character \n as the delimiter
    $array = explode("\n", $data);
    $html = '<ul class="feature_list">';
    for ($i = 0; $i < count($array); $i++) {
        $html .= ' <li><i class="mdi mdi-check-circle" aria-hidden="true"></i>' . $array[$i] . '</li>';
    }
    $html .= "</ul>";

    return $html;
}

function lineBreak($data)
{
    $response = '';
    $array = explode("\n", $data);
    for ($i = 0; $i < count($array); $i++) {
        $response .= $array[$i] . '<br>';
    }

    return $response;
}


///Foramt Date and Time String
function formatTime($timeString, $time = null)
{


    $dateTime = new DateTime($timeString);

    $formattedTime = $dateTime->format('F d, Y');
    if ($time === TRUE) {

        $formattedTime = $dateTime->format('F d, Y, h:i A');
    }
    return $formattedTime;
}


function variableType($var)
{
    $types = ["integer" => "i", "string" => "s", "double" => "d"];
    $varType = gettype($var);
    $data = array(
        "type" => $varType,
        "sym" => $types[$varType]

    );

    return $data;
}

function validate_session()
{
    // Check if the token and expire_time variables are set in the session
    if (isset($_SESSION['token']) && isset($_SESSION['expire_time'])) {
        // Check if the token has expired
        if (time() > $_SESSION['expire_time']) {
            // If the token has expired, unset the session variables
            unset($_SESSION['token']);
            unset($_SESSION['uid']);
            unset($_SESSION['expire_time']);
            $_SESSION['error'] = "Token has expired";
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}
