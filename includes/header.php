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
</head>
<body>
<div id="app">
    <header id="header">
        <a href="index.php" class="btn btn-warning">My School Event</a>
    </header>
    <main id="main">