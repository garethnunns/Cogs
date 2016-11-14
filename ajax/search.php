<?php
	require_once dirname(__FILE__).'/../check.php';

	if(isset($_GET['s'])) {
		echo "<h1>Searching for '".htmlspecialchars($_GET['s'])."'</h1>";

		if(!$_GET['page'] || $_GET['page']=='problems') {
			$problems = [];

			foreach ($tproblems as $problem)
				if(stripos($problem['title'], $_GET['s']))
					$problems[$problem['id']] = $problem['title'].($problem['solution'] ? ' - Solved' : '');

			echo "<h3>Problems</h3>";

			if(count($problems)) {
				krsort($problems);

				foreach ($problems as $id => $prob)
					echo "<p><a href='/problems#prob$id'><strong>$id</strong> - $prob</a></p>";
			}
			else echo '<p class="error">No unsolved problems could be found</p>';
		}

		if(isset($_GET['page']) && $_GET['page']=='problems') {

		}
	}
?>