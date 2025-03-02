<?php
session_start();
// i forgor this and it took like half an hour to figure this out. this needs to be in all pages that use the session (basically all)
?>

<!-- this is just a thing that shows like if the user is logged in and it they're an admin -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
	
</head>
<body>

	<!--
		this code is sort of obvious in what it does. btw remember the first rows at the top to not waste time debugging
	-->
	
	<?php 
	if (empty($_SESSION['role'])) { ?>
		<h1>lmfao you are not even logged in. what a loser amiright? what? don't be rude? ok fine....</h1>
	<?php } ?>
	
	<?php 
	if (!empty($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'superadmin'], true)) { ?>
		<p><a href="/laiterekisteri/user_test">You are an admin or the superadmin</a></p>
		<h1>you can put anything here</h1>
	<?php } ?>


	<?php 
	if (!empty($_SESSION['role']) && $_SESSION['role'] === 'superadmin') { ?>
		<p><a href="/laiterekisteri/user_test">You are a superadmin</a></p>
		<h1>you can put anything here</h1>
	<?php } ?>
	
	<?php 
	if (!empty($_SESSION['role']) && $_SESSION['role'] === 'user') { ?>
		<p><a href="/laiterekisteri/user_test">haha you are just a regular user lmao</a></p>
		<h1>you can put anything here</h1>
	<?php } ?>
	
	
</body>
</html>
