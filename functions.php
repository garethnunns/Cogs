<?php
/*
Functions used across the site.

Change log
==========

18/2/17 - Gareth Nunns
Added translate function outline
Added longAgoEpoch function
Added storeProblem function
Updated outputProblem function
Removed outputResponse function

14/2/17 - Gareth Nunns
Added changelog

*/

	function translate($phrase) {
		/*
		Function to output $phrase in users selected lang

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

	function longAgoEpoch($date) {
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

	function isValidTimezone($timezone) {
		@$tz=timezone_open($timezone);
		return $tz!==FALSE;
	}

	function storeProblems($probs) {
		// because the data structure is fairly complex, it's restructured in PHP because it's simpler than adding a lot of SQL
		// this will be a standard structure so it can be reused on many pages to output various tables
		// the problems are stored in this $problems array
		$problems = array();

		foreach ($probs as $row) {
			if(!array_key_exists($row['idProblem'], $problems)) // this problem hasn't been logged at all yet
				// so initialise a new empty array and store the generic attributes of the problem
				$problems[$row['idProblem']] = array(
					'title' => $row['title'],
					'type' => array(
						'id' => $row['idType'],
						'name' => $row['type']
					),
					// initialise an empty array to store all of the events in the problem
					'events' => array()
				);

			// messages
			$found = false; // variable to be used when traversing arrays
			// where the message will be positioned in the array
			$pos = count($problems[$row['idProblem']]['events'])-1; 

			foreach ($problems[$row['idProblem']]['events'] as $key => $event) {
				if(($event['type']=='message') && ($event['id']==$row['idMessage'])) {
					$found = true; // the message is already stored
					break; // stop searching for efficiency
				}
				if($event['date'] > strtotime($row['messDate'])) 
					$pos = $key; // so that they're stored in descending date order
			}
			if(!$found && !empty($row['idMessage'])) // the message isn't already in the array
				// add the message in the correct position
				array_splice($problems[$row['idProblem']]['events'], $pos+1, 0, array(
					array(
						'type' => 'message',
						'date' => strtotime($row['messDate'].' '.date_default_timezone_get()),
						'id' => $row['idMessage'],
						'subject' => $row['messSub'],
						'message' => $row['messMess'],
						'specialist' => array(
							'id' => $row['messSpec'],
							'name' => $row['messName'],
							'tel' => $row['messTel'],
							'job' => $row['messJob']
						)
					)
				));

			// assignments
			// reset vars
			$found = false;
			$pos = count($problems[$row['idProblem']]['events'])-1; 
			
			foreach ($problems[$row['idProblem']]['events'] as $key => $event) {
				if(($event['type']=='assign') && ($event['id']==$row['idAssign'])) {
					$found = true; // the assignment is already stored
					break; // stop searching for efficiency
				}
				if($event['date'] > strtotime($row['assDate'])) 
					$pos = $key; // so that they're stored in descending date order
			}
			if(!$found && !empty($row['idAssign'])) // the assignment isn't already in the array
				// add the assignment in the correct position
				array_splice($problems[$row['idProblem']]['events'], $pos+1, 0, array(
					array(
						'type' => 'assign',
						'date' => strtotime($row['assDate'].' '.date_default_timezone_get()),
						'id' => $row['idAssign'],
						'by' => array(
							'id' => $row['assBy'],
							'name' => $row['assByName']
						),
						'to' => array(
							'id' => $row['assTo'],
							'name' => $row['assToName']
						)
					)
				));

			// calls
			// reset vars
			$found = false;
			$pos = count($problems[$row['idProblem']]['events'])-1; 
			
			foreach ($problems[$row['idProblem']]['events'] as $key => $event) {
				if(($event['type']=='call') && ($event['id']==$row['idCalls'])) {
					$found = true; // the call is already stored
					break; // stop searching for efficiency
				}
				if($event['date'] > strtotime($row['callDate'])) 
					$pos = $key; // so that they're stored in descending date order
			}
			if(!$found && !empty($row['idCalls'])) // the call isn't already in the array
				// add the call in the correct position
				array_splice($problems[$row['idProblem']]['events'], $pos+1, 0, array(
					array(
						'type' => 'call',
						'date' => strtotime($row['callDate'].' '.date_default_timezone_get()),
						'id' => $row['idCalls'],
						'subject' => $row['callSubject'],
						'message' => $row['callNotes'],
						'caller' => array(
							'id' => $row['caller'],
							'name' => $row['callerName'],
							'tel' => $row['callerTel'],
							'job' => $row['callerJob']
						),
						'specialist' => array(
							'id' => $row['op'],
							'name' => $row['opName'],
							'tel' => $row['opTel'],
							'job' => $row['opJob']
						)
					)
				));
		}

		return $problems;
	}

	function outputProblem($id,$problem) {
		global $format;

		// empty arrays to store the names in
		$customers = array();
		$ops = array();

		// number of calls and messages
		$calls = 0;
		$messages = 0;
		$solved = 0;
		
		foreach ($problem['events'] as $event) // find all the people associated with the problem
			switch ($event['type']) {
				case 'call':
					$calls++;
					if(!in_array($event['caller']['name'],$customers)) // store the customers
						array_push($customers, $event['caller']['name']);
					if(!in_array($event['specialist']['name'],$ops)) // store the call operator
						array_push($ops, $event['specialist']['name']);
					break;
				case 'message':
					$messages++;
					if(!in_array($event['specialist']['name'],$ops)) // store the message sender
						array_push($ops, $event['specialist']['name']);
					break;
				case 'assign':
					if(!in_array($event['to']['name'],$ops)) // store the assigned specialist
						array_push($ops, $event['to']['name']);
					break;
				case 'solved':
					$solved++;
					break;
			}

		echo "<tr id='prob$id'>
		<td rowspan='2'>$id</td>
		<td rowspan='2'>{$problem['title']}</td>

		<td rowspan='2'>";

		foreach ($customers as $num => $customer) // output the list of customers
			echo $customer.($num==count($customers)-1 ? '' : '<br>');

		echo "</td>
		<td rowspan='2'>";

		foreach ($ops as $num => $op) // output the list of operators
			echo $op.($num==count($ops)-1 ? '' : '<br>');

		echo "</td>

		<td class='numCalls'>
		<span>".$calls."</span>
		<br>".translate($calls==1 ? 'Call' : 'Calls')."</td>";

		if($solved) {
			echo "<td>Solved ($solved)</td><td>";

			foreach ($problem['events'] as $event) // look for the most recent solution date
				if($event['type']=='solved')
					$latestSolved = $event['date'];

			echo date($format,$latestSolved);
			if($ago = longAgoEpoch($latestSolved))
				echo '<br>('.$ago.')';
		}
		else {
			if($problem['events'][0]) { // if there has been any activity on the problem
				echo "<td>Most recent</td><td>";				

				$latest = $problem['events'][0]['date'];

				echo date($format,$latest);
			
				if($ago = longAgoEpoch($latest))
					echo '<br>('.$ago.')';
			}
			else
				echo "<td colspan='2' rowspan='2'>".translate('No activity on this problem');
		}

		echo "</td>
		</tr>

		<tr>

		<td class='numCalls'>
		<span>".$messages."</span>
		<br>".translate($messages==1 ? 'Message' : 'Messages')."</td>";

		if($problem['events'][0]) {
			echo "<td>Earliest</td>
			<td>";
			
			$earliest = $problem['events'][count($problem['events'])-1]['date'];

			echo date($format,$earliest);
		
			if($ago = longAgoEpoch($earliest))
				echo '<br>('.$ago.')';

			echo "</td>";
		}
		
		echo "</tr>

		<tr class='responses'>
		<td colspan='7'>
		<h2>{$problem['title']}</h2>";
		
		foreach ($problem['events'] as $event) {
			if($event['type']=='assign') { // the event is an assignment (formatted differently)
				echo "<div class='assign'>
				<h3>{$event['by']['name']} <em>#{$event['by']['id']}</em> assigned the problem to {$event['to']['name']} <i>#{$event['to']['id']}</i></h3>
				<p>";

				echo date($format,$event['date']);

				if($ago = longAgoEpoch($event['date']))
					echo '<br><em>('.$ago.')</em>';

				echo "</p></div>";
			}
			else { // a message/call/solution
				echo "<div class='response'>
				<div class='staff'>
				<p><strong>";

				switch($event['type']) {
					case 'call':
						echo "Call with {$event['caller']['name']} <br>on {$event['caller']['tel']}";
						break;
					case 'message':
						echo 'Message';
						break;
					case 'solved':
						echo 'Solution';
						break;
				}

				echo "</strong>
				<br>" . date($format,$event['date']);

				if($ago = longAgoEpoch($event['date']))
				echo "<br><em>($ago)</em>";

				echo "</p>

				<p>{$event['specialist']['name']} <em>{$event['specialist']['id']}</em><br>
				{$event['specialist']['tel']}<br>
				<em>{$event['specialist']['job']}</em></p>
				</div>

				<div class='notes'>
				<h3>".($event['type']=='solved' ? 'Solution' : $event['subject'])."</h3>
				<p>{$event['message']}</p>
				</div>
				</div>";
			}
		}

		echo "</td></tr>";
	}
?>