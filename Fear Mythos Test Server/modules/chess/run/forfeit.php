<?php
if (!httpget('confirm')){
	addnav("Are you sure?");
	addnav("Yes!", "runmodule.php?module=chess&op=forfeit&confirm=1");
	addnav("No!", "runmodule.php?module=chess&op=play");
	$minuser = min($you, $current);
	$maxuser = max($you, $current);
	$gid = db_fetch_assoc(db_query("SELECT gameid FROM ".db_prefix("chess")." WHERE user1 = $minuser AND user2 = $maxuser"));
	chess_drawboard($gid['gameid']);
} else {
	require_once('lib/systemmail.php');
	$timeout = httpget('timeout');
	$minuser = min($you, $current);
	$maxuser = max($you, $current);
	$now = date("Y-m-d H:i:s");

	$row = db_fetch_assoc(db_query("SELECT gameid,turn,user1,user2 FROM ".db_prefix("chess")." WHERE user1 = $minuser AND user2 = $maxuser"));
	$gameid = $row['gameid'];
	$winner = ($you == $row['user1'] ? $row['user2'] : $row['user1']);

	$sql = "DELETE FROM ".db_prefix("chess")." WHERE gameid = $gameid";
	db_query($sql);
	$sql = "UPDATE ".db_prefix("chess_saved"). " SET endtime = '$now', winner = $winner WHERE gameid = $gameid";
	db_query($sql);

	increment_module_pref('wins',1,FALSE,$current);
	increment_module_pref('loss',1);

	clear_module_pref('current');
	clear_module_pref('current',FALSE,$current);

	clear_module_pref('color');
	clear_module_pref('color',FALSE,$current);

	if ($timeout){
		output("`#You timed out, and lost the game!");
		systemmail($current,"`QChess Game", "`QThe chess game was left too long without a move from your opponent! You win!", $you);
	} else {
		output("`#You forfeited, and lost the game!");
		systemmail($current,"`QChess Game", "`QThe chess game was forfeited! You win!", $you);
	}
	addnav("Return to Village", "village.php");
}
?>