<?php
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
		<p><?=$title?></p>
		<div class='menu'>
			<a href='<?=$this->url->create('questions/list-All/created' . $tagurl)?>' <?=$newest?>>Newest</a>
			<a href='<?=$this->url->create('questions/list-All/rate' . $tagurl)?>' <?=$votes?>>Votes</a>
			<a href='<?=$this->url->create('questions/ask')?>'>Ställ fråga <i class="fa fa-plus"></i></a>
		</div>
		<div class='clear'></div>
	</div>

<div class="clear"></div>
<?php foreach ($comments as $c) : ?>

	<?php
	//gmdate('Y-m-d H:i:s');
	$author = $user->find($c->getProperties()['user_id']);
	$userCount = $user->count_user_questions($author->getProperties()['id']);
	$date = new DateTime($c->getProperties()['created']);
	$date = date_format($date, "M j, Y, H:i");
	$tags = explode(',', $c->getProperties()['tags']);
	$rate = isset($c->getProperties()['rate']) ? $c->getProperties()['rate'] : 0;
	$text = $this->di->textFilter->doFilter($c->getProperties()['description'], 'shortcode, markdown');

	$this->comments = new \Anax\Comments\Comment();
	$this->comments->setDI($this->di);
	$id = $c->getProperties()['id'];
	$all = $this->comments->query()
	->where('questionid = ?')
	->andWhere('citeid is null')
	->execute([$id]);
	$totalcomments = count($all);

	?>
<div class='question-wrap'>
	<div class='stats'>
		<p> <i class="fa fa-comments"></i> <?=$totalcomments ?> </p>
		<p> <i class="fa fa-thumbs-up"></i> <?=$rate ?> </p>
	</div>

	<div class='question'>

	<div class='heading'>
		<a href="<?=$this->url->create('questions/question/' . $c->getProperties()['id']) ?>"><?=$c->getProperties()['heading'] ?></a>
	</div>



	<div class='content'>
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


</div>
<div class='clear'></div>
<?php endforeach; ?>
</div>
