<?php
function rrmdir($dir) 
{
	if($handle = opendir($dir))
	{
	    while(false !== ($file = readdir($handle)))
	    {
			if(is_file($dir . $file))
	        {
	            unlink($dir . $file);
	        }
	    }
	    
	    closedir($handle);
	}
	
	rmdir($dir);
}



if($handle = opendir('/var/www/vhosts/justinharings.nl/dev.justinharings.nl/temp/'))
{
    while(false !== ($file = readdir($handle)))
    {
        if(is_file('/var/www/vhosts/justinharings.nl/dev.justinharings.nl/temp/' . $file))
        {
            unlink('/var/www/vhosts/justinharings.nl/dev.justinharings.nl/temp/' . $file);
        }
    }
    
    closedir($handle);
}

if($handle = opendir('/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/temp/'))
{
    while(false !== ($file = readdir($handle)))
    {
        if(is_file('/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/temp/' . $file))
        {
            unlink('/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/temp/' . $file);
        }
    }
    
    closedir($handle);
}
?>