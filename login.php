<?php

session_start();
if (isset($_SESSION['username'])) {
	// User is not logged in, redirect to login page
	header("Location: index.php");
	exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Berk Emre Paker - Login</title>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css'>
	<link rel="stylesheet" href="./CSSstyles/login.css">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
</head>

<body>
	<form action="validate_login.php" method="post">
		<div class="container">
			<div id="login-box" style="margin-top: 15vh; border-radius: 25px;">
				<br><br>
				<div class="logo">
					<h1 class="logo-caption"><span class="tweak">L</span>o<span class="tweak">g</span>i<span class="tweak">n</span></h1>
				</div>
				<div class="controls">
					<input type="text" name="username" placeholder="Username" class="form-control" />
					<input type="password" name="password" placeholder="Password" class="form-control" />
					<button type="submit" class="btn btn-default btn-block btn-custom" name="login_type"
						value="user">Login</button>
					<button type="submit" class="btn btn-default btn-block btn-custom" name="login_type"
						value="guest">View Information as a Guest</button>
				</div>
				<br><br>
			</div>
		</div>
	</form>
	<div id="particles-js"></div>

	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css'></script>
	<script src='https://code.jquery.com/jquery-1.11.1.min.js'></script>
	<script src="./JSscripts/login.js"></script>

</body>

</html>