<?php
require_once 'includes/header.php';
use Carbon\Carbon;
$query = isset($_GET['q']) ? $_GET['q'] : null;
$found_users = searchUsers($query);
$found_events = searchEvents($query);
$found_helps = searchHelps($query);
$found_posts = searchPosts($query);

?>

<section>
    <div class="container">
        <h2 class="text-center mt-5">Contenus comprenant votre requête : </h2>
        <h5 class="text-muted text-center">(<?php echo $query ?>)</h5>
        <div class="row text-center mt-5">
            <div class="col-3">
                <h3 class="mt-5">Personnes</h3>
                <div class="text-muted mb-5">45 personnes trouvée(s)</div>
                <ul class="list-group">
                    <li class="list-group-item">Cras justo odio</li>
                    <li class="list-group-item">Dapibus ac facilisis in</li>
                    <li class="list-group-item">Morbi leo risus</li>
                    <li class="list-group-item">Porta ac consectetur ac</li>
                    <li class="list-group-item">Vestibulum at eros</li>
                </ul>
            </div>
            <div class="col-3">
                <h3 class="mt-5">Événements</h3>
                <div class="text-muted mb-5">4 événements lié(s)</div>
                <ul class="list-group">
                    <li class="list-group-item">Cras justo odio</li>
                    <li class="list-group-item">Dapibus ac facilisis in</li>
                    <li class="list-group-item">Morbi leo risus</li>
                    <li class="list-group-item">Porta ac consectetur ac</li>
                    <li class="list-group-item">Vestibulum at eros</li>
                </ul>
            </div>
            <div class="col-3">
                <h3 class="mt-5">Questions</h3>
                <div class="text-muted mb-5">3 question(s) en rapport</div>
                <ul class="list-group">
                    <li class="list-group-item">Cras justo odio</li>
                    <li class="list-group-item">Dapibus ac facilisis in</li>
                    <li class="list-group-item">Morbi leo risus</li>
                    <li class="list-group-item">Porta ac consectetur ac</li>
                    <li class="list-group-item">Vestibulum at eros</li>
                </ul>
            </div>
            <div class="col-3">
                <h3 class="mt-5">Publications</h3>
                <div class="text-muted mb-5">14 publication(s) la mentionnant</div>
                <ul class="list-group">
                    <li class="list-group-item">Cras justo odio</li>
                    <li class="list-group-item">Dapibus ac facilisis in</li>
                    <li class="list-group-item">Morbi leo risus</li>
                    <li class="list-group-item">Porta ac consectetur ac</li>
                    <li class="list-group-item">Vestibulum at eros</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
