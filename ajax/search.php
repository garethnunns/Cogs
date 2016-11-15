<?php
	require_once dirname(__FILE__).'/../check.php';

	if(isset($_GET['s'])) {
		echo "<h1>Searching for '".htmlspecialchars($_GET['s'])."'</h1>";

		if(!$_GET['page'] || $_GET['page']=='problems') {
			$problems = [];

			foreach ($tproblems as $problem)
				if(stripos($problem['title'], $_GET['s'])!==false && !$problem['solution'])
					$problems[$problem['id']] = $problem['title'];

			echo "<h3>Problems</h3>";

			if(count($problems)) {
				krsort($problems);

				foreach ($problems as $id => $prob)
					echo "<p><a href='/problems#prob$id'><strong>$id</strong> - $prob</a></p>";
			}
			else echo '<p class="error">No unsolved problems could be found</p>';
		}

		if(!$_GET['page'] || $_GET['page']=='solved') {
			$problems = [];

			foreach ($tproblems as $problem)
				if(stripos($problem['title'], $_GET['s'])!==false && $problem['solution'])
					$problems[$problem['id']] = $problem['title'];

			echo "<h3>Solved problems</h3>";

			if(count($problems)) {
				krsort($problems);

				foreach ($problems as $id => $prob)
					echo "<p><a href='/solved#prob$id'><strong>$id</strong> - $prob</a></p>";
			}
			else echo '<p class="error">No solved problems could be found</p>';
		}

		if(!$_GET['page'] || $_GET['page']=='specialists') {
			$specs = [];

			foreach ($tlogin as $id => $spec)
				if(stripos($spec['name'], $_GET['s'])!==false && $spec['job']=="Specialist")
					$specs[$id] = $spec['name'];

			echo "<h3>Specialists</h3>";

			if(count($specs)) {
				krsort($specs);

				foreach ($specs as $id => $spec)
					echo "<p><a href='/specialists#spec$id'><strong>$id</strong> - $spec</a></p>";
			}
			else echo '<p class="error">No specialists could be found</p>';
		}

		if(!$_GET['page'] || $_GET['page']=='software') {
			$softs = [];

			foreach ($tSoft as $id => $soft)
				if(stripos($soft['name'], $_GET['s'])!==false)
					$softs[$id] = $soft['name'];

			echo "<h3>Software</h3>";

			if(count($softs))
				foreach ($softs as $id => $soft)
					echo "<p><a href='/software#soft$id'><strong>$id</strong> - $soft</a></p>";
			else  echo '<p class="error">No software could be found</p>';
		}

		if(!$_GET['page'] || $_GET['page']=='hardware') {
			$hards = [];

			foreach ($tHard as $id => $hard)
				if(stripos($hard['name'], $_GET['s'])!==false)
					$hards[$id] = $hard['name'];

			echo "<h3>Harware</h3>";

			if(count($hards))
				foreach ($hards as $id => $hard)
					echo "<p><a href='/hardware#hard$id'><strong>$id</strong> - $hard</a></p>";
			else  echo '<p class="error">No software could be found</p>';
		}
	}
?>