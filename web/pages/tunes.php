<section>
<h1 class="pagename">Znělky</h1>
<table class="tunes" cellspacing="0" cellpadding="0">
<tr>
	<th>ID</th>
	<th>Název</th>
	<th>&nbsp;</th>
</tr>
<?php $rsRecordings = $database->executeQuery("SELECT * FROM `tunes` ORDER BY `name` ASC;"); ?>
<?php while ($row = $rsRecordings->fetch_row()) :?>

<tr>
	<td><?=$row[0]?></td>
	<td><?=$row[1]?></td>
	<td>
		<audio style="display: none;" id="tune_<?=$row[0]?>" src="./tunes/<?=$row[3]?>" type="audio/wave">
		</audio>
		<div style="margin-top: 3px;">
		<?php
			$prepareTitle = "<p><b>Název:</b> " . $row[1] . "</p>";
			$prepareTitle .= "<p><b>Soubor:</b> " . $row[3] . "</p>";
			$prepareTitle .= "<p><b>Popis:</b><br /> " . $row[2] . "</p>";
		?>
		<img data-title="<?=encodeURIComponent($prepareTitle)?>" class="tune_control_icon" src="./res/icons/record_see.png" /><!--
		--><img class="tune_control_icon" id="tune_edit" data-editid="<?=$row[0]?>" src="./res/icons/record_edit.png" /><!--
		--><img class="tune_control_icon" id="tune_playpause" src="./res/icons/record_play.png" data-audid="tune_<?=$row[0]?>" /><!--
		--><img class="tune_control_icon" id="tune_download" data-file="<?=$row[3]?>" src="./res/icons/record_download.png" /><!--
		--><img class="tune_control_icon" id="tune_delete" src="./res/icons/record_delete.png" data-id="<?=$row[0]?>" />
		</div>
	</td>
</tr>
<?php endwhile; ?>

<script>
$(document).ready(function() {
	String.prototype.endsWith = function(suffix) {
		return this.indexOf(suffix, this.length - suffix.length) !== -1;
	};
	
	$("audio").on('ended', function() {
		$("img#tune_playpause[data-audid='"+$(this).attr("id")+"']").attr("src", "./res/icons/record_play.png");
	});
	
	$("img#tune_edit").click(function() {
		location.href = "?page=edit_tune&id=" + $(this).data("editid");
	});
	
	$("img#tune_playpause").click(function() {
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

	$("img#tune_download").click(function() {
		location.href = "./tunes/"+$(this).data("file");
	});
	
	$("img#tune_delete").click(function() {
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
				url: './phplib/delete_tune.php',
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
	
	$('#tune_upload_btn').click(function() {
		if($("input#tune_upload_name").val() == "") {
			alert("Chybí název!");
			return;
		}
		if($("input#tune_upload_file").val() == "") {
			alert("Chybí soubor!");
			return;
		}
		if(!($("input#tune_upload_file").val().endsWith(".mp3") || $("input#tune_upload_file").val().endsWith(".wav"))) {
			alert("Soubor musí být ve formátu .mp3 nebo .wav!");
			return;
		}
		console.log("sending");
		var file_data = $('input#tune_upload_file').prop('files')[0];   
		var form_data = new FormData();
		form_data.append('file', file_data);
		form_data.append('name', $("input#tune_upload_name").val());
		form_data.append('description', $("textarea#tune_upload_description").val());
		alert(form_data);
		$.ajax({
			url: './phplib/upload_tune.php', // point to server-side PHP script 
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
	
	$("#tune_upload_file").change(function(event) {
		if(!$(this).val().endsWith(".wav")) {
			$("#tune_upload_file_label").html("Soubor není ve formátu .wav!");
			$("#tune_upload_file_label").css("background-color","#E32B02");
			setTimeout(function() {
				$("#tune_upload_file_label").html("Vyberte soubor se znělkou...");
				$("#tune_upload_file_label").css("background-color","#3498db");
			}, 2000);
		} else {
			var arr = $(this).val().split("\\");
			$("#tune_upload_file_label").html(arr[arr.length-1]);
			$("#tune_upload_file_label").removeClass("btn");
				$("#tune_upload_file_label").css("background-color","transparent");
		}
	});
});
</script>

</table>
</section>
<section>
	<h1 class="pagename">Nahrát znělku</h1>
	<table id="table_upload_tune">
		<tr>
			<td>Název</td>
			<td><input type="text" id="tune_upload_name" /></td>
		</tr>
		<tr>
			<td>Soubor</td>
			<td>
				<input type="file" name="tune_upload_file" id="tune_upload_file" />
				<label for="tune_upload_file" id="tune_upload_file_label" class="btn">Vyberte soubor se znělkou...</label>
			</td>
		</tr>
		<tr>
			<td>Popis</td>
			<td><textarea id="tune_upload_description"></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="text-align: right;"><button id="tune_upload_btn">Nahrát</button></td>
		</tr>
	</table>
</section>