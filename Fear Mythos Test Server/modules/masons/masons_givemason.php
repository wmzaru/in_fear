<?php
function masons_givemason(){
	global $session;
	masons_masonnav1();
	$who = httpget('who');
	if ($who==""){
		output("`&Who would you like to help?`n`n");
		$subop = httpget('subop');
		if ($subop!="search"){
			$search = translate_inline("Search");
			rawoutput("<form action='runmodule.php?module=masons&op=givemason&subop=search' method='POST'><input name='name' id='name'><input type='submit' class='button' value='$search'></form>");
			addnav("","runmodule.php?module=masons&op=givemason&subop=search");
			addnav("Give to Mason");
			addnav("Search Again","runmodule.php?module=masons&op=givemason");
			rawoutput("<script language='JavaScript'>document.getElementById('name').focus();</script>");
		}else{
			addnav("Give to Mason");
			addnav("Search Again","runmodule.php?module=masons&op=givemason");
			$search = "%";
			$name = httppost('name');
			for ($i=0;$i<strlen($name);$i++){
				$search.=substr($name,$i,1)."%";
			}
			$sql = "SELECT ".db_prefix("module_userprefs").".value, ".db_prefix("accounts").".name,login FROM " . db_prefix("module_userprefs") . "," . db_prefix("accounts") . " WHERE (locked=0 AND name LIKE '$search') AND acctid = userid AND modulename = 'masons' AND setting = 'masonnumber' AND value > 0 ORDER BY (0-value)";
			$result = db_query($sql);
			$max = db_num_rows($result);
			if ($max > 100) {
				output("`n`n`&There are too many `&M`)asons `&to pick from.");
				output("Please choose from the first couple...`n");
				$max = 100;
			}
			$n = translate_inline("");
			if ($max < 1) output("`&There are no `&M`)asons`& by that name. Please try to search again.`n`n");
			rawoutput("<table border=0 cellpadding=0><tr><td>$n</td></tr>");
			for ($i=0;$i<$max;$i++){
				$row = db_fetch_assoc($result);
				rawoutput("<tr><td><a href='runmodule.php?module=masons&op=givemason&who=".rawurlencode($row['login'])."'>");
				output_notl("%s", $row['name']);
				rawoutput("</a></td><td></tr>");
				addnav("","runmodule.php?module=masons&op=givemason&who=".rawurlencode($row['login']));
			}
		rawoutput("</table>");
		}
	}else{
		$sql = "SELECT name,acctid FROM " . db_prefix("accounts") . " WHERE login='$who'";
		$result = db_query($sql);
		if (db_num_rows($result)>0){
			$row = db_fetch_assoc($result);
			$id = $row['acctid'];
			$name = $row['name'];
			output("`&What benefit would you like to give to %s`&?",$name);
			addnav("Search");
			addnav("Search Again","runmodule.php?module=masons&op=givemason");
			addnav("Gift Benefits");
			$dragonkills=$session['user']['dragonkills'];
			$dkstart=get_module_setting("dkstart");
			$mgturns=get_module_setting("mgturns");
			$mggold=get_module_setting("mggold");
			$mghps=get_module_setting("mghps");
			$mggems=get_module_setting("mggems");
			$mgfavor=get_module_setting("mgfavor");
			$mgcharm=get_module_setting("mgcharm");
			$mgtrav=get_module_setting("mgtrav");
			$mghealpot=get_module_setting("mghealpot");
			$mgtorment=get_module_setting("mgtorment");
			$mgimpdef=get_module_setting("mgimpdef");
			$mgimpatk=get_module_setting("mgimpatk");
			$allprefs=unserialize(get_module_pref('allprefs'));
			$dksincego=$allprefs['dksincego'];
			if (get_module_setting("specnone")<=e_rand(1,100)){
				if ((($dkstart==0&&$dragonkills>=$mgturns) || ($dksincego>=$mgturns))&&($mgturns>=0)) addnav("`@Peace","runmodule.php?module=masons&op=givepeace&op2=1");
				if ((($dkstart==0&&$dragonkills>=$mggold) || ($dksincego>=$mggold))&&($mggold>=0)) addnav("`^Wealth","runmodule.php?module=masons&op=givewealth&op2=1");
				if ((($dkstart==0&&$dragonkills>=$mghps) || ($dksincego>=$mghps))&&($mghps>=0)) addnav("`&Health","runmodule.php?module=masons&op=givehealth&op2=1");
				if ((($dkstart==0&&$dragonkills>=$mggems) || ($dksincego>=$mggems))&&($mggems>=0)) addnav("`%Sparkle","runmodule.php?module=masons&op=givesparkle&op2=1");
				if ((($dkstart==0&&$dragonkills>=$mgfavor) || ($dksincego>=$mgfavor))&&($mgfavor>=0)) addnav("`\$Occult","runmodule.php?module=masons&op=giveoccult&op2=1");
				if ((($dkstart==0&&$dragonkills>=$mgcharm)|| ($dksincego>=$mgcharm))&&($mgcharm>=0)) addnav("`&Charisma","runmodule.php?module=masons&op=givecharisma&op2=1");
				if ((is_module_active('cities'))&&(($dkstart==0&&$dragonkills>=$mgtrav)|| ($dksincego>=$mgtrav))&&($mgtrav>=0)) addnav("`QTravel","runmodule.php?module=masons&op=givetravel&op2=1");
				if ((is_module_active('potions'))&&(($dkstart==0&&$dragonkills>=$mghealpot)|| ($dksincego>=$mghealpot))&&($mghealpot>=0)) addnav("`@Pure `!Health","runmodule.php?module=masons&op=givepurehealth&op2=1");
				if ((($dkstart==0&&$dragonkills>=$mgtorment)|| ($dksincego>=$mgtorment))&&($mgtorment>=0)) addnav("`\$Afterlife","runmodule.php?module=masons&op=giveafterlife&op2=1");
				if ((($dkstart==0&&$dragonkills>=$mgimpdef)|| ($dksincego>=$mgimpdef))&&($mgimpdef>=0)) addnav("`2Dexterity","runmodule.php?module=masons&op=givedexterity&op2=1");
				if ((($dkstart==0&&$dragonkills>=$mgimpatk)|| ($dksincego>=$mgimpatk))&&($mgimpatk>=0)) addnav("`@Strength","runmodule.php?module=masons&op=givestrength&op2=1");
				$allprefs['gifttoname']=$name;
				$allprefs['gifttoid']=$id;
				set_module_pref('allprefs',serialize($allprefs));
			}else output("'`7 don't know anyone named that.'");
		}
	}
}
?>