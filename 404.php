<?php
require_once 'includes/header.php';
use Carbon\Carbon;

?>

<section>
    <div class="container">
        <div class="card text-center mt-5">
            <div class="card-header">
                <h3 class="card-title">Aucun contenu ne correspond à votre requête</h3>
            </div>
            <div class="card-body">
                <p class="card-text">N'hésitez pas à utiliser notre barre de recherche pour plus de résultats !</p>
            </div>
            <div class="card-footer text-muted mx-auto">
                <form class="form-inline" method="post" action="assets/search.php">
                    <input class="form-control mr-2" type="search" name="search" id="search" aria-label="Search">
                    <button class="btn btn-outline-success my-2" type="submit">Rechercher</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
