<script>
	var switches = [];
	var jalousies = [];
	var ranges = [];
	var thermostats = [];
	var switchesOutputs = [];
	var jalousiesOutputs = [];
	var rangesOutputs = [];
	var thermostatsOutputs = [];
	
	function voidCatch(catchResult, catchId) { deleteRequest(catchId); }
	
	function requestOutputChange(output, val) {
		var reqid = makeRequest('voidCatch', 'output/'+output+'/'+val);
		setTimeout(function() {
			if(requestExists(reqid))
				deleteRequest(reqid);
		}, 1800);
	}
	
	function requestThermostatTargetChange(output, val) {
		var reqid = makeRequest('voidCatch', 'thermset/'+output+'/'+val);
		setTimeout(function() {
			if(requestExists(reqid))
				deleteRequest(reqid);
		}, 1800);
	}
	
	function requestUpdateSwitches() {
		var outputs = switchesOutputs.toString().replace(/,/g , "-");
		var reqid = makeRequest('updateSwitches', 'states/'+outputs);
		setTimeout(function() {
			if(requestExists(reqid))
				deleteRequest(reqid);
		}, 1800);
	}
	
	function requestUpdateThermostatSwitches() {
		var outputs = thermostatsOutputs.toString().replace(/,/g , "-");
		var reqid = makeRequest('updateSwitches', 'thermsactiveget/'+outputs);
		setTimeout(function() {
			if(requestExists(reqid))
				deleteRequest(reqid);
		}, 1800);
	}
	
	function updateSwitches(states, id) {
		deleteRequest(id);
		var pairs = states.split("-");
		for(var i = 0; i < pairs.length; i++) {
			var pair = pairs[i].split(":");
			var checkboxes = $("*[data-output='" + pair[0] +"']").toArray();
			if(checkboxes.length == 1)
                checkboxes[0].checked = pair[1] == "1";
		}
	}
	
	function requestUpdateRanges() {
		var outputs = rangesOutputs.toString().replace(/,/g , "-");
		var reqid = makeRequest('updateRanges', 'states/'+outputs);
		setTimeout(function() {
			if(requestExists(reqid))
				deleteRequest(reqid);
		}, 1800);
	}
	
	function updateRanges(states, id) {
		deleteRequest(id);
		var pairs = states.split("-");
		for(var i = 0; i < pairs.length; i++) {
			var pair = pairs[i].split(":");
			var ranges = $("*[data-output='" + pair[0] +"']").toArray();
			if(ranges.length == 1)
                ranges[0].value = parseInt(pair[1]);
		}
	}
	
	function requestUpdateTemperatureValues() {
		var outputs = rangesOutputs.toString().replace(/,/g , "-");
		var reqid = makeRequest('updateTemperatureValues', 'temps/'+outputs);
		setTimeout(function() {
			if(requestExists(reqid))
				deleteRequest(reqid);
		}, 1800);
	}
	
	function updateTemperatureValues(states, id) {
		deleteRequest(id);
		var pairs = states.split("-");
		for(var i = 0; i < pairs.length; i++) {
			var pair = pairs[i].split(":");
			var tempvals = $("*[data-output='" + pair[0] +"']").toArray();
			if(tempvals.length == 1)
                tempvals[0].value = pair[1] + " °C";
		}
	}
	
	function requestUpdateJalousies() {
		var outputs = jalousiesOutputs.toString().replace(/,/g , "-");
		var reqid = makeRequest('updateJalousies', 'states/'+outputs);
		setTimeout(function() {
			if(requestExists(reqid))
				deleteRequest(reqid);
		}, 1800);
	}
	
	function updateJalousies(states, id) {
		deleteRequest(id);
		var pairs = states.split("-");
		for(var i = 0; i < pairs.length; i++) {
			var pair = pairs[i].split(":");
			var radioButtons = $("*[data-output='" + pair[0] +"']").toArray();
			if(radioButtons.length == 1)
                radioButtons[0].checked = pair[1] == "1";
		}
		var stopRadioButtons = $("input.btnStop").toArray();
		for(var i = 0; i < stopRadioButtons.length; i++) {
			var radioBtns = $(stopRadioButtons[i]).parent().parent().find("input[type='radio']");
			if(radioBtns.length == 3) {
				var out1 = radioBtns[0].checked;
				var out2 = radioBtns[2].checked;
				stopRadioButtons[i].checked = !(out1 || out2);
			}
		}
	}
	
	$(document).ready(function() {
		setInterval(function() {
			requestUpdateSwitches();
			setTimeout(function(){requestUpdateThermostatSwitches();},50);
			setTimeout(function(){requestUpdateJalousies();},100);
		}, 200);
		setTimeout(function(){requestUpdateRanges();},150);
		setTimeout(function(){requestUpdateTemperatureValues();},200);
		setInterval(function() {
			setTimeout(function(){requestUpdateRanges();},150);
			setTimeout(function(){requestUpdateTemperatureValues();},200);
		}, 5000);
	});
</script>

<!--
╔═════════════════════════════╗
║ control_type možnosti       ║
╠═══════════════════════╦═════╣
║ přepínač              ║  0  ║
║ žaluzie               ║  1  ║
║ posuvník              ║  2  ║
║ termostat             ║  3  ║
╚═══════════════════════╩═════╝
-->

