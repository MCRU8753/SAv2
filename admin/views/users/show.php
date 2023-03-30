<h4>Uporabniško ime: <?php echo $user->username; ?></h4>
<p>Ime: <?php echo $user->firstname; ?></p>
<p>Priimek: <?php echo $user->lastname; ?></p>
<p>E-pošta: <?php echo $user->email; ?></p>

<?php
if(!empty($user->address)){
?>
<p>Naslov: <?php echo $user->address; ?></p>
<?php
}
?>

<?php
if(!empty($user->post)){
?>
<p>Pošta: <?php echo $user->post; ?></p>
<?php
}
?>

<?php
if(!empty($user->phone)){
?>
<p>Telefon: <?php echo $user->phone; ?></p>
<?php
}
?>

<?php
if($user->admin){
?>
<p>Administrator: <?php echo $user->admin; ?></p>
<?php
}
?>

<a href="index.php"><button class="btn btn-secondary">Nazaj</button></a>
