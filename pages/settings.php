<?php
	require_once dirname(__FILE__).'/../check.php';
?>

<form method="POST" action="settings.php" id="settings">
	<h1>Settings</h1>

	<div class="set">
		<h3><label for="fonts">Larger fonts</label></h3>
		<div class="setting">
			<input type="checkbox" name="fonts" id="fonts" <?php if($_SESSION['fonts']) echo 'checked'?>>
		</div>
	</div>
	<p>Increase all of the font size to make it easier to read</label></p>


	<div class="set">
		<h3>Date Format</h3>
		<div class="setting">
			<select name="format">
<?php
	foreach ($tFormats as $key => $dformat)
		echo "<option value='{$key}' ".($key==$_SESSION['format'] ? 'selected' : '').">{$key}</option>";
?>
			</select>
		</div>
	</div>
	<p>Please choose your preferred date format, currently dates are formatted like this: <strong id="time"><?php require_once dirname(__FILE__).'/../time.php'; ?></strong></p>

	<div class="set">
		<h3>Language</h3>
		<div class="setting">
			<select name="lang">
<?php
	foreach ($tLanguages as $code => $language)
		echo "<option value='{$code}' ".($code==$_SESSION['lang'] ? 'selected' : '').">{$language}</option>";
?>
			</select>
		</div>
	</div>
	<p>Please select your language</p>

	<div class="set">
		<h3><label for="guessLang">Automatically translate content</label></h3>
		<div class="setting">
			<input type="checkbox" name="guessLang" id="guessLang" <?php if($_SESSION['autoTrans']) echo 'checked'?>>
		</div>
	</div>
	<p>Would you like the site to attempt to translate content when there is no stored translation?</p>

	<div class="set">
		<h3>Timezone</h3>
		<div class="setting">
			<select name="timezone">
<?php
	$zones = timezone_identifiers_list();
	$group = '';
	$current = '';
	$last = '';

	foreach ($zones as $zoneLit) {
		if($zoneLit == date_default_timezone_get()) $current = 'selected';
		else $current = '';
		$zone = explode('/', $zoneLit); // 0 => Continent, 1 => City

		// Only use "friendly" continent names
		if ($zone[0] == 'Africa' || $zone[0] == 'America' || $zone[0] == 'Antarctica' || $zone[0] == 'Arctic' || $zone[0] == 'Asia' || $zone[0] == 'Atlantic' || $zone[0] == 'Australia' || $zone[0] == 'Europe' || $zone[0] == 'Indian' || $zone[0] == 'Pacific') {        
			if($zone[0]!=$group) {
				if(!$group) echo '</optgroup>';
				echo "<optgroup label='{$zone[0]}'>";
				$group = $zone[0];
			}
			if ((isset($zone[1]) != '') && ($zone[1]!=$last))
				echo "<option value='{$zoneLit}' {$current}>".str_replace('_', ' ', $zone[1]).'</option>';
			$last = $zone[1]; // some of the countries seem to be the same
		}
	}
	echo '</optgroup>';
?>
			</select>
		</div>
	</div>
	<p>Choose the timezone you would to see all dates presented as on the site</p>

	<p class="noJS"><input type="submit" value="Update settings"></p>

	<p id="tzlabel"></p>
	<div id="zonepicker"></div>
</form>

<script type="text/javascript" src="/site/jquery.timezone-picker.js"></script>
<script>
$('.noJS').remove();

$.getScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyB9DcqlMad06yazL8O2Gyffmovc2_9ZqRc", function() {
	$("#zonepicker").timezonePicker({
		initialLat: 20,
		initialLng: 0,
		initialZoom: 2,
		jsonRootUrl: '/site/tz_json/',
		fillColor: '#3498db',
		strokeColor: '#2c3e50',
		onReady: function() {
			$('#tzlabel').text('Alternatively, choose your timezone on the map below');
			$("#zonepicker").timezonePicker('selectZone', '<?php echo date_default_timezone_get(); ?>');
		},
		onHover: function(utcOffset, tzNames) {
			$('#tzlabel').html('<b>Timezone</b> - ' + tzNames.join(',') + ': ' + utcOffset + ' minutes');
		},
		onSelected: function(olsonName) {
			$('[name="timezone"]').val(olsonName);
			updateSettings(false);
			$("#zonepicker").timezonePicker('hideInfoWindow');
		},
		mapOptions: {
			maxZoom: 6,
			minZoom: 2,
			streetViewControl: false,
			scrollwheel: false,
		}
	});
});

$('[name="timezone"]').change(function() {
	$("#zonepicker").timezonePicker('selectZone', $(this).val());
});

$('[name="format"]').change(function() {
	updateSettings(false);
});

$('#settings select:not([name="timezone"],[name="format"]), #settings input').change(function () {
	updateSettings();
});

function updateSettings(reload = true) {
	$.ajax({
		type: "POST",
		url: 'settings.php',
		data: $("#settings").serialize(),
		success: function(data, status, xhr) {
			var refresh = xhr.getResponseHeader('refresh');
			if(refresh) console.log('Updated');
			else tempError(data);

			if(reload) location.reload();
			updateTime();
		},
		error: function(error) { // when there's a link to a page that doesn't exist
			console.log('Tried to load settings.php and got a '+error.status);
			tempError("There was an error updating the settings, please try again");
		}
	});
}

function updateTime() {
	$.ajax({
		type: "GET",
		url: 'time.php',
		success: function(data) {
			$('#time').html(data);
		},
		error: function(error) { // when there's a link to a page that doesn't exist
			console.log('Tried to load time.php and got a '+error.status);
			tempError("There was an error updating the settings, please try again");
		}
	});
}
</script>