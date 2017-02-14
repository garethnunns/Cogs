<?php
/*
Adding a call to system - so all the fields that this requires, and referencing things like hardware, software and previous problems

Change log
==========

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once dirname(__FILE__).'/../check.php';
?>

<form method="POST" class="call">

	<h1>New Call</h1>

	<div class="item">
		<h3>Caller</h3>
		<div class="data" id="caller">
			<input type="text" name="caller" placeholder="Name or ext. of caller" />
		</div>
	</div>
	<div id="resCaller"></div>

	<div class="item">
		<h3>Problem</h3>
		<div class="data" id="problem">
			<input type="text" name="problem" placeholder="Existing problem or new problem" />
		</div>
	</div>
	<div id="resProblem"></div>

	<h2>Call Details</h2>

	<div class="item">
		<h3>Title</h3>
		<div class="data">
			<input type="text" name="title" placeholder="Title of your call" />
		</div>
	</div>

	<h3>Notes</h3>
	<textarea placeholder="Summary of the call"></textarea>

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

		<div id="recommended">
			<h3>Recommended Specialists</h3>
			<table class="specialists">
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Phone</th>
					<th colspan="2">Problems</th>
					<th>Availability</th>
					<th>Assign</th>
				</tr>
<?php
	$rand3 = [];
	$i=0;

	while ($i <= 3) { 
		$rand = mt_rand(0,count($tlogin)-1);
		if(!in_array($rand, $rand3) && $tlogin[$rand]['job']=='Specialist') {
			$rand3[$rand] = $rand;
			$i++;
		}
	}

	foreach ($rand3 as $key => $value)
		$rand3[$key] = $tlogin[$value];

	foreach ($rand3 as $id => $spec) {
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
					if($id == $problem['assign'][$asskey]['op'])
						array_push($probs['unsolved'], $key);
				}
			}
		}

		echo "<tr>
		<td>{$id}</td>
		<td>{$spec['name']}</td>
		<td>ext ".mt_rand(10000, 55555)."</td>
		<td class='numProbs'><span>".count($probs['unsolved'])."</span><br>Unsolved</td>
		<td class='numProbs'><span>".mt_rand(0, 25)."</span><br>Solved this week</td>
		<td>".$avail[array_rand($avail,1)]."</td>
		<td><button>Assign</button></td>
		</tr>";
	}
?>
			</table>
		</div>
	</div>

	<p><input type="submit" value="Add call"></p>

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
	foreach ($tproblems as $problem)
		if(!$problem['solution']) // it hasn't been solved
			outputProblem($problem,false);
		else
			outputProblem($problem,true);
?>
</table>

<script type="text/javascript">
	// searching for a caller
	$('[name="caller"]').on('focus keyup',searchCallers);

	$('[name="caller"]').blur(function(){
		$('#resCaller').slideUp();
	});

	function searchCallers() {
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
	$('[name="problem"]').on('focus keyup',searchProblems);

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
						var selected = $(this).html();
						var id = '#prob'+$(this).data('id');
						$('[name="problem"]').fadeOut(300,function(){
							$('<p>'+selected+'<br><a href="#" class="change">Change</a></p>').hide().appendTo('#problem').fadeIn().on('click vlick',function(e){ // decided to change what they selected
								e.preventDefault();
								$(this).fadeOut(300, function(){
									$('[name="problem"]').fadeIn(300).focus();
								}).remove();
								$('#allProblemsTitle').text('All problems');
								$('#allProblems tr:nth-of-type(3n+2), #allProblems tr:nth-of-type(3n+3)').show();
								$('#allProblems '+id).trigger('click');
								$('#specialists #recommended').hide();
							});
						});
						$('#allProblemsTitle').text('Problem '+$(this).data('id'));
						$('#allProblems tr:nth-of-type(3n+2), #allProblems tr:nth-of-type(3n+3)').hide();
						$('#allProblems '+id).show().trigger('click').next().show();
						$('#specialists #recommended').show();
						specButtons();
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

	// searching for a caller
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
			$('#specSearch').slideUp();
			$('#assigned').html('');
			$('<span>Assigned to <strong>'+$(this).parent().siblings(':nth-of-type(2)').text()+'</strong></span><br>').hide().appendTo('#assigned').fadeIn();
			$('#specialists #recommended').hide();
			$('<a href="#" class="delete">Remove</a><br><br>').hide().fadeIn().appendTo('#assigned').on('click vclick',function(e){
				e.preventDefault();
				$('#specSearch').slideDown();
				$('#assigned').fadeOut();
				$('#specialists #recommended').show();
			});
		});
	}
</script>