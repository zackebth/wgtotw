
<h2><?=$title?></h2>

<div class="clear"></div>
<table style="width:100%;" class='simpellist'>
 <tr>
   <td><b>Fråga</b></td>
   <td><b>Rank</b></td>
   <td><b>Antal svar</b></td>

 </tr>
<?php foreach ($comments as $c) : ?>

	<?php
	$rate = isset($c->getProperties()['rate']) ? $c->getProperties()['rate'] : 0;
	$text = $this->di->textFilter->doFilter($c->getProperties()['description'], 'shortcode, markdown');
	$this->comments = new \Anax\Comments\Comment();
	$this->comments->setDI($this->di);

	$id = $c->getProperties()['id'];
	$all = $this->comments->query()
	->where('questionid = ?')
	->andWhere('citeid is null')
	->execute([$id]);

	$antal = count($all);
	$url =  $this->url->create('questions/question'.'/'.$id);
	?>




     <tr>
       <td width='70%'><a href='<?=$url?>'<?=$text?></a></td>
	   <td><?=$rate?></td>
	   <td><?=$antal?></td>
       <?php
       if($this->session->has('user_loggedin') && ($this->session->get('user_loggedin')['id'] ==  $c->getProperties()['user_id']))
       {
           $edit = "<td>
           <a href='". $this->url->create('questions/update/' . $c->getProperties()['id']). "'>Redigera fråga <i class='fa fa-pencil'></i></a>
           </td>";
           echo $edit;
       }
        ?>
     </tr>


<?php endforeach; ?>
</table>
</div>
