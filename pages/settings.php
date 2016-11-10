<?php
	require_once dirname(__FILE__).'/../check.php';
?>

<form>
	<h1>Settings</h1>

	<h3>Larger fonts</h3>
	<p><label for="impaired">Increase all of the font size to make it easier to read</label></p>
	<input type="checkbox" name="impaired" id="impaired">

	<h3>Language</h3>
	<p>Please select your language</p>
	<p>Would you like the site to try and automatically translate user-generated text?</p>

	<h3>Timezone</h3>
	<p>Choose the timezone you would to see all dates presented as on the site</p>

	<p><?php echo 'Current timezone: ' . date_default_timezone_get() ?></p>

	<div id="zonepicker" style="width: 100%; height: 500px;"></div>
	<p id="label"></p>

	<input type="hidden" name="timezone">
</form>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9DcqlMad06yazL8O2Gyffmovc2_9ZqRc"></script>
<script type="text/javascript" src="/site/jquery.timezone-picker.js"></script>
<script>
//$(function() {
	$("#zonepicker").timezonePicker({
		initialLat: 20,
		initialLng: 0,
		initialZoom: 2,
		jsonRootUrl: '/site/tz_json/',
		fillColor: '#3498db',
		strokeColor: '#2c3e50',
		onReady: function() {
			$("#zonepicker").timezonePicker('selectZone', '<?php echo date_default_timezone_get() ?>');
		},
		onHover: function(utcOffset, tzNames) {
			$('#label').html('<b>Timezone</b> - ' + tzNames.join(',') + ': ' + utcOffset + ' minutes');
		},
		onSelected: function(olsonName) {
			$('input[name="timezone"]').val(olsonName);
			updateSettings();
			$("#zonepicker").timezonePicker('hideInfoWindow');
		},
		mapOptions: {
			maxZoom: 6,
			minZoom: 2
		}
	});
//});

function updateSettings() {
	var tz = $('input[name="timezone"]').val();

	$.ajax({
		type: "POST",
		url: 'settings.php',
		data: {timezone: tz},
		success: function(data, status, xhr) {
			var refresh = xhr.getResponseHeader('refresh');
			if(refresh) console.log('Updated');
			else tempError(data);
		}
	});
}
</script>