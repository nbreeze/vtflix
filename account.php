<?php session_start(); 
	if ( $_SESSION['user_id'] == null )
	{
		header("Location: login.php");
		exit();
	}
?>

<html>
	<head>
		<?php require('pages/styles.php'); ?>
		<title>VTFlix - Account Settings</title>
		
		<script>
			function freqSubmit(result)
			{
				event.preventDefault();
				
				let freqId = objectifyForm( $( "#freqForm" ).get(0) )['freq_id'];
				
				respondToFriendRequest( freqId, result, function(data) {
					console.log( data );
					
					getFriendRequests();
					
				});
			}
		
			function getFriendRequests()
			{
				$.post( "php/userinfo.php", { user_id: <?php echo $_SESSION['user_id']; ?>, action: 'friendrequests' }, function(data) {
					let theList = $( '#friendrequests-list' );
					theList.html( '' );
					
					console.log( data );
					
					let count = 0;
					
					if ( data.length > 0 )
					{
						for ( let i = 0; i < data.length; i++ )
						{
							if ( data[i].result != null )
								continue;
							
							let item = $( '<div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"></div>' ); 
							item.append( '<a href="page.php?t=user&id=' + data[i]['user_id'] +'">' + data[i]['username'] + '</a>' );
							
							let freqActionForm = $( '<form class="form" id="freqForm"></form>' );
							item.append( freqActionForm );
							
							freqActionForm.append( '<input type="hidden" name="freq_id" value="' + data[i]['freq_id'] + '"/>' );
							freqActionForm.append( '<button class="btn btn-success" onclick="freqSubmit(1)">Accept</button>' );
							freqActionForm.append( '<button class="btn btn-danger" onclick="freqSubmit(0)">Decline</button>' );
							
							theList.append( item );
							
							count++;
						}
					}
					
					if ( count == 0 )
					{
						theList.append( '<p>You do not have any incoming friend requests.</p>' );
					}
					
				}, 'json');
			}
			
			$( function() {
				getFriendRequests();
			} );
			
		</script>
		
	</head>
	<body>
		<?php require('pages/header.php'); ?>
		<div class="jumbotron jumbotron-fluid bg-transparent" >
			<div class="container">
				<h2>Settings</h2>
			</div>
		</div>
		<div class="container container-fluid">
			<div class="row">
				<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
					<a class="nav-link active" id="overview-tab" data-toggle="pill" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
					<a class="nav-link" id="friendrequests-tab" data-toggle="pill" href="#friendrequests" role="tab" aria-controls="friendrequests" aria-selected="false">Friend Requests</a>
				</div>
				<div class="tab-content col sm-col-12" id="v-pills-tabContent">
					<div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
						<div class="container container-fluid">
							...
						</div>
					</div>
					<div class="tab-pane fade" id="friendrequests" role="tabpanel" aria-labelledby="friendrequests-tab">
						<div class="container container-fluid">
							<div class="list-group" id="friendrequests-list">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>