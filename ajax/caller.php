<?php
	require_once dirname(__FILE__).'/../check.php';

	$callers = [];

	foreach ($tlogin as $user)
		$callers[$user['name']] = $user['job'];

	foreach ($tproblems as $problem)
		foreach ($problem['calls'] as $call)
			if(!isset($callers[$call['caller']]))
				$callers[$call['caller']] = 'User';

	ksort($callers);

	foreach ($callers as $name => $role)
		if(stripos($name, $_GET['s'])!==false)
			echo "<p><b>$name</b> - $role</p>"
?>