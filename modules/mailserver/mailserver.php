<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "TPB_BB", 1));

$data = $mb->_runFunction("mailserver", "view", array($_SESSION['merchantID'], "", "mailserver.date_added DESC", "0,50"));
$form = "/form-betaallink/";
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "mailserver") ?></li>
</ul>

<div class="view-options">
	<input type="text" name="search" id="search" value="<?= (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : "") ?>" class="width-200" icon="fa-search" />
</div>

<table class="view">
	<thead>
		<tr>
			<td><?= $mb->_translateReturn("table-headers", "receiver") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "sender") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "subject") ?></td>
			<td><?= $mb->_translateReturn("table-headers", "date") ?></td>
			<td>&nbsp;</td>
		</tr>
	</thead>
	
	<tbody>
		<?php
		if($mb->num_rows($data))
		{
			foreach($data AS $value)
			{
				?>
				<tr>
					<td><?= $value['receiver'] ?></td>
					<td><?= $value['sender'] ?></td>
					<td>
						<?php
						if($value['attachment'] == 1)
						{
							print "<span class=\"fa fa-paperclip\"></span>&nbsp;";
						}
							
						print $value['subject'];	
						?>
					</td>
					<td><?= $value['date_added'] ?></td>
					<td><span class="fa large <?= $value['sent'] ? "fa-check-circle green" : "fa-times-circle red" ?>"></span></td>
				</tr>
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td colspan="6" align="center"><?= $mb->_translateReturn("table-headers", "no-results") ?></td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>