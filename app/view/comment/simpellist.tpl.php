
<h2><?=$title?></h2>

<div class="clear"></div>
<table style="width:100%;" class='simpellist'>
 <tr>
   <td><b>Kommentar</b></td>
   <td><b>Rank</b></td>
   <td><b>Fråga</b></td>

 </tr>
<?php foreach ($comments as $c) : ?>

	<?php
	$rate = isset($c->getProperties()['rate']) ? $c->getProperties()['rate'] : 0;
	$text = $this->di->textFilter->doFilter($c->getProperties()['comment'], 'shortcode, markdown');
	$this->comments = new \Anax\Questions\Question();
	$this->comments->setDI($this->di);

	$id = $c->getProperties()['id'];
    $qid = $c->getProperties()['questionid'];
	$all = $this->comments->query()
	->where('id = ?')
	->execute([$qid]);
    $qheading = get_object_vars($all[0]);

	$url =  $this->url->create('questions/question'.'/'.$qid.'#'.$id);
	?>




     <tr>
       <td width='70%'><a href='<?=$url?>'<?=$text?></a></td>
	   <td><?=$rate?></td>
	   <td><?=$qheading['heading']?></td>
       <?php
       if($this->session->has('user_loggedin') && ($this->session->get('user_loggedin')['id'] ==  $c->getProperties()['user_id']))
       {
           $edit = "<td>
           <a href='". $this->url->create('comments/update/' . $c->getProperties()['id']). "'>Edit Comment <i class='fa fa-pencil'></i></a>
           </td>";
           echo $edit;
       }
        ?>
     </tr>


<?php endforeach; ?>
</table>
</div>
