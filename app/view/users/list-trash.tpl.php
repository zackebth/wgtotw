<article class="article1">
<h1><?=$title?></h1>
<p>
	<span class='user-deleted'><i class='fa fa-user-times'></i></span> = Användare i papperskorgen.
</p>
	<table width="100%">
	<tr>
		<th align='left' colspan='2'>ID</th>
		<th align='left'>Acronym</th>
		<th align='left'>Namn</th>
		<th align='left'>Options</th>
	</tr>
<?php foreach ($users as $user) : ?>
 
	<?php 
    $class = "";
	$href = null;
		
	   if ($user->getProperties()['deleted'] != null) {
		  $faclass = "fa fa-user-times";
		  $class = "user-deleted";
		  $href = "";
		  $title = "Denna användaren ligger i papperskorgen";
		}
		elseif ($user->getProperties()['active'] == null) {
		  $faclass = "fa fa-user";
		  $class = "user-inactive";
		  $href = $this->url->create('users/activate'). "/". $user->getProperties()['id']. "/" .$this->request->getRoute();
		  $title = "Aktivera användaren";
		}
		else {
		  $faclass = "fa fa-user";
		  $class = "user-active";
		  $href = $this->url->create('users/deactivate'). '/'. $user->getProperties()['id']. '/' .$this->request->getRoute();
		  $title = "Inaktivera användaren";
		}
	?>
	
 <tr>
  <td width='5%'><?=$user->getProperties()['id']?></td>
    <td width='5%'><span class='<?=$class?>'><i class="<?=$faclass?>"></i></span></td>
    <td width='30%'><a href="<?=$this->url->create('users/id').'/'.$user->getProperties()['id']?>"><?=$user->getProperties()['acronym']?></a></td>
    <td width='50%'><?=$user->getProperties()['name']?></td>
    <td width='10%'>
	<a href="<?=$this->url->create('users/delete').'/'.$user->getProperties()['id']?>" title='Ta bort denna användaren permanent' class='user-deleted'><i class="fa fa-ban"></i></a>
	<a href="<?=$this->url->create('users/activate').'/'.$user->getProperties()['id']?>" title='Aktivera användaren igen' class='user-active'><i class="fa fa-rotate-left"></i></a>
	</td>
	
</tr>
 
<?php endforeach; ?>	 
	</table>
	
</article> 