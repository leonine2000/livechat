<?php


$path = realpath(__DIR__ . "/../vendor/autoload.php");
require_once($path);
//echo file_exists(__DIR__ . '/../../../config/.env') ? 'EXISTS' : 'DO NoT EXISTS';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../config/', '.env.chat');
$dotenv->load();
