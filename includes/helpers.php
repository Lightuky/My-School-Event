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

function getSchools() {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM schools");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function setNewUser($data) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO users (first_name, last_name, email, gender, school_id, school_year, password)
                                     VALUES (:first_name, :last_name, :email, :gender, :school_id, :school_year, :password)");
    $stmt->bindValue(':first_name', $data['first_name']);
    $stmt->bindValue(':last_name', $data['last_name']);
    $stmt->bindValue(':email', $data['email']);
    $stmt->bindValue(':gender', $data['gender']);
    $stmt->bindValue(':school_id', $data['school_id']);
    $stmt->bindValue(':school_year', $data['school_year']);
    $stmt->bindValue(':password', sha1($data['password']));
    $stmt->execute();
    return $dbh->lastInsertId();
}

function authUser($data) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email AND password = :password LIMIT 1");
    $stmt->bindValue(':email', $data['email']);
    $stmt->bindValue(':password', sha1($data['password']));
    $stmt->execute();
    return $stmt->fetch();
}

function authOut() {
    session_destroy();
}
