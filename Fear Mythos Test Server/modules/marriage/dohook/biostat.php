<?php
		$char = httpget('char');
		$sql = "SELECT a.acctid as userid, a.marriedto as married, b.name as partnername,a.sex as gender FROM ".db_prefix('accounts')." as a LEFT JOIN ".db_prefix('accounts')." as b ON a.marriedto=b.acctid WHERE a.login='$char'";
		$results = db_query($sql);
		$row = db_fetch_assoc($results);
		if ($row['married']!=0 && $row['partnername']!="") {
			if (!get_module_pref('user_bio',"marriage",$row['userid'])) $row['partnername']="`iSecret`i";
			output("`^Spouse: `2%s`n",$row['partnername']); //do it here
		} elseif ($row['married']==INT_MAX) {
			$partner = getsetting("barmaid", "`%Violet");
			if ($row['gender'] != SEX_MALE) {
				$partner = getsetting("bard", "`^Seth");
			}
			if (!get_module_pref('user_bio',"marriage",$row['userid'])) $row['partnername']="`iSecret`i";
			output("`^Spouse: `2%s`n",$partner);
		}
?>