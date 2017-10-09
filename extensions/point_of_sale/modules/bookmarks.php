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
				<td><?= $mb->_translateReturn("table-headers", "ac") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "product") ?></td>
				<td><?= $mb->_translateReturn("table-headers", "price") ?></td>
			</tr>
		</thead>
		
		<tbody>
			<?php
			$data = $mb->_runFunction("products", "view", array($_SESSION['merchantID'], "bookmarks", "products.article_code", "0,50"));
				
			if(count($data))
			{
				foreach($data AS $key => $value)
				{
					?>
					<tr key="<?= $value['article_code'] ?>">
						<td><?= $value['article_code'] ?></td>
						<td><?= $value['name'] ?></td>
						<td><?= $value['promo'] == true ? '<span class="fa fa-star"></span>' : '' ?>&nbsp;&euro;&nbsp;<?= number_format($value['price'], 2, ",", ".") ?></td>
					</tr>
					<?php
				}
			}
			else
			{
				?>
				<tr>
					<td colspan="5" align="center"><?= $mb->_translateReturn("table-headers", "no-results") ?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</div>

<div class="table-options">
	<input type="button" name="use_product" id="use_product" value="Product gebruiken" class="red" />
</div>