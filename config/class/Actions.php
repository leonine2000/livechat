<?php

require_once realpath(__DIR__ . '/../config.php');

class Actions
{

    private $salt1, $salt2, $enckey, $time_api;


    public function __construct()
    {
        $this->salt1 = $_ENV['SALT1'];
        $this->salt2 = $_ENV['SALT2'];
        $this->enckey = $this->salt1 . $this->salt2;
        $this->time_api = $_ENV['TIME_API'];
    }

    public function date()
    {
        date_default_timezone_set('Africa/Lagos');

        // Create a cURL handle to fetch the current datetime from an internet source
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $this->time_api);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        $datetime_json = curl_exec($curl_handle);
        curl_close($curl_handle);

        // Extract the datetime from the JSON response
        $datetime_data = json_decode($datetime_json, true);

        if (!$datetime_data) {
            die('402');
        } else {
            $datetime_str = $datetime_data['datetime'];

            // Convert the datetime string to a UNIX timestamp and format it as desired
            $datetime_timestamp = strtotime($datetime_str);
            $date_time = date('Y-m-d', $datetime_timestamp);
            return $date_time;
        }
    }

    public function dateTime()
    {
        date_default_timezone_set('Africa/Lagos');

        // Create a cURL handle to fetch the current datetime from an internet source
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $this->time_api);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        $datetime_json = curl_exec($curl_handle);
        curl_close($curl_handle);

        // Extract the datetime from the JSON response
        $datetime_data = json_decode($datetime_json, true);
        if (!$datetime_data) {
            die('Couldn\'t connect to date server. Please check your network service');
        } else {
            $datetime_str = $datetime_data['datetime'];

            // Convert the datetime string to a UNIX timestamp and format it as desired
            $datetime_timestamp = strtotime($datetime_str);
            $date_time = date('Y-m-d H:i:s', $datetime_timestamp);
            return $date_time;
        }
    }

    public function dateWithAddedDays($daysToAdd)
    {
        date_default_timezone_set('Africa/Lagos');

        // Create a cURL handle to fetch the current datetime from an internet source
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $this->time_api);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        $datetime_json = curl_exec($curl_handle);
        curl_close($curl_handle);

        // Extract the datetime from the JSON response
        $datetime_data = json_decode($datetime_json, true);
        if (!$datetime_data) {
            die('402');
        } else {
            $datetime_str = $datetime_data['datetime'];

            // Convert the datetime string to a UNIX timestamp and add days
            $datetime_timestamp = strtotime($datetime_str);
            $new_datetime_timestamp = strtotime("+$daysToAdd days", $datetime_timestamp);

            // Format the new datetime as desired
            $new_date_time = date('Y-m-d', $new_datetime_timestamp);
            return $new_date_time;
        }
    }

    public function addToDate($string, $daysToAdd)
    {
        $datetime_str = $string;

        // Convert the datetime string to a UNIX timestamp and add days
        $datetime_timestamp = strtotime($datetime_str);
        $new_datetime_timestamp = strtotime("+$daysToAdd days", $datetime_timestamp);

        // Format the new datetime as desired
        $new_date_time = date('F j, Y', $new_datetime_timestamp);
        return $new_date_time;
    }

    public function formatDate($string, $time = null)
    {
        $timestamp = strtotime($string);
        if (empty($time)) {

            $date =  date("F j, Y", $timestamp);
        } else if (!empty($time) && $time == 'time') {
            $date =  date("F j, Y H:i:sA", $timestamp);
        }
        return $date;
    }

    public function increaseTime($string)
    {
        $pattern = '/\d+/'; // Regular expression to match digits

        preg_match_all($pattern, $string, $matches);

        $numbers = $matches[0]; // Extracted numbers
        $modifiedString = implode(' - ', array_map(function ($number) {
            return $number + 3; // Add 3 to each number
        }, $numbers));

        return $modifiedString . " Working Days"; // Outputs: 6 - 8 Working Days
    }

    public function SSLencrypt($value, $enckey)
    {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($value, "AES-256-CBC", $enckey, 0, $iv);
        $iv_hex = bin2hex($iv);
        $encrypted_hex = bin2hex($encrypted);
        return urlencode("$iv_hex:$encrypted_hex");
    }

    public function SSLdecrypt($value, $enckey)
    {
        error_reporting(0);
        $decoded = urldecode($value);
        $parts = explode(':', $decoded);
        $iv = hex2bin($parts[0]);
        $encrypted = hex2bin($parts[1]);
        $decrypted = openssl_decrypt($encrypted, "AES-256-CBC", $enckey, 0, $iv);
        if ($decrypted === false) {
            // An error occurred during decryption
            echo "error";
            return false;
        } else {
            return $decrypted;
        }
        error_reporting(E_ALL); // Restore error reporting to previous level
    }

    public function salt1()
    {
        return $this->salt1;
    }

    public function salt2()
    {
        return $this->salt2;
    }
    public  function enckey()
    {
        return $this->enckey;
    }


    public function generateSalt($size)
    {
        return bin2hex(random_bytes($size));
    }

    public function salted($encrypt)
    {
        $salt1 = $this->generateSalt(32);
        $salt2 = $this->generateSalt(32);
        $salted = $salt1 . $encrypt . $salt2;
        $encrypted = hash("sha512", $salted);

        return array(
            "salt1" => $salt1,
            "salt2" => $salt2,
            "encrypted" => $encrypted
        );
    }

    public function genSalt($encrypt, $salt1, $salt2)
    {
        $salted = $salt1 . $encrypt . $salt2;
        $encrypted = hash("sha512", $salted);
        return $encrypted;
    }

    public function uniqueID($num1, $num2, $length)
    {
        if ($num1 === 0) {
            $temp = 1;
            $rgNum1 = (int)str_repeat($num1, $length - 1);
            $rNum1 = $temp . $rgNum1;
        } else {
            $rNum1 = (int)str_repeat($num1, $length);
        }
        $rNum2 = (int)str_repeat($num2, $length);
        $uid = mt_rand($rNum1, $rNum2);
        return $uid;
    }

    public function returnEnv($env)
    {
        $environmentVariable =  $_ENV[$env];
        return $environmentVariable;
    }

    public function getUrl($type)
    {
        // Define the components of the URL
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        $query = $_SERVER['QUERY_STRING'];
        $uri = preg_replace('/\.php$/', '', $uri);
        // Create full URL
        $fullUrl = $protocol . $host . $uri;

        // Define control array with server variables
        $control = array(
            "self" => $_SERVER['PHP_SELF'],
            "query" => $query,
            "uri" => $uri,
            "base" => $protocol . $host . $_SERVER['PHP_SELF'] . '?' . $query,
            "full" => $fullUrl
        );



        // Check if the requested type exists in the $control array
        if (isset($control[$type])) {
            return array(
                "main" => $control[$type],
                "type" => $type
            );
        } else {
            return array(
                "main" => null,
                "type" => $type,
                "error" => "Invalid URL type requested"
            );
        }
    }


}
