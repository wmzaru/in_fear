<?php
	if (get_module_pref('super')==1){
		$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
		$res = db_query($sql);
		$countapps1=0;
		$countapps2=0;
		$countapps3=0;
		for ($i=0;$i<db_num_rows($res);$i++){
			$row = db_fetch_assoc($res);
			$allprefsc=unserialize(get_module_pref('allprefs','doppleganger',$row['acctid']));
			if ($allprefsc['approve1']==3) $countapps1=$countapps1+1;
			if ($allprefsc['approve2']==3) $countapps2=$countapps2+1;
			if ($allprefsc['approve3']==3) $countapps3=$countapps3+1;
		}
		if ($countapps1>0) addnav(array("Process Doppleganger Phrase 1 `4(%s)",$countapps1),"runmodule.php?module=doppleganger&op=super1&ap=1");
		if ($countapps2>0) addnav(array("Process Doppleganger Phrase 2 `4(%s)",$countapps2),"runmodule.php?module=doppleganger&op=super1&ap=2");
		if ($countapps3>0) addnav(array("Process Doppleganger Phrase 3 `4(%s)",$countapps3),"runmodule.php?module=doppleganger&op=super1&ap=3");
	}
?>