<?php

require_once 'includes/header.php';
use Carbon\Carbon;
$errored = isset($_GET['errored']) ? $_GET['errored'] : null;

?>
<section>
        <div class="mt-5" style="padding-top: 60px!important;">
            <h2 class="text-center mt-5">Êtes-vous :</h2>
            <div class="d-flex mt-5 text-center mx-0">
                <a href="register.php" class="nav-link mt-5 p-5 bg-info text-white font-weight-bold mx-4 w-50" style="border-radius: 15px">Un étudiant ?</a>
                <a href="registerbrand.php" class="nav-link mt-5 p-5 bg-secondary text-white font-weight-bold mx-4 w-50" style="border-radius: 15px">Une entreprise ?</a>
            </div>
        </div>
</section>

<?php require_once 'includes/footer.php'; ?>
