<?php
	require_once('../../config/config.php');

	if (isset($_COOKIE['user_name']) && isset($_COOKIE['user_email']) && isset($_COOKIE['user_role'])) {
		if ($_COOKIE['user_role'] == 'admin') {
			header('location: /bonus/admin/');
		} elseif ($_COOKIE['user_role'] == 'public_servant' or $_COOKIE['user_role'] == 'not_verified') {
			header('location: /bonus/');
		}elseif($_COOKIE['user_role'] == 'doctor'){

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
	<title>
		Bonus Task
	</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/bonus/style/style.css">
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
				<li class="active"><a href="my-records.php">My specialization</a></li>
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
							<li><a href="/bonus/php/logoff.php"><span class="glyphicon glyphicon-log-out"></span> Log Off</a></li>
						<?php
					}

				?>
			</ul>
		</div>
	</nav>
	<div class="main-div" style="margin-bottom: 500px;">
		<div class="parent-section" style="width: 500px;">
			<div class="div-section">
				<div class="div-title">
					<h3>My Specialization</h3>
				</div>
				<table class="info">
					<?php
						$cur = [];
						$record = 'SELECT "dbSchema"."Specialize".id, description FROM "dbSchema"."DiseaseType"
									JOIN "dbSchema"."Specialize"
									ON "dbSchema"."Specialize".id = "dbSchema"."DiseaseType".id 
									WHERE email = \''.$_COOKIE['user_email'].'\' ORDER BY description ASC';
						$recordres = pg_query($connect, $record);
						if ($recordres) {
							if (pg_num_rows($recordres) > 0) {
								while ($row = pg_fetch_assoc($recordres)) {
									array_push($cur, $row['id']);?>
									<tr>
										<td class="spec-row" style="text-align: left;">
											<?php echo $row['description'] ?>
											<form method="POST" action="delete-spec.php" class="remove-row">
												<input type="hidden" name="id" value="<?php echo $row['id'] ?>">
												<button type="submit" style="border:none; background: transparent;">
													<span class="glyphicon glyphicon-remove"></span>
												</button>
											</form>
											
									</td>
									</tr>
								<?php }
							}
						}
					?>
				</table>
				<form method="POST" action="add-spec.php">
					<select name="id" style="width: 70%; display: inline-block;">
						<?php
							$specs = 'SELECT * FROM "dbSchema"."DiseaseType"';
							$result = pg_query($connect, $specs);
							if ($result) {
								if (pg_num_rows($result) > 0) {
									while ($row = pg_fetch_assoc($result)) {
										if (!in_array($row['id'], $cur)) {
											?>
												<option value="<?php echo $row['id'] ?>"><?php echo $row['description']; ?></option>
											<?php
										}
									}
								}
							}
						?>
					</select>
					<input type="submit" name="add" value="add" style="width: 25%; display: inline-block;">
				</form>
			</div>
		</div>
	</div>
</body>
</html>