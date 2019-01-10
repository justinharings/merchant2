<?php
$data = $mb->_runFunction("products", "load", array(intval($_GET['productID'])));

$dflt = ceil($_GET['sold']/5);
$dflt = ($dflt == 0 ? 1 : $dflt);
?>

<h1><span class="fa fa-fire"></span>&nbsp;&nbsp;Voorraad fout bijwerken</h1>

<div class="buttons">
	<a href="/extensions/vuurwerkassistent/library/php/soldout.php?productID=<?= intval($_GET['productID']) ?>">
		<div class="button fa fa-trash red"></div>
	</a>
	
	<a href="/extensions/vuurwerkassistent/library/php/timeout.php?productID=<?= intval($_GET['productID']) ?>">
		<div class="button fa fa-clock blue"></div>
	</a>
	
	<a href="/extensions/vuurwerkassistent/library/php/zerostock.php?productID=<?= intval($_GET['productID']) ?>">
		<div class="button fa fa-history green"></div>
	</a>
</div>

<div class="content">
	<div class="product-image">
		<?php
		$thumb = "https://haringstweewielers.com/library/media/no-image.png";

		foreach($data['images'] AS $media)
		{
			if($media['thumb'])
			{
				$thumb = "https://merchant.justinharings.nl/library/media/products/" . $media['productMediaID'] . ".png";
			}
		}
		?>
		
		<img src="<?= $thumb ?>" /><br/>
	</div>
	
	<div class="center-text">
		<strong><?= $data['article_code'] ?> - <?= $data['name'] ?></strong><br/>
		<div class="spacer"></div>
		<?= intval($data['stock'] - $_GET['reserved']) ?> economisch <small><?= intval($data['stock']) ?> op voorraad</small>
		<div class="spacer"></div>
		<small style="font-size: 60%;">
			Aangeraden wordt de voorraad te tellen. Je kunt hem<br/>
			ook op '0' (nul) zetten of op uitverkocht.
		</small>
	</div>
</div>