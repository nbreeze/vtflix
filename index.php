<?php session_start(); ?>

<html>
<head>
	<?php require('pages/styles.php'); ?>
	<title>VTFlix</title>
	
	<script>
	function showInfoSuggestions(element, str) {
		
		let menu = document.getElementById("info-suggest");
		
		if (str.length == 0)
		{
			clearInfoSuggestions();
			return;
		}
		
		let xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				let results = JSON.parse( this.responseText );
				if ( results != null )
				{
					let inner = "";
					if ( results.length <= 6 )
					{
						let cls = "list-group-item list-group-item-action flex-column align-items-start";
						
						for ( let i = 0; i < results.length; i++ )
						{
							let t = results[i]["type"];
							if ( t == "movie" )
							{
								inner += "<a class=\"" + cls + "\" href=\"page.php?t=" + t + "&id=" + results[i]["mv_id"] + "\">" + results[i]["title"] + "<br><small>Movie</small>" + "</a>";
							}
							else if ( t == "collection" )
							{
								inner += "<a class=\"" + cls + "\" href=\"page.php?t=" + t + "&id=" + results[i]["collection_id"] + "\">" + results[i]["title"] + "<br><small>TV Series</small>" + "</a>";
							}
							else if ( t == "tv_episode" )
							{
								inner += "<a class=\"" + cls + "\" href=\"page.php?t=" + t + "&id=" + results[i]["tvep_id"] + "\">" + results[i]["title"] + "<br><small>" + results[i]["collection_title"] + "</small>" + "<br><small>Season " + results[i]["season_num"] + ", Episode " + results[i]["episode_num"] + "</small>" + "</a>";
							}
							else if ( t == "director" )
							{
								inner += "<a class=\"" + cls + "\" href=\"page.php?t=" + t + "&id=" + results[i]["director_id"] + "\">" + results[i]["name"] + "<br><small>Director</small>" + "</a>";
							}
							else if ( t == "actor" )
							{
								inner += "<a class=\"" + cls + "\" href=\"page.php?t=" + t + "&id=" + results[i]["actor_id"] + "\">" + results[i]["name"] + "<br><small>Actor</small>" + "</a>";
							}
						}
					}
					
					menu.innerHTML = inner;
				}
			}
		};
		
		xmlhttp.open("GET", "php/search.php?q=" + str, true);
		xmlhttp.send();
	}
	
	function infoCheckFocus() {
		let menu = document.getElementById("info-suggest");
		let textBox = document.getElementById( "info-input" );
		if ( !menu.hasFocus() )
			clearInfoSuggestions();
	}
	
	function clearInfoSuggestions() {
		let menu = document.getElementById("info-suggest");
		menu.innerHTML = "";
	}
	</script>
</head>
<body>
	<?php require( 'pages/header.php' ); ?>
	
	<div id="main-content">
		<div class="jumbotron jumbotron-fluid bg-transparent">
			<div class="parallax" style="background-image: url(assets/home-bg.png); background-repeat: no-repeat; background-size: auto; z-index: -1; opacity: 0.35; position: absolute; left: 0px; top: 0px; width: 100vw; height: 100vh"></div>
			<div class="container container-fluid" >
				<h1 class="display-4">All the info you need of the videos you love in one place.</h1>
				<p class="lead">Whether you need the info about the video, the cast behind the big screen, or wholesome reviews by users across the Web, VTFlix has it all.</p>
				<form action="#" style="">
					<p class="lead">Search by video name, actor, or director:</p>
					<div class="form-group row">
						<div class="col-sm-6">
							<input type="text" class="form-control" id="info-input" style="width: 250px;" onkeyup="showInfoSuggestions(this, this.value)" onfocus="showInfoSuggestions(this, this.value)" onblur="infoCheckFocus()" >
						</div>
					</div>
				</form>
				<div class="list-group w-25" id="info-suggest">
				</div>
			</div>
		</div>
	</div>
</body>
</html>