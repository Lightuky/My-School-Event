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
        <div class="d-flex  justify-content-between">
            <a href="index.php" class="btn btn-warning">My School Event</a>
            <?php if (!isset($_SESSION['auth_id'])) {?>
                <a href="login.php" class="btn btn-secondary">Ajouter un event</a>
            <?php }
            else { ?>
                <a href="addevent.php" class="btn btn-secondary">Ajouter un event</a>
            <?php } ?>
            <div>
                <?php if (!isset($_SESSION['auth_id'])) {?>
                <a href="login.php" class="btn btn-success">Se connecter</a>
                <?php }
                else { ?>
                    <a href="profile.php?id=<?php echo $_SESSION['auth_id'] ?>" class="btn btn-info">Mon profil</a>
                    <a href="assets/logout.php" class="btn btn-danger">Déconnexion</a><?php
                } ?>
            </div>
        </div>
    </header>
    <main id="main">