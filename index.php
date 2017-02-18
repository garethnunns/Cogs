<?php
/*
This is the main page which acts as a wrapper, either including the requested page through PHP or calling it via AJAX.

Change log
==========

18/2/17 - Gareth Nunns
Fixed noJS functionality

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once 'site/secure.php';

	session_start();

	if(((isset($_GET['fallback'])) && ($_GET['fallback']!='login')) || empty(ltrim($_SERVER['REQUEST_URI'],'/'))) require_once 'check.php';
	else if($_GET['fallback']=='login' && isset($_SESSION['user']))
		header('Location: home');
?><!DOCTYPE html>
<html>
	<head>
		<title>Cogs Helpdesk</title>

		<meta charset="utf-8">

		<link rel="stylesheet" type="text/css" href="site/cogs.css?version=<?php echo time(); // remove when out of dev ?>">
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

		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
		<script src="site/jquery.mobile.custom.min.js"></script>
		<script type="text/javascript">
			// functions
			function noJS() {
				$('.noJS').remove();
			}

			$(document).ready(noJS);

			function tempError(error) {
				$('<p class="error">'+error+'</p>').prependTo("#content").delay(2000).slideUp(500);
			}

			function lastSplit(char,string) {
				return string.split(char)[string.split(char).length-1];
			}

			function JSifyLinks() {
				$('#content :not(.grid) a').off('click touchend').on('click touchend', function (e) {
					e.preventDefault();
					loadPage(this.href);
				});
			}

			function largeFonts() {
				$('#content *:not(:has(*)), #content *:has(>br)').each(function() {
					var size = parseFloat($(this).css('font-size').split('px'));
					size = Math.floor(size*1.3);
					$(this).css({'font-size':size+'px'});
				});
			}

			function loadPage(page, historyPush = true) { // load the [page] and then whether it should be added to the history
				page = lastSplit('/',page) // some browsers add on the full URL before, this removes it
				var hash = false;
				var url = page;
				if(page.indexOf('#')!==-1) {
					hash = page.substring(page.indexOf('#'));
					page = page.split('#')[0];
				}
				if(page=='') page = 'home';
				$.ajax({
					type: "GET",
					url: 'pages/'+page+".php",
					success: function(data, status, xhr) { // on loading the page...
						var refresh = xhr.getResponseHeader('refresh'); // the loaded page is doing a redirect
						if(refresh) loadPage(lastSplit('=',refresh)); // go to the redirected page
						else { // logged in
							$("#content").fadeTo(150,0, function() { // fade out then put the new content in
								if(historyPush) history.pushState(null, null, url);
								$("#content").html(data);
								$('#content .noJS').remove();
								JSifyLinks();
								<?php if($_SESSION['fonts']) echo 'largeFonts();' ?>
								$("#content").fadeTo(350,1);

								if(hash && $(hash).length)
									$('html, body').animate({
										scrollTop: $(hash).offset().top-80
									}, 400);
							});
						}
					}, error: function(error) { // when there's a link to a page that doesn't exist
						console.log('Tried to load "'+page+'" and got a '+error.status);
						loadPage('404');
						JSifyLinks(); // might need to JSify the links up again, probably not
					}
				});
			}

			function search() {
				var search = $('header #search').val();
				var page = window.location.pathname.substring(1);
				var pages = ['home','call','settings']
				var page = pages.indexOf(page)==-1 ? page : '';
				if($.trim(search)) {
					$.ajax({
						type: "GET",
						url: '../ajax/search.php',
						data: {'s':search,'page':page},
						success: function(data) {
							$('#content :not(#searchResults)').fadeOut(250,function() {
								if(!$('#searchResults').length) $('<div id="searchResults"></div>').appendTo('#content').hide();
								$('#searchResults').html('');
								$(data).appendTo('#searchResults');
								$('#searchResults').fadeIn();
								JSifyLinks();
							});
						},
						error: function(error) { // when there's a link to a page that doesn't exist
							console.log('Tried to load ajax/search.php and got a '+error.status);
							tempError("There was an error looking for callers, please try again");
						}
					});
				}
				else hideSearch();
			}

			function hideSearch() {
				$('#content #searchResults').fadeOut(250, function(){
					$('#content :not(#searchResults,script)').fadeIn();
					$('#content #searchResults').remove();
				});
			}

			$('header #search').on('focus keyup',search);

			//$('header #search').blur(hideSearch);


			$('header a').off('click touchend').on('click touchend', function (e) {
				e.preventDefault();
				loadPage(this.href);
			});

			$(window).on('popstate', function() {
				loadPage(window.location.pathname, false);
			});

			// move logo over when searching on mobiles
			$('#search').focus(function() {
				$('.logo').addClass('searching');
			});

			$('#search').blur(function() {
				$('.logo').removeClass('searching');
			});
		</script>

		<div class="wrapper" id="content" <?php if(isset($_GET['fallback'])) echo 'class="fallback"'; ?>>
			<?php
				if(isset($_GET['fallback']) && file_exists($file = 'pages/'.$_GET['fallback'].'.php')) include $file;
				else if (isset($_GET['fallback'])) include 'pages/404.php';
				else include 'pages/home.php';
			?>
		</div>

		<script type="text/javascript">
			<?php if($_SESSION['fonts']) echo 'largeFonts();' ?>
		</script>
	</body>
</html>