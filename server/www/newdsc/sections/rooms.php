<?php
	$defaultIcon = "./resources/appicons/room.png";
	
	$floors = array();
	$floorsResult = $database->executeQuery("SELECT * FROM `rooms` ORDER BY `floor` ASC;");
	while($row = $floorsResult->fetch_assoc()) {
		$floorNum = intval($row['floor']);
		if(!in_array($floorNum, $floors))
			array_push($floors, $floorNum);
	}
?>	
<?php foreach($floors as $floor): ?>
	<div class="floorheadline"><?php if($floor == 0) echo("Přízemí"); else echo($floor . ". patro"); ?></div>
	<?php $dbResult = $database->executeQuery("SELECT * FROM `rooms` WHERE `floor` = " . $floor . " ORDER BY `rank` ASC;");
	while($row = $dbResult->fetch_assoc()) :?>
		<?php $icon = file_exists("./resources/icons/".$row['icon']) && !is_dir("./resources/icons/".$row['icon']) ? "./resources/icons/".$row['icon'] : $defaultIcon; ?>
		<div class="iconbox" onclick="location.href = '?page=room&room=<?php echo($row['id']); ?>';">
			<img src="<?php echo($icon); ?>" />
			<div class="text"><?php echo($row['name']); ?></div>
		</div>
	<?php endwhile; ?>
<?php endforeach; ?>