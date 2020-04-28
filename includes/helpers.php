<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mse/env.php';

use Carbon\Carbon;

function dd($var) {
    var_dump($var);
    die();
}

function connectDB() {
    global $database;

    $host = $database['host'];
    $dbname = $database['dbname'];
    $username = $database['username'];
    $password = $database['password'];

    return new PDO("mysql:host=$host;dbname=$dbname", "$username","$password", array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
}

function getDateForHumans($date) {
    $c = new Carbon($date, 'Europe/Paris');
    return $c->diffForHumans();
}
