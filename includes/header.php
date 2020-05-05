<?php
require_once 'vendor/autoload.php';
require_once 'config.php';
require_once 'helpers.php';
session_start();
//include 'debug.php';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>My School Event</title>
    <link rel="stylesheet" href="./public/css/app.css">
    <script src="https://kit.fontawesome.com/7f8ca92050.js" crossorigin="anonymous"></script>
</head>
<body>
<div id="app">
    <header id="header" style="height: 60px; z-index: 999; top: 0" class="linear-gradient position-fixed w-100">

    </header>
    <main id="main">