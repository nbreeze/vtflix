<?php

session_start();

if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) die();

$action = $_POST["action"];

$actionRes = array();
$actionRes['result'] = false;

$dbinfo = require( 'dbinfo.php' );

$DB = new mysqli($dbinfo["hostname"], $dbinfo["username"], $dbinfo["password"], $dbinfo["database"]);

if (!$DB->connect_error )
{
	if ( $action && strlen( $action ) > 0 )
	{
		$action = strtolower( $action );
	
		if ( $_SESSION['user_id'] != null )
		{
			$actionRes['logged_in'] = true;
			
			switch ($action)
			{
				case "ratevideo":
				{
					$videoId = $_POST["video_id"];
					$rating = $_POST["rating"];
					if ( $videoId != null && ctype_digit( $videoId ) && $rating != null && ctype_digit( $rating ) )
					{
						$videoId = intval( $videoId, 10 );
						$rating = max( min( intval( $rating, 10 ), 5 ), 0 );
						
						$query = $DB->query( 'SELECT * FROM Video WHERE video_id = ' . $videoId );
						if ( $query->num_rows > 0 )
						{
							$query = $DB->query( 'INSERT INTO Video_User_Rating (rating, video_id, user_id) VALUES (' . $rating . ', ' . $videoId . ', ' . $_SESSION['user_id'] . ')' );
							if ( $query )
							{
								$actionRes['result'] = true;
							}
						}
					}
					
					break;
				}
				case "friendrequest_send":
				{
					$friendId = $_POST['friend_id'];
					if ( $friendId && ctype_digit( $friendId ) )
					{
						$friendId = intval( $friendId, 10 );
						if ( $friendId != $_SESSION['user_id'] )
						{
							$query = $DB->query("SELECT * FROM User WHERE user_id = " . $friendId );
							if ( $query && $query->num_rows > 0 )
							{
								$query = $DB->query("SELECT * FROM User_Friends WHERE user_id = " . $_SESSION['user_id'] . " AND friend_id = " . $friendId );
								if ( $query && $query->num_rows == 0 )
								{
									$actionRes['not_friends'] = true;
									
									$query = $DB->query( 'INSERT INTO User_Friend_Requests (user_id, friend_id) VALUES (' . $_SESSION['user_id'] . ', ' . $friendId . ')' );
									if ( $query )
									{
										$actionRes['result'] = true;
									}
								}
							}
						}
					}
					
					break;
				}
				case "friendrequest_reply":
				{
					$freqId = $_POST['freq_id'];
					$result = $_POST['result'];
					if ( $freqId != null && ctype_digit( $freqId ) && $result != null && ctype_digit( $result ) )
					{
						$freqId = intval( $freqId, 10 );
						$result = intval( $result, 10 );
						
						$query = $DB->query("SELECT * FROM User_Friend_Requests WHERE freq_id = " . $freqId . " AND friend_id = " . $_SESSION['user_id'] );
						if ( $query && $query->num_rows > 0 )
						{
							$freqInfo = $query->fetch_assoc();
							$query = $DB->query("UPDATE User_Friend_Requests SET result = " . $result . ", answer_date = NOW() WHERE freq_id = " . $freqId );
							if ( $query )
							{
								if ( $result == 1 )
								{
									$query = $DB->query("SELECT * FROM User_Friends WHERE user_id = " . $_SESSION['user_id'] . " AND friend_id = " . $freqInfo['user_id'] );
									if ( $query && $query->num_rows == 0 )
									{
										$query = $DB->query('INSERT INTO User_Friends (user_id, friend_id) VALUES (' . $_SESSION['user_id'] . ', ' . $freqInfo['user_id'] . ')' );
									}
									
									$query = $DB->query("SELECT * FROM User_Friends WHERE user_id = " . $freqInfo['user_id'] . " AND friend_id = " . $_SESSION['user_id'] );
									if ( $query && $query->num_rows == 0 )
									{
										$query = $DB->query('INSERT INTO User_Friends (user_id, friend_id) VALUES (' . $freqInfo['user_id'] . ', ' . $_SESSION['user_id'] . ')' );
									}
								}
								
								$actionRes['result'] = true;
							}
						}
					}
					
					break;
				}
				case "logout":
				{
					$_SESSION['user_id'] = null;
					session_destroy();
					$actionRes['result'] = true;
					break;
				}
			}
		}
		else
		{
			switch ($action)
			{
				case 'login':
				{
					$userName = $_POST['username'];
					$password = $_POST['password'];
					
					if ( $userName != null && strlen( $userName ) > 0 &&
						$password != null && strlen( $password ) > 0 )
					{
						$userName = $DB->escape_string( $userName );
						$password = $DB->escape_string( $password );
						
						$actionRes['valid_input'] = true;
						
						$query = $DB->query("SELECT * FROM User WHERE username = '{$userName}' AND password = '{$password}'");
						if ($query->num_rows > 0 )
						{
							$row = $query->fetch_assoc();
							$actionRes['result'] = true;
							$actionRes['user_id'] = $row['user_id'];
							
							$_SESSION['user_id'] = $row['user_id'];
						}
					}
					break;
				}
				case 'register':
				{
					$userName = $_POST['username'];
					$password = $_POST['password'];
					$name = $_POST['name'];
					$age = $_POST['age'];
					$gender = $_POST['gender'];
					$location = $_POST['location'];
					
					if ( $userName != null && strlen( $userName ) > 0 && 
						$password != null && strlen( $password ) > 0 &&
						$name != null && strlen( $name ) > 0 && 
						$age != null && strlen( $age ) > 0 && ctype_digit( $age ) &&
						$gender != null && strlen( $gender ) > 0 &&
						$location != null && strlen( $location ) > 0 )
					{
						$userName = $DB->escape_string( $userName );
						$password = $DB->escape_string( $password );
						$gender = $DB->escape_string( $gender );
						$location = $DB->escape_string( $location );
						$age = intval( $age, 10 );
						
						$query = $DB->query("SELECT * FROM User WHERE username = '{$userName}'");
						if ( $query && $query->num_rows == 0 )
						{
							$actionRes['valid_input'] = true;
							
							$query = $DB->query("INSERT INTO User ( username, password, name, age, gender, location ) VALUES ( '{$userName}', '{$password}', '{$name}', '{$age}', '{$gender}', '{$location}' )");
							if ( $query )
							{
								$actionRes['result'] = true;
							}
						}
					}
				}
			}
		}
	}
	
	$DB->close();
}

echo json_encode( $actionRes );

?>