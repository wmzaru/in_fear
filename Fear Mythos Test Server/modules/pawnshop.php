<?php
// BY Robert of maddrio dot com
// Translation help: Chris Vorndran

function pawnshop_getmoduleinfo(){
	$info = array(
		"name"=>"Pawn Shop",
		"version"=>"1.2",
		"author"=>"`2Robert",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=807",
		"description"=>"Lets players sell gems.",
		"settings"=>array(
			"Pawn Shop - Settings,title",
			"storeloc"=>"Where does this shop appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"perday"=>"How many visits/gems sold per day?,range,1,25,1|3",
			"lapse"=>"How many days between visits? - set 0 for daily access,range,0,50,1|0",
			"dkreq"=>"How many DK must player have?,range,0,100,1|1",
			"Gold for gems is random amount - set min & max to same if you want one price,note",
			"minunder"=>"Min gold for gems under level restriction,range,5,100,5|25",
			"maxunder"=>"Max gold for gems under level restriction,range,25,500,25|100",
			"level"=>"Over what level to sell for higher price?,range,1,14,1|4",
			"Players over the level set above will receive the following,note",
			"minover"=>"Min gold for gems over level restriction,range,25,1000,25|100",
			"maxover"=>"Max gold for gems over level restriction,range,50,5000,50|300",
			"Pawn Shop - Misc,title",
			"shopname"=>"The name of the shop?|Ye Olde Pawn Shop",
			"shopkeepname"=>"The name of the shopkeep?|Odin",
			"shopkeeprace"=>"The race of the shopkeep?|Troll",
			"shopkeepdesc"=>"A description of the shopkeep?|retched old man",
		),
		"prefs"=>array(
			"Pawn Shop - User Prefs,title",
			"gemtoday"=>"How many gems did player sell today?,int|0",
			"gemtotal"=>"Total gems they sold to this shop,int|0",
			"daysleft"=>"How many days till they can visit again?,int|0",
		)
	);
	return $info;
}

function pawnshop_install(){
	if (!is_module_active('pawnshop')){
		output("`^ Installing module Pawn Shop `n");
	}else{
		output("`^ Up Dating module Pawn Shop`n");
	}
	module_addhook("changesetting");
	module_addhook("newday");
	module_addhook("village");
	return true;
}

function pawnshop_uninstall(){
	output("`n`^ Un-Installing module Pawn Shop `0`n");
    return true;
}

function pawnshop_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "changesetting":
		if ($args['setting'] == "villagename") {
			if ($args['old'] == get_module_setting("storeloc")) {
				set_module_setting("storeloc", $args['new']);
			}
		}
	break;
	case "newday":
		if (get_module_pref("daysleft") >=1){
			increment_module_pref("daysleft",-1);
			output("`n`2 The hurt from selling your gems still burns deep. `n`0");
		}
		set_module_pref("gemtoday",0);
	break;
	case "village":
		if ($session['user']['location']==get_module_setting("storeloc")){
		$shopname = translate_inline(get_module_setting("shopname"));
		tlschema($args['schemas']['tavernnav']);
		addnav($args['tavernnav']);
		tlschema();
		addnav("$shopname","runmodule.php?module=pawnshop");
		}
		break;
	}
	return $args;
}

function pawnshop_run(){
	global $session;
	$op=httpget('op');
	$shopname=(get_module_setting("shopname"));
	$shopkeepname=(get_module_setting("shopkeepname"));
	$shopkeeprace=(get_module_setting("shopkeeprace"));
	$shopkeepdesc=(get_module_setting("shopkeepdesc"));
	$minunder = get_module_setting("minunder");
	$maxunder = get_module_setting("maxunder");
	$randunder = e_rand($minunder,$maxunder);
	$minover = get_module_setting("minover");
	$maxover = get_module_setting("maxover");
	$randover = e_rand($minover,$maxover);
	$playergems = $session['user']['gems'];
	$plevel = $session['user']['level'];
	$perday = get_module_setting("perday");
	$today = get_module_pref("gemtoday");
	$lapse = get_module_setting("lapse");
	$level = get_module_setting("level");
	
	if($plevel>=$level){ $amt=$randover;}else{ $amt=$randunder;}

	page_header($shopname);
	rawoutput("<font size='+1'>");
	output("`c`b`i`6 %s `0`i`b`c`n",$shopname);
	rawoutput("</font>");
	if ($op==""){
		if ($today > $perday){ // check if they visited more than setting
			output("`n You approach the shop but find its door locked. ");
			output("`n`n Perhaps it will open again soon - try another day. ");
		}else{
			output("`n You enter into a dark musty smelling shop, goods lay on display upon the wall shelves.  ");
			output("`n`n You go to the counter where you look above and see two heavily armed guards standing `n`n on a balcony overlooking the counter, watching you very carefully. ");
			output("`n`n A %s, named %s, scoffs at you in a dry raspy voice, `n`n `2I'm only buying gems and nothing else! ",$shopkeepdesc,$shopkeepname);

		if (($playergems >=1) && ($today < $perday)){  // uses check
		addnav("(1) Sell 1 Gem","runmodule.php?module=pawnshop&op=sell1");
		}elseif ($playergems <=0){
			output("`n`n ...and you dont even have any gems to sell me! ");
		}else{
			output("`n`n Back again so soon? Be gone, you bled me dry enough today!");
		}
	}
	}elseif ($op=="sell1"){
		output("`n`@ %s takes your gem, and carefully looks at it under the light of a nearby lamp. ",$shopkeepname);
			switch(e_rand(1,10)){
				case 1: output("`n`n`6 Ach.. your %s is probaly worth more than this chunk of glass. ",$weapon); break;
				case 2: output("`n`n`6 I've seen worse but can't remember when`@, says %s.",$shopkeepname); break;
				case 3: output("`n`n`6 Ohhh ...look at all these imperfections!`@ says %s.",$shopkeepname); break;
				case 4: output("`n`n`6 One of the finest gems, this is not.`@ says %s.",$shopkeepname); break;
				case 5: output("`n`n`6 I've seen %s crappy gems just like this one already today`@, says %s.",$level,$shopkeepname); break;
				case 6: output("`n`n`6 My eyes arent so good any more, neither is this gem`@, says %s.",$shopkeepname); break;
				case 7: output("`n`n`6 A fraction above the finest glass this gem is`@, says %s.",$shopkeepname); break;
				case 8: output("`n`n`6 Even without my glasses I can see the crack in it`@, says %s.",$shopkeepname); break;
				case 9: output("`n`n`6 What a piece of junk this one is`@, says %s.",$shopkeepname); break;
				case 10: output("`n`n`6 The quality on this one is so poor it is best to call it a piece of glass`@, says %s.",$shopkeepname); break;
			}
		output("`n`n`6 I'll be giving you %s in gold for it, best I can do.",$amt);
		output("`n`n`@ %s hands you %s and says, '`6you're bleeding me dry ...cant be `ithis`i generous with everyone.`@' ",$shopkeepname,$amt);
		output("`n`n`2 You grab the gold from the %s and tuck it away for safe keeping. ",$shopkeeprace);
		increment_module_pref("gemtoday",1);
		increment_module_pref("gemtotal",1);
		$session['user']['gems'] -= 1;
		$session['user']['gold'] += $amt;
		debuglog(" sold 1 gem for $amt gold at pawnshop`0");
		if ($gemtoday >= $lapse){
			set_module_pref("daysleft",get_module_setting("lapse"));
		}else{
			addnav("(S) Sell another?","runmodule.php?module=pawnshop");
		}
	}
	addnav(" exit ");
	villagenav();
	page_footer();
}
?>