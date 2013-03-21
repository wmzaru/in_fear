<?php
addnav("Return to Main Page", "runmodule.php?module=chess&op=enter");
addnav("Return to Village", "village.php");
if ($id){
	require_once('lib/systemmail.php');
	systemmail($id, "`QChess Challenge!`0", "You have been challenged to a game of chess!`n`nGo to the Gardens to accept or decline this challenge.", $you);
	$challenges = get_module_pref('challenges',FALSE,$id);
	if ($challenges) $challenges .= "|";
	$challenges .= $you;
	set_module_pref('challenges',$challenges,FALSE,$id);
	output("`n`n`c`#Challenge sent!`c");
} else {
	output("`#Enter the name of the player you wish to challenge!`0`n`n");
	rawoutput("<form action='runmodule.php?module=chess&op=challenge' method='post'>");
	addnav("", "runmodule.php?module=chess&op=challenge");
	rawoutput("Player Name: <input name='player' size='40' /> <input type='submit' value='".translate_inline("Challenge!")."' />");
	rawoutput("</form>");
	$player = httppost('player');
	if ($player){
		$name = implode("%",str_split($player));
		$sql = "SELECT acctid, name FROM ".db_prefix("accounts")." WHERE (name LIKE '%$name%' OR login LIKE '%$name%') AND acctid <> $you";
		$res = db_query($sql);
		if (db_num_rows($res)){
			while ($row = db_fetch_assoc($res)){
				$chall = explode("|",get_module_pref('challenges',FALSE,$row['acctid']));
				if ((is_array($chall) && count($chall) && !in_array($you, $chall)) || !is_array($chall) || !count($chall)){
					output_notl("[<a href='runmodule.php?module=chess&op=challenge&id={$row['acctid']}'>".translate_inline('Challenge!')."</a>] `&%s`0`n", $row['name'], TRUE);
					addnav("", "runmodule.php?module=chess&op=challenge&id={$row['acctid']}");
				}
			}
		} else {
			output("`#No Matches!`0");
		}
	}
}
?>