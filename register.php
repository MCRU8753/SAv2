<?php
include_once('header.php');

function username_exists($username){
	global $conn;
	$username = mysqli_real_escape_string($conn, $username);
	$query = "SELECT * FROM users WHERE username='$username'";
	$res = $conn->query($query);
	return mysqli_num_rows($res) > 0;
}

function register_user($username, $password, $email, $firstname, $lastname, $address, $post, $phone){
	global $conn;
	$username = mysqli_real_escape_string($conn, $username);
	$passwordsha = sha1($password);
    $email = mysqli_real_escape_string($conn, $email);
    $firstname = mysqli_real_escape_string($conn, $firstname);
    $lastname = mysqli_real_escape_string($conn, $lastname);
    $address = !empty($address) ? mysqli_real_escape_string($conn, $address) : '';
    $post = !empty($post) ? mysqli_real_escape_string($conn, $post) : '';
    $phone = !empty($phone) ? mysqli_real_escape_string($conn, $phone) : '';

	$query = "INSERT INTO users (username, password, email, firstname, lastname, address, post, phone) VALUES ('$username', '$passwordsha', '$email', '$firstname', '$lastname', '$address', '$post', '$phone');";
	if($conn->query($query)){
		return true;
	}
	else{
		echo mysqli_error($conn);
		return false;
	}
}

$error = "";
if(isset($_POST["submit"])){
    if($_POST["username"] == "" || $_POST["password"] == "" || $_POST["email"] == "" || $_POST["firstname"] == "" || $_POST["lastname"] == "") {
        $error = "Prosim zapolnite vsa obvezna polja.";
    }

	else if($_POST["password"] != $_POST["repeat_password"]){
		$error = "Gesli se ne ujemata.";
	}

	else if(username_exists($_POST["username"])){
		$error = "Uporabniško ime je že zasedeno.";
	}

    else if(register_user($_POST["username"], $_POST["password"], $_POST["email"], $_POST["firstname"], $_POST["lastname"], $_POST["address"], $_POST["post"], $_POST["phone"])){
		header("Location: login.php");
		die();
	}

    else{
		$error = "Prišlo je do napake med registracijo uporabnika.";
	}
}

?>
<div>
	<h2>Registracija</h2>
	<form action="register.php" method="POST">
		<label>Uporabniško ime*</label><input class="form-control" type="text" name="username" /> <br/>
		<label>Geslo*</label><input class="form-control" type="password" name="password" /> <br/>
		<label>Ponovi geslo*</label><input class="form-control" type="password" name="repeat_password" /> <br/>
        <label>E-pošta*</label><input class="form-control" type="email" name="email" /> <br/>
        <label>Ime*</label><input class="form-control" type="text" name="firstname" /> <br/>
        <label>Priimek*</label><input class="form-control" type="text" name="lastname" /> <br/>
        <label>Naslov</label><input class="form-control" type="text" name="address" /> <br/>
        <label>Pošta</label><input class="form-control" type="text" name="post" /> <br/>
        <label>Telefon</label><input class="form-control" type="text" name="phone" /> <br/>
		<input class="btn btn-primary" type="submit" name="submit" value="Registracija" /> <br/>
		<label><?php echo $error; ?></label>
	</form>
</div>
<?php
include_once('footer.php');
?>