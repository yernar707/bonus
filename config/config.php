<?php 
	$host = "ec2-34-251-245-108.eu-west-1.compute.amazonaws.com";
	$port = "5432";
	$db = "dfvdlum9o8fleo";
	$user = "jslxnmkrsaclhn";
	$password = "366db994d32cbd0631d1510e839196ea62e71c2b0feabef4ec95110e658507ad";
	$conn_string = "host=".$host." port=".$port." dbname=".$db." user=".$user." password=".$password;
	$connect = pg_connect($conn_string);
?>