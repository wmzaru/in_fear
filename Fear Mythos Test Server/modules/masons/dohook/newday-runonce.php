<?php
	//reset the turns on newday-runonce if set for that
	if (get_module_setting("resetnd")==1){
		$sql = "SELECT acctid FROM ".db_prefix("accounts")."";
		$res = db_query($sql);
		for ($i=0;$i<db_num_rows($res);$i++){
			$row = db_fetch_assoc($res);
			$allprefs=unserialize(get_module_pref('allprefs','masons',$row['acctid']));
			$allprefs['benefit']=0;
			$allprefs['expboost']=0;
			set_module_pref('allprefs',serialize($allprefs),'masons',$row['acctid']);
		}
	}
?>