<?php
$gameid = httpget('gameid');
$page = substr($_SERVER['REQUEST_URI'], 1);
$session['allowednavs'] = (is_array(unserialize($session['user']['allowednavs'])) ?
	unserialize($session['user']['allowednavs']) : array($session['user']['allowednavs']));
$session['allowednavs'][$page] = true;
rawoutput("<meta http-equiv='refresh' content='10' URL='$page'>");

$sql = db_query("SELECT gameid FROM ".db_prefix("chess")." WHERE gameid = $gameid");
$row = db_fetch_assoc($sql);
if (!$row['gameid']){
	require_once("lib/redirect.php");
	redirect("runmodule.php?module=chess&op=enter");
}

chess_drawboard($gameid, false, true);
addnav("Refresh", "runmodule.php?module=chess&op=watch&gameid=$gameid");
addnav("Main Page", "runmodule.php?module=chess&op=enter");
?>