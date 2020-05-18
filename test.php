<?php
require_once 'includes/header.php';

$array = [5,6,4,7,8,9]
?>
<section>
    <div class="container" style="margin-top: 85px">
        <?php for ($i = 1; $i <= count($array); $i++) {
            echo $i;
        } ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>


