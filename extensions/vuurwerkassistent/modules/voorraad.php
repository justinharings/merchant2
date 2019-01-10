<?php
$data = $mb->_runFunction("products", "load", array(intval($_GET['productID'])));

$dflt = ceil($_GET['sold']/5);
$dflt = ($dflt == 0 ? 1 : $dflt);
?>

<h1><span class="fa fa-fire"></span>&nbsp;&nbsp;Voorraad notificatie</h1>

<div class="buttons">
	<a href="/extensions/vuurwerkassistent/library/php/soldout.php?productID=<?= intval($_GET['productID']) ?>">
		<div class="button fa fa-trash red"></div>
	</a>
	
	<a href="/extensions/vuurwerkassistent/library/php/pauze.php?productID=<?= intval($_GET['productID']) ?>">
		<div class="button fa fa-pause green"></div>
	</a>
	
	<a href="/extensions/vuurwerkassistent/library/php/timeout.php?productID=<?= intval($_GET['productID']) ?>">
		<div class="button fa fa-clock blue"></div>
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
		<?= intval($_GET['sold']) ?> stuks verkocht<br/>
		<?= intval($data['stock'] - $_GET['reserved']) ?> economisch <small><?= intval($data['stock']) ?> op voorraad</small>
		<div class="spacer"></div>
		<small style="font-size: 60%;">Aangeraden wordt er <?= $dflt ?> of meer op voorraad te hebben.</small>
	</div>
</div>