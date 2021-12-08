<?php
	require_once('../config/config.php');

	if (isset($_COOKIE['user_name']) && isset($_COOKIE['user_email']) && isset($_COOKIE['user_role'])) {
		if ($_COOKIE['user_role'] == 'admin') {
		} elseif ($_COOKIE['user_role'] == 'doctor' or $_COOKIE['user_role'] == 'public_servant' or $_COOKIE['user_role'] == 'not_verified') {
			header('location: /');
		} else {
			unset($_COOKIE['user_email']);
    		setcookie('user_email', null, -1, '/');
			unset($_COOKIE['user_name']);
    		setcookie('user_name', null, -1, '/');
			unset($_COOKIE['user_role']);
    		setcookie('user_role', null, -1, '/');
			header('location: /');
		}
	} else {
		unset($_COOKIE['user_email']);
		setcookie('user_email', null, -1, '/');
		unset($_COOKIE['user_name']);
		setcookie('user_name', null, -1, '/');
		unset($_COOKIE['user_role']);
		setcookie('user_role', null, -1, '/');
		header('location: /');
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Beast Mode</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/style/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-inverse nav-top">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
				<li><a href="/">Beast Mode</a></li>
				<?php
					if (isset($_COOKIE['user_role'])) {
						$role = $_COOKIE['user_role'];
						if ($role == 'admin') {
							?>
								<li><a href="users.php">Users</a></li>
								<li><a href="countries.php">Countries</a></li>
								<li class="active"><a href="disease-type.php">Disease Types</a></li>
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
							<li><a href="/user/"><?php echo $_COOKIE['user_name'].'('.$_COOKIE['user_role'].')'; ?> - My cabinet</a></li>
							<li><a href="php/logoff.php"><span class="glyphicon glyphicon-log-out"></span> Log Off</a></li>
						<?php
					}

				?>
			</ul>
		</div>
	</nav>

	<div style="width: 700px; margin:auto; text-align: center;">
		<div class="auth-form col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin: 50px auto;">
			<div class="div-title">
				<h3>Disease Types</h3>
			</div>
			<div>
				<table>
					<tr>
						<th style="width: 40%;">
							Country
						</th>
						<th style="width: 40%;">
							Population
						</th>
					</tr>
					<?php
						$query = 'SELECT * FROM "dbSchema"."DiseaseType"';
						$lastid = 1;
						$result = pg_query($connect, $query);
						if ($result) {
							if (pg_num_rows($result) > 0) {
								while ($row = pg_fetch_assoc($result)) {?>
									<tr style="border-bottom: 1px solid #efefef;">
										<td><?php echo $row['description']; ?></td>
										<td style="padding: 3px;">
											<form method="POST" action="php/delete-dtype.php">
												<input type="hidden" required="" name="id" value="<?php echo $row['id']; ?>">
												<input type="submit" required="" name="delete" value="Delete">
											</form>
										</td>
									</tr>
								<?php
								$lastid = $row['id'];
								}
							}
						}
					?>
					<tr>
						<form method="POST" action="php/add-dtype.php">
							<td>
								<input style="width: 80%; text-align: center;" type="text" placeholder="Description" name="desc">
								<input type="hidden" value="<?php echo $lastid + 1; ?>" name="id">
							</td>
							<td>
								<input type="submit" name="add" value="Add">
							</td>
						</form>
					</tr>
				</table>
			</div>
		</div>
	</div>

</body>
</html>
