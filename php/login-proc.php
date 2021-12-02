<?php
	require_once('../config/config.php');

	if (isset($_COOKIE['user_name']) && isset($_COOKIE['user_email']) && isset($_COOKIE['user_role'])) {
		if ($_COOKIE['user_role'] == 'admin') {
			header('location: /bonus/admin/');
		} elseif ($_COOKIE['user_role'] == 'doctor' or $_COOKIE['user_role'] == 'public_servant' or $_COOKIE['user_role'] == 'not_verified') {
			header('location: /bonus/');
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

	if (isset($_POST['email']) && isset($_POST['password'])) {
		$email = $_POST['email'];
		$password = $_POST['password'];

		$query = 'SELECT "dbSchema"."Users".email, "dbSchema"."Users".name, "dbSchema"."Users".surname 
		FROM "dbSchema"."Users" 
		JOIN "dbSchema"."Admin"
		ON "dbSchema"."Users".email = "dbSchema"."Admin".email
		WHERE "dbSchema"."Users".email = \''.$email.'\' AND "dbSchema"."Users".password = \''.$password.'\'';

		$query_result = pg_query($connect, $query);
		if ($query_result) {
			if (pg_num_rows($query_result) == 1) {
				$row = pg_fetch_assoc($query_result);
				$cookie_names = ["user_email", "user_name", "user_role"];
				$cookie_values = [$row['email'], $row['name'].' '.$row['surname'], 'admin'];
				for ($i = 0; $i < 3; $i++) {
					setcookie($cookie_names[$i], $cookie_values[$i], time() + 3600, "/");
				}
				?>
					<script type="text/javascript">
						fun1("Hello, <?php echo $row['name'].' '.$row['surname']; ?>!", function() {
							document.location.replace('/bonus/admin/');
						});

						function fun1(s, callback) {
							alert(s);
							callback();
						}
					</script>
				<?php
			} else {
				$query = 'SELECT "dbSchema"."Users".email, "dbSchema"."Users".name, "dbSchema"."Users".surname 
				FROM "dbSchema"."Users" 
				JOIN "dbSchema"."Doctor"
				ON "dbSchema"."Users".email = "dbSchema"."Doctor".email
				WHERE "dbSchema"."Users".email = \''.$email.'\' AND "dbSchema"."Users".password = \''.$password.'\'';
				$result = pg_query($connect, $query);

				if ($result) {
					if (pg_num_rows($result) == 1) {
						$row = pg_fetch_assoc($result);
						$cookie_names = ["user_email", "user_name", "user_role"];
						$cookie_values = [$row['email'], $row['name'].' '.$row['surname'], 'doctor'];
						for ($i = 0; $i < 3; $i++) {
							setcookie($cookie_names[$i], $cookie_values[$i], time() + 3600, "/");
						}
						?>
							<script type="text/javascript">
								fun1("Hello, <?php echo $row['name'].' '.$row['surname']; ?>!", function() {
									document.location.replace('/bonus/');
								});

								function fun1(s, callback) {
									alert(s);
									callback();
								}
							</script>
						<?php
					} else {
						$query = 'SELECT "dbSchema"."Users".email, "dbSchema"."Users".name, "dbSchema"."Users".surname 
						FROM "dbSchema"."Users" 
						JOIN "dbSchema"."PublicServant"
						ON "dbSchema"."Users".email = "dbSchema"."PublicServant".email
						WHERE "dbSchema"."Users".email = \''.$email.'\' AND "dbSchema"."Users".password = \''.$password.'\'';
						$result = pg_query($connect, $query);

						if ($result) {
							if (pg_num_rows($result) == 1) {
								$row = pg_fetch_assoc($result);
								$cookie_names = ["user_email", "user_name", "user_role"];
								$cookie_values = [$row['email'], $row['name'].' '.$row['surname'], 'public_servant'];
								for ($i = 0; $i < 3; $i++) {
									setcookie($cookie_names[$i], $cookie_values[$i], time() + 3600, "/");
								}
								?>
									<script type="text/javascript">
										fun1("Hello, <?php echo $row['name'].' '.$row['surname']; ?>!", function() {
											document.location.replace('/bonus/');
										});

										function fun1(s, callback) {
											alert(s);
											callback();
										}
									</script>
								<?php
							} else {

								$query = 'SELECT "dbSchema"."Users".email, "dbSchema"."Users".name, "dbSchema"."Users".surname 
								FROM "dbSchema"."Users" 
								WHERE "dbSchema"."Users".email = \''.$email.'\' AND "dbSchema"."Users".password = \''.$password.'\'';
								$result = pg_query($connect, $query);

								if ($result) {
									if (pg_num_rows($result) == 1) {
										$row = pg_fetch_assoc($result);
										$cookie_names = ["user_email", "user_name", "user_role"];
										$cookie_values = [$row['email'], $row['name'].' '.$row['surname'], 'not_verified'];
										for ($i = 0; $i < 3; $i++) {
											setcookie($cookie_names[$i], $cookie_values[$i], time() + 3600, "/");
										}
										?>
											<script type="text/javascript">
												fun1("Hello, <?php echo $row['name'].' '.$row['surname']; ?>!", function() {
													document.location.replace('/bonus/');
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
												fun1("Incorrect email and/or password", function() {
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
			}
		} else {

			?>
				<script type="text/javascript">
					fun1("Server problems", function() {
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