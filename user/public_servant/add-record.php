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
	<title>Bonus Task</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/style/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body style="width: 400px; margin: auto">
	<div class="auth-form col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="div-title">
			<h3>New Record</h3>
		</div>
		<form method="POST" action="php/add-record-script.php">
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<select name="cname">
					<?php
						$query = 'SELECT cname FROM "dbSchema"."Country" ORDER BY cname ASC';
						$result = pg_query($connect, $query);
						if ($result) {
							if (pg_num_rows($result)) {
								while ($row = pg_fetch_assoc($result)) {?>
									<option value="<?php echo $row['cname']; ?>"><?php echo $row['cname']; ?></option>
								<?php }
							}
						}
					?>
				</select>
			</div>
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<select name="dcode">
					<?php
						$query = 'SELECT disease_code FROM "dbSchema"."Disease" ORDER BY disease_code ASC';
						$result = pg_query($connect, $query);
						if ($result) {
							if (pg_num_rows($result)) {
								while ($row = pg_fetch_assoc($result)) {?>
									<option value="<?php echo $row['disease_code']; ?>"><?php echo $row['disease_code']; ?></option>
								<?php }
							}
						}
					?>
				</select>
			</div>
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="number" pattern="[0-9]" name="deaths" placeholder="Total deaths" required="">
			</div>
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="number" pattern="[0-9]" name="patients" placeholder="Total patients" required="">
			</div>
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
				<input type="submit" name="save" value="Save">
			</div>
		</form>
		<div class="div-links col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<a href="/">
					← Main page
				</a>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="text-align: right;">
				<a href="my-records.php">
					My records →
				</a>
			</div>
		</div>

	</div>
</body>
</html>
