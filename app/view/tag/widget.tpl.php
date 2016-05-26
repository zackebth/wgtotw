<div id="widget">
<div class='right'>

<?php
$tList = "<ul>";
foreach ($latest_tags as $tag) {
    $tag = get_object_vars($tag);
    $tList .= "<li class='tags'><a href=" . $this->url->create('questions/list-All/rate/' . $tag['name']) . ">" . $tag['name'] . "<i class='fa fa-arrow-right right'></i></a></li>";
}
$tList .= "</ul>";
 ?>
<h3><?=$title?></h3>
<?=$tList?>
</div>
</div>
