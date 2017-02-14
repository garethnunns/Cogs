<?php
/*
Search results for problems

Change log
==========

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once dirname(__FILE__).'/../check.php';

	if(!empty($_GET['s'])) {
		$problems = [];

		foreach ($tproblems as $problem)
			if(stripos($problem['title'], $_GET['s']))
				$problems[$problem['id']] = $problem['title'].($problem['solution'] ? ' - Solved' : '');

		krsort($problems);

		foreach ($problems as $id => $prob)
			echo "<p data-id='$id'><strong>$id</strong> - $prob</p>";
		
		echo "<p>Create a new problem with the title <strong>'{$_GET['s']}'</strong></p>";
	}
?>