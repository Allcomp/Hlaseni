<section>
<h1 class="pagename">Hlášení</h1>
<table class="announcements" cellspacing="0" cellpadding="0">
<tr>
	<th>ID</th>
	<th>Název</th>
	<th>Datum/Čas</th>
	<th>Znělka</th>
	<th>&nbsp;</th>
</tr>
<?php $database2 = new Database(new DatabaseConfig($_CONFIG['dbhost'], $_CONFIG['dbuser'], $_CONFIG['dbpass'], $_CONFIG['dbname'])); ?>
<?php $rsRecordings = $database->executeQuery("SELECT * FROM `announcements` ORDER BY `time` DESC;"); ?>
<?php while ($row = $rsRecordings->fetch_row()) :?>
<tr>
	<td><?=$row[0]?></td>
	<td><?=$row[1]?></td>
	<td>
		<?php 
			echo(date('d.m.Y H:i', $row[5]/1000)); 
		?>
	</td>
	<td>
		<?php if($row[3]=="0") :?>
			<img src="./res/icons/tune_none.png" class="tune_icon" />
		<?php else :?>
			<?php
				$prepareTitlex = "";
				$rsTunesxx = $database->executeQuery("SELECT * FROM `tunes` WHERE `id` LIKE '".$row[3]."';");
				while ($rowTt = $rsTunesxx->fetch_row()) {
					$prepareTitlex .= "<p>" . $rowTt[1] . "</p>";
					break;
				}
			?>
			<img src="./res/icons/tune_yes.png" data-title="<?=encodeURIComponent($prepareTitlex)?>" class="tune_icon" />
		<?php endif; ?>
	</td>
	<td>
		<div style="margin-top: 3px;">
		<?php
			$prepareTitle = "<p><b>Název:</b> " . $row[1] . "</p>";
			$prepareTitle .= "<p><b>Datum:</b> " . date('d.m.Y', $row[5]/1000) . "</p>";
			$prepareTitle .= "<p><b>Čas:</b> " . date('H:i:s', $row[5]/1000) . "</p>";
			if($row[3]!="0") {
				$rsTunes = $database->executeQuery("SELECT * FROM `tunes` WHERE `id` LIKE '".$row[3]."';");
				while ($rowT = $rsTunes->fetch_row()) {
					$prepareTitle .= "<p><b>Znělka:</b> " . $rowT[1] . "</p>";
					break;
				}
			}
			if($row[4]!="0") {
				$rsRecs = $database->executeQuery("SELECT * FROM `recordings` WHERE `id` LIKE '".$row[4]."';");
				while ($rowR = $rsRecs->fetch_row()) {
					$prepareTitle .= "<p><b>Záznam:</b> " . $rowR[1] . "</p><br />";
					break;
				}
			}
			$prepareTitle .= "<p><b>Popis:</b><br /> " . $row[2] . "</p>";
		?>
		<img data-title="<?=encodeURIComponent($prepareTitle)?>" class="rec_control_icon" src="./res/icons/record_see.png" /><!--
		--><img class="rec_control_icon" id="announcement_edit" data-editid="<?=$row[0]?>" src="./res/icons/record_edit.png" /><!--
		--><img class="rec_control_icon" id="announcement_delete" src="./res/icons/record_delete.png" data-id="<?=$row[0]?>" />
		</div>
	</td>
