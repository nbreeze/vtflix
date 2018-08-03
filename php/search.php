<?php

$dbinfo = require( 'dbinfo.php' );

$DB = new mysqli($dbinfo["hostname"], $dbinfo["username"], $dbinfo["password"], $dbinfo["database"]);

if ($DB->connect_error )
	die('Could not DBect: ' . $DB->connect_error);

$jsonRes = array();

$q = $DB->escape_string($_GET['q']);
if (strlen( $q ) > 0 )
{
	$query = $DB->query("SELECT * FROM Movie INNER JOIN Video ON Video.video_id = Movie.video_id WHERE Video.title LIKE '%{$q}%' ORDER BY Video.title ASC");
	if ($query)
	{
		while ( $row = $query->fetch_assoc() ) {
			$row['type'] = 'movie';
			array_push( $jsonRes, $row );
		}
	}
	
	$query = $DB->query("SELECT * FROM Collection WHERE title LIKE '%{$q}%' ORDER BY title ASC");
	if ($query)
	{
		while ( $row = $query->fetch_assoc() ) {
			$row['type'] = 'collection';
			array_push( $jsonRes, $row );
		}
	}
	
	$query = $DB->query("SELECT TV_Episode.tvep_id, Video.video_id, Collection.title AS collection_title, Video.title, TV_Episode.episode_num, TV_Episode.season_num FROM TV_Episode, Video, Collection WHERE TV_Episode.video_id = Video.video_id and TV_Episode.collection_id = Collection.collection_id AND Video.title LIKE '%{$q}%' ORDER BY Video.title ASC");
	if ($query)
	{
		while ( $row = $query->fetch_assoc() ) {
			$row['type'] = 'tv_episode';
			array_push( $jsonRes, $row );
		}
	}
	
	$query = $DB->query("SELECT * FROM Director WHERE name LIKE '%{$q}%' ORDER BY name ASC");
	if ($query->num_rows > 0)
	{
		while ( $row = $query->fetch_assoc() ) {
			$row['type'] = 'director';
			array_push( $jsonRes, $row );
		}
	}
	
	$query = $DB->query("SELECT * FROM Actors WHERE name LIKE '%{$q}%' ORDER BY name ASC");
	if ($query->num_rows > 0)
	{
		while ( $row = $query->fetch_assoc() ) {
			$row['type'] = 'actor';
			array_push( $jsonRes, $row );
		}
	}
}

$DB->close();

echo json_encode( $jsonRes );



?>