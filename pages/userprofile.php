
<html>
<head>
	<?php require( 'pages/styles.php' ); ?>
	
	<title>VTFlix - <?php echo $userInfo['username'] . '\'s Profile'; ?></title>
</head>

<body>
	<?php require( 'pages/header.php' ); ?>
	
	<div class="jumbotron jumbotron-fluid bg-transparent">
		<div class="container">
			<h1 class="display-4"><?php echo $userInfo['username']; ?></h1>
		</div>
	</div>
	<div class="container container-fluid">
		<ul class="nav">
			<li class="nav-item">
				<button onclick="onPressFriendRequestButton(event)" class="btn btn-primary" <?php if ( $userInfo['user_id'] == $_SESSION['user_id'] || $_SESSION['user_id'] == null ) echo 'disabled'; ?>>Add Friend</button>
			</li>
		</ul>
		<div class="container container-fluid">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item">
				<a class="nav-link active" id="profile-general-tab" data-toggle="tab" href="#profile-general" role="tab" aria-controls="profile-general" aria-selected="true">About</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="profile-ratings-tab" data-toggle="tab" href="#profile-ratings" role="tab" aria-controls="profile-ratings" aria-selected="false">Ratings</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="profile-friends-tab" data-toggle="tab" href="#profile-friends" role="tab" aria-controls="profile-friends" aria-selected="false">Friends</a>
			  </li>
			</ul>
			<div class="tab-content" id="profileTabContent">
				<div class="tab-pane fade show active" id="profile-general" role="tabpanel" aria-labelledby="profile-general-tab">
					<div class="container container-fluid">
						<div class="row">
							<div class="col-sm-2">
								<span>Name:</span>
							</div>
							<div class="col-sm-10">
								<span><?php echo $userInfo['name']; ?></span>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-2">
								<span>Age:</span>
							</div>
							<div class="col-sm-10">
								<span><?php echo $userInfo['age']; ?></span>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-2">
								<span>Gender:</span>
							</div>
							<div class="col-sm-10">
								<span><?php echo $userInfo['gender']; ?></span>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-2">
								<span>Location:</span>
							</div>
							<div class="col-sm-10">
								<span><?php echo $userInfo['location']; ?></span>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="profile-ratings" role="tabpanel" aria-labelledby="profile-ratings-tab">
					<div class="container container-fluid">
						<div class="list-group" id="profile-ratings-list">
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="profile-friends" role="tabpanel" aria-labelledby="profile-friends-tab">
					<div class="container container-fluid">
						<div class="list-group" id="profile-friends-list">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script>
		if ( sessionUserData != null && sessionUserData.username != null )
		{
			console.log( "Test" );
			
			$( "#profile-general" ).html( profileHTML );
		}
		
		function getRatings()
		{
			$.post( "php/userinfo.php", { user_id: <?php echo $userInfo['user_id']; ?>, action: 'ratings' }, function(data) {
				let theList = $( '#profile-ratings-list' );
				theList.html( '' );
				
				console.log( data );
				
				if ( data.length > 0 )
				{
					for ( let i = 0; i < data.length; i++ )
					{
						let item = '<div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"><span>' + data[i]['title'] + '</span><span class="badge badge-primary">' + data[i]['rating'] + '</span></div>';
						theList.append( item );
					}
				}
				
			}, 'json');
		}
		
		function getFriends()
		{
			$.post( "php/userinfo.php", { user_id: <?php echo $userInfo['user_id']; ?>, action: 'friends' }, function(data) {
				let theList = $( '#profile-friends-list' );
				theList.html( '' );
				
				console.log( data );
				
				if ( data.length > 0 )
				{
					for ( let i = 0; i < data.length; i++ )
					{
						let item = '<div class="list-group-item list-group-item-action d-flex align-items-center"><a href="page.php?t=user&id='+ data[i]['user_id'] +'">' + data[i]['username'] + '</a></div>';
						theList.append( item );
					}
				}
				else
				{
					theList.append( '<p>This user has no friends.</p>' );
				}
				
			}, 'json');
		}
		
		function onPressFriendRequestButton(event)
		{
			event.preventDefault();
			sendFriendRequest(<?php echo $userInfo['user_id']; ?>);
		}
		
		$( function() { 
			getRatings(); 
			getFriends();
		});
		
	</script>
	
</body>

</html>