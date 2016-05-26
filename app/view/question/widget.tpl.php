<div id="widget">
<div class="left">

<?php
$qList = "<ul>";
foreach ($latest_questions as $q) {
    $q = get_object_vars($q);
    $ui = $user->get_information($q['user_id']);
    $comments = $comment->count_comments($q['id']);
    $qList .= "<li><span class='answercount'>" . $comments. " svar</span>" . "<span class='answercount'>" . $q['rate']. " rank</span>
    <a href=" . $this->url->create('questions/question/' . $q['id']) . ">" . $q['heading'] . "<i class='fa fa-arrow-right right'></i></a>";
    //<span class='by'> av " . $ui['acronym'] . "</span></li>";
}
$qList .= "</ul>";
 ?>

<h3><?=$title?></h3>
<?=$qList?>
</div>
</div>
