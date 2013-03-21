INSTALLATION:

If you are upgrading from a previous version, you MUST install the upgrade file first.  
If you do not, your players will loose their current kills and clan points totals.
After installation, uninstall and remove the upgrade file it is no longer needed.

IMAGES:

Place all images within the following directory/folder

/modules/clanpyramid/images/

TO MODIFY EXPERIENCE GAINED

1.	Open /modules/clanpyramid/dohook/battle-victory.php

2.	Modify the following code on line 5
		$bonus = $level*10;
		
	Change this to modify the amount of experience given if all 3 pyramids are owned.	