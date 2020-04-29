<?php
require_once 'includes/header.php';
$id = isset($_GET['id']) ? $_GET['id'] : null;
?>

<section>
    <div class="container">
        <a href="login.php" class="btn btn-success">login</a>
        <a href="register.php" class="btn btn-info">register</a>
        <a href="assets/logout.php" class="btn btn-danger">Déconnexion</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>