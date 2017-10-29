<?php	
try
{
	
}
catch (Exception $e)
{
	// Something failed, go back to the cancel page.
	//header("location: " . $_cancel_url);
	print $e->getMessage();
}
?>