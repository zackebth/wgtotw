<?php

$tagurl = isset($tag) ? '/' . $tag : null;
?>
<div id='questions'>
	<div class='top'>
		<h2><?=$title?></h2>
		<div class='clear'></div>
</div>

<div class="clear"></div>
<?php foreach ($comments as $c) : ?>

	<?php
	//gmdate('Y-m-d H:i:s');
	$author = $user->find($c->getProperties()['user_id']);
	$date = new DateTime($c->getProperties()['created']);
	$date = date_format($date, "M j, Y, H:i");
	$tags = explode(',', $c->getProperties()['tags']);
	$userCount = $user->count_user_questions($author->getProperties()['id']);
	$rate = isset($c->getProperties()['rate']) ? $c->getProperties()['rate'] : 0;
	$text = $this->di->textFilter->doFilter($c->getProperties()['description'], 'shortcode, markdown');
	?>
<div class='question-wrap qs'>
	<div class='vote'>
		<p><a href='<?=$this->url->create('questions/plusone/' . $c->getProperties()['user_id'] . '/'. $c->getProperties()['id'])?>' class='up'> <i class="fa fa-caret-up"></i></a></p>
		<p> <?=$rate?> </p>
		<p><a href='<?=$this->url->create('questions/minusone/' . $c->getProperties()['user_id'] . '/'. $c->getProperties()['id'])?>' class='down'><i class="fa fa-caret-down"></i></a></p>
	</div>


	<div class='question-single'>
		<div class='content-single'>
			<?=$text?>
		</div>


		<p class='tags'>
			<?php foreach ($tags as $tag) : ?>
			<a href="<?=$this->url->create('questions/list-All/created/' . strtolower($tag))?>"><?=$tag?></a>
			<?php endforeach; ?>
		</p>
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
				<a href='". $this->url->create('questions/update/' . $c->getProperties()['id']). "'>Edit Question <i class='fa fa-pencil'></i></a>
				</small>";
				echo $edit;
			}
			 ?>
			</p>
			<p><small>
			 <?php foreach ($userCount as $uc) : ?>
			<?=$uc->getProperties()['total']?>
			<?php endforeach; ?>
			frågor ställda.
		</small>
		</p>
	</div>

<div class='clear'></div>
</div>
<?php endforeach; ?>
</div>
