<?php
$gameid = httpget('gameid');
if (!$gameid){
	$i = 0;
	$player1 = translate_inline("Player 1");
	$player2 = translate_inline("Player 2");
	$gametime = translate_inline("Time of Game");
	$ops = translate_inline("Ops");
	rawoutput("<table><tr class='trhead'><td>$player1</td><td>$player2</td><td>$gametime</td><td>$ops</td></tr>");
	$sql = "
		SELECT cs.gameid, cs.starttime, a1.name AS name1, a2.name AS name2
		  FROM ".db_prefix("chess_saved")." AS cs
		LEFT JOIN ".db_prefix("accounts")." AS a1
		  ON a1.acctid = cs.user1
		LEFT JOIN ".db_prefix("accounts")." AS a2
		  ON a2.acctid = cs.user2
		ORDER BY gameid DESC";
	$res = db_query($sql);
	while ($row = db_fetch_assoc($res)){
		rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
			output("`&%s`0", $row['name1']);
		rawoutput("</td><td>");
			output("`&%s`0", $row['name2']);
		rawoutput("</td><td>");
			output("`&%s`0", date("dS M 'y h:i A", strtotime($row['starttime'])));
		rawoutput("</td><td>");
			rawoutput("[<a href='runmodule.php?module=chess&op=replay&gameid={$row['gameid']}&move=0'>Replay</a>]");
			if ($session['user']['superuser'] & SU_EDIT_USERS) 
			rawoutput("| [<a href='runmodule.php?module=chess&op=delete&gameid={$row['gameid']}'>Delete</a>]");
		rawoutput("</td></tr>");
		addnav("", "runmodule.php?module=chess&op=replay&gameid={$row['gameid']}&move=0");
		addnav("", "runmodule.php?module=chess&op=delete&gameid={$row['gameid']}");
		$i++;
	}
	rawoutput("</table>");
	addnav("Return", "runmodule.php?module=chess&op=enter");
} else {
	$move = (int)httpget('move');
	if (!$move) chess_drawboard($gameid, true);
	if ($move)  chess_drawboard($gameid, $move);
	$FileName = "gamelog".$gameid.".txt";
	$content = file_get_contents("modules/chess/logs/".$FileName);
	$content_a = explode("|^|", $content);

	if ($move>0)
		addnav("Previous <", "runmodule.php?module=chess&op=replay&gameid=$gameid&move=".($move-1)."");
	if ($move<count($content_a))
		addnav("Next >", "runmodule.php?module=chess&op=replay&gameid=$gameid&move=".($move+1)."");
	addnav("Navigation");
	addnav("Main Page", "runmodule.php?module=chess&op=enter");
	addnav("View Replays", "runmodule.php?module=chess&op=replay");
}
?>