<?php
require_once 'includes/header.php';

$query_city = "1";
$table_join = "cities";
$query_column = "city_id";
$events = getEventsQuery($query_city,$query_column, $table_join);



?>
<section>
    <div class="container" style="margin-top: 85px">
        <?php var_dump($events); ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>


