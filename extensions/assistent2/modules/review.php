<?php
$review = $mb->_runFunction("reviews", "load", array(intval($_GET['reviewID'])));
$product = $mb->_runFunction("products", "load", array(intval($review['productID'])));
?>

<div class="container lemon">
	<div class="inner-container">
		<div class="title fa fa-star"></div>
		<div class="menu-button fa fa-bars"></div>
		
		<div class="menu">
			<ul>
				<li browse="/extensions/assistent2/library/php/review_approve.php?reviewID=<?= intval($_GET['reviewID']) ?>">
					<span class="fa fa-thumbs-up"></span>
					Review goedkeuren
				</li>
				
				<li browse="/extensions/assistent2/library/php/review_delete.php?reviewID=<?= intval($_GET['reviewID']) ?>">
					<span class="fa fa-trash"></span>
					Review verwijderen
				</li>
			</ul>
		</div>
		
		<div class="content">
			<h1>Review goedkeuren van <?= $review['name'] ?></h1>
			
			<div class="center">
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
				
				<img class="product" src="<?= $image ?>" /><br/>
				
				<?php
				for($i = 1; $i < 6; $i++)
				{
					if($review['stars'] >= $i)
					{
						print '<span class="fa fa-star review-large"></span>';
					}
					else
					{
						print '<span class="fa fa-star-o review-large"></span>';
					}
				}
				
				print "<br/>".$review['description'];
				?>
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