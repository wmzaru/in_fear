<?php
output("`n`n`c`3Before you, many players are duelling each other at a challenging game of chess!`0`c");
output("`n`n`c`#Do you dare challenge another play to such a hard game?`0`c");
if (!$current)
addnav("Challenge Player", "runmodule.php?module=chess&op=challenge");
addnav("Waiting Lobby", "runmodule.php?module=chess&op=lobby");
if (get_module_setting('replays') || (!get_module_setting('replays') && $session['user']['superuser'] & SU_EDIT_USERS))
addnav("View Replays", "runmodule.php?module=chess&op=replay");
if (get_module_setting('rules')) 
addnav("`Q`bRules`b`0", "runmodule.php?module=chess&op=rules");
addnav("Return");
addnav("Return to Village", "village.php");
if (get_module_pref('challenges')){
	$chall = explode("|",get_module_pref('challenges'));
	addnav("Challenges");
	foreach ($chall as $val){
		$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid = $val";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		addnav(array("%s`0 - Accept", $row['name']), "runmodule.php?module=chess&op=offer&id=$val");
		addnav(array("%s`0 - Decline", $row['name']), "runmodule.php?module=chess&op=offer&id=$val&no=1");
	}
}
addnav("Current Games");
$sql = "
	SELECT 
		gameid, 
		a.name AS name1, 
		b.name AS name2
	FROM 
		".db_prefix("chess")." AS c
	LEFT JOIN 
	   ".db_prefix("accounts")." AS a ON a.acctid = c.user1
	LEFT JOIN 
	   ".db_prefix("accounts")." AS b ON b.acctid = c.user2
	WHERE c.user1 <> $you AND c.user2 <> $you";
$res = db_query($sql);
while ($row = db_fetch_assoc($res)){
	addnav(array("%s `0vs. %s", $row['name1'], $row['name2']), "runmodule.php?module=chess&op=watch&gameid={$row['gameid']}");
}
if ($current){
	addnav("PLAY!");
	$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid = $current";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	addnav(array("%s`0", $row['name']), "runmodule.php?module=chess&op=play");
}
?>