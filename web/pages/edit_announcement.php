<section>
<h1 class="pagename">Upravit znělku</h1>
<table id="table_edit_announcement" cellspacing="0" cellpadding="0">
<?php $rsRecordings = $database->executeQuery("SELECT * FROM `announcements` WHERE `id` LIKE '".$_GET["id"]."';"); ?>
<?php while ($row = $rsRecordings->fetch_row()) :?>
<tr>
	<td>ID</td>
	<td id="announcement_id" style="font-weight: bold;"><?=$row[0]?></td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>Název</td>
	<td><input type="text" id="upload_edit_announcement_name" value="<?=$row[1]?>" /></td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>Datum/čas</td>
	<td>
		<p><input type="text" id="upload_edit_announcement_datetime" value="<?php echo(date('d.m.Y H:i:s', $row[5]/1000)); ?>" /></p>
		<p><button id="announcement_macro_thishour">Tato hodina</button> <button id="announcement_macro_today">Dnes</button> <button id="announcement_macro_tomorrow">Zítra</button> <!--<button id="announcement_macro_halfminlater">Za půl minuty</button> <button id="announcement_macro_1minlater">Za minutu</button>--></p>
	</td>
	<td style="vertical-align: top; transform: translate(0,10px);">(př: "10.01.2012 18:20:13" zapište bez uvozovek)</td>
</tr>
<tr>
	<td>Znělka</td>
	<td>
		<select id="upload_edit_announcement_tune">
			<?php if($row[3]!="0") :?>
				<option value="0">--- --- ŽÁDNÁ --- ---</option>
			<?php else :?>
				<option value="0" selected>--- --- ŽÁDNÁ --- ---</option>
			<?php endif; ?>
			
			<?php $rsTunes = $database->executeQuery("SELECT * FROM `tunes` ORDER BY `name` ASC;");
			while ($rowT = $rsTunes->fetch_row()) :?>
				<option value="<?=$rowT[0]?>" <?php if($rowT[0]==$row[3]) :?>selected<?php endif; ?>><?=$rowT[1]?></option>
			<?php endwhile; ?>
		</select>
	</td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>Záznam</td>
	<td>
		<select id="upload_edit_announcement_recording">
			<?php if($row[4]=="0") :?>
				<option value="0" selected>Nebude přehráno!</option>
			<?php endif; ?>
			
			<?php $rsRecordings = $database->executeQuery("SELECT * FROM `recordings` ORDER BY `time` DESC;");
			while ($rowR = $rsRecordings->fetch_row()) :?>
				<option value="<?=$rowR[0]?>" <?php if($rowR[0]==$row[4]) :?>selected<?php endif; ?>><?=$rowR[1]?></option>
			<?php endwhile; ?>
		</select>
	</td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>Popis</td>
	<td><textarea id="upload_edit_announcement_description"><?=$row[2]?></textarea></td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td style="text-align: right;" colspan="2"><button id="upload_edit_announcement_goback">Zpět</button> <button id="upload_edit_announcement_btn">Uložit záznam</button> <button id="upload_edit_announcement_restore">Obnovit</button></td>
	<td>&nbsp;</td>
</tr>
<?php break; ?>
<?php endwhile; ?>
</table>
<script>
$(document).ready(function() {
	
	$('#upload_edit_announcement_goback').click(function() {
		location.href="?page=announcements";
	});
	
	$('#upload_edit_announcement_restore').click(function() {
		location.reload();
	});
	
	$('#upload_edit_announcement_btn').click(function() {
		if($("input#upload_edit_announcement_name").val() == "") {
			alert("Chybí název!");
			return;
		}
		if($("input#upload_edit_announcement_recording").val() == "0") {
			alert("Vyberte záznam!");
			return;
		}
		console.log($("#tune_announcement").html());
		$.ajax({
			url: './phplib/edit_announcement.php',
			data: {
				'id': $("#announcement_id").html(),
				'name': $("#upload_edit_announcement_name").val(),
				'description': $("#upload_edit_announcement_description").val(),
				'tune': $("#upload_edit_announcement_tune").val(),
				'recording': $("#upload_edit_announcement_recording").val(),
				'datetime': $("#upload_edit_announcement_datetime").val()
			},
			type: 'post',
			success: function(php_script_response) {
				console.log(php_script_response);
				location.reload();
			}
		});
	});
	
	$("button#announcement_macro_today").click(function() {
		var datetimeElem = $("#upload_edit_announcement_datetime");
		var d = new Date();
		var datetimeStr = d.getDate() + "." + (d.getMonth()+1) + "." + d.getFullYear() + " ";
		datetimeElem.focus();
		datetimeElem.val(datetimeStr);
	});
	
	$("button#announcement_macro_tomorrow").click(function() {
		var datetimeElem = $("#upload_edit_announcement_datetime");
		var d = new Date((new Date).getTime() + 24*60*60*1000);
		var datetimeStr = d.getDate() + "." + (d.getMonth()+1) + "." + d.getFullYear() + " ";
		datetimeElem.focus();
		datetimeElem.val(datetimeStr);
	});
	
	$("button#announcement_macro_thishour").click(function() {
		var datetimeElem = $("#upload_edit_announcement_datetime");
		var d = new Date();
		var datetimeStr = d.getDate() + "." + (d.getMonth()+1) + "." + d.getFullYear() + " " + d.getHours() + ":";
		datetimeElem.focus();
		datetimeElem.val(datetimeStr);
	});
	
	$("button#announcement_macro_halfminlater").click(function() {
		var datetimeElem = $("#upload_edit_announcement_datetime");
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
		var datetimeElem = $("#upload_edit_announcement_datetime");
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