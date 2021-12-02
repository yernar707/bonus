<?php
	require_once('../../config/config.php');

	if (isset($_COOKIE['user_name']) && isset($_COOKIE['user_email']) && isset($_COOKIE['user_role'])) {
		if ($_COOKIE['user_role'] == 'admin') {
		} elseif ($_COOKIE['user_role'] == 'doctor' or $_COOKIE['user_role'] == 'public_servant' or $_COOKIE['user_role'] == 'not_verified') {

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
	$name = "";
	$email = $_COOKIE['user_email'];
	$surname = "";
	$cname = "";
	$phone = "";
	$query = 'SELECT name, surname, cname, phone FROM "dbSchema"."Users" WHERE email = \''.$_COOKIE['user_email'].'\'';
	$result = pg_query($connect, $query);
	$num_rows = pg_num_rows($result);
	if ($result) {
		if ($num_rows == 1) {
			while ($row = pg_fetch_assoc($result)) {
				$name = $row['name'];
				$cname = $row['cname'];
				$surname = $row['surname'];
				$phone = $row['phone'];
			}
		}
	}

	if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['cname'])) {
		$query = 'UPDATE "dbSchema"."Users" SET name = \''.$_POST['name'].'\', surname = \''.$_POST['surname'].'\', cname = \''.$_POST['cname'].'\', email = \''.$_POST['email'].'\', phone = \''.$_POST['phone'].'\' WHERE email = \''.$_COOKIE['user_email'].'\'';
		$result = pg_query($connect, $query);
		if ($result) {
			setcookie("user_name", $_POST['name'].' '.$_POST['surname'], time() + 3600, "/");
			setcookie("user_email", $_POST['email'], time() + 3600, "/");
			setcookie("user_role", $_COOKIE['user_role'], time() + 3600, "/");
			?>
				<script type="text/javascript">
					fun1("Information updated", function() {
						location.replace('/');
					});

					function fun1(s, callback) {
						alert(s);
						callback();
					}
				</script>
			<?php
		} else {
			?>
				<script type="text/javascript">
					fun1("Server error", function() {
						window.history.back();
					});

					function fun1(s, callback) {
						alert(s);
						callback();
					}
				</script>
			<?php
		}
	} else {
		?>
			<script type="text/javascript">
				fun1("Something went wrong", function() {
					window.history.back();
				});

				function fun1(s, callback) {
					alert(s);
					callback();
				}
			</script>
		<?php
	}

?>
