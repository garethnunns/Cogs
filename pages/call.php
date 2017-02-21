<?php
/*
Adding a call to system - so all the fields that this requires, and referencing things like hardware, software and previous problems

Change log
==========

21/2/17 - Gareth Nunns
Added assigning specialist functionality

20/2/17 - Gareth Nunns
Completed functionality of adding type, problem & caller

18/2/17 - Gareth Nunns
Linked callers search to database

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once dirname(__FILE__).'/../check.php'; // check the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database

	if(isset($_POST['add'])) { // adding a call
		try {
			// add the new problem (if there is one)
			if($_POST['idproblem']=='-1') { // the user chose to create a new problem
				if(!empty($_POST['newtype']) &&
					valid('type.name',$_POST['newtype'])) { // add new type (if there is one)

					$sth = $dbh->prepare("INSERT INTO type (name, category) VALUES (?, ?)");

					$sth->execute(array($_POST['newtype'],$_POST['type']));

					$type = $dbh->lastInsertId(); // get the type we just popped in

					echo "<p>Added {$_POST['newtype']} (#$type)</p>";
				}
				else $type = $_POST['type'];

				if(!empty($_POST['newproblem']) && 
					valid('problem.title',$_POST['newproblem'])) { // add new type (if there is one)

					$sth = $dbh->prepare("INSERT INTO problem (idType, title) VALUES (?, ?)");

					$sth->execute(array($type,$_POST['newproblem']));

					$prob = $dbh->lastInsertId(); // get that new problem

					echo "<p>Created problem #$prob: {$_POST['newproblem']}</p>";
				}
			}
			else $prob = $_POST['idproblem'];

			// add the call
			if(valid('calls.subject',$_POST['subject']) &&
				valid('calls.notes',$_POST['notes']) &&
				isset($_POST['idcaller']) && 
				isset($_POST['idproblem'])) { // validation

				$sth = $dbh->prepare("INSERT INTO calls (idProblem, caller, op, `date`, subject, notes) 
					VALUES (?, ?, ?, ?, ?, ?)");

				$sth->execute(array($prob, $_POST['idcaller'], $_SESSION['user'], gmdate('Y-m-d H:i:s'), $_POST['subject'], $_POST['notes']));

				$call = $dbh->lastInsertId();

				echo "<p>Added '{$_POST['subject']}' as call #$call to <a href='".(solved($prob) ? 'solved' : 'problems')."#$prob'>problem #$prob</a></p>";
			}

			if(!empty($_POST['idspec'])) { // assigning a specialist
				$sth = $dbh->prepare("INSERT INTO assign (idProblem, assBy, assTo, assDate) 
				VALUES (?, ?, ?, ?)");

				$sth->execute(array(
					$prob,
					$_SESSION['user'], 
					$_POST['idspec'],
					gmdate('Y-m-d H:i:s')
				));

				echo "<p>Assigned specialist (#{$_POST['idspec']})</p>";
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
?>

<form method="POST" class="call">

	<h1>New Call</h1>

<?php
	try {
		$sql = "SELECT CONCAT(emp.firstName, ' ', emp.surname) AS name, emp.tel
				FROM emp
				WHERE emp.idEmp = {$_SESSION['user']}";

		$sth = $dbh->prepare($sql);

		$sth->execute();

		if(!$sth->rowCount()) // we should really be able to find them in the database
			echo '<p class="error">'.translate('There has been a fundamental problem here').'</p>';
		else { 
			$user = $sth->fetch(PDO::FETCH_OBJ);
			echo "<p>This call will be logged to <strong>{$user->name} #{$_SESSION['user']}</strong> on <strong>{$user->tel}</strong></p>";
		}
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}

	echo solved(27);
?>

	<div class="item">
		<h3>Caller<?php asterisk('calls.caller'); ?></h3>
		<div class="data" id="caller">
			<input type="text" name="caller" placeholder="Name or ext. of caller" />
		</div>
	</div>

	<p class="noJS">Please enter the ID of the person calling</p>
	<input type="number" name="idcaller" placeholder="ID of caller" />

	<div id="resCaller"></div>



	<div class="item">
		<h3>Problem<?php asterisk('calls.idProblem'); ?></h3>
		<div class="data" id="problem">
			<input type="text" name="problem" placeholder="Existing problem or new problem" />

			<div id="type">
				<h4>Type of problem<?php asterisk('problem.idType'); ?></h4>
				<select name="type">
					<option value='' disabled selected>Please select a type</option>
<?php
	$sql = "SELECT * FROM type ORDER BY name";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	// we're going to use PHP to resolve the recursive relationship
	$types = array();

	// so we'll empty the search results out into this style of array
	foreach ($sth->fetchAll() as $row)
		$types[$row['idType']] = array(
			'name' => $row['name'],
			'cat' => $row['category']
		);

	function categories($type,$types){
		if(empty($type['cat'])) // base case
			return $type['name'];
		else
			return categories($types[$type['cat']],$types)." -> ".$type['name'];
	}

	foreach ($types as $id => $type)
		$types[$id]['path'] = categories($type,$types);

	uasort($types, function($a, $b) {
		return strcmp($a["path"], $b["path"]);
	});

	foreach ($types as $id => $type)
		echo "<option value='$id'>{$type['path']}</option>";
?>
				</select>

				<p>Create a new type:<br>
				<input name="newtype" type="text" placeholder="This will use the category above as its parent"></p>
			</div>
		</div>
	</div>

	<p class="noJS">Please enter the ID of the problem</p>
	<input type="number" name="idproblem" placeholder="ID of problem" />
	<input type="text" name="newproblem" placeholder="Name of problem" />

	<div id="resProblem"></div>

	<h2>Call Details</h2>

	<div class="item">
		<h3>Subject<?php asterisk('calls.subject'); ?></h3>
		<div class="data">
			<input type="text" name="subject" placeholder="Subject of your call" />
		</div>
	</div>

	<h3>Notes<?php asterisk('calls.notes'); ?></h3>
	<textarea name="notes" placeholder="Details of the call"></textarea>

	<div class="ware">
		<div class="hardware">
			<h3>Related Hardware</h3>
			<p><input type="text" name="hardware" placeholder="Search hardware"></p>
			<table id="hardList">
<?php
	foreach ($tHard as $id => $hard)
		echo "<tr><td>$id</td><td>{$hard['name']}</td><td><button>Add</button>";
?>
			</table>
		</div><div class="software">
			<h3>Related Software</h3>
			<p><input type="text" name="software" placeholder="Search software"></p>
			<table id="softList">
<?php
	foreach ($tSoft as $id => $soft)
		echo "<tr><td>$id</td><td>{$soft['name']}</td><td><button>Add</button>";
?>
			</table>
		</div>
	</div>

	<h2>Assign a specialist</h2>
	<p id="specSearch"><input type="text" name="specialist" placeholder="Search for a specialist"> or <a href="specialists">view all specialists &raquo;</a></p>
	<div id="assigned"></div>


	<div id="specialists">
		<div id="found"></div>
	</div>

	<p class="noJS">Please enter the ID of the specialist asigned</p>
	<input type="number" name="idspec" placeholder="ID of specialist" />

	<p><input type="submit" value="Add call" name="add"></p>

</form>

<h1 id="allProblemsTitle">All problems</h1>

<table class="problems" id="allProblems">
	<tr>
		<th>ID</th>
		<th>Title</th>
		<th>Customers</th>
		<th>Operators</th>
		<th colspan="3">Calls</th>
	</tr>

<?php

	// output a list of all the problems

	$sql = "
SELECT problem.idProblem, problem.title, type.idType, type.name AS type,

-- messages
message.idMessage, message.date AS messDate, message.subject AS messSub, message.message AS messMess, message.specialist AS messSpec,
CONCAT(memp.firstName, ' ', memp.surname) AS messName, memp.tel AS messTel, mempJob.name AS messJob,

-- assignments
assign.idAssign, assign.assBy, CONCAT(assByemp.firstName, ' ', assByemp.surname) AS assByName,
assign.assTo, CONCAT(assToemp.firstName, ' ', assToemp.surname) AS assToName, assDate,

-- calls
calls.idCalls, calls.caller, CONCAT(calleremp.firstName, ' ', calleremp.surname) AS callerName, calleremp.tel AS callerTel, callerJob.name AS callerJob,
calls.op, CONCAT(opemp.firstName, ' ', opemp.surname) AS opName, opemp.tel AS opTel, opJob.name AS opJob,
calls.date AS callDate, calls.subject AS callSubject, calls.notes AS callNotes,

-- solved
solved.specialist AS solvedSpec, solved.date AS solvedDate, solved.message AS solvedMess,
CONCAT(specemp.firstName, ' ', specemp.surname) as solvedName, specemp.tel as solvedTel, specJob.name as solvedJob

FROM problem

-- JOINS
-- type
LEFT JOIN type ON problem.idType = type.idType 

-- messages
LEFT JOIN message ON problem.idProblem = message.idProblem
LEFT JOIN emp AS memp ON message.specialist = memp.idEmp
LEFT JOIN jobTitle as mempJob ON memp.jobTitle = mempJob.idJobTitle

-- assignments
LEFT JOIN assign ON problem.idProblem = assign.idProblem
LEFT JOIN emp AS assByemp ON assign.assBy = assByemp.idEmp
LEFT JOIN emp AS assToemp ON assign.assTo = assToemp.idEmp

-- calls
LEFT JOIN calls ON problem.idProblem = calls.idProblem
LEFT JOIN emp AS calleremp ON calls.caller = calleremp.idEmp
LEFT JOIN jobTitle as callerJob ON calleremp.jobTitle = callerJob.idJobTitle
LEFT JOIN emp AS opemp ON calls.op = opemp.idEmp
LEFT JOIN jobTitle as opJob ON opemp.jobTitle = opJob.idJobTitle

-- solved 
LEFT JOIN solved ON problem.idProblem = solved.idProblem
LEFT JOIN emp AS specemp ON solved.specialist = specemp.idEmp 
LEFT JOIN jobTitle AS specJob ON specemp.jobTitle = specJob.idJobTitle
 
ORDER BY idProblem, message.date DESC, assDate DESC, calls.date DESC";

	try {
		$sth = $dbh->prepare($sql);

		$sth->execute();

		$problems = storeProblems($sth->fetchAll());

		foreach ($problems as $id => $problem)
			outputProblem($id,$problem);
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
?>
</table>

<script type="text/javascript">
	// searching for a caller

	// hide the ID field
	$('[name="idcaller"]').hide();

	$('[name="caller"]').on('focus keyup',searchCallers); // search when the user types

	$('[name="caller"]').blur(function() { // hide the results when they're not searching
		$('#resCaller').slideUp();
	});

	function searchCallers() {
		// output the list of found callers
		var search = $('[name="caller"]').val();
		if($.trim(search)) {
			$.ajax({
				type: "GET",
				url: '../ajax/caller.php',
				data: {'s':search},
				success: function(data) {
					$('#resCaller').html(data);
					$('#resCaller').slideDown();

					$('#resCaller p').off('click vclick').on('click vclick', function(){
						// update the hidden field
						$('[name="idcaller"]').val($(this).data('id'));
						// get the html
						var selected = $(this).html();
						$('[name="caller"]').fadeOut(300,function(){
							$('<p>'+selected+'<br><a href="#" class="change">Change</a></p>').hide().appendTo('#caller').fadeIn().on('click vlick',function(e){
								e.preventDefault();
								$(this).fadeOut(300, function(){
									$('[name="caller"]').fadeIn(300).focus();
								}).remove();
							});
						});
					});
				},
				error: function(error) { // when there's a link to a page that doesn't exist
					console.log('Tried to load ajax/caller.php and got a '+error.status);
					tempError("There was an error looking for callers, please try again");
				}
			});
		}
		else {
			$('#resCaller').slideUp();
			$('#resCaller').html('');
		}
	}

	// the problems box
	// hide the no JS backup fields field
	$('[name="idproblem"]').hide();
	$('[name="newproblem"]').hide();

	$('[name="problem"]').on('focus keyup',searchProblems);

	// hide the types field (will be shown later if they're making a new problem)
	$('#type').hide();

	$('[name="problem"]').blur(function(){
		$('#resProblem').slideUp();
	});

	$('#specialists #recommended').hide();

	function searchProblems() {
		var search = $('[name="problem"]').val();
		if($.trim(search)) {
			$.ajax({
				type: "GET",
				url: '../ajax/problems.php',
				data: {'s':search},
				success: function(data) {
					$('#resProblem').html(data);
					$('#resProblem').slideDown();

					$('#resProblem p').off('click vclick').on('click vclick', function(){ // chose one of the results
						// update the hidden field
						$('[name="idproblem"]').val($(this).data('id'));
						$('[name="newproblem"]').val($('[name="problem"]').val());
						var selected = $(this).html();
						var id = '#prob'+$(this).data('id');
						$('[name="problem"]').fadeOut(300,function(){
							$('<p>'+selected+'<br><a href="#" class="change">Change</a></p>').hide().prependTo('#problem').fadeIn().on('click vlick',function(e){ // decided to change what they selected
								e.preventDefault();
								$(this).fadeOut(300, function(){
									$('[name="problem"]').fadeIn(300).focus();
								}).remove();
								$('#allProblemsTitle').text('All problems');
								$('#allProblems tr:nth-of-type(3n+2), #allProblems tr:nth-of-type(3n+3)').show();
								$('#allProblems '+id).trigger('click');
								$('#type]').fadeOut();
							});
						});
						if($(this).data('id')!='-1') { // not creating a new problem
							$('#allProblemsTitle').text('Problem '+$(this).data('id'));
							$('#allProblems tr:nth-of-type(3n+2), #allProblems tr:nth-of-type(3n+3)').hide();
							$('#allProblems '+id).show().trigger('click').next().show();
						}
						else $('#type').fadeIn();
					});
				},
				error: function(error) { // when there's a link to a page that doesn't exist
					console.log('Tried to load ajax/problem.php and got a '+error.status);
					tempError("There was an error looking for problems, please try again");
				}
			});
		}
		else {
			$('#resProblem').slideUp();
			$('#resProblem').html('');
		}
	}


	// search hardware/software
	$('[name="hardware"]').on('focus keyup',function() {
		searchWare('#hardList',$(this).val());
	});

	$('[name="software"]').on('focus keyup',function() {
		searchWare('#softList',$(this).val());
	});

	$('#hardList button, #softList button').on('click vclick',function(e){
		e.preventDefault();
		$(this).parent().parent().toggleClass('selected');
		$(this).toggleClass('delete');
		$(this).text($(this).text()=="Add" ? 'Remove' : 'Add');
	});
	
	function searchWare(list,search) {
		$(list+' tr').each(function() {
			var haystack = $(this).text();
			if(haystack.match("Add$"))
				haystack.slice(0, -3);
			else if(haystack.match("Remove$"))
				haystack.slice(0, -6);
			if((search.length && haystack.match(new RegExp(search, "i"))) || $(this).is('.selected')) 
				$(this).show();
			else
				$(this).hide();
		});
	}

	$('#hardList tr:not(.selected), #softList tr:not(.selected)').each(function() {
		$(this).hide();
	});

	$('tr:nth-of-type(3n+4)').hide().each(function() {
		$(this).children().first().children().slideUp();
	});

	$('tr:nth-of-type(3n+2), tr:nth-of-type(3n+3)').on('click vclick', function() {
		$(this).nextUntil('tr:nth-of-type(3n+2)','.responses').toggle().children().first().children().slideToggle(300);
	});

	// searching for a specialist
	// hide the ID field
	$('[name="idspec"]').hide();

	$('[name="specialist"]').on('focus keyup',searchSpecs);

	$('[name="specialist"]').blur(function(){
		$('#specialists #found').slideUp();
	});

	function searchSpecs() {
		var search = $('[name="specialist"]').val();
		if($.trim(search)) {
			$.ajax({
				type: "GET",
				url: '../ajax/specialists.php',
				data: {'s':search},
				success: function(data) {
					$('#specialists #found').html(data);
					$('#specialists #found').slideDown();
					specButtons();
				},
				error: function(error) { // when there's a link to a page that doesn't exist
					console.log('Tried to load ajax/specialists.php and got a '+error.status);
					tempError("There was an error looking for specialists, please try again");
				}
			});
		}
		else {
			$('#specialists #found').slideUp();
			$('#specialists #found').html('');
		}
	}

	function specButtons() {
		$('#specialists button').off('click vclick').on('click vclick', function(e) {
			e.preventDefault();
			$('[name="idspec"]').val($(this).data('id'));
			$('#specSearch').slideUp();
			$('#assigned').html('');
			$('<span>Assigned to <strong>'+$(this).parent().siblings(':nth-of-type(2)').text()+'</strong></span><br>').hide().appendTo('#assigned').fadeIn();
			$('#specialists #recommended').hide();
			$('<a href="#" class="delete">Remove</a><br><br>').hide().fadeIn().appendTo('#assigned').on('click vclick',function(e){
				e.preventDefault();
				$('[name="idspec"]').val('')
				$('#specSearch').slideDown();
				$('#assigned').fadeOut();
				$('#specialists #recommended').show();
			});
		});
	}
</script>