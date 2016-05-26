<div class='clear'></div>
<div id="widget">
<div class='left'>

<?php
$tList = "<ul>";
$r = 1;
foreach ($users as $u) {
    $tList .= "<li class=''>(".$r.") <a href=" . $this->url->create('users/id/' . $u['id']) . ">" . $u['name'] . "<i class='fa fa-arrow-right right'></i></a></li>";
    $r++;
}
$tList .= "</ul>";
 ?>
<h3><?=$title?></h3>
<?=$tList?>
</div>
</div>
