<?php

session_start();

if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) die();

$dbinfo = require( 'dbinfo.php' );
$DB = new mysqli($dbinfo["hostname"], $dbinfo["username"], $dbinfo["password"], $dbinfo["database"]);

$actionRes = array();

if (!$DB->connect_error )
{
	$action = $_POST['action'];
	$userId = $_POST['user_id'];
	if ( $userId && ctype_digit( $userId ) && $action && strlen( $action ) > 0 )
	{
		$action = strtolower( $action );
		$userId = intval( $userId, 10 );
		$query = $DB->query("SELECT * FROM User WHERE user_id = " . $userId );
		if ( $query && $query->num_rows > 0 )
		{
			$userInfo = $query->fetch_assoc();
			
			switch ( $action )
			{
				case 'info':
				{
					$actionRes = $userInfo;
					$actionRes['password'] = null;
					break;
				}
				case 'friends':
				{
					$query = $DB->query("SELECT * FROM User_Friends, User WHERE User_Friends.friend_id = User.user_id AND User_Friends.user_id = " . $userId );
					if ($query && $query->num_rows > 0)
					{
						while ( $row = $query->fetch_assoc() )
						{
							$row['password'] = null;
							array_push( $actionRes, $row );
						}
					}
					
					break;
				}
				case 'friendrequests':
				{
					if ( $_SESSION['user_id'] != null && $_SESSION['user_id'] == $userId )
					{
						$query = $DB->query("SELECT * FROM User_Friend_Requests, User WHERE User_Friend_Requests.user_id = User.user_id AND User_Friend_Requests.friend_id = " . $userId );
						if ($query && $query->num_rows > 0)
						{
							while ( $row = $query->fetch_assoc() )
							{
								$row['password'] = null;
								array_push( $actionRes, $row );
							}
						}
					}
					break;
				}
				case 'ratings':
				{
					$videoId = $_POST['video_id'];
					if ( $videoId != null && ctype_digit( $videoId ) )
						$videoId = intval( $videoId, 10 );
					else
						$videoId = null;
					
					if ( $videoId == null )
						$query = $DB->query("SELECT * FROM Video_User_Rating, Video WHERE Video.video_id = Video_User_Rating.video_id AND Video_User_Rating.user_id = " . $userId . ' ORDER BY Video_User_Rating.date DESC' );
					else
						$query = $DB->query("SELECT * FROM Video_User_Rating, Video WHERE Video.video_id = Video_User_Rating.video_id AND Video_User_Rating.user_id = " . $userId . " AND Video_User_Rating.video_id = " . $videoId . ' ORDER BY Video_User_Rating.date DESC');
					
					if ($query && $query->num_rows > 0)
					{
						while ( $row = $query->fetch_assoc() )
							array_push( $actionRes, $row );
					}
					
					break;
				}
			}
		}
	}
	
	$DB->close();
}

echo json_encode( $actionRes );

?>