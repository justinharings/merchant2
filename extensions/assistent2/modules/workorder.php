<?php
$workorder = $mb->_runFunction("workorders", "loadWorkorder", array(intval($_GET['workorderID'])));
?>

<div class="container rose">
	<div class="inner-container">
		<div class="title fa fa-wrench"></div>
		<div class="menu-button fa fa-bars"></div>
		
		<div class="menu">
			<ul>
				<li browse="/extensions/assistent2/library/php/workorder_date.php?workorderID=<?= intval($_GET['workorderID']) ?>">
					<span class="fa fa-calendar"></span>
					Datum vooruit schuiven
				</li>
				
				<?php
				if($workorder['phone_number'] != "" && $workorder['status'] == 1)
				{
					?>
					<li browse="/extensions/assistent2/library/php/workorder_sms.php?workorderID=<?= intval($_GET['workorderID']) ?>&phone=<?= $workorder['phone_number'] ?>">
						<span class="fa fa-comments"></span>
						SMS opnieuw versturen
					</li>
					<?php
				}
				?>
				
				<li browse="/extensions/assistent2/library/php/workorder_delete.php?workorderID=<?= intval($_GET['workorderID']) ?>&customerID=<?= $workorder['customerID'] ?>">
					<span class="fa fa-trash"></span>
					Werkbon verwijderen
				</li>
			</ul>
		</div>
		
		<div class="content">
			<h1>Werkbonnen opschonen</h1>
			
			<div class="table">
				<table>
					<tr>
						<td>
							<?php
							if($workorder['status'] == 1)
							{
								?>
								<span class="fa fa-check" style="color: green;"></span>&nbsp;&nbsp;
								<?php
							}
							?>
							
							#<?= $workorder['workorderID'] ?>
							&nbsp;&nbsp;>&nbsp;&nbsp;
							<?= $workorder['expiration_date'] ?>
							
							<hr/>
							
							<?= $workorder['workorder'] ?>
						</td>
					</tr>
				</table>
			</div>
			
			<?php
			if($workorder['note'] != "")
			{
				?>
				<div class="table">
					<table>
						<tr>
							<td>
								<?= $workorder['note'] ?>
							</td>
						</tr>
					</table>
				</div>
				<?php
			}
			?>
		</div>
		
		<div class="footer">
			<div class="date-time-stamp">
				<?= date("d-m-Y H:i") ?> uur
			</div>
			
			<div class="button refresh fa fa-sync"></div>
			<div class="button vuurwerk fa fa-fire"></div>
			
			<div class="spacer"></div>
			
			<div class="button calendar fa fa-calendar"></div>
		</div>
	</div>
</div>