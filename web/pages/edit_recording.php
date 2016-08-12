<section>
<h1 class="pagename">Upravit záznam</h1>
<table id="table_edit_recording" cellspacing="0" cellpadding="0">
<?php $rsRecordings = $database->executeQuery("SELECT * FROM `recordings` WHERE `id` LIKE '".$_GET["id"]."';"); ?>
<?php while ($row = $rsRecordings->fetch_row()) :?>
<tr>
	<td>ID</td>
	<td id="recording_id" style="font-weight: bold;"><?=$row[0]?></td>
</tr>
<tr>
	<td>Soubor</td>
	<td><i><?=$row[3]?></i></td>
</tr>
<tr>
	<td>Nahráno</td>
	<td><i><?php  echo(date('d.m.Y H:i:s', $row[4]/1000)); ?></i></td>
</tr>
<tr>
	<td>Název</td>
	<td><input type="text" id="upload_edit_recording_name" value="<?=$row[1]?>" /></td>
</tr>
<tr>
	<td>Popis</td>
	<td><textarea id="upload_edit_recording_description"><?=$row[2]?></textarea></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td style="text-align: right;"><button id="upload_edit_recording_goback">Zpět</button> <button id="upload_edit_recording_btn">Uložit záznam</button> <button id="upload_edit_recording_restore">Obnovit</button></td>
</tr>
<?php break; ?>
<?php endwhile; ?>
</table>
<script>
$(document).ready(function() {
	
	$('#upload_edit_recording_goback').click(function() {
		location.href="?page=recordings";
	});
	
	$('#upload_edit_recording_restore').click(function() {
		location.reload();
	});
	
	$('#upload_edit_recording_btn').click(function() {
		if($("input#upload_edit_recording_name").val() == "") {
			alert("Chybí název!");
			return;
		}
		console.log($("#recording_id").html());
		$.ajax({
			url: './phplib/edit_recording.php',
			data: {
				'id': $("#recording_id").html(),
				'name': $("#upload_edit_recording_name").val(),
				'description': $("#upload_edit_recording_description").val()
			},
			type: 'post',
			success: function(php_script_response) {
				console.log(php_script_response);
				location.reload();
			}
		});
	});
});
</script>
</section>