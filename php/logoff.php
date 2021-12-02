<?php
	unset($_COOKIE['user_email']);
	setcookie('user_email', null, -1, '/'); 
	unset($_COOKIE['user_name']);
	setcookie('user_name', null, -1, '/'); 
	unset($_COOKIE['user_role']);
	setcookie('user_role', null, -1, '/'); 

	?>
		<script type="text/javascript">
			fun1("Logged off", function() {
				document.location.replace('/bonus/');
			});

			function fun1(s, callback) {
				alert(s);
				callback();
			}
		</script>
	<?php
?>