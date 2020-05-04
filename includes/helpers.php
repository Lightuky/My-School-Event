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

function getSchool($user_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM schools WHERE id = :user_id");
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
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

function searchUsers($query) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM users WHERE (user1_id = :user1_id AND user2_id = :user2_id) OR (user1_id = :user2_id AND user2_id = :user1_id)");
    $stmt->bindValue(':user1_id', $auth_id);
    $stmt->bindValue(':user2_id', $user2);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchEvents($query) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM events WHERE (user1_id = :user1_id AND user2_id = :user2_id) OR (user1_id = :user2_id AND user2_id = :user1_id)");
    $stmt->bindValue(':user1_id', $auth_id);
    $stmt->bindValue(':user2_id', $user2);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchHelps($query) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM help_posts WHERE (user1_id = :user1_id AND user2_id = :user2_id) OR (user1_id = :user2_id AND user2_id = :user1_id)");
    $stmt->bindValue(':user1_id', $auth_id);
    $stmt->bindValue(':user2_id', $user2);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchPosts($query) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM posts WHERE (user1_id = :user1_id AND user2_id = :user2_id) OR (user1_id = :user2_id AND user2_id = :user1_id)");
    $stmt->bindValue(':user1_id', $auth_id);
    $stmt->bindValue(':user2_id', $user2);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCategories() {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM categories");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCategory($id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM categories WHERE id = :cat_id");
    $stmt->bindValue(':cat_id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getCategoryEvents($id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM events WHERE category = :cat_id");
    $stmt->bindValue(':cat_id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function setNewEvent($data, $id, $addressId) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO events (name, category, date, time, duration, description, address_id, admin_id, member_limit, is_private)
                                     VALUES (:name, :category, :date, :time, :duration, :description, :address_id, :admin_id, :member_limit, :is_private)");
    $stmt->bindValue(':name', $data['name']);
    $stmt->bindValue(':category', $data['category']);
    $stmt->bindValue(':date', $data['date']);
    $stmt->bindValue(':time', $data['time']);
    $stmt->bindValue(':duration', $data['duration']);
    $stmt->bindValue(':description', $data['description']);
    $stmt->bindValue(':address_id', $addressId);
    $stmt->bindValue(':admin_id', $id);
    $stmt->bindValue(':member_limit', $data['member_limit']);
    $stmt->bindValue(':is_private', $data['is_private']);
    $stmt->execute();
    return $dbh->lastInsertId();
}

function setNewAddress($data) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO event_addresses (street_number, address_line1, address_line2, city, zip_code, country)
                                     VALUES (:street_number, :address_line1, :address_line2, :city, :zip_code, :country)");
    $stmt->bindValue(':street_number', $data['street_number']);
    $stmt->bindValue(':address_line1', $data['address_line1']);
    $stmt->bindValue(':address_line2', $data['address_line2']);
    $stmt->bindValue(':city', $data['city']);
    $stmt->bindValue(':zip_code', $data['zip_code']);
    $stmt->bindValue(':country', $data['country']);
    $stmt->execute();
    return $dbh->lastInsertId();
}

function getEvent($id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM events WHERE id = :id LIMIT 1");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getEventMembers($event_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM event_users WHERE event_id = :event_id AND private_pending = :private_pending");
    $stmt->bindValue(':event_id', $event_id);
    $stmt->bindValue(':private_pending', 0);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEventMembersSorted($event_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM event_users WHERE event_id = :event_id AND private_pending = :private_pending ORDER BY date_added ASC");
    $stmt->bindValue(':event_id', $event_id);
    $stmt->bindValue(':private_pending', 0);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEventAddress($event_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT events.address_id, event_addresses.* FROM events LEFT JOIN event_addresses
                                    ON event_addresses.id = events.address_id WHERE events.id = :event_id LIMIT 1");
    $stmt->bindValue(':event_id', $event_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function checkEventState($event_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM event_users WHERE user_id = :user_id AND event_id = :event_id");
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->bindValue(':event_id', $event_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateEvent($data, $event_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("UPDATE events SET  name = :name, category = :category, date = :date, time = :time, duration = :duration, description = :description, member_limit = :member_limit, is_private = :is_private, date_edited = :date_edited WHERE id = $event_id");
    $stmt->bindValue(':name', $data['name']);
    $stmt->bindValue(':category', $data['category']);
    $stmt->bindValue(':date', $data['date']);
    $stmt->bindValue(':time', $data['time']);
    $stmt->bindValue(':duration', $data['duration']);
    $stmt->bindValue(':description', $data['description']);
    $stmt->bindValue(':member_limit', $data['member_limit']);
    $stmt->bindValue(':is_private', $data['is_private']);
    $stmt->bindValue(':date_edited', date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . ' + 2 hours')));
    $stmt->execute();
}

function updateAddress($data, $address_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "UPDATE event_addresses SET street_number = :street_number, address_line1 = :address_line1, address_line2 = :address_line2, city = :city, zip_code = :zip_code, country = :country, date_edited = :date_edited WHERE id = $address_id");
    $stmt->bindValue(':street_number', $data['street_number']);
    $stmt->bindValue(':address_line1', $data['address_line1']);
    $stmt->bindValue(':address_line2', $data['address_line2']);
    $stmt->bindValue(':city', $data['city']);
    $stmt->bindValue(':zip_code', $data['zip_code']);
    $stmt->bindValue(':country', $data['country']);
    $stmt->bindValue(':date_edited', date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . ' + 2 hours')));
    $stmt->execute();
}

function getPendingUsers($event_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT event_users.*, users.id, users.first_name, users.last_name, users.school_id, users.school_year 
                                    FROM event_users LEFT JOIN users ON users.id = event_users.user_id WHERE private_pending = :private_pending AND event_id = :event_id");
    $stmt->bindValue(':private_pending', 1);
    $stmt->bindValue(':event_id', $event_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEventMembersCredentials($event_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT event_users.*, users.id, users.first_name, users.last_name, users.school_id, users.school_year 
                                    FROM event_users LEFT JOIN users ON users.id = event_users.user_id WHERE private_pending = :private_pending AND event_id = :event_id");
    $stmt->bindValue(':private_pending', 0);
    $stmt->bindValue(':event_id', $event_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOwnedEvents($user_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM events WHERE admin_id = :admin_id");
    $stmt->bindValue(':admin_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function joinEvent($private_pending, $auth_id, $event_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO event_users (user_id, event_id, private_pending) VALUES (:user_id, :event_id, :private_pending)");
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->bindValue(':event_id', $event_id);
    $stmt->bindValue(':private_pending', $private_pending);
    $stmt->execute();
}

function acceptEventJoin($private_pending, $event_id, $user_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("UPDATE event_users SET private_pending = :private_pending WHERE user_id = :user_id AND event_id = :event_id");
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':event_id', $event_id);
    $stmt->bindValue(':private_pending', $private_pending);
    $stmt->execute();
}

function quitEvent($auth_id, $event_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("DELETE FROM event_users WHERE (user_id = :user_id AND event_id = :event_id)");
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->bindValue(':event_id', $event_id);
    $stmt->execute();
}

function discardEventPending($event_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("DELETE FROM event_users WHERE (event_id = :event_id AND private_pending = :private_pending)");
    $stmt->bindValue(':event_id', $event_id);
    $stmt->bindValue(':private_pending', 1);
    $stmt->execute();
}