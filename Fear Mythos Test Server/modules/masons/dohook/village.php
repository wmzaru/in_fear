<?php
	global $session;
	$allprefs=unserialize(get_module_pref('allprefs'));
	if (((($session['user']['superuser'] & SU_EDIT_USERS) && $allprefs['superspiel']==0) || $allprefs['masonmember']==1 || get_module_pref("supermember")==1 || $allprefs['offermember']==1) && $session['user']['location'] == get_module_setting("masonsloc")){
		tlschema($args['schemas']['tavernnav']);
		addnav($args['tavernnav']);
		tlschema();
		addnav("`&M`)asons `&O`)rder","runmodule.php?module=masons&op=enter");
	}
?>