<?php if (is_array($comments)) : ?>
<div class='comments'>
<h3>Kommentarer</h3>
<?php foreach ($comments as $id => $comment) : ?>
<?php if (!empty($comment['name']) || !empty($comment['content'])) :  ?>


<?php
$date = date_create();
date_timestamp_set($date, $comment['timestamp']);
$dateis = date_format($date, 'Y-m-d H:i') . "\n";
?>


<div class='comment'>


<p class='name'>
	<?php if (!empty($comment['web'])) :  ?>
	<a href='http://<?=$comment['web'] ?>'><?=$comment['name'] ?></a>
	<?php else : ?>
	<?=$comment['name'] ?>
	<?php endif; ?>

</p>
<p class='date'><?=$dateis?></p>

<div class='clear'></div>
<p><?=$comment['mail']?> </p>
<p class='content'><?=$comment['content'] ?></p>
	
	<div class='right'>
	<form method=post>
		<input type=hidden name="id" value="<?=$id?>">
		<input type=hidden name="redirect" value="<?=$this->url->create($comment['redirect'])?>">
		<input type=hidden name="session" value="<?=$comment['session']?>">
		<button type='submit' name='doRemoveOne' title='Remove' value='Remove Comment' onClick="this.form.action = '<?=$this->url->create('comment/remove-one')?>'"> <i class="fa fa-trash-o"></i></button>
		<button type='submit' name='doEditComment' title='Edit' value='Edit Comment' onClick="this.form.action = '<?=$this->url->create('comment/edit-comment')?>'"> <i class="fa fa-edit"></i></button>
	</form>
	</div>

</div>
<?php endif; ?>
<?php endforeach; ?>

</div>
<?php endif; ?> 
