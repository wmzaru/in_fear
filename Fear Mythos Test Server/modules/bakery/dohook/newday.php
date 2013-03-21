<?php
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($allprefs['caketoday']==1 && $allprefs['usedcake']==0) output("`n`%You realize you didn't use your `i'Special cake'`i and look at it.  It's moldy!  You toss it away.`n");
	$allprefs['pastrytoday']=0;
	$allprefs['caketoday']=0;
	$allprefs['usedcake']=0;
	$allprefs['jailvisittoday']=0;
	set_module_pref('allprefs',serialize($allprefs));
?>