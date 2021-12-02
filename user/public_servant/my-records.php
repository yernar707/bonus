<?php
	require_once('../../config/config.php');

	if (isset($_COOKIE['user_name']) && isset($_COOKIE['user_email']) && isset($_COOKIE['user_role'])) {
		if ($_COOKIE['user_role'] == 'admin') {
			header('location: /admin/');
		} elseif ($_COOKIE['user_role'] == 'doctor' or $_COOKIE['user_role'] == 'not_verified') {
			header('location: /');
		}elseif($_COOKIE['user_role'] == 'public_servant'){

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
	<title>
		Bonus Task
	</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/style/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		function showInfo(x) {
			let child = x.querySelector('.hidden-info');
			child.classList.remove('inactive');
		}
		function hideInfo(x) {
			let child = x.querySelector('.hidden-info');
			child.classList.add('inactive');
		}
	</script>
</head>
<body>
		<nav class="navbar navbar-inverse nav-top">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
				<li><a href="/bonus">Bonus Task</a></li>
				<li class="active"><a href="my-records.php">My records</a></li>
				<li><a href="add-record.php">Add record</a></li>
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
							<li><a href="<?php echo $$_COOKIE['user_role'].'/'; ?>"><?php echo $_COOKIE['user_name'].'('.$_COOKIE['user_role'].')'; ?> - My cabinet</a></li>
							<li><a href="/php/logoff.php"><span class="glyphicon glyphicon-log-out"></span> Log Off</a></li>
						<?php
					}

				?>
			</ul>
		</div>
	</nav>
	<div class="main-div" style="margin-bottom: 500px;">
		<div class="parent-section">
			<div class="div-section">
				<div class="div-title">
					<h3>My Records</h3>
				</div>
				<table class="info">
					<tr>
						<th>
							Country
						</th>
						<th>
							Disease Code
						</th>
						<th>
							Total Deaths
						</th>
						<th>
							Total Patients
						</th>
					</tr>
					<?php
						$record = 'SELECT disease_code, cname, total_deaths, total_patients FROM "dbSchema"."Record" WHERE email = \''.$_COOKIE['user_email'].'\' ORDER BY cname ASC';
						$recordres = pg_query($connect, $record);
						if ($recordres) {
							if (pg_num_rows($recordres) > 0) {
								while ($row = pg_fetch_assoc($recordres)) {?>
									<tr>
										<td><?php echo $row['cname'] ?></td>
										<td><?php echo $row['disease_code'] ?></td>
										<td><?php echo $row['total_deaths'] ?></td>
										<td><?php echo $row['total_patients'] ?></td>
									</tr>
								<?php }
							}
						}
					?>
				</table>
			</div>
		</div>
	</div>
</body>
</html>
