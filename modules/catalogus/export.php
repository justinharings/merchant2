<?php
if(isset($_GET['dataID']))
{
	$data = $mb->_runFunction("categories", "loadDescription", array($_GET['dataID']));
}
?>

<ul class="breadcrumbs">
	<li>Merchant</li>
	<li><?= $mb->_translateReturn("menu", "catalog") ?></li>
	<li><?= $mb->_translateReturn("menu", "article-export") ?></li>
</ul>

<form method="post" id="form" action="/library/php/posts/catalogus/export.php">
	<br/><br/>
	<div class="simple-form">
		<div class="form-content">
			<div class="content-header">
				<span class="fa fa-pencil-square-o"></span>
				Verkoopgroep keuze
			</div>
			
			<select name="groupID" id="groupID" class="width-200 margin" holder="<?= $mb->_translateReturn("forms", "form-products-group") ?>">
				<option value="0">Alle verkoopgroepen</option>
				
				<?php
				$data_groups = $mb->_runFunction("groups", "view", array($_SESSION['merchantID'], "", "groups.name", "0,50"));
				
				foreach($data_groups AS $values)
				{
					?>
					<option <?= isset($_GET['dataID']) && $data['groupID'] == $values['groupID'] ? "selected=\"selected\"" : "" ?> value="<?= $values['groupID'] ?>"><?= $values['name'] ?></option>
					<?php
				}
				?>
			</select>
			
			<br/>
			
			<input type="submit" name="start" id="start" class="red" value="Starten met exporteren" />&nbsp;
		</div>
	</div>
</form>