<?php
	require_once('../../config/config.php');

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

	if (isset($_POST['role']) && isset($_POST['email']) && isset($_POST['old_role'])) {
		if ($_POST['old_role'] != $_POST['role']) {
			if ($_POST['role'] == 'not_verified') {
				$query = 'DELETE FROM "dbSchema"."'.$_POST['old_role'].'" WHERE email = \''.$_POST['email'].'\'';
				if (pg_query($connect, $query)) {
					?>
						<script type="text/javascript">
							fun1("Role is updated", function() {
								location.replace('/admin/users.php');
							});

							function fun1(s, callback) {
								alert(s);
								callback();
							}
						</script>
					<?php
				}
			}
			if ($_POST['old_role'] == 'not_verified') {
				if ($_POST['role'] == 'Doctor') {
					$degrees = ['Bachelor of Medicine', 'Master of Medicine','Doctor of Medicine','Bachelor of Surgery','Master of Surgery','Doctor of Surgery'];
					$degree_key = array_rand($degrees, 1);
					$query = 'INSERT INTO "dbSchema"."Doctor"(email, degree) VALUES(\''.$_POST['email'].'\', \''.$degrees[$degree_key].'\')';
					if (pg_query($connect, $query)) {
						?>
							<script type="text/javascript">
								fun1("Role is updated", function() {
									location.replace('/admin/users.php');
								});

								function fun1(s, callback) {
									alert(s);
									callback();
								}
							</script>
						<?php
					}
				}
				if ($_POST['role'] == 'PublicServant') {
					$dept = rand(1,4);
					$query = 'INSERT INTO "dbSchema"."PublicServant"(email, department) VALUES(\''.$_POST['email'].'\', \'Dept'.$dept.'\')';
					if (pg_query($connect, $query)) {
						?>
							<script type="text/javascript">
								fun1("Role is updated", function() {
									location.replace('/admin/users.php');
								});

								function fun1(s, callback) {
									alert(s);
									callback();
								}
							</script>
						<?php
					}
				}
			}
		}
	}
?>
