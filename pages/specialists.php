<?php
	require_once dirname(__FILE__).'/../check.php';
?>

<h1>Specialists</h1>

<table class="specialists">
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Phone</th>
		<th colspan="2">Problems</th>
		<th>Availability</th>
	</tr>
<?php
	foreach ($tlogin as $id => $spec) {
		$avail = ["Full Time","Monday - Wednesday","Weekends","Weekdays 9:00 - 13:00","On holiday, back in ".mt_rand(2, 10)." days"];

		$probs = array("unsolved"=>array(),"solved"=>array());

		foreach ($tproblems as $key => $problem) {
			$high = 0;

			if($problem['solution']['op']==$id)
				array_push($probs['solved'], $key);
			else {
				foreach ($problem['assign'] as $asskey => $ass)
					if(strtotime($ass['date']) > $high) {
						$high = strtotime($ass['date']);
						$highkey = $asskey;
					}

				if(isset($asskey)) { // found an assignment
					if($id == $problem['assign'][$asskey])
						array_push($probs['unsolved'], $key);
				}
			}
		}

		if($spec['job']=='Specialist') {
			echo "<tr>
			<td>{$id}</td>
			<td>{$spec['name']}</td>
			<td>ext ".mt_rand(10000, 55555)."</td>
			<td class='numProbs'><span>".count($probs['unsolved'])."</span><br>Unsolved</td>
			<td class='numProbs'><span>".mt_rand(0, 25)."</span><br>Solved this week</td>
			<td>".$avail[array_rand($avail,1)]."</td>
			</tr>";
		}
	}
?>
</table>