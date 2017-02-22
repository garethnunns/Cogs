<?php
/*
Functions used across the site.

Change log
==========

22/2/17 - Gareth Nunns
Updated translate function

21/2/17 - Gareth Nunns
Updated solved function

20/2/17 - Gareth Nunns
Updated storeProblems function

18/2/17 - Gareth Nunns
Added translate function outline
Added longAgoEpoch function
Added storeProblems function
Updated outputProblem function
Removed outputResponse function

14/2/17 - Gareth Nunns
Added changelog

*/

	function translate($phrase) {
		global $dbh;
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

		if($_SESSION['lang'] == 'en') // no need to translate
			return $phrase;

		try {
			$sql = "SELECT trans 
					FROM langStor
					WHERE transLang = ?
					AND orig = ?";

			$sth = $dbh->prepare($sql);

			$sth->execute(array($_SESSION['lang'], $phrase));

			if($sth->rowCount())
				return $sth->fetchColumn();
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}

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

	function valid($field, $text) {
		// verify the text is valid to be inserted
		global $dbh;

		list($table, $column) = explode('.',$field);

		try {
			$sql = "SELECT character_maximum_length as len, IS_NULLABLE as n   
					FROM information_schema.columns  
					WHERE table_name = '$table'
					AND column_name = '$column'";

			$sth = $dbh->prepare($sql);

			$sth->execute();

			$attr = $sth->fetch(PDO::FETCH_OBJ);

			if(strlen($text) > $attr->len)
				return false;

			if(empty(ltrim($text)) && ($attr->n == "NO"))
				return false;

			return true;
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	function asterisk($field) {
		// output an asterisk if it's a mandatory field
		global $dbh;
		
		list($table, $column) = explode('.',$field);

		try {
			$sql = "SELECT IS_NULLABLE as n  
					FROM information_schema.columns  
					WHERE table_name = '$table'
					AND column_name = '$column'";

			$sth = $dbh->prepare($sql);

			$sth->execute();

			if($sth->fetchColumn()=="NO")
				echo '<span class="error">*</span>';
		}
		catch (PDOException $e) {
			return false;
			echo $e->getMessage();
		}
	}

	function solved($probid) {
		// check to see whether a problem has been solved
		global $dbh;

		try {
			$sql = "SELECT COUNT(idProblem) FROM solved WHERE idProblem = ?";

			$sth = $dbh->prepare($sql);

			$sth->execute(array($probid));

			return $sth->fetchColumn() >0;
		}
		catch (PDOException $e) {
			return false;
			echo $e->getMessage();
		}
	}

	function isValidTimezone($timezone) {
		@$tz=timezone_open($timezone);
		return $tz!==FALSE;
	}

	function categories($type,$types) {
		// recursive function to get the category
		if(empty($type['cat'])) // base case
			return $type['name'];
		else
			return categories($types[$type['cat']],$types)." -> ".$type['name'];
	}

	function storeProblems($probs) {
		global $dbh;
		// because the data structure is fairly complex, it's restructured in PHP because it's simpler than adding a lot of SQL
		// this will be a standard structure so it can be reused on many pages to output various tables
		// the problems are stored in this $problems array
		$problems = array();

		// store types in array
		$sql = "SELECT * FROM type";
		$sth = $dbh->prepare($sql);

		$sth->execute();

		// we're going to use PHP to resolve the recursive relationship
		$types = array();

		// so we'll empty the search results out into this style of array
		foreach ($sth->fetchAll() as $trow)
			$types[$trow['idType']] = array(
				'name' => $trow['name'],
				'cat' => $trow['category']
			);

		foreach ($types as $id => $type)
			$types[$id]['path'] = categories($type,$types);

		foreach ($probs as $row) {
			if(!array_key_exists($row['idProblem'], $problems)) { // this problem hasn't been logged at all yet
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

				$problems[$row['idProblem']]['type']['path'] = $types[$row['idType']]['path'];
			}
			// solutions
			$found = false; // variable to be used when traversing arrays

			foreach ($problems[$row['idProblem']]['events'] as $key => $event) {
				if(($event['type']=='solved') && ($event['id']==$row['solvedSpec'])) {
					$found = true; // the solution is already stored
					break; // stop searching for efficiency
				}
			}
			if(!$found && !empty($row['solvedSpec'])) // the solution isn't already in the array
				// add the solution
				array_push($problems[$row['idProblem']]['events'], array(
					'type' => 'solved',
					'date' => strtotime($row['solvedDate'].' GMT'),
					'id' => $row['solvedSpec'],
					'message' => $row['solvedMess'],
					'specialist' => array(
						'id' => $row['solvedSpec'],
						'name' => $row['solvedName'],
						'tel' => $row['solvedTel'],
						'job' => $row['solvedJob']
					)
				));

			// messages
			// reset var
			$found = false;

			foreach ($problems[$row['idProblem']]['events'] as $key => $event) {
				if(($event['type']=='message') && ($event['id']==$row['idMessage'])) {
					$found = true; // the message is already stored
					break; // stop searching for efficiency
				}
			}
			if(!$found && !empty($row['idMessage'])) // the message isn't already in the array
				// add the message
				array_push($problems[$row['idProblem']]['events'], array(
					'type' => 'message',
					'date' => strtotime($row['messDate'].' GMT'),
					'id' => $row['idMessage'],
					'subject' => $row['messSub'],
					'message' => $row['messMess'],
					'specialist' => array(
						'id' => $row['messSpec'],
						'name' => $row['messName'],
						'tel' => $row['messTel'],
						'job' => $row['messJob']
					)
				));

			// assignments
			// reset var
			$found = false;
			
			foreach ($problems[$row['idProblem']]['events'] as $key => $event) {
				if(($event['type']=='assign') && ($event['id']==$row['idAssign'])) {
					$found = true; // the assignment is already stored
					break; // stop searching for efficiency
				}
			}
			if(!$found && !empty($row['idAssign'])) // the assignment isn't already in the array
				// add the assignment
				array_push($problems[$row['idProblem']]['events'], array(
					'type' => 'assign',
					'date' => strtotime($row['assDate'].' GMT'),
					'id' => $row['idAssign'],
					'by' => array(
						'id' => $row['assBy'],
						'name' => $row['assByName']
					),
					'to' => array(
						'id' => $row['assTo'],
						'name' => $row['assToName']
					)
				));

			// calls
			// reset var
			$found = false;
			
			foreach ($problems[$row['idProblem']]['events'] as $key => $event) {
				if(($event['type']=='call') && ($event['id']==$row['idCalls'])) {
					$found = true; // the call is already stored
					break; // stop searching for efficiency
				}
			}
			if(!$found && !empty($row['idCalls'])) // the call isn't already in the array
				// add the call
				array_push($problems[$row['idProblem']]['events'], array(
					'type' => 'call',
					'date' => strtotime($row['callDate'].' GMT'),
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
				));

			// order by date
			usort($problems[$row['idProblem']]['events'], function($a, $b) {
				return $a['date'] - $b['date'];
			});

			// reverse chronological
			$problems[$row['idProblem']]['events'] = array_reverse($problems[$row['idProblem']]['events']);
		}

		// sort by most recent change
		uasort($problems, function($a, $b) {
			return $a['events'][0]['date'] - $b['events'][0]['date'];
		});

		// reverse chronological
		$problems = array_reverse($problems,true);

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
		<h2>{$problem['title']}</h2>
		<p>Type of problem: <b>{$problem['type']['path']}</b></p>

		<p style='text-align: center'>
			<button onClick=\"expand('addspectoprob$id')\">Assign specialist</button>
			<button onClick=\"expand('addmessagetoprob$id')\">Add message</button>
			<button onClick=\"expand('addsolutiontoprob$id')\">Add solution</button>
		</p>

		<form method='POST' action='/forms/addspecialist.php' id='addspectoprob$id'>
			<h3>Assign a specialist</h3>
			<p id=\"specSearch\">
				<input type=\"text\" name=\"specialist\" placeholder=\"Search for a specialist\" data-id='$id'> or <a href=\"specialists\">view all specialists &raquo;</a>
			</p>
			<div id=\"assigned\"></div>


			<div id=\"specialists\">
				<div id=\"found\"></div>
			</div>

			<p class=\"noJS\">Please enter the ID of the specialist asigned</p>
			<input type=\"number\" name=\"idspec\" placeholder=\"ID of specialist\" />
			<input name='prob' type='hidden' value='$id'>
			<p><input type='submit' value='Assign specialist'></p>
		</form>

		<form method='POST' action='/forms/addmessage.php' id='addmessagetoprob$id'>
			<h3>Add a message</h3>
			<p>Subject: <input name='subject' type='text' placeholder='Title of your message'></p>
			<textarea name='message' placeholder='Add a message to this problem'></textarea>
			<input name='prob' type='hidden' value='$id'>
			<p><input type='submit' value='Add message'></p>
		</form>

		<form method='POST' action='/forms/addsolution.php' id='addsolutiontoprob$id'>
			<h3>Add a solution</h3>
			<textarea name='message' placeholder='Add a solution to this problem'></textarea>
			<input name='prob' type='hidden' value='$id'>
			<p><input type='submit' value='Add message'></p>
		</form>
		";
		
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

				<p>{$event['specialist']['name']} <em>#{$event['specialist']['id']}</em><br>
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