
<div id='tags-section'>
	<div class='top'>
		<p><?=$title?></p>

		<div class='clear'></div>
	</div>

<div class="clear"></div>
<?php foreach ($comments as $c) : ?>

	<?php
	//gmdate('Y-m-d H:i:s');
	//$author = $user->find($c->getProperties()['user_id']);
	$date = new DateTime($c->getProperties()['created']);
	$date = date_format($date, "M j, Y, H:i");
	$rate = isset($c->getProperties()['rate']) ? $c->getProperties()['rate'] : 0;
	?>

<div class='tag left'>

	<p class='tags'>
		<a href="<?=$this->url->create('questions/list-All/rate/' . strtolower($c->getProperties()['name'])) ?>"><?=$c->getProperties()['name'] ?></a> <small> x <?=$c->getProperties()['rate'] ?></small>
		<?php if($this->session->has('user_loggedin') && $this->session->get('user_loggedin')['rank'] > 99) :?>
		<span class="right">
			<a href="<?=$this->url->create('tags/update/' . $c->getProperties()['id']) ?>">Edit <i class='fa fa-pencil'></i></a>
		</span>
		<?php endif;?>
	</p>

	<p class='content'>
		<?=$c->getProperties()['description'] ?>
	</p>
</div>



<?php endforeach; ?>
</div>
<div class='clear'></div>
