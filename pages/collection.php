<html>
<head>
	<?php require( 'pages/styles.php' ); ?>
	<title>VTFlix - <?php echo $collectionInfo['title']; ?></title>
</head>

<body>
	<?php require( 'pages/header.php' ); ?>
	<div class="jumbotron jumbotron-fluid bg-transparent">
		<div class="container">
			<h1><?php echo $collectionInfo['title']; ?></h1>
		</div>
	</div>
	<div class="container container-fluid">
		<h2>Episode List</h3>
		<?php 
			$query = $DB->query( "SELECT * FROM TV_Episode, Video WHERE TV_Episode.video_id = Video.video_id AND TV_Episode.collection_id = " . $collectionInfo['collection_id'] . " ORDER BY TV_Episode.season_num ASC, TV_Episode.episode_num ASC" );
			if ( $query->num_rows > 0 )
			{
				$newSeason = false;
				$lastSeason = NAN;
				while ( $row = $query->fetch_assoc() )
				{
					if ( is_nan( $lastSeason ) || $row['season_num'] != $lastSeason )
						$newSeason = true;
					
					if ( $newSeason )
					{
						$newSeason = false;
						
						if ( !is_nan( $lastSeason ) )
							echo '</ol>';
						
						echo '<h3>Season ' . $row['season_num'] . '</h3>';
						echo '<ol>';
					}
					
					echo '<li value="' . $row['episode_num'] . '"><a href="page.php?t=tv_episode&id=' . $row['tvep_id'] . '">' . $row['title'] . '</a></li>';
					$lastSeason = intval( $row['season_num'], 10 );
				}
				
				echo '</ol>';
			}
		?>
	</div>
</body>
</html>