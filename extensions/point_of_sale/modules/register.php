<div class="register-screen small">
	<?php
	if(!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0)
	{
		?>
		<img src="/library/media/logo.png" />
		<?php
	}
	else
	{
		if(isset($_SESSION['customer']))
		{
			$customer = $mb->_runFunction("customers", "load", array($_SESSION['customer']));
			?>
			<div class="additional blue">
				<span class="fa fa-coffee"></span>
				<span class="content">
					<?= $customer['name'] ?>
					<small>(<?= $customer['city'] . ", " . $customer['country'] ?>)</small>
				</span>
				
				<a href="/extensions/point_of_sale/library/php/posts/cart_customer_remove.php">
					<span class="fa fa-times"></span>
				</a>
			</div>
			<?php
		}
		
		if(isset($_SESSION['shipment']))
		{
			$shipment = $mb->_runFunction("shipment_methods", "load", array($_SESSION['shipment']));
			?>
			<div class="additional red">
				<span class="fa fa-plane"></span>
				<span class="content">
					<?= $shipment['name'] ?>
					<small>(&euro;&nbsp;<?= _frontend_float($shipment['price']) ?>)</small>
				</span>
				
				<a href="/extensions/point_of_sale/library/php/posts/cart_shipment_remove.php">
					<span class="fa fa-times"></span>
				</a>
			</div>
			<?php
		}
		
		if(isset($_SESSION['key_number']))
		{
			?>
			<div class="additional green">
				<span class="fa fa-key"></span>
				<span class="content">
					<?= sprintf('%03d', $_SESSION['key_number']); ?>
				</span>
			</div>
			<?php
		}
		?>
		
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
					<td><?= $mb->_translateReturn("table-headers", "quantity") ?></td>
					<td>Subtotaal</td>
				</tr>
			</thead>
			
			<tbody>
				<?php
				$num = 1;
					
				foreach($_SESSION['cart'] AS $key => $cart)
				{
					$product = $cart['code'];
					$product = $mb->_runFunction("products", "load", array($product));
					
					$focus = "";
					
					if(isset($_GET['focus']) && $_GET['focus'] == "last" && $num == count($_SESSION['cart']))
					{
						$focus = "active";
					}
					else if(isset($_GET['focus']) && $_GET['focus'] != "last" && $key == $_GET['focus'])
					{
						$focus = "active";
					}
					?>
					
					<tr class="<?= $focus ?>" key="<?= $key ?>" price="<?= $cart['price'] ?>">
						<td><?= $product['article_code_long'] ?></td>
						<td><?= $cart['name'] ?></td>
						<td>&euro;&nbsp;<?= _frontend_float($cart['price']) ?></td>
						<td><?= $cart['quantity'] ?></td>
						<td>
							&euro;&nbsp;<?= _frontend_float($cart['quantity']*$cart['price']) ?>
							<input type="hidden" name="grand_total[]" value="<?= $cart['quantity']*$cart['price'] ?>" class="grand_total" />
						</td>
					</tr>
					
					<?php
						
					$num++;
				}
				?>
			</tbody>
		</table>
		<?php
	}
	?>
</div>

<div class="register-keyboard">
	<div class="line-1">
		<input type="text" name="grand_total" id="grand_total" class="width-100-percent margin" icon="fa-euro" />
		
		<form id="barcode-form" method="post" action="/extensions/point_of_sale/library/php/posts/cart_add.php">
			<input type="hidden" name="key" id="key" value="0" />
			<input type="hidden" name="qty" id="qty" value="0" />
			
			<input type="text" name="barcode" id="barcode" class="width-100-percent double-margin" icon="fa-barcode" />
		</form>
		
		<input type="button" name="new_order" id="new_order" class="width-100-percent red" value="Wegwerpen en opnieuw" />
	
		<div class="button first" <?= count($_SESSION['cart']) > 0 ? 'click="/pos/modules/customers/"' : "" ?>>
			<div class="pos-button fa fa-coffee"></div>
		</div>
		
		<div class="button">
			<div class="pos-button fa fa-plane correction"></div>
		</div>
		
		<div class="button" click="/pos/modules/bookmarks/">
			<div class="pos-button fa fa-bookmark"></div>
		</div>
	</div>
	
	<div class="line-2">
		<div class="line-2-1">
			<div class="button first">
				<div class="pos-button fa fa-hashtag"></div>
			</div>
			
			<div class="button">
				<div class="pos-button fa fa-trash"></div>
			</div>
			
			<div class="button">
				<div class="pos-button fa fa-euro correction"></div>
			</div>
			
			<div class="button">
				<div class="pos-button fa fa-pencil"></div>
			</div>
		</div>
		
		<div class="spacer"></div>
		
		<div class="line-2-2">
			<div class="button first">
				<div class="pos-button keyboard">1</div>
			</div>
			
			<div class="button">
				<div class="pos-button keyboard">2</div>
			</div>
			
			<div class="button">
				<div class="pos-button keyboard">3</div>
			</div>
			
			<div class="button first">
				<div class="pos-button keyboard">4</div>
			</div>
			
			<div class="button">
				<div class="pos-button keyboard">5</div>
			</div>
			
			<div class="button">
				<div class="pos-button keyboard">6</div>
			</div>
			
			<div class="button first">
				<div class="pos-button keyboard">7</div>
			</div>
			
			<div class="button">
				<div class="pos-button keyboard">8</div>
			</div>
			
			<div class="button">
				<div class="pos-button keyboard">9</div>
			</div>
			
			<div class="button first">
				<div class="pos-button fa fa-list"></div>
			</div>
			
			<div class="button">
				<div class="pos-button keyboard">0</div>
			</div>
			
			<div class="button">
				<div class="pos-button fa fa-backward keyboard"></div>
			</div>
		</div>
		
		<div class="spacer"></div>
		
		<div class="line-2-3">
			<input type="button" name="run_order" id="run_order" class="width-100-percent red" value="Bestelling inboeken" />
			
			<div class="button first">
				<div class="pos-button fa fa-history"></div>
			</div>
			
			<div class="button" orderID="<?= isset($_SESSION['last_order']) ? $_SESSION['last_order'] : 0 ?>">
				<div class="pos-button fa fa-print"></div>
			</div>
			
			<div class="button-large">
				<span class="fa fa-cart-arrow-down"></span>
			</div>
		</div>
	</div>
</div>