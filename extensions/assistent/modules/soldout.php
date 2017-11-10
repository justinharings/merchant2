<?php
$mb->_runFunction("authorization", "userPermission", array($_SESSION['userID'], "SET_BP", 1));

$data = $mb->_runFunction("products", "view", array($_SESSION['merchantID'], "export", "LPAD(products.article_code, 5, 0)", "0,99999"));
?>

<form method="post" action="/extensions/assistent/library/php/posts/soldout.php">
	<div class="view-options">
		<input type="submit" name="save" id="save" value="Wijzigingen opslaan" class="red show-load validate-form" style="float: right;" />
	</div>
	
	<table class="view <?= count($data) ? "hoverable" : "" ?>">
		<thead>
			<tr>
				<td>O / V</td>
				<td><?= $mb->_translateReturn("table-headers", "ac") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "product") ?></td>
				<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "visible") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "stock") ?></td>
				<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "updated") ?></td>
			</tr>
		</thead>
		
		<tbody>
			<?php
			if(count($data))
			{
				$cnt = 0;
				
				foreach($data AS $key => $value)
				{
					if($value['status'] != 4)
					{
						continue;
					}
					
					if	(
							(
								strtotime($value['date_update_core']) > strtotime(date("Y-m-d H:i:s", (time()-2592000)))
							) 
							&& $value['date_update'] != "n.v.t."
						)
					{
						continue;
					}
					
					$cnt++;
					
					if($cnt == 25)
					{
						return false;
					}
					
					?>
					<tr>
						<td>
							<input type="hidden" name="productIDs[]" id="productIDs_<?= $value['productID'] ?>"  value="<?= $value['productID'] ?>" />
							
							<input type="radio" checked="checked" name="action_<?= $value['productID'] ?>" id="action_<?= $value['productID'] ?>" value="0" style="-webkit-appearance: radio !important;" />
							&nbsp;&nbsp;
							<input type="radio" name="action_<?= $value['productID'] ?>" id="action_<?= $value['productID'] ?>" value="1" style="-webkit-appearance: radio !important;" />
						</td>
						<td><?= $value['article_code'] ?></td>
						<td><?= $value['name'] ?></td>
						<td class="hide-mobile"><?= $mb->_runFunction("products", "translateVisibility", array($value['visibility'])) ?></td>
						<td><?= $value['stock'] ?> stuks</td>
						<td class="hide-mobile"><?= $value['date_update'] ?></td>
					</tr>
					<?php
				}
			}
			else
			{
				?>
				<tr>
					<td colspan="6" align="center">Er zijn geen artikelen die opgeruimd hoeven te worden.</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</form>