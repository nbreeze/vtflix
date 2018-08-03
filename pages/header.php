<nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between">
	<a class="navbar-brand mb-0 h1" href="index.php">VTFlix</a>
	
	<ul class="navbar-nav">
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="nav-username" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
			<div class="dropdown-menu dropdown-menu-right" aria-labelledby="nav-username" id="nav-username-menu">
				<a class="dropdown-item" href="login.php">Login</a>
				<a class="dropdown-item" href="register.php">Register</a>
			</div>
		</li>
	</ul>
	
	<script>
		var sessionUserData = null;
	
		function logout(event)
		{
			event.preventDefault();
			
			$.post( "php/useraction.php", { action: 'logout' }, function( data ) {
				console.log( data );
				if ( data.result )
					window.location = "index.php";
			}, 'json');
		}
		
		function sendFriendRequest(userId)
		{
			$.post( "php/useraction.php", { action: 'friendrequest_send', friend_id: parseInt( userId ) }, function( data ) {
				console.log( data );
				if ( data.result )
					alert('Your friend request has been sent!');
				else
					alert('Could not send friend request.');
			}, 'json');
		}
		
		function respondToFriendRequest( freq_id, result, success )
		{
			$.post( "php/useraction.php", { action: 'friendrequest_reply', freq_id: parseInt( freq_id ), result: parseInt( result ) }, success, 'json');
		}
		
		function objectifyForm(formArray) {
			let returnArray = {};
			
			for (let i = 0; i < formArray.length; i++)
				returnArray[formArray[i]['name']] = formArray[i]['value'];
			
			return returnArray;
		}
		
		$.post( 'php/userinfo.php', { user_id: <?php echo $_SESSION['user_id'] ? $_SESSION['user_id'] : 'null'; ?>, action: 'info' }, function( data ) {
			sessionUserData = data;
			if ( data.username != null )
			{
				$( "#nav-username" ).html( data.username );
				
				let usernameMenu = $( "#nav-username-menu" );
				usernameMenu.html('');
				
				usernameMenu.append( '<a class="dropdown-item" href=page.php?t=user&id=' + data.user_id + '>My Profile</a>' );
				usernameMenu.append( '<a class="dropdown-item" href="account.php">Settings</a>' );
				usernameMenu.append( '<a class="dropdown-item" href="#" onclick="logout(event)">Logout</a>' );
			}
			else
			{
				$( "#nav-username" ).html( "Guest User" );
			}
		}, 'json');
	</script>
</nav>