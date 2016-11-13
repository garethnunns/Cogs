<?php
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
		</div><div class="software">
		<h3>Related Software</h3>
		<p><input type="text" name="software" placeholder="Search software"></p>
		</div>
	</div>

	<input type="submit" value="Add call">

</form>

<h1>All problems</h1>

<table class="problems">
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

	$('[name="problem"]').on('focus keyup',searchProblems);

	$('[name="problem"]').blur(function(){
		$('#resProblem').slideUp();
	});

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

					$('#resProblem p').off('click vclick').on('click vclick', function(){
						var selected = $(this).html();
						$('[name="problem"]').fadeOut(300,function(){
							$('<p>'+selected+'<br><a href="#" class="change">Change</a></p>').hide().appendTo('#problem').fadeIn().on('click vlick',function(e){
								e.preventDefault();
								$(this).fadeOut(300, function(){
									$('[name="problem"]').fadeIn(300).focus();
								}).remove();
							});

						});
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

	$('tr:nth-of-type(3n+4)').hide().each(function() {
		$(this).children().first().children().slideUp();
	});

	$('tr:nth-of-type(3n+2), tr:nth-of-type(3n+3)').on('click vclick', function() {
		$(this).nextUntil('tr:nth-of-type(3n+2)','.responses').toggle().children().first().children().slideToggle(300);
	});
</script>