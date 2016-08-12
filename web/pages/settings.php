<section>
	<h1 class="pagename">Nastavení</h1>
	<table id="table_edit_announcement">
		<tr>
			<td>Výchozí znělka pro živé vysílání</td>
			<td>
				<select id="default_tune">
					<?php if($_WEBSETTINGS["default_tune_live_announcement"]!="0") :?>
						<option value="0">--- --- ŽÁDNÁ --- ---</option>
					<?php else :?>
						<option value="0" selected>--- --- ŽÁDNÁ --- ---</option>
					<?php endif; ?>
					
					<?php $rsTunes = $database->executeQuery("SELECT * FROM `tunes` ORDER BY `name` ASC;");
					while ($rowT = $rsTunes->fetch_row()) :?>
						<option value="<?=$rowT[0]?>" <?php if($rowT[0]==$_WEBSETTINGS["default_tune_live_announcement"]) :?>selected<?php endif; ?>><?=$rowT[1]?></option>
					<?php endwhile; ?>
				</select>
			</td>
			<td><button id="save_default_tune">Uložit</button></td>
		</tr>
	</table>
	<script>
		$(document).ready(function() {
			$("button#save_default_tune").click(function() {
				$.ajax({
					url: './phplib/update_settings.php',
					data: {
						'default_tune_live_announcement': $("#default_tune").val()
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