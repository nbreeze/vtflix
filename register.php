<?php session_start(); ?>

<html>
<head>
	<?php require( 'pages/styles.php' ); ?>
</head>
<body>
	<?php require( 'pages/header.php' ); ?>
	
	<script>
		function register( event )
		{
			event.preventDefault();
			
			let registerData = objectifyForm( $("#registerForm").get(0) );
			registerData['action'] = 'register';
			
			console.log( registerData );
			
			$.post( "php/useraction.php", registerData, function( data ) {
				
				console.log( data );
				
				if ( data != null && data.result )
				{
					alert( "Your account has been successfully registered. Please login with your details." );
					window.location = "login.php";
					return;
				}
				
			}, "json");
		}
	</script>
	
	<div class="container container-fluid">
		<div class="jumbotron jumbotron-fluid bg-transparent">
			<div class="container">
				<h2>Register</h2>
			</div>
		</div>
		<form id="registerForm" method="POST" onsubmit="register(event)">
		  <div class="form-group">
			<label for="registerUserName">Username</label>
			<input type="text" class="form-control" name="username" id="registerUserName">
		  </div>
		  <div class="form-group">
			<label for="registerPassword">Password</label>
			<input type="password" class="form-control" name="password" id="registerPassword">
		  </div>
		  <div class="form-group">
			<label for="registerName">Name</label>
			<input type="text" class="form-control" name="name" id="registerName">
		  </div>
		  <div class="form-group row">
			  <div class="col sm-col-6">
				<label for="registerAge">Age</label>
				<input type="text" class="form-control" name="age" id="registerAge">
			  </div>
			  <div class="col sm-col-6">
				<label for="registerGender">Gender</label>
				<input type="text" class="form-control" name="gender" id="registerGender">
			  </div>
			</div>
		  
		  <div class="form-group">
			<label for="registerLocation">Location</label>
			<input type="text" class="form-control" name="location" id="registerLocation">
		  </div>
		  
		  <button type="submit" class="btn btn-primary">Submit</button>
		  
		</form>
	</div>
</body>
</html>