</tr>
<?php endwhile; ?>
</table>
<script>
$(document).ready(function() {
	String.prototype.endsWith = function(suffix) {
		return this.indexOf(suffix, this.length - suffix.length) !== -1;
	};
	
	$("img#announcement_edit").click(function() {
		location.href = "?page=edit_announcement&id=" + $(this).data("editid");
	});
	
	$("img#announcement_delete").click(function() {
		var src = $(this).attr("src");
		var id = $(this).data("id");
		if(src == "./res/icons/record_delete.png") {
			$(this).attr("src", "./res/icons/record_deleteq.png");
			var elem = $(this);
			setTimeout(function() {
				elem.attr("src", "./res/icons/record_delete.png");
			}, 2000);
		} else if(src == "./res/icons/record_deleteq.png") {
			$.ajax({
				url: './phplib/delete_announcement.php',
				data: {
					'id': id
				},
				type: 'post',
				success: function(res) {
					location.reload();
				}
			});
		}
	});
	$('#announcement_upload_btn').click(function() {
		if($("input#announcement_upload_name").val() == "") {
			alert("Chybí název!");
			return;
		}
		if($("input#announcement_upload_recording").val() == "") {
			alert("Vyberte nahrávku!");
			return;
		}
		if($("input#announcement_upload_datetime").val() == "") {
			alert("Chybí datum/čas!");
			return;
		}
		$.ajax({
			url: './phplib/upload_announcement.php',
			type: 'post',
			data: {
				'name': $("#announcement_upload_name").val(),
				'tune': $("#announcement_upload_tune").val(),
				'recording': $("#announcement_upload_recording").val(),
				'description': $("#announcement_upload_description").val(),
				'datetime': $("#announcement_upload_datetime").val()
			},
			success: function(php_script_response) {
				location.reload();
			}
		});
	});
	
	$("button#announcement_macro_today").click(function() {
		var datetimeElem = $("#announcement_upload_datetime");
		var d = new Date();
		var datetimeStr = d.getDate() + "." + (d.getMonth()+1) + "." + d.getFullYear() + " ";
		datetimeElem.focus();
		datetimeElem.val(datetimeStr);
	});
	
	$("button#announcement_macro_tomorrow").click(function() {
		var datetimeElem = $("#announcement_upload_datetime");
		var d = new Date((new Date).getTime() + 24*60*60*1000);
		var datetimeStr = d.getDate() + "." + (d.getMonth()+1) + "." + d.getFullYear() + " ";
		datetimeElem.focus();
		datetimeElem.val(datetimeStr);
	});
	
	$("button#announcement_macro_thishour").click(function() {
		var datetimeElem = $("#announcement_upload_datetime");
		var d = new Date();
		var datetimeStr = d.getDate() + "." + (d.getMonth()+1) + "." + d.getFullYear() + " " + d.getHours() + ":";
		datetimeElem.focus();
		datetimeElem.val(datetimeStr);
	});
	
	$("button#announcement_macro_halfminlater").click(function() {
		var datetimeElem = $("#announcement_upload_datetime");
		var d = new Date((new Date).getTime() + 30*1000);
		var mins = d.getMinutes();
		if(mins < 10)
			mins = "0"+mins;
		var secs = d.getSeconds();
		if(secs < 10)
			secs = "0"+secs;
		var datetimeStr = d.getDate() + "." + (d.getMonth()+1) + "." + d.getFullYear() + " " + d.getHours() + ":" + mins + ":" + secs;
		datetimeElem.focus();
		datetimeElem.val(datetimeStr);
	});
	
	$("button#announcement_macro_1minlater").click(function() {
		var datetimeElem = $("#announcement_upload_datetime");
		var d = new Date((new Date).getTime() + 60*1000);
		var mins = d.getMinutes();
		if(mins < 10)
			mins = "0"+mins;
		var secs = d.getSeconds();
		if(secs < 10)
			secs = "0"+secs;
		var datetimeStr = d.getDate() + "." + (d.getMonth()+1) + "." + d.getFullYear() + " " + d.getHours() + ":" + mins + ":" + secs;
		datetimeElem.focus();
		datetimeElem.val(datetimeStr);
	});
});
</script>
</section>
<section>
	<h1 class="pagename">Nahrát hlášení</h1>
	<table id="table_upload_announcement">
		<tr>
			<td>Název</td>
			<td><input type="text" id="announcement_upload_name" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Znělka</td>
			<td>
				<select id="announcement_upload_tune">
					<option value="0">--- --- ŽÁDNÁ --- ---</option>
					<?php $rsxTunes = $database->executeQuery("SELECT * FROM `tunes` ORDER BY `name` ASC;");
					while ($row = $rsxTunes->fetch_row()) :?>
						<option value="<?=$row[0]?>"><?=$row[1]?></option>
					<?php endwhile; ?>
				</select>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Nahrávka</td>
			<td>
				<select id="announcement_upload_recording">
					<?php $rsxRecordings = $database->executeQuery("SELECT * FROM `recordings` ORDER BY `time` DESC;");
					while ($row = $rsxRecordings->fetch_row()) :?>
						<option value="<?=$row[0]?>"><?=$row[1]?> (<?=date('d.m.Y H:i', $row[4]/1000)?>)</option>
					<?php endwhile; ?>
				</select>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Datum/čas</td>
			<td style="text-align: center;">
				<p><input type="text" id="announcement_upload_datetime" /></p>
				<p><button id="announcement_macro_thishour">Tato hodina</button> <button id="announcement_macro_today">Dnes</button> <button id="announcement_macro_tomorrow">Zítra</button> <!--<button id="announcement_macro_halfminlater">Za půl minuty</button> <button id="announcement_macro_1minlater">Za minutu</button>--></p>
			</td>
			<td style="vertical-align: top; transform: translate(0,10px);">(př: "10.01.2012 18:20:13" zapište bez uvozovek)</td>
		</tr>
		<tr>
			<td>Popis</td>
			<td><textarea id="announcement_upload_description"></textarea></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="text-align: right;"><button id="announcement_upload_btn">Nahrát</button></td>
			<td>&nbsp;</td>
		</tr>
	</table>
</section>