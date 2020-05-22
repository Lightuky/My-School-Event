<?php
require_once 'vendor/autoload.php';
require_once 'config.php';
require_once 'helpers.php';
session_start();
/*include 'debug.php';*/
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>My School Event</title>
    <link rel="stylesheet" href="./public/css/app.css">
    <link rel="icon" type="image/png" href="img/mse.png">
    <script src="https://kit.fontawesome.com/7f8ca92050.js" crossorigin="anonymous"></script>
</head>
<body>
<div id="app">
    <header id="header" style="height: 60px; z-index: 999; top: 0" class="linear-gradient position-fixed w-100 justify-content-between d-none">
        <a href="index.php"><img src="img/mse.png" alt="logo" style="height: 70px; width: 70px;" class="pb-1"></a>
        <form class="form-inline mr-2" method="post" action="assets/search.php">
            <input class="form-control mr-2" type="search" name="search" id="search" minlength="2" aria-label="Search">
            <button class="btn my-2" type="submit"><i class="fas fa-search text-white"></i></button>
        </form>
    </header>
    <main id="main">