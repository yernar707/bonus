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
								<li><a href="disease-type.php">Disease Types</a></li>
								<li class="active"><a href="diseases.php">Diseases</a></li>
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

	<div style="width: 1100px; margin:auto; text-align: center;">
		<div class="auth-form col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin: 50px auto;">
			<div class="div-title">
				<h3>Diseases</h3>
			</div>
			<div>
				<table>
					<tr>
						<th style="width: 20%;">
							Disease code
						</th>
						<th style="width: 20%;">
							Pathogen
						</th>
						<th width="20%">
							Disease Type
						</th>
						<th width="40%">
							Description
						</th>
					</tr>
					<?php
						$query = 'SELECT "dbSchema"."Disease".disease_code, "dbSchema"."Disease".pathogen, "dbSchema"."Disease".description as d, "dbSchema"."DiseaseType".description as t FROM "dbSchema"."Disease" JOIN "dbSchema"."DiseaseType" ON "dbSchema"."DiseaseType".id = "dbSchema"."Disease".id';
						$result = pg_query($connect, $query);
						if ($result) {
							if (pg_num_rows($result) > 0) {
								while ($row = pg_fetch_assoc($result)) {?>
									<tr style="border-bottom: 1px solid #efefef;">
										<td><?php echo $row['disease_code']; ?></td>
										<td><?php echo $row['pathogen']; ?></td>
										<td><?php echo $row['t']; ?></td>
										<td><?php echo $row['d']; ?></td>
										<td style="padding: 3px;">
											<form method="POST" action="php/delete-disease.php">
												<input type="hidden" required="" name="dcode" value="<?php echo $row['disease_code']; ?>">
												<input type="submit" required="" name="delete" value="Delete">
											</form>
										</td>
									</tr>
								<?php
								}
							}
						}
					?>
					<tr>
						<form method="POST" action="php/add-disease.php">
							<td>
								<input style="width: 80%; text-align: center;" type="text" placeholder="Disease Code" name="dcode">
							</td>
							<td>
								<input style="width: 80%; text-align: center;" type="text" placeholder="Pathogen" name="pathogen">
							</td>
							<td>
								<select name="type">
									<?php
										$select = 'SELECT * FROM "dbSchema"."DiseaseType"';
										$select_res = pg_query($connect, $select);
										if ($select_res) {
											if (pg_num_rows($select_res) > 0) {
												while ($type = pg_fetch_assoc($select_res)) {
													?>
														<option value="<?php echo $type['id']; ?>">
															<?php echo $type['description']; ?>
														</option>
													<?php
												}
											}
										}
									?>
								</select>
							</td>
							<td>
								<textarea name="desc" placeholder="Description" cols="45" rows="2"></textarea>
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
