<?php
	$allprefs=unserialize(get_module_pref('allprefs'));
	$count=0;
	for ($i=1;$i<=20;$i++) {
		if ($allprefs['hasmap'.$i]==1){
			$count++;
		}
	}
	if ($count>0 && $session['user']['acctid']==$args['acctid']){
		addnav("Maps","runmodule.php?module=cartographer&op=locations&return=".URLencode($_SERVER['REQUEST_URI']));
	}
?>