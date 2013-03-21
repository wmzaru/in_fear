<?php
		$sql = "SELECT name,marriedto FROM ".db_prefix("accounts")." WHERE acctid='{$args['acctid']}' AND locked=0";
		$res = db_query($sql);
		if (db_num_rows($res)!=0) {
			$row = db_fetch_assoc($res);
			if ($row['marriedto']!=0&&$row['marriedto']!=4294967295) {
				$mailmessage=array("%s`0`@ has committed suicide by jumping off a cliff.",$row['name']);
				$t = array("`%Suicide!");
				require_once("lib/systemmail.php");
				systemmail($row['marriedto'],$t,$mailmessage);
				$sql = "UPDATE " . db_prefix("accounts") . " SET marriedto=0 WHERE acctid='{$row['marriedto']}'";
				db_query($sql);
			}
		}
		$list=get_module_pref('flirtssent');
		$list=unserialize($list);
		require_once("./modules/marriage/marriage_func.php");
		if (is_array($list)) {
			while (list($who,$amount)=each($list)) {
				marriage_removeplayer($who,$session['user']['acctid']);
			}
		}
?>