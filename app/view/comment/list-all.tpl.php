<?php
$commentstotal = count($comments);
if($orderby == 'rate') {
	$newest = null;
	$votes = "class='selected'";
} else {
	$newest = "class='selected'";
	$votes = null;
}
$tagurl = isset($tag) ? '/' . $tag : null;
 ?>

<div id='questions'>
	<div class='top'>
		<p><?=$commentstotal . " " . $title?></p>
		<div class='menu'>
			<a href='<?=$this->url->create('questions/question/'.$pageid.'/created' . $tagurl)?>' <?=$newest?>>Newest</a>
			<a href='<?=$this->url->create('questions/question/'.$pageid.'/rate' . $tagurl)?>' <?=$votes?>>Votes</a>
			<a href='#answer'>Kommentera <i class="fa fa-plus"></i></a>
		</div>
		<div class='clear'></div>
</div>

<?php foreach ($comments as $c) : ?>

	<?php
	//gmdate('Y-m-d H:i:s');
	echo $c->getProperties()['citeid'];
	$author = $user->find($c->getProperties()['user_id']);
	$rank = $author->get_rank();
	$date = new DateTime($c->getProperties()['created']);
	$date = date_format($date, "M j, Y, H:i");
	$text = $this->di->textFilter->doFilter($c->getProperties()['comment'], 'shortcode, markdown');
	?>

<div class='question-wrap' id='<?=$c->getProperties()['id']?>'>
		<div class='vote'>
			<p><a href='<?=$this->url->create('comments/plusone/' . $c->getProperties()['user_id'] . '/'. $c->getProperties()['questionid'] . '/' . $c->getProperties()['id'])?>' class='up'> <i class="fa fa-caret-up"></i></a></p>
			<p> <?=$c->getProperties()['rate'] ?> </p>
			<p><a href='<?=$this->url->create('comments/minusone/' . $c->getProperties()['user_id'] . '/'. $c->getProperties()['questionid'] . '/' . $c->getProperties()['id'])?>' class='down'><i class="fa fa-caret-down"></i></a></p>
		</div>

		<div class='question-single'>
			<div class='content-single'>
				<?=$text ?>
			</div>
		</div>

		<div class='clear'></div>
			<div class='author-section'>
				<p>
					<img src="<?=$author->get_gravatar(20);?>" alt='<?=$author->getProperties()['name'];?>' title='<?=$author->getProperties()['name'];?>'>
					<small><a href="<?=$this->url->create('users/id/' . $c->getProperties()['user_id'])?>"> <?=$author->getProperties()['name'];?></a>
					</small>
					<?php
					if($this->session->has('user_loggedin') && ($this->session->get('user_loggedin')['id'] ==  $c->getProperties()['user_id']))
					{
						$edit = " | <small>
						<a href='". $this->url->create('comments/update/' . $c->getProperties()['id']). "'>Edit Comment <i class='fa fa-pencil'></i></a>
						</small>";
						echo $edit;
					}
					 ?>
					<span class='right'><a href='<?=$this->url->create('comments/answer/' . $c->getProperties()['questionid'] . '/' . $c->getProperties()['id'] )?>'>
						<small>Svara på denna kommentar <i class="fa fa-reply"></i> </small> </a></span>
				</p>
				<p><small><?=$rank?></small></p>
			</div>
	<div class='clear'></div>


	<?php if(!empty($cite)) : ?>
	<ul class='doubleanswer'>
		<?php foreach ($cite as $ci) : ?>
			<?php $citeauthor = $user->find($ci->getProperties()['user_id']); ?>
			  <?php	if($ci->getProperties()['citeid'] == $c->getProperties()['id'] ) :	?>
					<li id='<?=$ci->getProperties()['id']?>'>
						<small><?=$citeauthor->getProperties()['name'];?>:
							<?php
							if($this->session->has('user_loggedin') && ($this->session->get('user_loggedin')['id'] ==  $ci->getProperties()['user_id']))
							{
								$edit = "<span class='right'>
								<a href='". $this->url->create('comments/update/' . $ci->getProperties()['id']). "'>Edit Comment <i class='fa fa-pencil'></i></a>
								</span>";
								echo $edit;
							}
							 ?>

							 <?=$ci->getProperties()['comment'] ?>

					 </small>
					</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>

</div>
<?php endforeach; ?>


</div>
