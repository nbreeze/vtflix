<?php
	$videoInfo['release_date'] = strtotime( $videoInfo['release_date'] );
?>

<html>
<head>
	<?php require( 'pages/styles.php' ); ?>
	
	<title>VTFlix - <?php echo $videoInfo['title']; ?></title>
</head>

<body>
	<?php require( 'pages/header.php' ); ?>
	
	<div class="jumbotron jumbotron-fluid bg-transparent">
		<div class="container">
			<h1><?php echo $videoInfo['title']; ?></h1>
			<?php
				if ( $videoInfo['type'] == 'tv_episode' )
				{
					$query = $DB->query( "SELECT * FROM Collection WHERE collection_id = " . $videoInfo['collection_id'] );
					if ( $query->num_rows > 0 )
					{
						$row = $query->fetch_assoc();
						echo '<h1><small>' . $row['title'] . ' | Season ' . $videoInfo['season_num'] . ', Episode ' . $videoInfo['episode_num'] . '</small></h1>';
					}
				}
			?>
			<h1><small><?php echo date( 'Y', $videoInfo['release_date'] ); ?></small></h1>
			<div class="container row justify-content-between">
			<?php
				$videoRating = 0;
				$query = $DB->query( 'SELECT AVG(rating) AS rating_avg FROM Video_User_Rating WHERE video_id = ' . $videoInfo['video_id'] );
				if ( $query->num_rows > 0 )
				{
					$row = $query->fetch_assoc();
					if ( $row['rating_avg'] )
						$videoRating = floatval( $row['rating_avg'] );
				}
				
				echo '<h2><span class="display-4">' . round( $videoRating, 1 ) . '</span>/<small>5</small></h2>';
			?>
				<form class="form-inline" id="ratingForm" onsubmit="submitRating(event)">
					<div class="form-group row">
						<select class="form-control" name="rating" <?php if ( $_SESSION['user_id'] == null ) echo 'disabled'; ?>>
							<option>5</option>
							<option>4</option>
							<option>3</option>
							<option>2</option>
							<option>1</option>
							<option>0</option>
						</select>
						<button class="btn" type="submit"<?php if ( $_SESSION['user_id'] == null ) echo 'disabled'; ?>>Rate this!</button>
					</div>
				</form>
				
				<script>
					function submitRating(event)
					{
						event.preventDefault();
						
						let ratingFormData = objectifyForm( $('#ratingForm').get(0) );
						let actionData = { action: 'ratevideo', video_id: <?php echo $videoInfo['video_id']; ?>, rating: parseInt( ratingFormData.rating ) };
						console.log( actionData );
						
						$.post( "php/useraction.php", actionData, function(data) {
							console.log( data );
							if ( data.result )
								alert( "Thanks for your rating!" );
						}, 'json');
					}
				</script>
				
			</div>
		</div>
	</div>
	
	<div class="container container-fluid">
		<h3>Info</h3>
		<div class="container container-fluid">
			<p>Release Date: <?php echo date( "F j, Y", $videoInfo['release_date'] ); ?> </p>
			<p>Produced by: <?php echo $videoInfo['producer']; ?> </p>
			<p>Directed by: <?php 
				$query = null;
				if ( $videoInfo['type'] == 'movie' )
					$query = $DB->query( "SELECT * FROM Movie_Directors INNER JOIN Director ON Movie_Directors.director_id = Director.director_id WHERE Movie_Directors.mv_id = " . $videoInfo['mv_id'] );
				else if ( $videoInfo['type'] == 'tv_episode' )
					$query = $DB->query( "SELECT * FROM TV_Episode_Directors INNER JOIN Director ON TV_Episode_Directors.director_id = Director.director_id WHERE TV_Episode_Directors.tvep_id = " . $videoInfo['tvep_id'] );
				
				if ( $query->num_rows > 0 )
				{
					$i = 0;
					while ( $row = $query->fetch_assoc() )
					{
						if ( $i > 0 )
							echo ', ';
						echo '<a href="page.php?t=director&id=' . $row['director_id'] . '">' . $row['name'] . '</a>';
						$i++;
					}
				}
			?>
			</p>
			<p>Rating: <?php echo $videoInfo['mpaa']; ?> </p>
			<p>Genres: <?php 
				$query = $DB->query( "SELECT * FROM Video_Genre WHERE Video_Genre.video_id = " . $videoInfo['video_id'] );
				if ( $query->num_rows > 0 )
				{
					$i = 0;
					while ( $row = $query->fetch_assoc() )
					{
						if ( $i > 0 )
							echo ', ';
						echo $row['genre'];
						$i++;
					}
				}
			?>
			<p>Color type: <?php echo $videoInfo['color']; ?> </p>
		</div>
		<h3>Cast</h3>
		<div class="container container-fluid">
			<ul>
			<?php
				$query = $DB->query( "SELECT * FROM Video_Actors INNER JOIN Actors ON Video_Actors.actor_id = Actors.actor_id WHERE Video_Actors.video_id = " . $videoInfo['video_id'] );
				if ( $query->num_rows > 0 )
				{
					while ( $row = $query->fetch_assoc() )
					{
						echo '<li><a href="page.php?t=actor&id=' . $row['actor_id'] . '">' . $row['name'] . '</a> as ' . $row['role'] . '</li>';
					}
				}
				else
				{
					echo "<li>This video had no one casted for it.</li>";
				}
			?>
			</ul>
		</div>
	</div>
</body>
</html>