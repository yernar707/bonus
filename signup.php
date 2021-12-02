<?php
	require_once('config/config.php');

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

?>
<!DOCTYPE html>
<html>
<head>
	<title>Sign up</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		function checkPassword() {
			var main = document.getElementById('main_pass').value;
			var rep = document.getElementById('rep_pass').value;
			var ind = document.getElementById('indicator');
			var ltr = document.getElementById('ltr-ind');
			var len = document.getElementById('len-ind');
			var same = false;
			var l = false;
			var lt = false;
			if (main.length >= 8 && main.length <= 32) {
				l = true;
				len.classList.remove('glyphicon-remove');
				len.classList.add('glyphicon-ok');
			} else {
				l = false;
				len.classList.remove('glyphicon-ok');
				len.classList.add('glyphicon-remove');
			}

			if (main.match(/^[0-9a-zA-Z]+$/)) {
				lt = true;
				ltr.classList.remove('glyphicon-remove');
				ltr.classList.add('glyphicon-ok');
			} else {
				lt = false;
				console.log('cyr');
				ltr.classList.remove('glyphicon-ok');
				ltr.classList.add('glyphicon-remove');
			}

			if (main == rep && main.length != 0) {
				same = true;
				ind.classList.remove('glyphicon-remove');
				ind.classList.add('glyphicon-ok');
			} else {
				same = false;
				ind.classList.remove('glyphicon-ok');
				ind.classList.add('glyphicon-remove');
			}

			if (l && lt && same) {
				document.getElementById('submit').removeAttribute("disabled");
			} else {
				document.getElementById('submit').setAttribute("disabled", "");
			}
		}
	</script>
	<style type="text/css">
		.div-input {
			position: relative;
		}
		.div-input #indicator {
			position: absolute;
			top: 50%;
			right: 10px;
			transform: translateY(-50%);
		}
		.glyphicon-remove {
			color: #d9534f;
		}
		.glyphicon-ok {
			color: #39ae2d;
		}
	</style>
</head>
<body style="width: 400px; margin: auto">
	<div style="margin-top: 50px;" class="auth-form col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="div-title">
			<h3>Sign up</h3>
		</div>
		<form method="POST" action="php/signup-proc.php">
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="text" maxlength="30" name="name" required="" placeholder="Name">
			</div>
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="text" maxlength="40" name="surname" required="" placeholder="Surname">
			</div>
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="email" maxlength="60" name="email" required="" placeholder="Email">
			</div>
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="password" id="main_pass" name="password" oninput="checkPassword();" placeholder="password" required="">
				<div style="text-align: left; font-size: 11px; padding: 0;">
					<label style="margin: 0;">8 to 32 symbols</label>
					<span id="len-ind" class="glyphicon glyphicon-remove"></span>
				</div>
				<div style="text-align: left; font-size: 11px; padding: 0;">
					<label style="margin: 0;">latin letters and/or digits only</label>
					<span id="ltr-ind" class="glyphicon glyphicon-remove"></span>
				</div>
			</div>
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="password" id="rep_pass" oninput="checkPassword()" name="reppass" placeholder="repeat password" required="">
				<span id="indicator" class="glyphicon glyphicon-remove"></span>
			</div>
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="padding-top: 15px;">Country: </div>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<select name="country">
						<?php
							$query = 'SELECT cname FROM "dbSchema"."Country"';
							$result = pg_query($connect, $query);
							if ($result) {
							 	if (pg_num_rows($result)) {
							 		while ($row = pg_fetch_assoc($result)) {
							 			?>
							 				<option value="<?php echo $row['cname']; ?>"><?php echo $row['cname']; ?></option>
							 			<?php
							 		}
							 	}
							 } 
						?>
					</select>
				</div>
			</div>
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input id="phone" type="tel" oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Введите номер телефона в виде: +71231231234')" name="phone" placeholder="phone number" required="">
			</div>
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
				<input id="submit" type="submit" name="signup" value="Sign up" disabled="">
			</div>
		</form>
		<div class="div-links col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<a href="index.php">
					← Go to main page
				</a>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="text-align: right;">
				<a href="login.php">
					Go login →
				</a>
			</div>
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