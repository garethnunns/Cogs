<?php
/*
Functions used across the site.

Change log
==========

18/2/17 - Gareth Nunns
Added translate function outline

14/2/17 - Gareth Nunns
Added changelog

*/

	function translate($phrase, $lang) {
		/*
		Function to output $phrase in $lang

		Psuedocode:
		function translate(phrase, lang) {
		    if (phrase exists in langStor with language lang)
		        return database result;
		    else if (autoTrans == true)
		        return autoTranslate(phrase, lang);
		    else
		        return phrase;
		}
		*/

		return $phrase;
	}

	function longAgo($newDate) {
		if($date = strtotime($newDate.' '.date_default_timezone_get())) {
			if(($diff = time() - $date) <= 30)
				return 'A few seconds ago';
			else if($diff <= 4*60)
				return 'A few minutes ago';
			else if($diff < 60*60)
				return floor($diff/60).' minutes ago';
			else if($diff < 24*60*60)
				return floor($diff/(60*60)).' hours ago';
			else if($diff < 365*24*60*60)
				return floor($diff/(24*60*60)).' days ago';
		}
	}

	function isValidTimezone($timezone) {
		@$tz=timezone_open($timezone);
		return $tz!==FALSE;
	}


	// temp functions
	function outputProblem($problem,$solved) {
		global $format, $tlogin, $tproblems;

		$customers = array();
		$ops = array();
		
		foreach ($problem['calls'] as $call) {
			if(!in_array($call['caller'],$customers))
				array_push($customers, $call['caller']);
			if(!in_array($call['op'],$ops))
				array_push($ops, $call['op']);
		}
		
		foreach ($problem['messages'] as $mess)
			if(!in_array($mess['op'],$ops))
				array_push($ops, $mess['op']);
		
		foreach ($problem['assign'] as $ass)
			if(!in_array($ass['op'],$ops))
				array_push($ops, $ass['op']);

		echo "<tr id='prob{$problem['id']}'>
		<td rowspan='2'>{$problem['id']}</td>
		<td rowspan='2'>{$problem['title']}</td>

		<td rowspan='2'>";

		foreach ($customers as $num => $customer)
			echo $customer.($num==count($customers)-1 ? '' : '<br>');

		echo "</td>
		<td rowspan='2'>";

		foreach ($ops as $num => $op)
			echo $tlogin[$op]['name'].($num==count($ops)-1 ? '' : '<br>');

		echo "</td>

		<td rowspan='2' class='numCalls'>
		<span>".count($problem['calls'])."</span>
		<br>".(count($problem['calls'])==1 ? 'Call' : 'Calls')."</td>";

		if($problem['solution']) {
			echo "<td>Solved</td><td>";
		
			echo date($format,strtotime($problem['solution']['date']));
			if($ago = longAgo($problem['solution']['date']))
				echo '<br>('.$ago.')';
		}
		else {
			echo "<td>Most recent</td><td>";

			echo date($format,strtotime($problem['calls'][0]['date']));
			
			if($ago = longAgo($problem['calls'][0]['date']))
				echo '<br>('.$ago.')';
		}

		echo "</td>
		</tr>

		<tr>
		<td>Reported</td>
		<td>";
		
		echo date($format,strtotime(end($problem['calls'])['date']));
		if($ago = longAgo(end($problem['calls'])['date']))
			echo '<br>('.$ago.')';

		echo "</td>
		</tr>

		<tr class='responses'>
		<td colspan='7'>
		<h2>{$problem['title']}</h2>";
		
		$shown = array();
		$highest = 0;

		do {
			$high = 0;
			foreach ($problem['calls'] as $key => $call) {
				$callDate = strtotime($call['date']);
				if($callDate > $high)
					$high = $callDate;
				if($callDate == $highest) {
					echo outputResponse($call,'call',$format);
					unset($problem['calls'][$key]);
					continue;
				}
			}

			foreach ($problem['messages'] as $key => $mess) {
				$messDate = strtotime($mess['date']);
				if($messDate > $high)
					$high = $messDate;
				if($messDate == $highest) {
					echo outputResponse($mess,'message',$format);
					unset($problem['messages'][$key]);
					continue;
				}
			}

			foreach ($problem['assign'] as $key => $ass) {
				$assDate = strtotime($ass['date']);
				if($assDate > $high)
					$high = $assDate;
				if($assDate == $highest) {
					echo "<div class='assign'>
					<h3>{$tlogin[$ass['by']]['name']} assigned the problem to {$tlogin[$ass['op']]['name']}</h3>
					<p>";

					echo date($format,$assDate);
		

					if($ago = longAgo($ass['date']))
						echo '<br><em>('.$ago.')</em>';

					echo "</p></div>";

					unset($problem['assign'][$key]);
					continue;
				}
			}

			if($solved) {
				$solDate = strtotime($problem['solution']['date']);
				if($solDate > $high)
					$high = $solDate;
				if($solDate == $highest) {
					echo outputResponse($problem['solution'],'solution',$format);
					unset($problem['solution']);
					continue;
				}
			}

			$highest = $high;
		} while ((count($problem['calls'])!=0) || (count($problem['messages'])!=0) || (count($problem['assign'])!=0) || (count($problem['solution'])!=0));


		echo "</td>
		</tr>";
	}


	function outputResponse($response, $type, $format) {
		global $tlogin;

		$op = $tlogin[$response['op']];

		$ret = "<div class='response'>
		<div class='staff'>
		<p><strong>";
		
		switch($type) {
			case 'call':
				$ret .= 'Call with '.$response['caller'];
				break;
			case 'message':
				$ret .= 'Message';
				break;
			case 'solution':
				$ret .= 'Solution';
				break;
		}

		$ret .= "</strong>
		<br>" . date($format,strtotime($response['date'].' '.date_default_timezone_get()));

		if($ago = longAgo($response['date']))
			$ret .= '<br><em>('.$ago.')</em>';

		$ret .= "</p>

		<p>{$op['name']}<br>
		<em>{$op['job']}</em></p>
		</div>

		<div class='notes'>
		<h3>".($type=='solution' ? 'Solution' : $response['title'])."</h3>
		<p>{$response['text']}</p>
		</div>
		</div>";

		return $ret;
	}
?>