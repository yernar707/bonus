<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body style="width: 400px; margin: auto">
	<div class="auth-form col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="div-title">
			<h3>Login</h3>
		</div>
		<form method="POST" action="php/login-proc.php">
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="email" name="email" required="" placeholder="Email">
			</div>
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<input type="password" name="password" placeholder="Password">
			</div>
			<div class="div-input col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
				<input type="submit" name="login" value="login">
			</div>
		</form>
		<div class="div-links col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<a href="index.php">
					← Go to main page
				</a>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="text-align: right;">
				<a href="signup.php">
					Go sign up →
				</a>
			</div>
		</div>
		
	</div>
</body>
</html>