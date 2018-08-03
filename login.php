<?php session_start(); ?>

<html>
<head>
	<?php require( 'pages/styles.php' ); ?>
</head>
<body>
	<?php require( 'pages/header.php' ); ?>
	
	<script>
		function login( event )
		{
			event.preventDefault();
			
			let loginData = objectifyForm( $("#loginForm").get(0) );
			loginData['action'] = 'login';
			
			$.post( "php/useraction.php", loginData, function( data ) {
				
				console.log( data );
				
				if ( data != null && data.result )
				{
					window.location = "index.php";
					return;
				}
				
			}, "json");
		}
	</script>
	
	<div class="container container-fluid">
		<div class="jumbotron jumbotron-fluid bg-transparent">
			<div class="container">
				<h2>Login</h2>
			</div>
		</div>
		<form id="loginForm" method="POST" onsubmit="login(event)">
		  <div class="form-group">
			<label for="loginUserName">Username</label>
			<input type="text" class="form-control" name="username" id="loginUserName">
		  </div>
		  <div class="form-group">
			<label for="loginPassword">Password</label>
			<input type="password" class="form-control" name="password" id="loginPassword">
		  </div>
		  <button type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div>
</body>
</html>