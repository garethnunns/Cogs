<?php
	require_once dirname(__FILE__).'/../check.php';
?>

<h1>Problems</h1>

<table class="problems">
	<tr>
		<th>Title</th>
		<th>Customer</th>
		<th>Specialist</th>
		<th colspan="3">Calls</th>
	</tr>

	<tr>
		<td rowspan="2"><strong>Computer won't turn on</strong></td>
		<td rowspan="2">Joe Yelland</td>
		<td rowspan="2">Mike Ross</td>
		<td rowspan="2" class="numCalls"><span>3</span><br>Calls</td>
		<td>Most recent</td>
		<td>Today at 14:53 (20 minutes ago)</td>
	</tr>
	<tr>
		<td>Reported</td>
		<td>4/11/16 (5 days ago)</td>
	</tr>
	<tr class="responses">
		<td colspan="6">
			<h2>Computer won't turn on</h2>

			<div class="response">
				<div class="staff">
					<p><strong>Message from Mike Ross</strong><br>
					<em>Today at 17:56<br>(20 minutes ago)</em></p>
					<p>Mike Ross<br>
					<em>Specialist</em></p>
				</div>

				<div class="notes">
					<h3>Maybe he's a retard</h3>

					<p>Just saying.</p>
				</div>
			</div>

			<div class="response">
				<div class="staff">
					<p><strong>Call with Joe Yelland</strong><br>
					<em>Today at 14:53<br>(3 hours ago)</em></p>
					<p>Donna Paulsen<br>
					<em>Helpdesk Operator</em></p>
				</div>

				<div class="notes">
					<h3>Issue remains</h3>

					<p>Joe has called again saying:</p>

					<blockquote>
						I've tried everything to turn it on - put on Netflix, worn my waviest garms and even lit scented joints but she still doesn't seem turned on.
					</blockquote>

					<p><em>Needs following up</em></p>
				</div>
			</div>

			<div class="response">
				<div class="staff">
					<p><strong>Call with Joe Yelland</strong><br>
					<em>6/11/16 at 15:22<br>(3 days ago)</em></p>
					<p>Mike Ross<br>
					<em>Specialist</em></p>
				</div>

				<div class="notes">
					<h3>Checked basics</h3>

					<p>Told Joe to check the power lead was plugged in and the socket was switched on</p>
				</div>
			</div>

			<div class="response">
				<div class="staff">
					<p><strong>Call with Joe Yelland</strong><br>
					<em>4/11/16 at 15:22<br>(5 days ago)</em></p>
					<p>Donna Paulsen<br>
					<em>Helpdesk Operator</em></p>
				</div>

				<div class="notes">
					<h3>Computer won't turn on</h3>

					<p>Joe has called in again saying:</p>

					<blockquote>
						My computer won't turn on
					</blockquote>
				</div>
			</div>
		</td>
	</tr>

	<tr>
		<td rowspan="2"><strong>This f***ing helpdesk</strong></td>
		<td rowspan="2">Gareth Nunns</td>
		<td rowspan="2">Harvey Specter</td>
		<td rowspan="2" class="numCalls"><span>69</span><br>Calls</td>
		<td>Most recent</td>
		<td>Yesterday at 03:20 (35 hours ago)</td>
	</tr>
	<tr>
		<td>Reported</td>
		<td>1/10/16 (1 month ago)</td>
	</tr>
	<tr class="responses">
		<td colspan="6">
			<div class="response">
				<h2>This f***ing helpdesk</h2>
				<p>Yeah, I'm not typing all that out&hellip;</p>
			</div>
		</td>
	</tr>
</table>

<script type="text/javascript">
	$('tr:nth-of-type(3n+4)').hide().each(function() {
		$(this).children().first().children().slideUp();
	});

	$('tr:nth-of-type(3n+2), tr:nth-of-type(3n+3)').on('click vclick', function() {
		$(this).nextUntil('tr:nth-of-type(3n+2)','.responses').toggle().children().first().children().slideToggle(300);
	});
</script>