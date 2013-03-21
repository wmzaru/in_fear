<?php
require_once("lib/villagenav.php");
require_once("lib/commentary.php");
global $session;
        page_header("Clan War vault");
        $clanid = $session['user']['clanid'];
        $op = httpget('op');
        $att = get_module_objpref("clans", $clanid, "att");
        $def = get_module_objpref("clans", $clanid, "def");
        $total = get_module_objpref("clans", $clanid, "total");
if($op==""){
        page_header("Clan War Vault");
                output("`c`nThis clan currently has:`n`c");
                output("`c`nAttack: `^%s`0`c`n",$att);
                output("`cDefense: `^%s`c`n`n",$def);
                output("`c`n There is currently %s gold in the vault`c`n",$total);
                output("`nYou walk into large room with a counter on the other side, You walk over to the counter and behind it stands a very excitable little elf.`n He jumps up and down at the sight of you,`n`n\"Hello there! Are you here to donate to your Clan?`n How much would you like to donate for your clans attack and defense?\"");
                output("`n`n`6You currently have %s gold on hand.`n`n", $session['user']['gold']);
        if ($session ['user']['gold']<=0){
                output("You don't have any gold to deposit!");
                addnav("Nevermind then","clan.php");
        }else{
                output("Deposit how much?");
                rawoutput("<form action='runmodule.php?module=clanwarvault&op=depositfinish' method='POST'>");
                rawoutput(" <input id='input' name='depgoldamount' width=5 > <input type='submit' class='button' value='deposit gold'>");
                rawoutput("</form>");
                rawoutput("<script language='javascript'>document.getElementById('input').focus();</script>",true);
                addnav("","runmodule.php?module=clanwarvault&op=depositfinish");
                addnav("Maybe later","clan.php");
                modulehook("clanwarvault");
}

villagenav();
rawoutput("<div style=\"text-align: center;\"><a href=\"http://www.shadowlegacy.com\" target=\"_blank\">ClanWar Vault made by Melissa(ShadowRaven)~ShadowLegacy.com</a></div><br>");
page_footer();
}

