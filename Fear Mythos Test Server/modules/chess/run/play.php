<?php
$minuser = min($you, $current);
$maxuser = max($you, $current);
$sql = db_query("SELECT gameid,turn FROM ".db_prefix("chess")." WHERE user1 = $minuser AND user2 = $maxuser");
$row = db_fetch_assoc($sql);
if (!$row['gameid']){
	require_once("lib/redirect.php");
	redirect("runmodule.php?module=chess&op=enter");
}

if ($row['turn'] != $you){
	$page = substr($_SERVER['REQUEST_URI'], 1);
	$session['allowednavs'] = (is_array(unserialize($session['user']['allowednavs'])) ?
		unserialize($session['user']['allowednavs']) : array($session['user']['allowednavs']));
	$session['allowednavs'][$page] = true;
	rawoutput("<meta http-equiv='refresh' content='10' URL='$page'>");
}

if ($move && httppost('move1') && httppost('move2')) chess_checkmove(httppost('move1'), httppost('move2'), $row['gameid']);
chess_drawboard($row['gameid']);
addnav("Refresh", "runmodule.php?module=chess&op=play");
addnav("Forfeit Game", "runmodule.php?module=chess&op=forfeit");
?>