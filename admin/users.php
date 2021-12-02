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
								<li class="active"><a href="users.php">Users</a></li>
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

	<div style="width: 1200px; margin:auto; text-align: center;">
		<div class="auth-form col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin: 50px auto;">
			<div class="div-title">
				<h3>Users</h3>
			</div>
			<div>
				<table>
					<tr>
						<th>
							Name
						</th>
						<th>
							Surname
						</th>
						<th>
							Email
						</th>
						<th>
							Phone Number
						</th>
						<th>
							Country
						</th>
						<th>
							Salary
						</th>
						<th>
							Role
						</th>
					</tr>
					<?php
						$query = 'SELECT name, surname, email, phone, cname, salary FROM "dbSchema"."Users"';
						$result = pg_query($connect, $query);
						if ($result) {
							if (pg_num_rows($result) > 0) {
								while ($row = pg_fetch_assoc($result)) {
									$role = 'not_verified';
									$role_query = 'SELECT email FROM "dbSchema"."Admin" WHERE email = \''.$row['email'].'\'';
									$role_result = pg_query($connect, $role_query);
									if (pg_num_rows($role_result) > 0) {
										$role = 'Admin';
									} else {
										$role_query = 'SELECT email FROM "dbSchema"."Doctor" WHERE email = \''.$row['email'].'\'';
										$role_result = pg_query($connect, $role_query);
										if (pg_num_rows($role_result) > 0) {
											$role = 'Doctor';
										} else {
											$role_query = 'SELECT email FROM "dbSchema"."PublicServant" WHERE email = \''.$row['email'].'\'';
											$role_result = pg_query($connect, $role_query);
											if (pg_num_rows($role_result) > 0) {
												$role = 'PublicServant';
											}
										}
									}
								?>
									<tr style="border-bottom: 1px solid #efefef;">
										<td><?php echo $row['name']; ?></td>
										<td><?php echo $row['surname']; ?></td>
										<td><?php echo $row['email']; ?></td>
										<td><?php echo $row['phone']; ?></td>
										<td><?php echo $row['cname']; ?></td>
										<td><?php echo $row['salary']; ?></td>
										<td>
											<?php
												if ($role != 'Admin') {
													?>
													<form method="POST" action="php/change-role.php">

														<select name = "role" style="width: 40%; display: inline-block;">
															<option <?php if ($role == 'Not Verified') {
																echo "selected";
															} ?> value = "not_verified">
																Not Verified
															</option>
															<option <?php if ($role == 'Doctor') {
																echo "selected";
															} ?> value = "Doctor">
																Doctor
															</option>
															<option <?php if ($role == 'PublicServant') {
																echo "selected";
															} ?> value = "PublicServant">
																Public Servant
															</option>
														</select>
														<input type="hidden" name="email" value="<?php echo $row['email']; ?>">
														<input type="hidden" name="old_role" value="<?php echo $role; ?>">
														<input style="width: 40%; display: inline-block;" type="submit" name="submit" value="Save">
													</form>
													<?php
												} else {
													echo "Admin";
												}
											?>
										</td>
									</tr>
								<?php
								}
							}
						}
					?>
				</table>
			</div>
		</div>
	</div>

</body>
</html>
