<?php
$product = $mb->_runFunction("products", "load", array(intval($_GET['productID'])));
$data_locations = $mb->_runFunction("stock", "viewLocations", array(1, "", "locations.name", "0,50"));

$type = "";

foreach($data_locations AS $location)
{
	$stock = $mb->_runFunction("stock", "getStock", array(intval($_GET['productID']), $location['locationID']));
	$eco = ($stock['stock']-$stock['reserved']);
}

$query = sprintf(
	"	SELECT		SUM(orders_product.quantity) AS sold
		FROM		orders_product
		INNER JOIN	orders ON orders.orderID = orders_product.orderID
		INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
			AND		order_statuses.finished = 1
			AND		order_statuses.declined = 0
		WHERE		orders_product.productID = %d
			AND		YEAR(orders.date_added) = YEAR(CURDATE())",
	intval($_GET['productID'])
);
$result = $mb->query($query);
$row = $mb->fetch_assoc($result);

$sold = $row['sold'];
?>

<div class="container blush">
	<div class="inner-container">
		<div class="title fa fa-tags"></div>
		<div class="menu-button fa fa-bars"></div>
		
		<div class="menu">
			<ul>
				<li browse="/extensions/assistent2/">
					<span class="fa fa-thumbs-up"></span>
					Gezien en doorgaan
				</li>
			</ul>
		</div>
		
		<div class="content">
			<h1>Opgeslagen voorraad notificatie</h1>
			
			<?php
			if(count($product['images']) == 0)
			{
				$image = "https://www.haringstweewielers.com/library/media/no-image.png";
			}
			else
			{
				foreach($product['images'] AS $media)
				{
					if($media['thumb'])
					{
						$image = "https://merchant.justinharings.nl/library/media/products/". $media['productMediaID'] .".png";
					}
				}
			}
			?>
			
			<div class="center">
				<?php
				if($product['core_product'] == 1)
				{
					?>
					<div class="core-product fa fa-bullseye"></div>
					<?php
				}
				?>
				
				<img class="product" src="<?= $image ?>" /><br/>
				<h2><?= $product['article_code'] . " - " . $product['name'] ?></h2>
				Nog <strong><?= $product['stock'] ?></strong> op voorraad, <strong><?= $eco ?></strong> economisch.<br/>
				<small>Er is/zijn <?= $sold ?> van deze artikel(en) verkocht dit jaar.</small>
			</div>
		</div>
		
		<div class="footer">
			<div class="date-time-stamp">
				<?= date("d-m-Y H:i") ?> uur
			</div>
			
			<div class="button refresh fa fa-sync"></div>
			<div class="button vuurwerk fa fa-fire"></div>
			
			<div class="spacer"></div>
			
			<div class="button calendar fa fa-calendar"></div>
			<div class="button core_products fa fa-bullseye"></div>
			<div class="button cleanup fa fa-trash"></div>
		</div>
	</div>
</div>