<?php
	$roomId = $_GET["room"];
	$roomNameResult = $database->executeQuery("SELECT * FROM `rooms` WHERE `id` = " . $roomId . ";");
	$roomName = "";
	while($row = $roomNameResult->fetch_assoc()) {
		$roomName = $row['name'];
		break;
	}
	$defaultDeviceIcon = "./resources/appicons/defaultdevice.png";
	$controlItemsResult = $database->executeQuery("SELECT * FROM `controls` WHERE `room` = " . $roomId . " ORDER BY `rank` ASC;")
?>

<div class="floorheadline"><?php echo($roomName); ?></div>

<?php while($row = $controlItemsResult->fetch_assoc()) :?>
	<?php $icon = file_exists("./resources/icons/".$row['icon']) && !is_dir("./resources/icons/".$row['icon']) ? "./resources/icons/".$row['icon'] : $defaultDeviceIcon; ?>
	<div class="controlitem">
		<div class="icon"><img src="<?php echo($icon); ?>" /></div>
		<div class="name"><?php echo($row['name']); ?></div>
		<div class="controls">
			<?php if($row['control_type'] == 0) :?>
				<div>&nbsp;<div class="switch">
					<input id="switch<?php echo($row['id']); ?>" class="cmn-toggle cmn-toggle-round" type="checkbox" data-output="<?php echo($row['outputs']); ?>">
					<label onclick="requestOutputChange(<?php echo($row['outputs']); ?>, document.getElementById('switch<?php echo($row['id']); ?>').checked ? 0 : 1);"></label>
				</div></div>
				<script>
					switches.push(<?php echo($row['id']); ?>);
					switchesOutputs.push("<?php echo($row['outputs']); ?>");
				</script>
			<?php elseif($row['control_type'] == 1) :?>
				<div><div class='radioContainer'>
					<input type='radio' id='rad<?php echo($row['id']); ?>_1' name='rad<?php echo($row['id']); ?>' class='btnDown' data-output="<?php echo(explode(",", $row['outputs'])[0]); ?>" onclick="requestOutputChange(<?php echo(explode(",", $row['outputs'])[0]); ?>, 1); this.checked = false;" />
					<label for='rad<?php echo($row['id']); ?>_1'></label>
                </div>
                <div class='radioContainer'>
					<input type='radio' id='rad<?php echo($row['id']); ?>_2' name='rad<?php echo($row['id']); ?>' class='btnStop' />
					<label for='rad<?php echo($row['id']); ?>_2'></label>
                </div>
                <div class='radioContainer'>
					<input type='radio' id='rad<?php echo($row['id']); ?>_3' name='rad<?php echo($row['id']); ?>' class='btnUp' data-output="<?php echo(explode(",", $row['outputs'])[1]); ?>" onclick="requestOutputChange(<?php echo(explode(",", $row['outputs'])[1]); ?>, 1); this.checked = false;" />
					<label for='rad<?php echo($row['id']); ?>_3'></label>
                </div></div>
				<div><button>&darr;</button> <button>&uarr;</button></div>
				<script>
					jalousies.push(<?php echo($row['id']); ?>);
					jalousiesOutputs.push("<?php echo(explode(",", $row['outputs'])[0]); ?>");
					jalousiesOutputs.push("<?php echo(explode(",", $row['outputs'])[1]); ?>");
				</script>
			<?php elseif($row['control_type'] == 2) :?>
				<div><button class="zero" onclick="document.getElementById('range<?php echo($row['id']); ?>').value = '1'; requestOutputChange(<?php echo($row['outputs']); ?>,0);">×</button><input id="range<?php echo($row['id']); ?>" type="range" data-output="<?php echo($row['outputs']); ?>" min="1" max="100" step="1" value="0" onchange="requestOutputChange(<?php echo($row['outputs']); ?>, document.getElementById('range<?php echo($row['id']); ?>').value);" /></div>
				<script>
					ranges.push(<?php echo($row['id']); ?>);
					rangesOutputs.push("<?php echo($row['outputs']); ?>");
				</script>
			<?php elseif($row['control_type'] == 3) :?>
				<div>
					<p class="tempval" id="tempval<?php echo($row['id']); ?>" data-output="<?php echo($row['outputs']); ?>"></p>
					<div class="switch">
						<input id="switch<?php echo($row['id']); ?>" class="cmn-toggle cmn-toggle-round" type="checkbox" data-output="<?php echo($row['outputs']); ?>">
						<label for="switch<?php echo($row['id']); ?>"></label>
					</div>
				</div>
				<div>
					<p id="tempset<?php echo($row['id']); ?>" class="temptarget">10</p> °C 
					<button onclick="document.getElementById('tempset<?php echo($row['id']); ?>').innerHTML = (parseInt(document.getElementById('tempset<?php echo($row['id']); ?>').innerHTML)-1); requestThermostatTargetChange(<?php echo($row['outputs']); ?>, document.getElementById('tempset<?php echo($row['id']); ?>').innerHTML);">&darr;</button> <button onclick="document.getElementById('tempset<?php echo($row['id']); ?>').innerHTML = (parseInt(document.getElementById('tempset<?php echo($row['id']); ?>').innerHTML)+1); requestThermostatTargetChange(<?php echo($row['outputs']); ?>, document.getElementById('tempset<?php echo($row['id']); ?>').innerHTML);">&uarr;</button>
				</div>
				<script>
					thermostats.push(<?php echo($row['id']); ?>);
					thermostatsOutputs.push("<?php echo($row['outputs']); ?>");
				</script>
			<?php else :?>
				×
			<?php endif; ?>
		</div>
	</div>
<?php endwhile; ?>