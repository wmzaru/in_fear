<?php
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ((get_module_setting('mapmaker') == 1 && $session['user']['dragonkills']>=get_module_setting("dkair") && $allprefs['sigmap5']==0)||get_module_pref("super")==1){
		addnav("Maps For Sale");
		addnav("Special Maps","runmodule.php?module=signetsale&op=enter");
	}
?>