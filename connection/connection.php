<?php
require_once realpath(__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__ . '/..'));
$dotenv->load();

$localhost = $_ENV['localhost'];
$username = $_ENV['username'];
$password = $_ENV['password'];
$database_name = $_ENV['database_name'];
$port = $_ENV['port'];

$con = mysqli_connect($localhost, $username, $password, $database_name, $port) or die("Connection Failed");
