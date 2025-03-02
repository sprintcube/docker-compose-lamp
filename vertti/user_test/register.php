<!-- register.php thing -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>

	<!-- this should not be accessible by regular people cus like why would this. -->
	<!-- this is just for testing and a demo -->
	<h1>Create an Account</h1>
	<form action="register_process.php" method="post">
		<label for="name">Name:</label>
		<input type="text" id="name" name="name" required autocomplete="off">

		<label for="email">Email:</label>
		<input type="email" id="email" name="email" required autocomplete="off">
		
		<label for="username">Username:</label> <!-- this like is just here cus the other thing depends on this and i don't like logging in with like first and last names cus that's very long. we could change this later to be like the teacher id (like the one in wilma that's like SUNDMIN) . i'll change this later  -->
		<input type="username" id="username" name="username" required autocomplete="off">

		<label for="password">Password:</label>
		<input type="password" id="password" name="password" required autocomplete="off">

		<button type="submit">Register</button>
	</form>
</body>
</html>
