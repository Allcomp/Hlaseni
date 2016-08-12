<section>
<h1 class="pagename">Záznamy</h1>
<table class="recordings" cellspacing="0" cellpadding="0">
<tr>
	<th>ID</th>
	<th>Název</th>
	<th>Datum/Čas</th>
	<th>&nbsp;</th>
</tr>
<?php $rsRecordings = $database->executeQuery("SELECT * FROM `recordings` ORDER BY `time` DESC;"); ?>
<?php while ($row = $rsRecordings->fetch_row()) :?>
<tr>
	<td><?=$row[0]?></td>
	<td><?=$row[1]?></td>
	<td>
		<?php 
			echo(date('d.m.Y H:i', $row[4]/1000)); 
		?>
	</td>
	<td>
		<audio style="display: none;" id="record_<?=$row[0]?>" src="./records/<?=$row[3]?>" type="audio/wave">
		</audio>
		<div style="margin-top: 3px;">
		<?php
			$prepareTitle = "<p><b>Název:</b> " . $row[1] . "</p>";
			$prepareTitle .= "<p><b>Soubor:</b> " . $row[3] . "</p>";
			$prepareTitle .= "<p><b>Nahráno:</b> " . date('d.m.Y H:i', $row[4]/1000) . "</p><br />";
			$prepareTitle .= "<p><b>Popis:</b><br /> " . $row[2] . "</p>";
		?>
		<img data-title="<?=encodeURIComponent($prepareTitle)?>" class="rec_control_icon" src="./res/icons/record_see.png" /><!--
		--><img class="rec_control_icon" id="record_edit" data-editid="<?=$row[0]?>" src="./res/icons/record_edit.png" /><!--
		--><img class="rec_control_icon" id="record_playpause" src="./res/icons/record_play.png" data-audid="record_<?=$row[0]?>" /><!--
		--><img class="rec_control_icon" id="record_download" data-file="<?=$row[3]?>" src="./res/icons/record_download.png" /><!--
		--><img class="rec_control_icon" id="record_delete" src="./res/icons/record_delete.png" data-id="<?=$row[0]?>" />
		</div>
	</td>
</tr>
<?php endwhile; ?>
</table>
<script>
$(document).ready(function() {
	$("audio").trigger('load');

	String.prototype.endsWith = function(suffix) {
		return this.indexOf(suffix, this.length - suffix.length) !== -1;
	};
	
	$("audio").on('ended', function() {
		$("img#record_playpause[data-audid='"+$(this).attr("id")+"']").attr("src", "./res/icons/record_play.png");
	});
	
	$("img#record_download").click(function() {
		location.href = "./records/"+$(this).data("file");
	});
	
	$("img#record_edit").click(function() {
		location.href = "?page=edit_recording&id=" + $(this).data("editid");
	});
	
	$("img#record_playpause").click(function() {
		var src = $(this).attr("src");
		var id = $(this).data("id");
		if(src == "./res/icons/record_play.png") {
			$(this).attr("src", "./res/icons/record_pause.png");
			$("audio#"+$(this).data("audid")).trigger('play');
			/*var elem = $(this);
			setTimeout(function() {
				elem.attr("src", "./res/icons/record_delete.png");
			}, 2000);*/
		} else if(src == "./res/icons/record_pause.png") {
			$(this).attr("src", "./res/icons/record_play.png");
			$("audio#"+$(this).data("audid")).trigger('pause');
		}
	});
	
	$("img#record_delete").click(function() {
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
				url: './phplib/delete_recording.php',
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
	
	$('#recording_upload_btn').click(function() {
		if($("input#recording_upload_name").val() == "") {
			alert("Chybí název!");
			return;
		}
		if($("input#recording_upload_file").val() == "") {
			alert("Chybí soubor!");
			return;
		}
		if(!($("input#recording_upload_file").val().endsWith(".mp3") || $("input#recording_upload_file").val().endsWith(".wav"))) {
			alert("Soubor musí být ve formátu .mp3 nebo .wav!");
			return;
		}
		console.log("sending");
		var file_data = $('input#recording_upload_file').prop('files')[0];   
		var form_data = new FormData();
		form_data.append('file', file_data);
		form_data.append('name', $("input#recording_upload_name").val());
		form_data.append('description', $("textarea#recording_upload_description").val());
		form_data.append('time', (new Date()).getTime()+'');
		alert(form_data);
		$.ajax({
			url: './phplib/upload_recording.php', // point to server-side PHP script 
			dataType: 'text',  // what to expect back from the PHP script, if anything
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,
			type: 'post',
			success: function(php_script_response) {
				location.reload();
			}
		});
	});
	
	$("#recording_upload_file").change(function(event) {
		if(!$(this).val().endsWith(".wav")) {
			$("#recording_upload_file_label").html("Soubor není ve formátu .wav!");
			$("#recording_upload_file_label").css("background-color","#E32B02");
			setTimeout(function() {
				$("#recording_upload_file_label").html("Vyberte soubor s nahrávkou...");
				$("#recording_upload_file_label").css("background-color","#3498db");
			}, 2000);
		} else {
			var arr = $(this).val().split("\\");
			$("#recording_upload_file_label").html(arr[arr.length-1]);
			$("#recording_upload_file_label").removeClass("btn");
				$("#recording_upload_file_label").css("background-color","transparent");
		}
	});
});
</script>
</section>
<section>
	<h1 class="pagename">Nahrát záznam</h1>
	<table id="table_upload_recording">
		<tr>
			<td>Název</td>
			<td><input type="text" id="recording_upload_name" /></td>
		</tr>
		<tr>
			<td>Soubor</td>
			<td>
				<input type="file" name="recording_upload_file" id="recording_upload_file" />
				<label for="recording_upload_file" id="recording_upload_file_label" class="btn">Vyberte soubor s nahrávkou...</label>
			</td>
		</tr>
		<tr>
			<td>Popis</td>
			<td><textarea id="recording_upload_description"></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="text-align: right;"><button id="recording_upload_btn">Nahrát</button></td>
		</tr>
	</table>
</section>