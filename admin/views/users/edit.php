<h2>Uredi uporabnika</h2>
<form action="?controller=users&action=update" method="POST" enctype="multipart/form-data">
        <input class="form-control" type="hidden" name="id" value="<?php echo $id; ?>"/>
        <?php $user = User::find($id) ?>
		<label>Uporabniško ime*</label><input class="form-control" type="text" name="username" value="<?php echo $user->username; ?>"/> <br/>
		<label>Geslo*</label><input class="form-control" type="text" name="password" /> <br/>
		<label>Ponovi geslo*</label><input class="form-control" type="text" name="repeat_password" /> <br/>
        <label>E-pošta*</label><input class="form-control" type="email" name="email" value="<?php echo $user->email; ?>"/> <br/>
        <label>Ime*</label><input class="form-control" type="text" name="firstname" value="<?php echo $user->firstname; ?>"/> <br/>
        <label>Priimek*</label><input class="form-control" type="text" name="lastname" value="<?php echo $user->lastname; ?>"/> <br/>
        <label>Naslov</label><input class="form-control" type="text" name="address" value="<?php echo $user->address; ?>"/> <br/>
        <label>Pošta</label><input class="form-control" type="text" name="post" value="<?php echo $user->post; ?>"/> <br/>
        <label>Telefon</label><input class="form-control" type="text" name="phone" value="<?php echo $user->phone; ?>"/> <br/>
		<input class="btn btn-primary" type="submit" name="submit" value="Posodobi" /> <br/>
	</form>
    <a href="index.php"><button class="btn btn-secondary">Nazaj</button></a><br>
    <?php if (!empty($error)) { ?>
        <label><?php echo $error; ?></label>
    <?php } ?>
