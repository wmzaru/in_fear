<?php
function masons_givenonmason(){
	global $session;
	masons_masonnav1();
	$who = httpget('who');
	if ($who==""){
		output("`&Who would you like to help?`n`n");
		$subop = httpget('subop');
		if ($subop!="search"){
			$search = translate_inline("Search");
			rawoutput("<form action='runmodule.php?module=masons&op=givenonmason&subop=search' method='POST'><input name='name' id='name'><input type='submit' class='button' value='$search'></form>");
			addnav("","runmodule.php?module=masons&op=givenonmason&subop=search");
			addnav("Give to Non-Mason");
			addnav("Search Again","runmodule.php?module=masons&op=givenonmason");
			rawoutput("<script language='JavaScript'>document.getElementById('name').focus();</script>");
		}else{
			addnav("Give to Non-Mason");
			addnav("Search Again","runmodule.php?module=masons&op=givenonmason");
			$search = "%";
			$name = httppost('name');
			for ($i=0;$i<strlen($name);$i++){
				$search.=substr($name,$i,1)."%";
			}
			$sql = "SELECT name,level,login FROM " . db_prefix("accounts") . " WHERE (locked=0 AND name LIKE '$search') ORDER BY level DESC";
			$result = db_query($sql);
			$max = db_num_rows($result);
			if ($max > 100) {
				output("`n`n`&There are too many Villagers to pick from.");
				output("Please choose from the first couple...`n");
				$max = 100;
			}
			$n = translate_inline("");
			if ($max < 1) output("`&There are no Villagers by that name. Please try to search again.`n`n");
			rawoutput("<table border=0 cellpadding=0><tr><td>$n</td></tr>");
			for ($i=0;$i<$max;$i++){
				$row = db_fetch_assoc($result);
				rawoutput("<tr><td><a href='runmodule.php?module=masons&op=givenonmason&who=".rawurlencode($row['login'])."'>");
				output_notl("%s", $row['name']);
				rawoutput("</a></td><td></tr>");
				addnav("","runmodule.php?module=masons&op=givenonmason&who=".rawurlencode($row['login']));
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
			addnav("Search Again","runmodule.php?module=masons&op=givenonmason");
			addnav("Gift Benefits");
			$dkstart=get_module_setting("dkstart");
			$dragonkills=$session['user']['dragonkills'];
			$gturns=get_module_setting("gturns");
			$ggold=get_module_setting("ggold");
			$ghps=get_module_setting("ghps");
			$ggems=get_module_setting("ggems");
			$gfavor=get_module_setting("gfavor");
			$gcharm=get_module_setting("gcharm");
			$gtrav=get_module_setting("gtrav");
			$ghealpot=get_module_setting("ghealpot");
			$gtorment=get_module_setting("gtorment");
			$gimpdef=get_module_setting("gimpdef");
			$gimpatk=get_module_setting("gimpatk");
			$allprefs=unserialize(get_module_pref('allprefs'));
			$dksincego=$allprefs['dksincego'];
			if ((($dkstart==0&&$dragonkills>=$gturns) || ($dksincego>=$gturns))&&($gturns>=0)) addnav("`@Peace","runmodule.php?module=masons&op=givepeace&op2=2");
			if ((($dkstart==0&&$dragonkills>=$ggold) || ($dksincego>=$ggold))&&($ggold>=0)) addnav("`^Wealth","runmodule.php?module=masons&op=givewealth&op2=2");
			if ((($dkstart==0&&$dragonkills>=$ghps) || ($dksincego>=$ghps))&&($ghps>=0)) addnav("`&Health","runmodule.php?module=masons&op=givehealth&op2=2");
			if ((($dkstart==0&&$dragonkills>=$ggems) || ($dksincego>=$ggems))&&($ggems>=0)) addnav("`%Sparkle","runmodule.php?module=masons&op=givesparkle&op2=2");
			if ((($dkstart==0&&$dragonkills>=$gfavor) || ($dksincego>=$gfavor))&&($gfavor>=0)) addnav("`\$Occult","runmodule.php?module=masons&op=giveoccult&op2=2");
			if ((($dkstart==0&&$dragonkills>=$gcharm)|| ($dksincego>=$gcharm))&&($gcharm>=0)) addnav("`&Charisma","runmodule.php?module=masons&op=givecharisma&op2=2");
			if ((is_module_active('cities'))&&(($dkstart==0&&$dragonkills>=$gtrav)|| ($dksincego>=$gtrav))&&($gtrav>=0)) addnav("`QTravel","runmodule.php?module=masons&op=givetravel&op2=2");
			if ((is_module_active('potions'))&&(($dkstart==0&&$dragonkills>=$ghealpot)|| ($dksincego>=$ghealpot))&&($ghealpot>=0)) addnav("`@Pure `!Health","runmodule.php?module=masons&op=givepurehealth&op2=2");
			if ((($dkstart==0&&$dragonkills>=$gtorment)|| ($dksincego>=$gtorment))&&($gtorment>=0)) addnav("`\$Afterlife","runmodule.php?module=masons&op=giveafterlife&op2=2");
			if ((($dkstart==0&&$dragonkills>=$gimpdef)|| ($dksincego>=$gimpdef))&&($gimpdef>=0)) addnav("`2Dexterity","runmodule.php?module=masons&op=givedexterity&op2=2");
			if ((($dkstart==0&&$dragonkills>=$gimpatk)|| ($dksincego>=$gimpatk))&&($gimpatk>=0)) addnav("`@Strength","runmodule.php?module=masons&op=givestrength&op2=2");
			$allprefs['gifttoname']=$name;
			$allprefs['gifttoid']=$id;
			set_module_pref('allprefs',serialize($allprefs));
		}else output("'`7 don't know anyone named that.'");
	}
}
?>