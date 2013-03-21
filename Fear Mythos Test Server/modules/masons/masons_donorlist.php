<?php
function masons_donorlist(){
	global $session;
	$perpage = get_module_setting("perpage");
	$subop = httpget('subop');
	if ($subop=="") $subop=1;
	$min = (($subop-1)*$perpage);
	$max = $perpage*$subop;
	$sql = "SELECT acctid FROM ".db_prefix("accounts")."";
	$res = db_query($sql);
	$number=0;
	for ($i=0;$i<db_num_rows($res);$i++){
		$row = db_fetch_assoc($res);
		$allprefs=unserialize(get_module_pref('allprefs','masons',$row['acctid']));
		if ($allprefs['donated']>0) $number=$number+1;
	}
	$totalpages=ceil($number/$perpage);
	addnav("Pages");
	if ($totalpages>1){
		for($i = 0; $i < $totalpages; $i++) {
			$j=$i+1;
			$minpage = (($j-1)*$perpage)+1;
			$maxpage = $perpage*$j;
			if ($maxpage>$number) $maxpage=$number;
			if ($maxpage==$minpage) addnav(array("Page %s (%s)", $j, $minpage), "runmodule.php?module=masons&op=donorlist&subop=$j");
			else addnav(array("Page %s (%s-%s)", $j, $minpage, $maxpage), "runmodule.php?module=masons&op=donorlist&subop=$j");
		}
	}
	$rank = translate_inline("Rank");
	$name = translate_inline("Name");
	$donated = translate_inline("Total Amount Donated");
	$none = translate_inline("No Donations Received");
	rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
	rawoutput("<tr class='trhead'><td>$rank</td><td>$name</td><td>$donated</td></tr>");
	if ($number==0) output_notl("<tr class='trlight'><td colspan='3' align='center'>`&$none`0</td></tr>",true);
	else{
		$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
		$res = db_query($sql);
		//Thanks to Sichae for the next lines
		$new_array = array();
		while($row = db_fetch_assoc($res)){
			$array = unserialize(get_module_pref('allprefs','masons',$row['acctid']));
			$new_array[$row['name']] = $array['donated'];
		}
		arsort($new_array);
		foreach($new_array AS $name => $value){
			if ($value>0){
			//End Sichae code
				$n=$n+1;
				if ($n>$min && $n<=$max){
					if ($name==$session['user']['name']) rawoutput("<tr class='trhilight'><td>");
					else rawoutput("<tr class='".($n%2?"trdark":"trlight")."'><td>");
					output_notl("`&%s",$n);
					rawoutput("</td><td>");
					output_notl("`&%s",$name);
					rawoutput("</td><td><center>");
					output_notl("`@%s",$value);
					rawoutput("</center></td></tr>");
				}
			}
		}
	}
	rawoutput("</table>");
	addnav("The Order");
	addnav("`&Donate To The Order","runmodule.php?module=masons&op=donate");
	addnav("`&Narmyan's Office","runmodule.php?module=masons&op=office");
	addnav("`&The Lounge","runmodule.php?module=masons&op=enter");
	addnav("Village");
	addnav("Return to Village","village.php");
}
?>