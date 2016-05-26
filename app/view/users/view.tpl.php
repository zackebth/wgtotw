 <?php
$status = null;
	if ($user->getProperties()['active'] == null) {
		  $faclass = "fa fa-user";
		  $class = "user-inactive";
		  $status = "inaktiv";
		}
		else {
		  $faclass = "fa fa-user";
		  $class = "user-active";
		 $status = "aktiv";
		}
        $question = get_object_vars($user->count_user_questions($user->getProperties()['id'])[0]);
        $totalcomments = get_object_vars($user->count_user_comments($user->getProperties()['id'])[0]);
        $totalcomments = $totalcomments['total'];
        $qu = $user->get_latest_questions();
        $cu = $user->get_latest_comments();

?>

<div id="profil-container">


<!--
 == Check if out user is active.
 -->
<?php if ($user->getProperties()['deleted'] != null) : ?>
    <h1>Denna användaren är inte tillgänlig, kanske är den tillfälligt borttagen?</h1>
    <p>Gå tillbaka till användarlistan: <a href="<?=$this->url->create('users/list')?>"><i class="fa fa-arrow-right"></i></a></p>
<?php else: ?>

<div class='user-info'>
    <h3><?=$user->getProperties()['name']?>
        <?php if($this->session->get('user_loggedin')['id'] == $user->getProperties()['id'] ) : ?>
        <span class='right'>
            <a href="<?=$this->url->create('users/update').'/'.$user->getProperties()['id']?>" title='Ändra'>Redigera <i class="fa fa-pencil"></i></a>
            |
            <a href="<?=$this->url->create('logout')?>" title='Ändra'> Logga ut <i class="fa fa-sign-out"></i></a>
        </span>
        <?php endif; ?>
    </h3>


    <div class='img-section'>
        <img src="<?=$user->get_gravatar(80);?>" class='left' alt='<?=$user->getProperties()['name'];?>' title='<?=$user->getProperties()['name'];?>'>

        <p><small><?=$user->getProperties()['name']?> (<?=$user->get_rank()?> <?=$user->getProperties()['rank']?>p) </small></p>
        <p><small>Medlem sedan: <?=$user->getProperties()['created']?> </small></p>
    </div>


	<p> <b>Användarnamn:</b> <?=$user->getProperties()['acronym']?> </p>
    <p> <b>Email:</b> <?=$user->getProperties()['email']?> </p>

	<span class="<?=$class?>"><i class="<?=$faclass?>"></i></span> Du är <?=$status?>.
    </p>
</div>

    <!--
     ## Display latest Questions and Comments.
     -->
<div class='user-latest'>
    <?php
    $lq = "<ul class='latest'>";
         for($i = 0; $i < sizeof($qu); $i++) {

             $this->comments = new \Anax\Comments\Comment();
             $this->comments->setDI($this->di);
             $id = get_object_vars($qu[$i])['id'];
             $all = $this->comments->query()
             ->where('questionid = ?')
             ->andWhere('citeid is null')
             ->execute([$id]);
             $totalquestions = count($all);
             $url =  $this->url->create('questions/question'.'/'.get_object_vars($qu[$i])['id']);

             $lq .= "<li><a href='". $url . "'>" . strip_tags(get_object_vars($qu[$i])['heading']) . " <span class='right'><i class='fa fa-comments'></i>". $totalquestions ." </span></a> </li>";
         }
    $lq .= "</ul>";


    $lc = "<ul class='latest'>";
         for($i = 0; $i < sizeof($cu); $i++) {

             $url =  $this->url->create('questions/question'.'/'.get_object_vars($cu[$i])['questionid']);

             $lc .= "<li><a href='". $url . "'>" . strip_tags(get_object_vars($cu[$i])['comment']) . "</a> </li>";
         }
    $lc .= "</ul>"


     ?>
     <h3>Senaste Frågorna <span class='right'><a href='<?=$this->url->create('questions/user'.'/'.$user->getProperties()['id'])?>' title='Antal Kommentarer'>Se alla (<?=$question['total']?>)</a></span></h3>
     <?=$lq?>

     <h3>Senaste Kommentarerna <span class='right'><a href='<?=$this->url->create('comments/user'.'/'.$user->getProperties()['id'])?>' title='Antal Kommentarer'>Se alla (<?=$totalcomments?>)</a></span></h3>
     <?=$lc?>
</div>

<?php endif; ?>

</div>
