<?php
require_once 'includes/header.php';
use Carbon\Carbon;

date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.UTF8');
$nav_month = isset($_GET['m']) ? $_GET['m'] : null;
$nav_year = isset($_GET['y']) ? $_GET['y'] : null;
$events = [];
require_once 'calendarclass.php';

if (isset($_SESSION['auth_id'])):
    $owned_events = getOwnedEventsCalendar($_SESSION['auth_id']);
    $joined_events = getUserJoinedEventsCalendar($_SESSION['auth_id']);

    foreach ($joined_events as $joined_event):
        array_push($owned_events, $joined_event);
    endforeach;
else:
    $pathError =  "/mse/index.php";
    header('Location: '. $pathError);
endif;

?>

<section>
    <div class="container" style="margin-top: 50px;">
        <?php foreach ($owned_events as $owned_event):
            $events[$owned_event['date']] = ["text" => $owned_event["name"], "href" => "event.php?id=" . $owned_event["id"]];
        endforeach;
        if ($nav_month AND $nav_year): ?>
            <div class="d-flex justify-content-center pt-2">
                <a href="calendar.php?m=<?php echo ($nav_month-1) == 0 ? "12" : ($nav_month-1) ?>&y=<?php echo ($nav_month-1) == 0 ? ($nav_year-1) : $nav_year ?>" class="nav-link text-info h3 my-auto ml-3">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <div class="text-center h2 my-4"><?php echo ucfirst(strftime("%B", strtotime(date("F", mktime(0, 0, 0, $nav_month, 10))))) . " " . $nav_year ?></div>
                <a href="calendar.php?m=<?php echo ($nav_month+1) == 13 ? "1" : ($nav_month+1) ?>&y=<?php echo ($nav_month+1) == 13 ? ($nav_year+1) : $nav_year ?>" class="nav-link text-info h3 my-auto ml-3">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <?php echo build_html_calendar($nav_year, $nav_month, $events);
        else: ?>
            <div class="d-flex justify-content-center pt-2">
                <a href="calendar.php?m=<?php echo (date('m')-1) == 0 ? "12" : (date('m')-1) ?>&y=<?php echo (date('m')-1) == 0 ? (date('Y')-1) : date('Y') ?>" class="nav-link text-info h3 my-auto mr-3">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <div class="text-center h2 my-4"><?php echo ucfirst(strftime("%B %Y", time())) ?></div>
                <a href="calendar.php?m=<?php echo (date('m')+1) == 13 ? "1" : (date('m')+1) ?>&y=<?php echo (date('m')+1) == 13 ? (date('Y')+1) : date('Y') ?>" class="nav-link text-info h3 my-auto ml-3">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <?php echo build_html_calendar(date('Y'), date('m'), $events);
        endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
