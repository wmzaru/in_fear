<?php
$gameid = httpget('gameid');
$sql = "DELETE FROM ".db_prefix("chess_saved")." WHERE gameid = $gameid";
db_query($sql);
$FileName = "gamelog".$gameid.".txt";
unlink("modules/chess/logs/".$FileName."");
output("`c`@Gamelog Deleted!`0`c`n");
addnav("View Replays", "runmodule.php?module=chess&op=replay");
addnav("Main Page", "runmodule.php?module=chess&op=enter");
?>