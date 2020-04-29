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

function getUser($id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserSchool($id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT users.id, schools.* FROM users LEFT JOIN schools ON users.school_id = schools.id WHERE users.id = :id LIMIT 1");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUsers($id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM users WHERE id != $id");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function checkFriend($auth_id, $user2) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM friends WHERE (user1_id = :user1_id AND user2_id = :user2_id) OR (user1_id = :user2_id AND user2_id = :user1_id)");
    $stmt->bindValue(':user1_id', $auth_id);
    $stmt->bindValue(':user2_id', $user2);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addFriend($pending, $auth_id, $user2_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO friends (user1_id, user2_id, pending) VALUES (:user1_id, :user2_id, :pending)");
    $stmt->bindValue(':user1_id', $auth_id);
    $stmt->bindValue(':user2_id', $user2_id);
    $stmt->bindValue(':pending', $pending);
    $stmt->execute();
}

function acceptFriendRequest($pending, $auth_id, $user2_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("UPDATE friends SET pending = :pending WHERE user1_id = :user1_id AND user2_id = :user2_id");
    $stmt->bindValue(':pending', $pending);
    $stmt->bindValue(':user1_id', $user2_id);
    $stmt->bindValue(':user2_id', $auth_id);
    $stmt->execute();
}

function deleteFriend($pending, $auth_id, $user2_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("DELETE FROM friends WHERE (user1_id = :user1_id AND user2_id = :user2_id AND pending = :pending) OR (user1_id = :user2_id AND user2_id = :user1_id AND pending = :pending)");
    $stmt->bindValue(':pending', $pending);
    $stmt->bindValue(':user1_id', $auth_id);
    $stmt->bindValue(':user2_id', $user2_id);
    $stmt->execute();
}

function updateUser($data, $id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("UPDATE users SET  first_name = :first_name, last_name = :last_name, phone_number = :phone_number, birthday = :birthday, gender = :gender, school_id = :school_id, school_year = :school_year, date_edited = :date_edited WHERE id = $id");
    $stmt->bindValue(':first_name', $data['first_name']);
    $stmt->bindValue(':last_name', $data['last_name']);
    $stmt->bindValue(':phone_number', $data['phone_number']);
    $stmt->bindValue(':birthday', $data['birthday']);
    $stmt->bindValue(':gender', $data['gender']);
    $stmt->bindValue(':school_id', $data['school_id']);
    $stmt->bindValue(':school_year', $data['school_year']);
    $stmt->bindValue(':date_edited', date("Y-m-d H:i:s", time()));
    $stmt->execute();
}