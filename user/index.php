<?php
	require_once('../config/config.php');

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
?>
<!DOCTYPE html>
<html>
<head>
	<title>
		Bonus Task
	</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/bonus/style/style.css">
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
	<style type="text/css">
		.auth-form div input:not([type=submit]) {
			width: 100%;
		}
	</style>
</head>
<body>

	<nav class="navbar navbar-inverse nav-top">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
				<li><a href="/bonus">Bonus Task</a></li>
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
							<li class="active"><a href="user/"><?php echo $_COOKIE['user_name'].'('.$_COOKIE['user_role'].')'; ?> - My cabinet</a></li>
							<li><a href="php/logoff.php"><span class="glyphicon glyphicon-log-out"></span> Log Off</a></li>
						<?php
					}

				?>
			</ul>
		</div>
	</nav>

	<div style="width: 500px; margin: auto;">
		
		<div class="auth-form col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin: 50px auto;">
			<div class="div-title">
				<h3>My account</h3>
			</div>
			<form method="POST" action="php/update-info.php">
				<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<input type="text" maxlength="30" name="name" required="" value="<?php echo $name; ?>" placeholder="Name">
				</div>
				<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<input type="text" maxlength="40" name="surname" required="" value="<?php echo $surname; ?>" placeholder="Surname">
				</div>
				<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<input type="email" maxlength="60" name="email" required="" value="<?php echo $email; ?>" placeholder="Email">
				</div>
				<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="padding-top: 15px;">Country: </div>
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<select name="cname">
							<?php
								$query = 'SELECT cname FROM "dbSchema"."Country"';
								$result = pg_query($connect, $query);
								if ($result) {
								 	if (pg_num_rows($result)) {
								 		while ($row = pg_fetch_assoc($result)) {
								 			if ($cname == $row['cname']) {
								 				?>
								 					<option selected="" value="<?php echo $row['cname']; ?>"><?php echo $row['cname']; ?></option>
								 				<?php
								 			} else {
								 			?>
								 				<option value="<?php echo $row['cname']; ?>"><?php echo $row['cname']; ?></option>
								 			<?php
								 			}
								 		}
								 	}
								} 
							?>
						</select>
					</div>
				</div>
				<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<input id="phone" type="tel" oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Введите номер телефона в виде: +71231231234')" name="phone" required="" value="<?php echo $phone; ?>" placeholder="phone number" required="">
				</div>
				<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
					<input id="submit" type="submit" name="signup" value="Save" >
				</div>
			</form>
			
		</div>
	</div>

	
</body>
<script type="text/javascript">
	$('#phone').keyup(function(e){
	    var ph = this.value.replace(/\D/g,'').substring(0,11);
	    // Backspace and Delete keys
	    var deleteKey = (e.keyCode == 8 || e.keyCode == 46);
	    var len = ph.length;
	    if(len==0){
	        ph=ph;
	    }else if(len<1){
	        ph='+'+ph;
	    }else if(len==1){
	        ph = '+'+ph + (deleteKey ? '' : '-');
	    }else if(len<4){
	        ph='+'+ph.substring(0,1)+'-'+ph.substring(1,4);
	    }else if(len==4){
	        ph='+'+ph.substring(0,1)+'-'+ph.substring(1,4)+ (deleteKey ? '' : '-');
	    }else if (len<7){
	        ph='+'+ph.substring(0,1)+'-'+ph.substring(1,4)+'-'+ph.substring(4,7);
	    }else if (len==7) {
	        ph='+'+ph.substring(0,1)+'-'+ph.substring(1,4)+'-'+ph.substring(4,7)+ (deleteKey ? '' : '-');
	    }else {
	        ph='+'+ph.substring(0,1)+'-'+ph.substring(1,4)+'-'+ph.substring(4,7)+'-'+ph.substring(7,11);
	    }
	    this.value = ph;
	    console.log(len);
	});
</script>
</html>