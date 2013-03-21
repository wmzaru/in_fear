<?php
switch($hookname){
	case "biostat":
		output("`^Chess Games: `@%s`^|`\$%s`0`n",
			(int)get_module_pref('wins',FALSE,$args['acctid']),
			(int)get_module_pref('loss',FALSE,$args['acctid'])
		);
	break;
	case "gardens":
		if (!get_module_pref('banned')){
			addnav("Games");
			addnav("Play Chess", "runmodule.php?module=chess&op=enter");
		}
	break;
	case "footer-hof":
		addnav("Warrior Rankings");
		addnav("Chess Rankings","runmodule.php?module=chess&op=hof&rank=wins");
	break;
	case "insertcomment":
		global $session;
		$gameid = str_replace("chess-", "", $args['section']);
		if (!is_numeric($gameid)) break;
		$sql = "SELECT user1, user2 FROM ".db_prefix('chess')." WHERE gameid = $gameid";
		$res = db_query($sql);
		if (!db_num_rows($res)) break;
		$row = db_fetch_assoc($res);
		if ($session['user']['acctid'] != $row['user1'] && $session['user']['acctid'] != $row['user2']){
			$args['mute'] = true;
			$args['mutemsg'] = "`n";
		}
	break;
}
?>