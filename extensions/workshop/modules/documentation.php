<div class="register-screen table-grid">
	<input type="text" name="search" id="search" value="" class="width-200 double-margin" icon="fa-search" />
	
	<div class="table-control up">
		<span class="fa fa-caret-up"></span>
	</div>
	
	<div class="table-control down">
		<span class="fa fa-caret-down"></span>
	</div>
	
	<table class="view">
		<thead>
			<tr>
				<td><?= $mb->_translateReturn("table-headers", "name") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "employee") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "added") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "updated") ?></td>
			</tr>
		</thead>
		
		<tbody>
			<?php
			$data = $mb->_runFunction("workorders", "viewDocumentation", array($_SESSION['merchantID'], (isset($_GET['search_string']) ? trim($_GET['search_string'], "/") : ""), "documentation.name", "0,50"));
				
			if($mb->num_rows($data))
			{
				foreach($data AS $value)
				{				
					?>
					<tr key="<?= $value['documentID'] ?>">
						<td><?= $value['name'] ?></td>
						<td><?= $value['employee_name'] ?></td>
						<td><?= $value['date_added'] ?></td>
						<td><?= $value['date_update'] ?></td>
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
</div>

<div class="table-options">
	<input type="button" name="open_documentation" id="open_documentation" value="Documentatie inzien" class="red" />
	<div class="spacer"></div>
	<input type="button" name="new_documentation" id="new_documentation" value="Nieuwe documentatie" />
	<input type="button" name="edit_documentation" id="edit_documentation" value="Documentatie bewerken" />
</div>