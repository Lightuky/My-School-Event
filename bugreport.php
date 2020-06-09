<?php
require_once 'includes/header.php';
use Carbon\Carbon;
?>


<div class="display-none-mobile">
    <div class="col-2 m-0 p-0 bg-dark d-flex flex-column justify-content-between position-fixed" style="height: calc(100vh - 60px); bottom: 0;">
        <div>
            <a href="index.php" class="text-white nav-link border py-3  border-left-0">Acceuil</a>
            <?php if (!isset($_SESSION['auth_id'])): ?>
                <a href="login.php" class="text-white nav-link border py-3 mt-5 border-left-0">Se connecter</a>
                <a href="login.php" class="text-white nav-link border py-3 border-left-0">Calendrier</a>
                <a href="login.php" class="text-white nav-link border py-3 border-left-0">Signaler un problème</a>
                <a href="login.php" class="text-white nav-link border py-3 border-left-0">Mes amis</a>
                <a href="login.php" class="text-white nav-link border py-3 border-left-0">Progression</a>
            <?php else: ?>
                <a href="profile.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 mt-5 border-left-0">Mon profil</a>
                <a href="calendar.php" class="text-white nav-link border py-3 border-left-0">Calendrier</a>
                <a href="bugreport.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 border-left-0">Signaler un problème</a>
                <a href="friends.php?id=<?php echo $_SESSION['auth_id'] ?>" class="text-white nav-link border py-3 border-left-0">Mes amis</a>
                <a href="progress.php" class="text-white nav-link border py-3 border-left-0">Progression</a>
            <?php endif; ?>
        </div>
        <?php if (isset($_SESSION['auth_id'])): ?>
            <div class="">
                <a href="assets/logout.php" class="text-white nav-link border py-3 mt-5 border-left-0" style="background-color: rgba(206, 130, 299, 0.3)">Supprimer mon compte</a>
                <a href="assets/logout.php" class="bg-white text-dark font-weight-bold nav-link border py-3 border-left-0">Déconnexion</a>
            </div>
        <?php endif; ?>
    </div>

</div>


<section style="margin-left: 15%">
<h1 class="ml-5" style="margin-top: 100px;">Signaler un problème</h1>
<p class="ml-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
    dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<form method="post" action="mail.php" class="m-5">
    <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
    </div>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Titre</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="1" name="Title"></textarea>
    </div>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Votre message</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="message"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Envoyer</button>
</form>
</section>

<?php require_once 'includes/footer.php'; ?>
