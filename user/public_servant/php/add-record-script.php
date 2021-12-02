<<?php
	require_once('../../../config/config.php');

	if (isset($_COOKIE['user_name']) && isset($_COOKIE['user_email']) && isset($_COOKIE['user_role'])) {
		if ($_COOKIE['user_role'] == 'admin') {
			header('location: /bonus/admin/');
		} elseif ($_COOKIE['user_role'] == 'doctor' or $_COOKIE['user_role'] == 'not_verified') {
			header('location: /bonus/');
		}elseif($_COOKIE['user_role'] == 'public_servant'){

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

	if (isset($_POST['cname']) && isset($_POST['dcode']) && isset($_POST['deaths']) && isset($_POST['patients'])) {
		$check = 'SELECT * FROM "dbSchema"."Record" WHERE cname = \''.$_POST['cname'].'\' AND email = \''.$_COOKIE['user_email'].'\' AND disease_code = \''.$_POST['dcode'].'\'';
		$checkresult = pg_query($connect, $check);
		if ($checkresult) {
			if (pg_num_rows($checkresult) > 0) {
				?>
					<script type="text/javascript">
						fun1("Record for the same country and disease exists!", function() {
							window.history.back();
						});

						function fun1(s, callback) {
							alert(s);
							callback();
						}
					</script>
				<?php
			} else {
				$insert = 'INSERT INTO "dbSchema"."Record"(email, cname, disease_code, total_deaths, total_patients) VALUES(\''.$_COOKIE['user_email'].'\', \''.$_POST['cname'].'\', \''.$_POST['dcode'].'\', \''.$_POST['deaths'].'\', \''.$_POST['patients'].'\')';
				if (pg_query($connect, $insert)) {
					?>
						<script type="text/javascript">
							fun1("Record added", function() {
								location.replace('/bonus/');
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
							fun1("Problem with the server", function() {
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
					fun1("Problem with the server", function() {
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