if($op=="depositfinish"){
        page_header("Deposit Gold");
                $depgoldamount = abs((int)httppost('depgoldamount'));
                $total = get_module_objpref("clans", $clanid, "total");
                $new = $total+$depgoldamount;
        if ($depgoldamount>$session['user']['gold']){
                output("What are you trying to pull?? You don't have that much on hand!");
                 addnav("back to Clan halls","clan.php");
        }else{
                output("`@You deposit  %s gold into the Vault.",$depgoldamount);
                $session['user']['gold']-=$depgoldamount;
                set_module_objpref("clans", $clanid, "total",$new);
                set_module_pref("don",get_module_pref("don")+$depgoldamount);
                addnav("Back to Vault","runmodule.php?module=clanwarvault");
                addnav("Back to Clan halls","clan.php");

}
        page_footer();
}
if($op=="warroom"){
          page_header("War Room");
                $catt = get_module_setting("catt");
                $cdef = get_module_setting("cdef");
                $type = httpget('type');
                $link = "runmodule.php?module=clanwarvault&op=warroom";
          switch ($type) {

                case "toggle":
                        $status = translate_inline((get_module_objpref("clans", $session['user']['clanid'], "allowed")?"0":"1"));
                        set_module_objpref("clans", $session['user']['clanid'], "allowed",$status);
                        $status = translate_inline(($status?"On":"Off"));
                        output_notl("`n`n`b`Q%s `^%s `Q%s`b",translate_inline("You have just toggled Officers abliltiy to purchase attack and defense to"),$status,translate_inline("."));
                break;
                        }

         if ($session['user']['clanrank'] == CLAN_LEADER){
                        $status = translate_inline((get_module_objpref("clans", $clanid, "allowed")?"On":"Off"));
                        output_notl("`n`@%s `^%s `@%s",translate_inline("You currently have Officers purchasing ability"),$status,translate_inline(". If this is on, Officers and all higher ranks can purchase attack and/or defense, otherwise, only Leaders can.`n`n"));
                        addnav(array("`^%s`@%s`^%s",translate_inline("Toggle Clan Officers (Currently: "),$status,translate_inline(")")),$link."&type=toggle");
                }
                output("`c`nThis clan currently has:`n`c");
                output("`c`nAttack: `^%s`0`c`n",$att);
                output("`cDefense: `^%s`c`n`n",$def);
                output("`c`n There is currently %s gold in the vault`c`n",$total);
                output("`c`n`nFrom here, you may purchase attack and/or defense for your clan.`c`n");
                output("`c`nIt costs %s gold per attack point and`c`n",$catt);
                output("`c`n%s per defense point.`c`n",$cdef);
                addcommentary();
                         commentdisplay("","clanwarvault-warroom-{$session['user']['clanid']}","`#Discuss Stratagies:`@",25,"interjects");
                modulehook("clanwarvault-warroom");
                addnav("Make purchase");
                addnav("Buy Attack","runmodule.php?module=clanwarvault&op=batt");
                addnav("Buy Defense","runmodule.php?module=clanwarvault&op=bdef");
                addnav("Leave");
                addnav("Back to Clan halls","clan.php");
                villagenav();
                page_footer();
}
if($op=="batt"){
       page_header("War Room-purchase Attack");
                $catt = get_module_setting("catt");

        if($total < $catt){
                output("Sorry, There is not enough gold to purchase anything!");
                debuglog("tried to buy Clan attack");
                addnav("Back","runmodule.php?module=clanwarvault&op=warroom");
        }else{
                output("There is `@%s`0 gold available.`n",$total);
                $tatt = floor($total/$catt);
                output("`b`^With %s, The max number of attack you can buy is %s `b",$total,$tatt);
                output("`nHow much attack would you like to buy?`n`n");
                rawoutput("<form action='runmodule.php?module=clanwarvault&op=battfinish' method='POST'>");
                rawoutput("<input id='input' name='bought'>");
                rawoutput("<input type='submit' class='button' value='Buy attack'>");
                rawoutput("</form>");
                rawoutput("<script language='javascript'>document.getElementById('input').focus();</script>",true);
                addnav("","runmodule.php?module=clanwarvault&op=battfinish");
                addnav("Forget it","runmodule.php?module=clanwarvault&op=warroom");
}
page_footer();
}
if($op=="battfinish"){
        page_header("War Room-purchase Attack");
                $bought = abs((int)httppost('bought'));
                $catt = get_module_setting("catt");
                $cost = $bought*$catt;
                $tatt = floor($total/$catt);
                $newtotal = $total-$cost;
                $newatt = $att+$bought;
                $gatt = get_module_objpref("clans", $clanid, "gatt");
                $newgatt = $gatt+$cost;
                $total = get_module_objpref("clans", $clanid, "total");
        if($cost > $total){
                output("`n`nThere is not enough gold to buy that much attack!!`n`n");
                output("`b`^With %s, The max number of attack you can buy is %s `b",$total,$tatt);
                debuglog("tried to buy $bought Clan attack");
                addnav("Back","runmodule.php?module=clanwarvault&op=warroom");
        }elseif($cost <= $total){
                output("`cYou have bought %s attack for your clan!`c`n`n ",$bought);
                output("`n`n`cThere is now %s gold left in the vault`c",$newtotal);
                set_module_objpref("clans", $clanid, "att",$newatt);
                set_module_objpref("clans", $clanid, "total",$newtotal);
                set_module_objpref("clans", $clanid, "gatt",$newgatt);
                debuglog("bought $bought Attack points for their clan");
                addnav("Make purchase");
                addnav("Buy Attack","runmodule.php?module=clanwarvault&op=batt");
                addnav("Buy Defense","runmodule.php?module=clanwarvault&op=bdef");
                addnav("Leave");
                addnav("Back","runmodule.php?module=clanwarvault&op=warroom");
                addnav("Back to Clan halls","clan.php");
                villagenav();
}

page_footer();
}
if($op=="bdef"){
       page_header("War Room-purchase Defense");
                $cdef = get_module_setting("cdef");
        if($total< $cdef){
                output("Sorry, There is not enough gold to purchase anything!");
                debuglog("tried to buy Clan defense");
                addnav("Back","runmodule.php?module=clanwarvault&op=warroom");
        }else{
                output("There is `@%s`0 gold available.`n",$total);
                $tdef = floor($total/$cdef);
                output("`b`^With %s, The max number of attack you can buy is %s `b",$total,$tdef);
                output("`nHow much defense would you like to buy?`n`n");
                rawoutput("<form action='runmodule.php?module=clanwarvault&op=bdeffinish' method='POST'>");
                rawoutput("<input id='input' name='bought'>");
                rawoutput("<input type='submit' class='button' value='Buydef'>");
                rawoutput("</form>");
                rawoutput("<script language='javascript'>document.getElementById('input').focus();</script>",true);
                addnav("","runmodule.php?module=clanwarvault&op=bdeffinish");
                addnav("Forget it","runmodule.php?module=clanwarvault&op=warroom");
}
page_footer();
}
if($op=="bdeffinish"){
        page_header("War Room-purchase Defense");
                $bought = abs((int)httppost('bought'));
                $cdef = get_module_setting("cdef");
                $cost = ($bought*$cdef);
                $tdef = floor($total/$cdef);
                $newtotal = $total-$cost;
                $newdef = $def+$bought;
                $gdef = get_module_objpref("clans", $clanid, "gdef");
                $newgdef = $gdef+$cost;
        if($cost > $total){
                output("`n`nThere is not enough gold to buy that much defense!!`n`n");
                output("`b`^With %s, The max number of defense you can buy is %s `b",$total,$tdef);
                debuglog("tried to buy $bought Clan defense");
                addnav("Back","runmodule.php?module=clanwarvault&op=warroom");
        }elseif($cost <= $total){
                output("`cYou have bought %s defense for your clan!`c`n`n ",$bought);
                 output("`n`n`cThere is now %s gold left in the vault`c",$newtotal);
                set_module_objpref("clans", $clanid, "def",$newdef);
                set_module_objpref("clans", $clanid, "total",$newtotal);
                set_module_objpref("clans", $clanid, "gdef",$newgdef);
                debuglog("bought $bought Defense points for their clan");
                addnav("Make purchase");
                addnav("Buy Attack","runmodule.php?module=clanwarvault&op=batt");
                addnav("Buy Defense","runmodule.php?module=clanwarvault&op=bdef");
                addnav("Leave");
                addnav("Back","runmodule.php?module=clanwarvault&op=warroom");
                addnav("Back to Clan halls","clan.php");
        villagenav();
}

page_footer();
}
if($op=="hof"){
//HoF done by Kalazaar. Thanks kala! :)
      $clanid=$session['user']['clanid'];
page_header("Top 10 clan Donators");
$acc = db_prefix("accounts");
$mp = db_prefix("module_userprefs");
$sql = "SELECT $acc.name AS name,
$acc.acctid AS acctid,
$mp.value AS donated,
$mp.userid FROM $mp INNER JOIN $acc
ON $acc.acctid = $mp.userid
WHERE $mp.modulename = 'clanwarvault'
AND $mp.setting = 'don'
AND $acc.clanid = '$clanid'
AND $mp.value > 0 ORDER BY ($mp.value+0)
DESC limit 10";
$result = db_query($sql);
$rank = translate_inline("Donated");
$name = translate_inline("Name");
output("`n`b`c`#Top `3Clan `#Donators`n`n`c`b");
rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center'>");
rawoutput("<tr class='trhead'><td align=center>$name</td><td align=center>$rank</td></tr>");
for ($i=0;$i < db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($row['name']==$session['user']['name']){
        rawoutput("<tr class='trhilight'><td>");
}else{
        rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td align=left>");
}
        output_notl("%s",$row['name']);
        rawoutput("</td><td align=right>");
        output_notl("%s",$row['donated']);
        rawoutput("</td></tr>");
}
rawoutput("</table>");
addnav("Back to Clan Hall", "clan.php");
villagenav();

page_footer();
}
?>
