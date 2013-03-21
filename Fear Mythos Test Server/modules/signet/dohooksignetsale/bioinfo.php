<?php
	$argsid = $args['acctid'];
	$argsname = $args['login'];
	$allprefs=unserialize(get_module_pref('allprefs','signetsale',$argsid));
	$allprefs1=unserialize(get_module_pref('allprefs','signetd1',$argsid));
	if ($allprefs1['airsignet']==1 || $allprefs['completednum']>0) {
		addnav("Elemental Signets","runmodule.php?module=signetsale&op=signetnotes&user=$argsid&username=$argsname&return=".URLencode($_SERVER['REQUEST_URI']));
	}elseif ($allprefs['scroll1']==1 && $session['user']['acctid']==$argsid){
		addnav("Elemental Signets","runmodule.php?module=signetsale&op=signetnotes&user=$argsid&username=$argsname&return=".URLencode($_SERVER['REQUEST_URI']));
	}
	if ($allprefs['completednum']>0){
		output("Signet Recognition Title: ");
		if ($allprefs['completednum']>10) output("`^`bGreat Mage of the Signets`b");
		elseif ($allprefs['completednum']==10) output("`%Power Signet Mage");
		elseif ($allprefs['completednum']==9) output("`\$Fire Signet Mage");
		elseif ($allprefs['completednum']==8) output("`!Water Signet Mage");
		elseif ($allprefs['completednum']==7) output("`QEarth Signet Mage");
		elseif ($allprefs['completednum']==6) output("`3Air Signet Mage");
		elseif ($allprefs['completednum']==5) output("`6Dark Lord's Bane");
		elseif ($allprefs['completednum']==4) output("`1Nemesis of Mierscri");
		elseif ($allprefs['completednum']==3) output("`#Supreme Vanquisher");
		elseif ($allprefs['completednum']==2) output("`@Grand Vanquisher");
		else output("`^Vanquisher");
		output_notl("`n");
	}
?>