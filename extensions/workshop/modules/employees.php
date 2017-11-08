<?php
$data = $mb->_runFunction("pos", "loadEmployeeSettings", array($_SESSION['merchantID']));

foreach($data AS $value)
{
	$image = "/library/media/employee_pictures/" . $value['employeeID'] . ".png";
	
	if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $image))
	{
		$image = "/library/media/employee_pictures/no-picture.png";
	}
	?>
	
	<div class="option-item-page" onclick="document.location.href = '/extensions/point_of_sale/library/php/posts/employees.php?employeeID=<?= $value['employeeID'] ?>'">
		<div class="image fa no-padding">
			<img src="<?= $image ?>" />
		</div>
		
		<div class="title"><?= $value['name'] ?></div>
	</div>
	
	<?php
}
?>