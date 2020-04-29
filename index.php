<?php
require_once 'includes/header.php';
use Carbon\Carbon;

$users = getUsers();

?>

<section>
    <div class="container">
        <?php foreach ($users as $user) {
            echo $user['phone_number'];
        } ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
