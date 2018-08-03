<?php

session_start();

$dbinfo = ( require( 'php/dbinfo.php' ) );
$DB = new mysqli($dbinfo["hostname"], $dbinfo["username"], $dbinfo["password"], $dbinfo["database"]);

if ($DB->connect_error )
	die('Could not connect: ' . $DB->connect_error);

$pageHandled = false;

$type = strtolower( $_GET["t"] );
if ( strlen( $type ) > 0 )
{
	if ( strcmp( $type, "movie" ) == 0 )
	{
		$id = $_GET["id"];
		if ( $id && strlen( $id ) > 0 && ctype_digit( $id ) )
		{
			$movieId = intval( $id, 10 );
			$query = $DB->query( "SELECT * FROM Movie INNER JOIN Video ON Video.video_id = Movie.video_id WHERE Movie.mv_id = {$movieId}" );
			if ( $query->num_rows > 0 )
			{
				$pageHandled = true;
				$videoInfo = $query->fetch_assoc();
				$videoInfo['type'] = 'movie';
				require( 'pages/video.php' );
			}
		}
	}
	else if ( strcmp( $type, "tv_episode" ) == 0 )
	{
		$id = $_GET["id"];
		if ( $id && strlen( $id ) > 0 && ctype_digit( $id ) )
		{
			$id = intval( $id, 10 );
			$query = $DB->query( "SELECT * FROM TV_Episode, Video WHERE TV_Episode.video_id = Video.video_id AND TV_Episode.tvep_id = {$id}" );
			if ( $query->num_rows > 0 )
			{
				$pageHandled = true;
				$videoInfo = $query->fetch_assoc();
				$videoInfo['type'] = 'tv_episode';
				require( 'pages/video.php' );
			}
		}
	}
	else if ( strcmp( $type, "collection" ) == 0 )
	{
		$id = $_GET["id"];
		if ( $id && strlen( $id ) > 0 && ctype_digit( $id ) )
		{
			$collectionId = intval( $id, 10 );
			$query = $DB->query( "SELECT * FROM Collection WHERE collection_id = {$collectionId}" );
			if ( $query->num_rows > 0 )
			{
				$pageHandled = true;
				$collectionInfo = $query->fetch_assoc();
				$collectionInfo['type'] = 'collection';
				require( 'pages/collection.php' );
			}
		}
	}
	else if ( strcmp( $type, "user" ) == 0 )
	{
		$id = $_GET["id"];
		if ( $id && strlen( $id ) > 0 && ctype_digit( $id ) )
		{
			$userId = intval( $id, 10 );
			$query = $DB->query( "SELECT * FROM User WHERE user_id = {$userId}" );
			if ( $query->num_rows > 0 )
			{
				$pageHandled = true;
				$userInfo = $query->fetch_assoc();
				require( 'pages/userprofile.php' );
			}
		}
	}
	else if ( strcmp( $type, "actor" ) == 0 )
	{
		$id = $_GET["id"];
		if ( $id && strlen( $id ) > 0 && ctype_digit( $id ) )
		{
			$id = intval( $id, 10 );
			$query = $DB->query( "SELECT * FROM Actors WHERE actor_id = {$id}" );
			if ( $query->num_rows > 0 )
			{
				$pageHandled = true;
				$bioInfo = $query->fetch_assoc();
				$bioInfo['type'] = 'actor';
				require( 'pages/bio.php' );
			}
		}
	}
	else if ( strcmp( $type, "director" ) == 0 )
	{
		$id = $_GET["id"];
		if ( $id && strlen( $id ) > 0 && ctype_digit( $id ) )
		{
			$id = intval( $id, 10 );
			$query = $DB->query( "SELECT * FROM Director WHERE director_id = {$id}" );
			if ( $query->num_rows > 0 )
			{
				$pageHandled = true;
				$bioInfo = $query->fetch_assoc();
				$bioInfo['type'] = 'director';
				require( 'pages/bio.php' );
			}
		}
	}
}

if (!$pageHandled)
	require( 'pages/errors/404.php' );

$DB->close();

?>