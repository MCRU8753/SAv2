<!DOCTYPE html>
<head>
	<title>Vaja 2</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<h1>Oglasnik - administracija</h1>
	<nav class="navbar navbar-expand-lg navbar-light">
		<ul class="navbar-nav mr-auto">
			<li class="btn btn-primary"><a class="text-white" href="/index.php">Domov</a></li>
			<li class="btn btn-primary"><a class="text-white" href="/logout.php">Odjava</a></li>
		</ul>
	</nav>
	<hr/>

    <!-- tukaj se bo vključevala koda pogledov, ki jih bodo nalagali kontrolerji -->
    <!-- klic akcije iz routes bo na tem mestu zgeneriral html kodo, ki bo zalepnjena v našo predlogo -->
    <?php require_once('routes.php'); ?> 

    </body>
</html>