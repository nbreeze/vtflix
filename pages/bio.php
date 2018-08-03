
<html>
<head>
	<?php require( 'pages/styles.php' ); ?>
	
	<title>VTFlix - <?php echo $bioInfo['name']; ?></title>
</head>

<body>
	<?php require( 'pages/header.php' ); ?>
	<div class="jumbotron jumbotron-fluid bg-transparent">
		<div class="container">
			<h1><?php echo $bioInfo['name']; ?></h1>
		</div>
	</div>
	
	<div class="container container-fluid">
		<h3>Info</h3>
		<div class="container container-fluid">
			<p>Age: <?php echo $bioInfo['age']; ?> </p>
			<p>Gender: <?php echo $bioInfo['gender']; ?> </p>
		</div>
		
		<h3>Filmography</h3>
		<div class="container container-fluid">
			<ul>
				<?php
					$query = null;
					
					$discographySet = array();
					
					if ( $bioInfo['type'] == 'actor' )
					{
						$query = $DB->query( "SELECT * FROM Movie, Video, Video_Actors WHERE Movie.video_id = Video.video_id AND Video.video_id = Video_Actors.video_id AND Video_Actors.actor_id = " . $bioInfo['actor_id'] );
						if ( $query && $query->num_rows > 0 )
						{
							while ( $row = $query->fetch_assoc() )
							{
								$row['type'] = 'movie';
								array_push( $discographySet, $row );
							}
						}
						
						$query = $DB->query( "SELECT * FROM TV_Episode, Video, Video_Actors WHERE TV_Episode.video_id = Video.video_id AND Video.video_id = Video_Actors.video_id AND Video_Actors.actor_id = " . $bioInfo['actor_id'] );
						if ( $query && $query->num_rows > 0 )
						{
							while ( $row = $query->fetch_assoc() )
							{
								$row['type'] = 'tv_episode';
								array_push( $discographySet, $row );
							}
						}
					}
					else if ( $bioInfo['type'] == 'director' )
					{
						$query = $DB->query( "SELECT * FROM Movie, Video, Movie_Directors WHERE Movie.video_id = Video.video_id AND Movie.mv_id = Movie_Directors.mv_id AND Movie_Directors.director_id = " . $bioInfo['director_id'] );
						if ( $query && $query->num_rows > 0 )
						{
							while ( $row = $query->fetch_assoc() )
							{
								$row['type'] = 'movie';
								array_push( $discographySet, $row );
							}
						}
						
						$query = $DB->query( "SELECT * FROM TV_Episode, Video, TV_Episode_Directors WHERE TV_Episode.video_id = Video.video_id AND TV_Episode.tvep_id = TV_Episode_Directors.tvep_id AND TV_Episode_Directors.director_id = " . $bioInfo['director_id'] );
						if ( $query && $query->num_rows > 0 )
						{
							while ( $row = $query->fetch_assoc() )
							{
								$row['type'] = 'tv_episode';
								array_push( $discographySet, $row );
							}
						}
					}
					
					if ( sizeof( $discographySet ) > 0 )
					{
						for ( $i = 0; $i < sizeof( $discographySet ); $i++ )
						{
							$videoType = $discographySet[$i]['type'];
							$href = '#';
							if ( strcmp( $videoType, 'movie' ) == 0 )
								$href = 'page.php?t=movie&id=' . $discographySet[$i]['mv_id'];
							else if ( strcmp( $videoType, 'tv_episode' ) == 0 )
								$href = 'page.php?t=tv_episode&id=' . $discographySet[$i]['tvep_id'];
							
							echo '<li><a href="' . $href . '">' . $discographySet[$i]['title'] . '</a></li>';
						}
					}
					else
					{
						echo '<li>This person has not been featured in any media.</li>';
					}
				?>
			</ul>
		</div>
		
	</div>
	
</body>

</html>