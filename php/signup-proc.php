<?php
	require_once('../config/config.php');

	if (isset($_COOKIE['user_name']) && isset($_COOKIE['user_email']) && isset($_COOKIE['user_role'])) {
		if ($_COOKIE['user_role'] == 'admin') {
			header('location: /admin/');
		} elseif ($_COOKIE['user_role'] == 'doctor' or $_COOKIE['user_role'] == 'public_servant' or $_COOKIE['user_role'] == 'not_verified') {
			header('location: /');
		} else {
			unset($_COOKIE['user_email']);
    		setcookie('user_email', null, -1, '/');
			unset($_COOKIE['user_name']);
    		setcookie('user_name', null, -1, '/');
			unset($_COOKIE['user_role']);
    		setcookie('user_role', null, -1, '/');
		}
	} else {
		unset($_COOKIE['user_email']);
		setcookie('user_email', null, -1, '/');
		unset($_COOKIE['user_name']);
		setcookie('user_name', null, -1, '/');
		unset($_COOKIE['user_role']);
		setcookie('user_role', null, -1, '/');
	}

	if (!$connect) {
		?>
			<script type="text/javascript">
				fun1("Server error!", function() {
					window.history.back();
				});

				function fun1(s, callback) {
					alert(s);
					callback();
				}
			</script>
		<?php
	}

	if (isset($_POST['email']) && isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['password']) && isset($_POST['country']) && isset($_POST['phone'])) {
		$email = $_POST['email'];
		$name = $_POST['name'];
		$surname = $_POST['surname'];
		$password = $_POST['password'];
		$country = $_POST['country'];
		$phone = $_POST['phone'];

		$check = 'SELECT email FROM "dbSchema"."Users" WHERE email = \''.$email.'\'';
		$check_result = pg_query($connect, $check);
		if ($check_result) {
			if (pg_num_rows($check_result) > 0) {
				?>
					<script type="text/javascript">
						fun1("User with this email is already signed up", function() {
							window.history.back();
						});

						function fun1(s, callback) {
							alert(s);
							callback();
						}
					</script>
				<?php
			} else {
				$query = 'INSERT INTO "dbSchema"."Users"(email, password, name, surname, cname, phone) VALUES(\''.$email.'\', \''.$password.'\', \''.$name.'\' , \''.$surname.'\' , \''.$country.'\' ,\''.$phone.'\')';

				$query_result = pg_query($connect, $query);

				if ($query_result) {
					?>
						<script type="text/javascript">
							fun1("Success!", function() {
								document.location.replace('/login.php');
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
							fun1("Something went wrong:(", function() {
								window.history.back();
							});

							function fun1(s, callback) {
								alert(s);
								callback();
							}
						</script>
					<?php
				}
			}
		} else {
			?>
				<script type="text/javascript">
					fun1("Try again later", function() {
						window.history.back();
					});

					function fun1(s, callback) {
						alert(s);
						callback();
					}
				</script>
			<?php
		}
	}
?>
