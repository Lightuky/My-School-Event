<?php
require_once 'includes/header.php';

$title = $_POST['Title'];
$message = $_POST['message'];
$email = $_POST['email'];

$retour = mail('antoinemourat@gmail.com', $title, $message, $email);
if ($retour) {?>


<?php }
?>

