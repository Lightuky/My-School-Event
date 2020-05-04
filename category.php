<?php
require_once 'includes/header.php';
use Carbon\Carbon;
$cat_id = isset($_GET['id']) ? $_GET['id'] : null;
$category = getCategory($cat_id);
$cat_events = getCategoryEvents($cat_id);

?>

<section>
    <div class="container">
        <h2 class="text-center mt-5"><?php echo $category['name'] ?></h2>
        <div class="row mt-5">
            <?php foreach ($cat_events as $cat_event) { ?>
                <div class="card col-2 text-center py-4 d-flex m-auto">
                    <div class="card-body">
                        <a class="h5 nav-link" href="event.php?id=<?php echo $cat_event['id'] ?>"><?php echo $cat_event['name'] ?></a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
