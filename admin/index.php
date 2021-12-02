<?php
	require_once('../config/config.php');

	if (isset($_COOKIE['user_name']) && isset($_COOKIE['user_email']) && isset($_COOKIE['user_role'])) {
		if ($_COOKIE['user_role'] == 'admin') {
		} elseif ($_COOKIE['user_role'] == 'doctor' or $_COOKIE['user_role'] == 'public_servant' or $_COOKIE['user_role'] == 'not_verified') {
			header('location: /bonus/');
		} else {
			unset($_COOKIE['user_email']);
    		setcookie('user_email', null, -1, '/'); 
			unset($_COOKIE['user_name']);
    		setcookie('user_name', null, -1, '/'); 
			unset($_COOKIE['user_role']);
    		setcookie('user_role', null, -1, '/'); 
			header('location: /bonus/');
		}
	} else {
		unset($_COOKIE['user_email']);
		setcookie('user_email', null, -1, '/'); 
		unset($_COOKIE['user_name']);
		setcookie('user_name', null, -1, '/'); 
		unset($_COOKIE['user_role']);
		setcookie('user_role', null, -1, '/'); 
		header('location: /bonus/');
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Beast Mode</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/bonus/style/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-inverse nav-top">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
				<li class="active"><a href="/bonus">Beast Mode</a></li>
				<?php
					if (isset($_COOKIE['user_role'])) {
						$role = $_COOKIE['user_role'];
						if ($role == 'admin') {
							?>
								<li><a href="users.php">Users</a></li>
								<li><a href="countries.php">Countries</a></li>
								<li><a href="disease-type.php">Disease Types</a></li>
								<li><a href="diseases.php">Diseases</a></li>
							<?php
						}
					}

				?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php
					if (!isset($_COOKIE['user_name'])) {
						?>
							<li><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
							<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
						<?php
					} else {
						?>
							<li><a href="user/"><?php echo $_COOKIE['user_name'].'('.$_COOKIE['user_role'].')'; ?> - My cabinet</a></li>
							<li><a href="php/logoff.php"><span class="glyphicon glyphicon-log-out"></span> Log Off</a></li>
						<?php
					}

				?>
			</ul>
		</div>
	</nav>

	<div style="width: 500px; margin:auto; text-align: center;">
		<div class="auth-form col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin: 50px auto;">
			<div class="div-title">
				<h3>Admin panel</h3>
			</div>
			<div>
				<h4>
					This is admin panel of this website. <br>
					If you are here it means that you are VIP and you can manage the website.
				</h4>
			</div>
		</div>
	</div>

</body>
</html>