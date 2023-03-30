<?php
	session_start();
	
	if(isset($_SESSION['LAST_ACTIVITY']) && time() - $_SESSION['LAST_ACTIVITY'] < 1800){
		session_regenerate_id(true);
	}
	$_SESSION['LAST_ACTIVITY'] = time();
	
	$conn = new mysqli('localhost', 'root', '', 'v1');
	$conn->set_charset("UTF8");
?>
<html>
<head>
	<title>V1</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body>
	<h1>Oglasnik</h1>
	<nav class="navbar navbar-expand-lg navbar-light">
		<ul class="navbar-nav mr-auto">
			<li class="btn btn-primary"><a class="text-white" href="index.php">Domov</a></li>
			<?php
			if(isset($_SESSION["USER_ID"])){
				$user_id = $_SESSION["USER_ID"];
				$query = "SELECT admin FROM users WHERE id = $user_id AND admin = true";
				$result = mysqli_query($conn, $query);
				if (mysqli_num_rows($result) > 0) {
					?>
					<li class="btn btn-primary"><a class="text-white" href="admin/index.php">Administracija</a></li>
					<?php
				}
				?>
				<li class="btn btn-primary"><a class="text-white" href="myads.php">Moji oglasi</a></li>
				<li class="btn btn-primary"><a class="text-white" href="publish.php">Objavi oglas</a></li>
				<li class="btn btn-primary"><a class="text-white" href="logout.php">Odjava</a></li>
				<?php
			} else{
				?>
				<li class="btn btn-primary"><a class="text-white" href="login.php">Prijava</a></li>
				<li class="btn btn-primary"><a class="text-white" href="register.php">Registracija</a></li>
				<?php
			}
			?>
		</ul>
	</nav>
	<hr/>