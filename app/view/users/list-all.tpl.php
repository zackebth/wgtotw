
<h1><?=$title?></h1>
<p>
	<span class='user-active'><i class='fa fa-user'></i></span> Aktiva Användare |
	<span class='user-inactive'><i class='fa fa-user'></i></span> = Inaktiva Användare |
	<span class='user-deleted'><i class='fa fa-user-times'></i></span> = Tillfälligt borttagen.
</p>
<?php if($this->session->has('user_loggedin') && $this->session->get('user_loggedin')['rank'] > 99) :?>
	<p><a href="<?=$this->url->create('users/trashcan')?>">Gå till Papperskorgen </a></p>
<?php endif; ?>
	<table width="100%">
	<tr>
		<th align='left' colspan='2'>ID</th>
		<th align='left'>Acronym</th>
		<th align='left'>Namn</th>
		<?php if($this->session->has('user_loggedin') && $this->session->get('user_loggedin')['rank'] > 99) :?>
		<th align='left'>Delete User</th>
		<?php endif; ?>

	</tr>
<?php foreach ($users as $user) : ?>

	<?php
    $class = "";
	$href = null;

	   if ($user->getProperties()['deleted'] != null) {
		  $faclass = "fa fa-user-times";
		  $class = "user-deleted";
		  $href = $this->url->create('users/trashcan');
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
	<?php if($this->session->has('user_loggedin') && $this->session->get('user_loggedin')['rank'] > 99) :?>
   		<td width='10%' align='center'>
    <?php if ($user->getProperties()['deleted'] == null) : ?>
	<a href="<?=$this->url->create('users/soft-Delete').'/'.$user->getProperties()['id']?>" title='Flytta till papperskorgen'><i class="fa fa-trash"></i></a>
	</td>
	 <?php endif; ?>
 <?php endif;?>

</tr>

<?php endforeach; ?>
	</table>
