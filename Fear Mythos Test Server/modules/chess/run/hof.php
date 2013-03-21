<?php
require_once("lib/datetime.php");
page_header("Hall of Fame");

$rank = httpget('rank');
$ac = db_prefix("accounts");
$cs = db_prefix("chess_saved");
$mu = db_prefix("module_userprefs");

$hrnk = translate_inline("Rank");
$name = translate_inline("Name");

$header = array("wins"=>"Winning Players", "loss"=>"Losing Players", "longest"=>"Longest Matches", "shortest"=>"Quickest Matches");
$header = translate_inline($header);

$rheader = array("wins"=>"Wins", "loss"=>"Losses", "longest"=>"Time", "shortest"=>"Time");
$rheader = translate_inline($rheader);

if ($rank == "wins" || $rank == "loss"){
$sql = "SELECT $mu.value, $ac.name FROM $mu , $ac 
		WHERE acctid = userid 
		AND modulename = 'chess' 
		AND setting = '$rank' 
		AND value >= 1
		ORDER BY (value+0) DESC LIMIT 25";
} else {
$sql = "SELECT c.*, a1.name as name1, a2.name as name2
		FROM $cs as c
		LEFT JOIN $ac as a1
		ON a1.acctid = c.user1
		LEFT JOIN $ac as a2
		ON a2.acctid = c.user2
		LIMIT 25";
}
$result = db_query($sql);

rawoutput("<big>");
output("`c`b`^Top 25 %s in Chess`b`c`0`n",$header[$rank]);
rawoutput("</big>");

rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
rawoutput("<tr class='trhead'><td>$hrnk</td><td>$name</td><td>{$rheader[$rank]}</td></tr>");

if ($rank == "longest" || $rank == "shortest") {
$i = 0;
$times = array();
while($row = db_fetch_assoc($result)){
	$key = $row['gameid']."|$#^$#|".$row['name1']."|$#^$#|".$row['name2'];
	$times[$key] = strtotime($row['endtime']) - strtotime($row['starttime']);
}
if ($rank == "longest") arsort($times); else asort($times);
if (count($times)){
	foreach ($times as $key => $val){
		$i++;
		$time = reltime(strtotime("now") + $val);
		list ($id, $name1, $name2) = explode("|$#^$#|",$key);
		rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
		output_notl("$i.");
		rawoutput("</td><td>");
		output_notl("`&%s vs. %s`0",$name1, $name2);
		rawoutput("</td><td>");
		output_notl("`c`@%s`c`0",$time);
		rawoutput("</td></tr>");
	}
}
} else {
if (db_num_rows($result)>0){
	$i = 0;
	while($row = db_fetch_assoc($result)){
		$i++;
		if ($row['name']==$session['user']['name']){
			rawoutput("<tr class='trhilight'><td>");
		} else {
			rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
		}
		output_notl("$i.");
		rawoutput("</td><td>");
		output_notl("`&%s`0",$row['name']);
		rawoutput("</td><td>");
		output_notl("`c`@%s`c`0",$row['value']);
		rawoutput("</td></tr>");
	}
}
}

rawoutput("</table>");

addnav("Chess Rankings");
addnav("Wins","runmodule.php?module=chess&op=hof&rank=wins");
addnav("Losses","runmodule.php?module=chess&op=hof&rank=loss");
addnav("Longest Matches","runmodule.php?module=chess&op=hof&rank=longest");
addnav("Shortest Matches","runmodule.php?module=chess&op=hof&rank=shortest");

addnav("Other");
addnav("Back to HoF", "hof.php");
addnav("Back to Village", "village.php");
?>