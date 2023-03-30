<h2>Ustvari uporabnika</h2>
<form action="?controller=users&action=store" method="POST" enctype="multipart/form-data">
		<label>Uporabniško ime*</label><input class="form-control" type="text" name="username" /> <br/>
		<label>Geslo*</label><input class="form-control" type="text" name="password" /> <br/>
		<label>Ponovi geslo*</label><input class="form-control" type="text" name="repeat_password" /> <br/>
        <label>E-pošta*</label><input class="form-control" type="email" name="email" /> <br/>
        <label>Ime*</label><input class="form-control" type="text" name="firstname" /> <br/>
        <label>Priimek*</label><input class="form-control" type="text" name="lastname" /> <br/>
        <label>Naslov</label><input class="form-control" type="text" name="address" /> <br/>
        <label>Pošta</label><input class="form-control" type="text" name="post" /> <br/>
        <label>Telefon</label><input class="form-control" type="text" name="phone" /> <br/>
		<input class="btn btn-primary" type="submit" name="submit" value="Ustvari" /> <br/>
	</form>
    <a href="index.php"><button class="btn btn-secondary">Nazaj</button></a><br>
    <?php if (!empty($error)) { ?>
    <label><?php echo $error; ?></label>
<?php } ?>

   