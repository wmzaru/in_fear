<?php
	global $session;
	$allprefs=unserialize(get_module_pref('allprefs'));
	if (get_module_setting("resetnd")==0){
		$allprefs['benefit']=0;
		$allprefs['expboost']=0;
	}
	$allprefs['visited']=$allprefs['visited']+1;
	if ($allprefs['visited']>=get_module_setting("kickedout") && $allprefs['masonmember']==1) {
		$subj = array("`\$Dismissal Notice`& from `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons");
		$body = array("`n`&D`)ear `^%s`&,`n`nWe have noticed that you do not feel that the `&O`)rder`& is a priority for you.  We have removed your tattoo and revoked your membership.  Perhaps you can join again some other day.`n`nSincerely,`n`n`&N`)armyan`n",$session['user']['name']);
		require_once("lib/systemmail.php");
		systemmail($session['user']['acctid'],$subj,$body);
		$allprefs['masonmember']=0;
		$allprefs['tatpain']=0;
		$allprefs['benefit']=0;
		$allprefs['donated']=0;
		$allprefs['duespaid']=0;
		$allprefs['dksincego']=0;
		$allprefs['duestime']=get_module_setting("duetime");
		set_module_pref("masonnumber",0);
		if (get_module_setting("newestid")==$session['user']['acctid']) set_module_setting("newestmember","Nobody");
		output("`n`&You have been dismissed from `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons`& due to inactivity.`n");
		modulehook("masons-dismiss");
	}
	if ($allprefs['masonmember']==1){
		$allprefs['duestime']=$allprefs['duestime']-1;
		if ($allprefs['duestime']<=0) {
			$allprefs['duestime']=get_module_setting("duetime");
			$allprefs['duespaid']=0;
		}
		if ($allprefs['tatpain']>1) {
			$allprefs['tatpain']=$allprefs['tatpain']-1;
			output("`n`&Your `&S`)ecret `&O`)rder `&o`)f `&M`)asons `&tattoo is gradually healing.  You wake to the excruciating pain with only `\$one hitpoint`&.`n");
			$session['user']['hitpoints']=1;
		}elseif ($allprefs['tatpain']==1) {
			$allprefs['tatpain']=0;
			output("`n`&Your `&S`)ecret `&O`)rder `&o`)f `&M`)asons `&tattoo has finally healed.`n");
		}
	}
	set_module_pref('allprefs',serialize($allprefs));
?>