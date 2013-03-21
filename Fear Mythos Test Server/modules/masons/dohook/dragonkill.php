<?php
	$allprefs=unserialize(get_module_pref('allprefs'));
	if (get_module_setting("dkban")>0 && $allprefs['permaban']==1) $allprefs['dksinceban']=$allprefs['dksinceban']+1;
	if (get_module_setting("dkban")>0 && $allprefs['permaban']==1 && $allprefs['dksinceban']>=get_module_setting("dkban")) $allprefs['permaban']=0;
	$allprefs['incspecialty']=0;
	$allprefs['defenseboost']=0;
	$allprefs['attackboost']=0;
	$allprefs['gdefenseboost']=0;
	$allprefs['gattackboost']=0;
	$allprefs['benefit']=0;
	$allprefs['expboost']=0;
	$allprefs['dksincego']=$allprefs['dksincego']+1;
	set_module_pref('allprefs',serialize($allprefs));
?>