<?php
	require_once('config/config.php');

	if (isset($_COOKIE['user_name']) && isset($_COOKIE['user_email']) && isset($_COOKIE['user_role'])) {
		if ($_COOKIE['user_role'] == 'admin') {
			header('location: /admin/');
		} elseif ($_COOKIE['user_role'] == 'doctor' or $_COOKIE['user_role'] == 'public_servant' or $_COOKIE['user_role'] == 'not_verified') {

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

?>
<!DOCTYPE html>
<html>
<head>
	<title>
		Bonus Task
	</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style/style.css">
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
				<li class="active"><a href="/">Bonus Task</a></li>
				<?php
					if (isset($_COOKIE['user_role'])) {
						$role = $_COOKIE['user_role'];
						if ($role == 'doctor') {
							?>
								<li><a href="user/doctor/specialize.php">My specialization</a></li>
							<?php
						}
						if ($role == 'public_servant') {
							?>
								<li><a href="user/public_servant/my-records.php">My records</a></li>
								<li><a href="user/public_servant/add-record.php">Add record</a></li>
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

	<div class="main-div" style="margin-bottom: 500px;">
		<div class="parent-section">
			<div class="div-section">
				<div class="div-title">
					<h3>diseases</h3>
				</div>
				<table class="info">
					<tr>
						<th>
							disease name
						</th>
						<th>
							pathogen
						</th>
						<th>
							disease type
						</th>
						<th>
							disease description
						</th>
					</tr>
					<?php
						$query = 'SELECT disease_code, pathogen, "dbSchema"."Disease".description as ddesc, "dbSchema"."DiseaseType".description FROM "dbSchema"."Disease"
									JOIN "dbSchema"."DiseaseType"
									ON "dbSchema"."Disease".id = "dbSchema"."DiseaseType".id
									ORDER BY "dbSchema"."Disease".id';
						$result = pg_query($connect, $query);
						if ($result) {
							if (pg_num_rows($result) > 0) {
								while ($row = pg_fetch_assoc($result)) {
									?>
										<tr>
											<td>
												<?php echo $row['disease_code']; ?>
											</td>
											<td>
												<?php echo $row['pathogen']; ?>
											</td>
											<td>
												<?php echo $row['description']; ?>
											</td>
											<td>
												<?php echo $row['ddesc']; ?>
											</td>
										</tr>
									<?php
								}
							}
						}
					?>
				</table>
			</div>

			<div class="div-section">
				<div class="div-title">
					<h3>disease statistics</h3>
				</div>
				<table class="stats">
					<?php
						$query = 'SELECT "dbSchema"."Country".cname, "dbSchema"."Country".population, SUM("dbSchema"."Record".total_patients) as tp, SUM("dbSchema"."Record".total_deaths) as td
									FROM "dbSchema"."Country", "dbSchema"."Record"
									WHERE "dbSchema"."Record".cname = "dbSchema"."Country".cname
									GROUP BY "dbSchema"."Country".cname
									ORDER BY "dbSchema"."Country".population DESC';
						$result = pg_query($connect, $query);
						if ($result) {
							if (pg_num_rows($result) > 0) {
								while ($row = pg_fetch_assoc($result)) {
									?>
									<tr>
										<th colspan="4" style="border:none; padding-top: 50px; text-align: right;">
											<h5>
												<b>Country:</b> <?php echo $row['cname']; ?>
												--- <b>Population:</b> <?php echo $row['population']; ?>
												--- <b>Total patients:</b> <?php echo $row['tp']; ?>
												--- <b>Total deaths:</b> <?php echo $row['td']; ?>
											</h5>
										</th>
									</tr>
									<?php
										$record = 'SELECT email, disease_code, total_deaths, total_patients FROM "dbSchema"."Record" WHERE cname = \''.$row['cname'].'\'';
										$recordres = pg_query($connect, $record);
										if ($recordres) {
											if (pg_num_rows($recordres) > 0) {
												?>
												<tr>
													<th>
														Who recorded
													</th>
													<th>
														Disease name
													</th>
													<th>
														# of patients
													</th>
													<th>
														# of deaths
													</th>
												</tr>

												<?php
												while ($recrow = pg_fetch_assoc($recordres)) {
													?>
														<tr>
															<td>
																<?php echo $recrow['email']; ?>
															</td>
															<td>
																<?php echo $recrow['disease_code']; ?>
															</td>
															<td>
																<?php echo $recrow['total_patients']; ?>
															</td>
															<td>
																<?php echo $recrow['total_deaths']; ?>
															</td>
														</tr>
													<?php
												}
											}
										}
								}
							}
						}
					?>
				</table>
			</div>

			<div class="div-section col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="div-title">
					<h3>doctors</h3>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<?php
						$query = 'SELECT "dbSchema"."Doctor".email, "dbSchema"."Doctor".degree, "dbSchema"."Users".name, "dbSchema"."Users".surname, "dbSchema"."Users".cname, "dbSchema"."Users".phone
									FROM "dbSchema"."Doctor"
									JOIN "dbSchema"."Users"
									ON "dbSchema"."Doctor".email = "dbSchema"."Users".email';
						$result = pg_query($connect, $query);
						if ($result) {
							if (pg_num_rows($result) > 0) {
								while ($row = pg_fetch_assoc($result)) {
									?>
									<div class="shell col-lg-4 col-md-4 col-sm-6 col-xs-12">
										<div onmouseover="showInfo(this)" onmouseout="hideInfo(this)" class="doctor-card col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<h4><?php echo $row['name'].' '.$row['surname']; ?></h4>
											<h5><?php echo $row['cname']; ?></h5>
											<div class="hidden-info inactive">
												<h5>Contacts: </h5>
												<ul style="text-align: left;">
													<li><?php echo $row['email']; ?></li>
													<li><?php echo $row['phone']; ?></li>
												</ul>
												<?php
													$spec = 'SELECT "dbSchema"."Specialize".email, "dbSchema"."DiseaseType".description
																FROM "dbSchema"."Specialize"
																JOIN "dbSchema"."DiseaseType"
																ON "dbSchema"."Specialize".id = "dbSchema"."DiseaseType".id
																WHERE email = \''.$row['email'].'\'';
													$specres = pg_query($connect, $spec);
													if ($specres) {
														if (pg_num_rows($specres) > 0) {
															?>
															<h5>Specialized in:</h5>
															<ul style="text-align: left;">
															<?php
															while ($specrow = pg_fetch_assoc($specres)) {
																?>
																	<li><?php echo $specrow['description']; ?></li>
																<?php
															}
														}
														?>
														</ul>
														<?php
													}
												?>
											</div>
											<h5><?php echo $row['degree']; ?></h5>
										</div>
									</div>
									<?php
								}
							}
						}
					?>
				</div>
			</div>
			<div class="div-section col-lg-12 col-md-12 col-sm-12 col-xs-12 footer">
				<h4>The website was created as a Bonus Task</h4>
				<h5>by Yernar Yergaziyev, 2021</h5>
			</div>
		</div>
	</div>


</body>
</html>
