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

function getCities() {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM cities");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    $stmt = $dbh->prepare( "INSERT INTO users (first_name, last_name, email, gender, school_id, school_year, city_id, password)
                                     VALUES (:first_name, :last_name, :email, :gender, :school_id, :school_year, :city_id, :password)");
    $stmt->bindValue(':first_name', $data['first_name']);
    $stmt->bindValue(':last_name', $data['last_name']);
    $stmt->bindValue(':email', $data['email']);
    $stmt->bindValue(':gender', $data['gender']);
    $stmt->bindValue(':school_id', $data['school_id']);
    $stmt->bindValue(':school_year', $data['school_year']);
    $stmt->bindValue(':city_id', $data['city']);
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

function getFriends($user_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM friends WHERE user1_id = :user1_id  OR user2_id = :user1_id");
    $stmt->bindValue(':user1_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    $stmt = $dbh->prepare("UPDATE users SET  first_name = :first_name, last_name = :last_name, phone_number = :phone_number, birthday = :birthday, gender = :gender, school_id = :school_id, school_year = :school_year, city_id = :city_id, date_edited = :date_edited WHERE id = $id");
    $stmt->bindValue(':first_name', $data['first_name']);
    $stmt->bindValue(':last_name', $data['last_name']);
    $stmt->bindValue(':phone_number', $data['phone_number']);
    $stmt->bindValue(':birthday', $data['birthday']);
    $stmt->bindValue(':gender', $data['gender']);
    $stmt->bindValue(':school_id', $data['school_id']);
    $stmt->bindValue(':school_year', $data['school_year']);
    $stmt->bindValue(':city_id', $data['city']);
    $stmt->bindValue(':date_edited', date("Y-m-d H:i:s", time()));
    $stmt->execute();
}

function searchUsers($query) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM users WHERE first_name LIKE '%$query%' OR last_name LIKE '%$query%'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchEvents($query) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM events WHERE name LIKE '%$query%' OR description LIKE '%$query%'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchHelps($query) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM helps WHERE title LIKE '%$query%' OR content LIKE '%$query%'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchPosts($query) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM posts WHERE content LIKE '%$query%'");
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

function getEventsSorted() {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT events.*, users.first_name, users.last_name, users.email FROM users LEFT JOIN events 
                            ON users.id = events.admin_id WHERE events.admin_id != 'NULL' ORDER BY date_added DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEventsQueryOneParam($query_value, $column, $table_join) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT events.*, users.first_name, users.last_name, users.email, $table_join.name AS :tablejoin_name, $table_join.id AS :tablejoin_id 
                                    FROM users LEFT JOIN events ON users.id = events.admin_id INNER JOIN $table_join ON users.$column = $table_join.id 
                                    WHERE users.$column = :query_value AND events.admin_id != 'NULL' ORDER BY date_added DESC");
    $stmt->bindValue(':query_value', $query_value);
    $stmt->bindValue(':tablejoin_name', $table_join . "_name");
    $stmt->bindValue(':tablejoin_id', $table_join . "_id");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEventsQueryTwoParam($query_city, $query_school) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT events.*, users.first_name, users.last_name, users.email, cities.name AS city_name, cities.id AS city_id, schools.name AS school_name, schools.id AS school_id
                                    FROM users LEFT JOIN events ON users.id = events.admin_id INNER JOIN cities ON users.city_id = cities.id INNER JOIN schools ON users.school_id = schools.id
                                    WHERE users.city_id = :query_city AND users.school_id = :query_school AND events.admin_id != 'NULL' ORDER BY date_added DESC");
    $stmt->bindValue(':query_school', $query_school);
    $stmt->bindValue(':query_city', $query_city);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPostsSorted() {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT posts.*, users.first_name, users.last_name, users.email FROM users LEFT JOIN posts 
                            ON users.id = posts.author_id WHERE posts.author_id != 'NULL' ORDER BY date_added DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPostsQueryOneParam($query_value, $column, $table_join) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT posts.*, users.first_name, users.last_name, users.email, $table_join.name AS :tablejoin_name, $table_join.id AS :tablejoin_id 
                                    FROM users LEFT JOIN posts ON users.id = posts.author_id INNER JOIN $table_join ON users.$column = $table_join.id 
                                    WHERE users.$column = :query_value AND posts.author_id != 'NULL' ORDER BY date_added DESC");
    $stmt->bindValue(':query_value', $query_value);
    $stmt->bindValue(':tablejoin_name', $table_join . "_name");
    $stmt->bindValue(':tablejoin_id', $table_join . "_id");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPostsQueryTwoParam($query_city, $query_school) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT posts.*, users.first_name, users.last_name, users.email, cities.name AS city_name, cities.id AS city_id, schools.name AS school_name, schools.id AS school_id
                                    FROM users LEFT JOIN posts ON users.id = posts.author_id INNER JOIN cities ON users.city_id = cities.id INNER JOIN schools ON users.school_id = schools.id
                                    WHERE users.city_id = :query_city AND users.school_id = :query_school AND posts.author_id != 'NULL' ORDER BY date_added DESC");
    $stmt->bindValue(':query_school', $query_school);
    $stmt->bindValue(':query_city', $query_city);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getHelpsSorted() {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT helps.*, users.first_name, users.last_name, users.email FROM users LEFT JOIN helps 
                            ON users.id = helps.author_id WHERE helps.author_id != 'NULL' ORDER BY date_added DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getHelpsQueryOneParam($query_value, $column, $table_join) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT helps.*, users.first_name, users.last_name, users.email, $table_join.name AS :tablejoin_name, $table_join.id AS :tablejoin_id 
                                    FROM users LEFT JOIN helps ON users.id = helps.author_id INNER JOIN $table_join ON users.$column = $table_join.id 
                                    WHERE users.$column = :query_value AND helps.author_id != 'NULL' ORDER BY date_added DESC");
    $stmt->bindValue(':query_value', $query_value);
    $stmt->bindValue(':tablejoin_name', $table_join . "_name");
    $stmt->bindValue(':tablejoin_id', $table_join . "_id");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getHelpsQueryTwoParam($query_city, $query_school) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT helps.*, users.first_name, users.last_name, users.email, cities.name AS city_name, cities.id AS city_id, schools.name AS school_name, schools.id 
                                    AS school_id FROM users LEFT JOIN helps ON users.id = helps.author_id INNER JOIN cities ON users.city_id = cities.id INNER JOIN schools 
                                    ON users.school_id = schools.id WHERE users.city_id = :query_city AND users.school_id = :query_school AND helps.author_id != 'NULL' ORDER BY date_added DESC");
    $stmt->bindValue(':query_school', $query_school);
    $stmt->bindValue(':query_city', $query_city);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getHelp($help_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT helps.*, users.first_name, users.last_name, users.email FROM users LEFT JOIN helps 
                            ON users.id = helps.author_id WHERE helps.id = :id LIMIT 1");
    $stmt->bindValue(':id', $help_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getEventComments($event_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT event_comments.*, users.first_name, users.last_name, users.email FROM event_comments LEFT JOIN users
                            ON users.id = event_comments.author_id WHERE event_comments.event_id = :event_id ORDER BY date_added DESC");
    $stmt->bindValue(':event_id', $event_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPostComments($post_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT post_comments.*, users.first_name, users.last_name, users.email FROM post_comments LEFT JOIN users
                            ON users.id = post_comments.author_id WHERE post_comments.post_id = :post_id ORDER BY date_added DESC");
    $stmt->bindValue(':post_id', $post_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getHelpComments($help_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT help_answers.*, users.first_name, users.last_name, users.email FROM help_answers LEFT JOIN users
                            ON users.id = help_answers.author_id WHERE help_answers.help_id = :help_id ORDER BY date_added DESC");
    $stmt->bindValue(':help_id', $help_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getHelpComment($help_answer_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT help_answers.*, users.first_name, users.last_name, users.email FROM help_answers LEFT JOIN users 
                            ON users.id = help_answers.author_id WHERE help_answers.id = :help_answer_id LIMIT 1");
    $stmt->bindValue(':help_answer_id', $help_answer_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getEventCommentLikes($comment_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT event_comment_likes.*, users.first_name, users.last_name FROM event_comment_likes LEFT JOIN users
                            ON users.id = event_comment_likes.user_id WHERE event_comment_likes.comment_id = :comment_id");
    $stmt->bindValue(':comment_id', $comment_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getHelpLikes($help_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT help_likes.*, users.first_name, users.last_name FROM help_likes LEFT JOIN users
                            ON users.id = help_likes.user_id WHERE help_likes.help_id = :help_id");
    $stmt->bindValue(':help_id', $help_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getHelpAnswerLikes($help_answer_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT help_answers_likes.*, users.first_name, users.last_name FROM help_answers_likes LEFT JOIN users
                            ON users.id = help_answers_likes.user_id WHERE help_answers_likes.comment_id = :help_answer_id");
    $stmt->bindValue(':help_answer_id', $help_answer_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getHelpAnswerDislikes($help_answer_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT help_answers_dislikes.*, users.first_name, users.last_name FROM help_answers_dislikes LEFT JOIN users
                            ON users.id = help_answers_dislikes.user_id WHERE help_answers_dislikes.comment_id = :help_answer_id");
    $stmt->bindValue(':help_answer_id', $help_answer_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPostLikes($post_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT post_likes.*, users.first_name, users.last_name FROM post_likes LEFT JOIN users
                            ON users.id = post_likes.user_id WHERE post_likes.post_id = :post_id");
    $stmt->bindValue(':post_id', $post_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPostCommentLikes($comment_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT post_comment_likes.*, users.first_name, users.last_name FROM post_comment_likes LEFT JOIN users
                            ON users.id = post_comment_likes.user_id WHERE post_comment_likes.comment_id = :comment_id");
    $stmt->bindValue(':comment_id', $comment_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function setNewPost($data, $id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO posts (author_id, content, privacy_level) VALUES (:author_id, :content, :privacy_level)");
    $stmt->bindValue(':author_id', $id);
    $stmt->bindValue(':content', $data['content']);
    $stmt->bindValue(':privacy_level', 0);
    $stmt->execute();
}

function setNewHelp($data, $id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO helps (author_id, title, content) VALUES (:author_id, :title, :content)");
    $stmt->bindValue(':author_id', $id);
    $stmt->bindValue(':title', $data['title']);
    $stmt->bindValue(':content', $data['content']);
    $stmt->execute();
}

function addEventComment($author_id, $event_id, $data) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO event_comments (author_id, event_id, content) VALUES (:author_id, :event_id, :content)");
    $stmt->bindValue(':author_id', $author_id);
    $stmt->bindValue(':event_id', $event_id);
    $stmt->bindValue(':content', $data['content']);
    $stmt->execute();
}

function delEventComment($event_comment_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "DELETE FROM event_comments WHERE (id = :event_comment_id AND author_id = :user_id)");
    $stmt->bindValue(':event_comment_id', $event_comment_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
}

function addPostComment($author_id, $post_id, $data) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO post_comments (author_id, post_id, content) VALUES (:author_id, :post_id, :content)");
    $stmt->bindValue(':author_id', $author_id);
    $stmt->bindValue(':post_id', $post_id);
    $stmt->bindValue(':content', $data['content']);
    $stmt->execute();
}

function delPostComment($post_comment_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "DELETE FROM post_comments WHERE (id = :post_comment_id AND author_id = :user_id)");
    $stmt->bindValue(':post_comment_id', $post_comment_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
}

function addHelpAnswer($author_id, $help_id, $data) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO help_answers (author_id, help_id, content) VALUES (:author_id, :help_id, :content)");
    $stmt->bindValue(':author_id', $author_id);
    $stmt->bindValue(':help_id', $help_id);
    $stmt->bindValue(':content', $data['content']);
    $stmt->execute();
}

function delHelpAnswer($help_answer_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "DELETE FROM help_answers WHERE (id = :help_answer_id AND author_id = :user_id)");
    $stmt->bindValue(':help_answer_id', $help_answer_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
}

function addPostLike($post_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO post_likes (post_id, user_id) VALUES (:post_id, :user_id)");
    $stmt->bindValue(':post_id', $post_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
}

function delPostLike($post_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "DELETE FROM post_likes WHERE (post_id = :post_id AND user_id = :user_id)");
    $stmt->bindValue(':post_id', $post_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
}

function addPostCommentLike($post_comment_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO post_comment_likes (comment_id, user_id) VALUES (:post_comment_id, :user_id)");
    $stmt->bindValue(':post_comment_id', $post_comment_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
}

function delPostCommentLike($post_comment_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "DELETE FROM post_comment_likes WHERE (comment_id = :post_comment_id AND user_id = :user_id)");
    $stmt->bindValue(':post_comment_id', $post_comment_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
}

function addEventCommentLike($event_comment_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO event_comment_likes (comment_id, user_id) VALUES (:event_comment_id, :user_id)");
    $stmt->bindValue(':event_comment_id', $event_comment_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
}

function delEventCommentLike($event_comment_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "DELETE FROM event_comment_likes WHERE (comment_id = :event_comment_id AND user_id = :user_id)");
    $stmt->bindValue(':event_comment_id', $event_comment_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
}

function addHelpLike($help_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO help_likes (help_id, user_id) VALUES (:help_id, :user_id)");
    $stmt->bindValue(':help_id', $help_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
}

function delHelpLike($help_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "DELETE FROM help_likes WHERE (help_id = :help_id AND user_id = :user_id)");
    $stmt->bindValue(':help_id', $help_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
}

function addHelpAnswerLike($help_comment_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO help_answers_likes (comment_id, user_id) VALUES (:help_comment_id, :user_id)");
    $stmt->bindValue(':help_comment_id', $help_comment_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
}

function delHelpAnswerLike($help_comment_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "DELETE FROM help_answers_likes WHERE (comment_id = :help_comment_id AND user_id = :user_id)");
    $stmt->bindValue(':help_comment_id', $help_comment_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
}

function addHelpAnswerDislike($help_comment_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "INSERT INTO help_answers_dislikes (comment_id, user_id) VALUES (:help_comment_id, :user_id)");
    $stmt->bindValue(':help_comment_id', $help_comment_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
}

function delHelpAnswerDislike($help_comment_id, $auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare( "DELETE FROM help_answers_dislikes WHERE (comment_id = :help_comment_id AND user_id = :user_id)");
    $stmt->bindValue(':help_comment_id', $help_comment_id);
    $stmt->bindValue(':user_id', $auth_id);
    $stmt->execute();
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

function getUserJoinedEventsCalendar($auth_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT events.name, events.id, events.date, events.time, events.duration FROM events LEFT JOIN event_users ON events.id = event_users.event_id WHERE event_users.user_id = :auth_id");
    $stmt->bindValue(':auth_id', $auth_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOwnedEventsCalendar($user_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT events.name, events.id, events.date, events.time, events.duration FROM events WHERE admin_id = :admin_id");
    $stmt->bindValue(':admin_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserPosts($user_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM posts WHERE author_id = :author_id");
    $stmt->bindValue(':author_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserHelps($user_id) {
    $dbh = connectDB();
    $stmt = $dbh->prepare("SELECT * FROM helps WHERE author_id = :author_id");
    $stmt->bindValue(':author_id', $user_id);
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