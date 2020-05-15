<?php
require_once 'includes/header.php';
use Carbon\Carbon;
$query = isset($_GET['q']) ? $_GET['q'] : null;
$found_users = searchUsers($query);
$found_events = searchEvents($query);
$found_helps = searchHelps($query);
$found_posts = searchPosts($query);
?>

<section style="margin-top: 90px">
    <div class="mt-5">
        <h2 class="text-center mt-5">Contenus comprenant votre requête : </h2>
        <h5 class="text-muted text-center">(<?php echo $query ?>)</h5>
        <div class="row text-center mt-5 w-100 mx-auto">
            <div class="col-3">
                <h3 class="mt-5">Personnes</h3>
                <div class="text-muted mb-5"><?php echo (empty($found_users) ? "Aucune personne trouvée" : count($found_users) . " personne(s) trouvée(s)") ?></div>
                <ul class="list-group">
                    <?php foreach ($found_users as $found_user):
                        if (stristr($found_user['first_name'], $query)):
                            $text_pre_query = substr($found_user['first_name'], (stripos($found_user['first_name'], $query) - 10), stripos($found_user['first_name'], $query));
                            $text_post_query = substr($found_user['first_name'], (stripos($found_user['first_name'], $query) + strlen($query) )); ?>
                            <a href="profile.php?id=<?php echo $found_user['id'] ?>" class="list-group-item text-truncate nav-link text-secondary">
                                <?php echo $text_pre_query ?><span style="color: red"><?php echo $query ?></span><?php echo $text_post_query . " " . $found_user['last_name']; ?>
                            </a>
                        <?php else:
                            $text_pre_query = substr($found_user['last_name'], (stripos($found_user['last_name'], $query) - 10), stripos($found_user['last_name'], $query));
                            $text_post_query = substr($found_user['last_name'], (stripos($found_user['last_name'], $query) + strlen($query) )); ?>
                            <a href="profile.php?id=<?php echo $found_user['id'] ?>" class="list-group-item text-truncate nav-link text-secondary">
                                <?php echo $found_user['first_name'] . " " . $text_pre_query ?><span style="color: red"><?php echo $query ?></span><?php echo $text_post_query; ?>
                            </a>
                        <?php endif;
                    endforeach; ?>
                </ul>
            </div>
            <div class="col-3">
                <h3 class="mt-5">Événements</h3>
                <div class="text-muted mb-5"><?php echo (empty($found_events) ? "Aucun événement trouvé" : count($found_events) . " événement(s) lié(s)") ?></div>
                <ul class="list-group">
                    <?php foreach ($found_events as $found_event):
                        if (stristr($found_event['name'], $query)):
                            $text_pre_query = substr($found_event['name'], (stripos($found_event['name'], $query) - 10), stripos($found_event['name'], $query));
                            $text_post_query = substr($found_event['name'], (stripos($found_event['name'], $query) + strlen($query) )); ?>
                            <a href="event.php?id=<?php echo $found_event['id'] ?>" class="list-group-item text-truncate nav-link text-secondary">
                                <?php echo $text_pre_query ?><span style="color: red"><?php echo $query ?></span><?php echo $text_post_query; ?>
                            </a>
                        <?php else:
                            $text_pre_query = substr($found_event['description'], (stripos($found_event['description'], $query) - 10), stripos($found_event['description'], $query));
                            $text_post_query = substr($found_event['description'], (stripos($found_event['description'], $query) + strlen($query) )); ?>
                            <a href="event.php?id=<?php echo $found_event['id'] ?>" class="list-group-item text-truncate nav-link text-secondary">
                                <?php echo $text_pre_query ?><span style="color: red"><?php echo $query ?></span><?php echo $text_post_query; ?>
                            </a>
                        <?php endif;
                    endforeach; ?>
                </ul>
            </div>
            <div class="col-3">
                <h3 class="mt-5">Questions</h3>
                <div class="text-muted mb-5"><?php echo (empty($found_helps) ? "Aucune question trouvée" : count($found_helps) . " question(s) en rapport") ?></div>
                <ul class="list-group">
                    <?php foreach ($found_helps as $found_help):
                        if (stristr($found_help['title'], $query)):
                            $text_pre_query = substr($found_help['title'], (stripos($found_help['title'], $query) - 10), stripos($found_help['title'], $query));
                            $text_post_query = substr($found_help['title'], (stripos($found_help['title'], $query) + strlen($query) )); ?>
                            <a href="help.php?id=<?php echo $found_help['id'] ?>" class="list-group-item text-truncate nav-link text-secondary">
                                <?php echo $text_pre_query ?><span style="color: red"><?php echo $query ?></span><?php echo $text_post_query; ?>
                            </a>
                        <?php else:
                            $text_pre_query = substr($found_help['content'], (stripos($found_help['content'], $query) - 8), stripos($found_help['content'], $query));
                            $text_post_query = substr($found_help['content'], (stripos($found_help['content'], $query) + strlen($query) )); ?>
                            <a href="help.php?id=<?php echo $found_help['id'] ?>" class="list-group-item text-truncate nav-link text-secondary">
                                <?php echo $text_pre_query ?><span style="color: red"><?php echo $query ?></span><?php echo $text_post_query; ?>
                            </a>
                        <?php endif;
                    endforeach; ?>
                </ul>
            </div>
            <div class="col-3 pr-0">
                <h3 class="mt-5">Publications</h3>
                <div class="text-muted mb-5"><?php echo (empty($found_posts) ? "Aucun post trouvé" : count($found_posts) . " publication(s) la mentionnant") ?></div>
                <ul class="list-group">
                    <?php foreach ($found_posts as $found_post):
                        $text_pre_query = substr($found_post['content'], (stripos($found_post['content'], $query) - 8), stripos($found_post['content'], $query));
                        $text_post_query = substr($found_post['content'], (stripos($found_post['content'], $query) + strlen($query) )); ?>
                        <a href="index.php" class="list-group-item text-truncate nav-link text-secondary">
                            <?php echo $text_pre_query ?><span style="color: red"><?php echo $query ?></span><?php echo $text_post_query; ?>
                        </a>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
