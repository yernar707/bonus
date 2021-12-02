<?php
	require_once('../../config/config.php');

	if (isset($_COOKIE['user_name']) && isset($_COOKIE['user_email']) && isset($_COOKIE['user_role'])) {
		if ($_COOKIE['user_role'] == 'admin') {
			header('location: /admin/');
		} elseif ($_COOKIE['user_role'] == 'public_servant' or $_COOKIE['user_role'] == 'not_verified') {
			header('location: /');
		}elseif($_COOKIE['user_role'] == 'doctor'){

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

	if (isset($_POST['id'])) {
		$query = 'DELETE FROM "dbSchema"."Specialize" WHERE id = \''.$_POST['id'].'\'';
		if (pg_query($connect, $query)) {
			?>
				<script type="text/javascript">
					fun1("Specialization removed", function() {
						location.replace('/user/doctor/specialize.php');
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
					fun1("Server problems", function() {
						location.replace('/user/doctor/specialize.php');
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
				fun1("Something wrong", function() {
					location.replace('/user/doctor/specialize.php');
				});

				function fun1(s, callback) {
					alert(s);
					callback();
				}
			</script>
		<?php
	}
