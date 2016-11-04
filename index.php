<?php 
	if((isset($_GET['fallback'])) && ($_GET['fallback']!='login')) require_once 'check.php';
?><!DOCTYPE html>
<html>
	<head>
		<title>Cogs Helpdesk</title>

		<link rel="stylesheet" type="text/css" href="site/cogs.css">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0">
	</head>

	<body>
		<header>
			<div class="wrapper">
				<div class="logo"><img src="site/logo-small.png" alt="Make-It-All Logo"></div>

				<a href="home" class="home"><img src="img/icons/home-small.png" alt="Home"></a>
				
				<div class="right-buttons">
					<form method="POST"><input type="search" name="search" id="search"></form>
					<a href="settings"><img src="img/icons/settings.png" alt="Settings"></a>
					<a href="logout"><img src="img/icons/logout.png" alt="Log out"></a>
				</div>
			</div>
		</header>

		<div class="wrapper" id="content" <?php if(isset($_GET['fallback'])) echo 'class="fallback"'; ?>>
			<?php
				if(isset($_GET['fallback'])) include 'pages/'.$_GET['fallback'].'.php';
				else echo '<p class="noJS error">JavaScript is not currently enabled - this will decrease the user experience but the site is still usable, please continue to the <a href="'.(isset($_SESSION['user']) ? 'home">home' : 'login">login').' page</a></p>'; // homepage
			?>
		</div>

		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
		<script src="site/jquery.mobile.custom.min.js"></script>
		<script type="text/javascript">
			$('.noJS').remove();

			$('header a').off('click touchend').on('click touchend', function (e) {
					e.preventDefault();
					loadPage(this.href);
				});

			function JSifyLinks() {
				$('#content a').off('click touchend').on('click touchend', function (e) {
					e.preventDefault();
					loadPage(this.href);
				});
			}

			function loadPage(page) {
				page = lastSplit('/',page) // some browsers add on the full URL before, this removes it
				if(page=='') page = 'grid';
				$.ajax({
					type: "GET",
					url: 'pages/'+page+".php",
					success: function(data, status, xhr) { // on loading the page...
						var refresh = xhr.getResponseHeader('refresh');
						if(refresh) loadPage('login'); // not logged in
						else { // logged in
							$("#content").fadeTo(150,0, function() { // fade out then put the new content in
								$("#content").html(data);
								JSifyLinks();
								$("#content").fadeTo(350,1);
							});
						}
					}, error: function(error) { // when there's a link to a page that doesn't exist
						console.log('Tried to load "'+page+'" and got a '+error.status);
						tempError('There was an error loading the page, please try again');
						JSifyLinks(); // might need to JSify the links up again, probably not
					}
				});
			}

			$(document).ready(function(){
				loadPage('login');
			});


			// move logo over when searching
			$('#search').focus(function() {
				$('.logo').addClass('searching');
			});

			$('#search').blur(function() {
				$('.logo').removeClass('searching');
			});

			function tempError(error) {
				$('<p class="error">'+error+'</p>').prependTo("#content").delay(2000).slideUp(500);
			}

			function lastSplit(char,string) {
				return string.split(char)[string.split(char).length-1];
			}
		</script>
	</body>
</html>