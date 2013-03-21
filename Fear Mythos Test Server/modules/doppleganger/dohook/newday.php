<?php
	global $session;
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefs['encountered']=0;
	$dks=$session['user']['dragonkills'];
	if($dks>= get_module_setting("doppledk") && get_module_pref("dopplename")==0) set_module_pref("dopplename",2);
	if($dks>= get_module_setting("phrase1dk") && $allprefs['approve1']==0) $allprefs['approve1']=2;
	if($dks>= get_module_setting("phrase2dk") && $allprefs['approve2']==0) $allprefs['approve2']=2;
	if($dks>= get_module_setting("phrase3dk") && $allprefs['approve3']==0) $allprefs['approve3']=2;
	if(get_module_pref("dopplename")==2 || $allprefs['approve1']==2 || $allprefs['approve2']==2 || $allprefs['approve3']==2){
		blocknav("news.php");
		blocknav("village.php");
		if ($session['user']['restorepage'] != "runmodule.php?module=doppleganger&op=choose"){
			addnav("The Doppleganger","runmodule.php?module=doppleganger&op=choose");
		}
	}
	set_module_pref('allprefs',serialize($allprefs));
?>