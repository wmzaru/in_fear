<?php
	if (get_module_setting("requireapp") && get_module_pref("super")==1){
		$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
		$res = db_query($sql);
		$countapps=0;
		for ($i=0;$i<db_num_rows($res);$i++){
			$row = db_fetch_assoc($res);
			$allprefsc=unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
			if ($allprefsc['jobapp']>0) $countapps=$countapps+1;
		}
		if ($countapps>0) addnav(array("Process Job Applications `^(%s)",$countapps),"runmodule.php?module=jobs&place=super");
	}
	if (get_module_setting("woodapprove")==1 && get_module_pref("super")==1){
		$sql = "SELECT acctid FROM ".db_prefix("accounts")."";
		$res = db_query($sql);
		$furncount=0;
		for ($i=0;$i<db_num_rows($res);$i++){
			$row = db_fetch_assoc($res);			
			$allprefs=unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
			if ($allprefs['cust1']==2) $furncount++;
			if ($allprefs['cust2']==2) $furncount++;
			if ($allprefs['cust3']==2) $furncount++;
			if ($allprefs['cust4']==2) $furncount++;
			if ($allprefs['cust5']==2) $furncount++;
			if ($allprefs['cust6']==2) $furncount++;
		}
		if ($furncount>0) addnav(array("Process Furniture Names `@(%s)",$furncount),"runmodule.php?module=jobs&place=supername");
	}
?>