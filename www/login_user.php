<!-- this is the login page or something. -->
<!-- we might put this so if the user is not logged in they are redirected here. -->
<!-- this used to be just named login.php, but it obviously had to be changed. -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
	
</head>
<body>
		<!-- this is copied from my other project. -->
            <h1>Login</h1>
            <form action="login_process.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
				<br>
				<a href="contact.html">Register</a>
            </form>
</body>
</html>
