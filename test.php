<?php
require_once 'includes/header.php';

$event_members_sorted = getEventMembersSorted(1);
$event_infos = getEvent(1);

if ($event_infos['member_limit'] < count($event_members_sorted)) {
    $event_members_kicked = array_slice($event_members_sorted, $event_infos['member_limit']);
    $event_members_safe = array_slice($event_members_sorted, 0, $event_infos['member_limit']);
    var_dump("Gens safe : ",$event_members_safe);
    var_dump("Gens kicked : ",$event_members_kicked);
}


?>
<section>
    <div class="container">

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>


