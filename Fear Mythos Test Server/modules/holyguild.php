<?php

function holyguild_getmoduleinfo(){
	$info = array(
		"name"=>"Holy Redeemer's Guild",
		"author"=>"Chris Vorndran",
		"category"=>"Village",
		"version"=>"1.0",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1119",
		"settings"=>array(
			"Holy Redeemer's Guild General Settings,title",
				"hploss"=>"How much HP is lost to redeem a creature's soul?,int|1",
				"maxhold"=>"Total merits allowed on a single person.,int|30",
			"Merit Pricings,title",
				"special"=>"Meritss to increase specialty uses,int|3",
				"gems"=>"Merits to forge a gem,int|7",
				"atkdef"=>"Merits to increase Attack/Defense/Hitpoints,int|20",
				"hpgain"=>"How much HP is gained at trade-in,int|5",
			"Holy Redeemer's Guild Location,title",
				"mindk"=>"What is the minimum DK before this shop will appear to a user?,int|0",
				"holyloc"=>"Where does Redeemer's Guild appear,location|".getsetting("villagename", LOCATION_FIELDS)
		),
		"prefs"=>array(
			"Holy Redeemer's Guild Preferences,title",
				"holyguild_prefs"=>"All prefs held within this module.,viewonly|",
				"explanation"=>",viewonly|manygems - How many gems did Seraph take when joining.`n".
				"merits - Merits currently held by person.`n".
				"apply - Has person applied to the Redeemer's Guild?`n".
				"atk - Has user built up attack?`n".
				"def - Has user built up defense?`n".
				"hp - Has user built up HP?",
				"user_showmerit"=>"Do you wish to see how many Merits you have,bool|1",
		),
	);
	return $info;
}
function holyguild_install(){
	module_addhook("village");
	module_addhook("battle-victory");
	module_addhook("bioinfo");
	module_addhook("newday");
	module_addhook("charstats");
	module_addhook("dragonkilltext");
	return true;
}
function holyguild_uninstall(){
	return true;
}
function holyguild_dohook($hookname,$args){
	global $session,$badguy;
	$maxhold = get_module_setting("maxhold");
	$cost = get_module_setting("cost");
	$guild = unserialize(get_module_pref("holyguild_prefs"));
	switch ($hookname){
	case "charstats":
		if (get_module_pref("user_showmerit") && $guild['apply']){
			$title = translate_inline("Personal Info");
			$name = translate_inline("Merits");
			setcharstat($title,$name,$guild['merits']);
		}
		break;
	case "village":
		if (is_module_active("slayerguild")){
			if (get_module_pref("convert","slayerguild")){
				$grab = unserialize(get_module_pref("slayerguild_prefs","slayerguild"));
				if ($grab['apply']) break;
			}else{
				if (get_module_pref("apply","slayerguild")) break;
			}
		}
		if ($session['user']['location'] == get_module_setting("holyloc")
			&& $session['user']['dragonkills'] >= get_module_setting("mindk")){
			tlschema($args['schemas']['marketnav']);
			addnav($args['marketnav']);
			tlschema();
			addnav("Holy Redeemer's Guild","runmodule.php?module=holyguild&op=enter");
		}
        break;
	case "changesetting":
		if ($args['setting'] == "villagename"){
			if ($args['old'] == get_module_setting("holyloc")){
				set_module_setting("holyloc",$args['new']);
			}
		}
		break;
	case "battle-victory":
		if ($args['type'] == 'forest'){
			$hploss = get_module_setting("hploss");
			$apply = $guild['apply'];
			$merits = $guild['merits'];
			$maxhold = get_module_setting("maxhold");
			if ($apply && $merits < $maxhold){
				if ($badguy['graveyard']){
					output("`n`b`&You have redeemed their soul!`b`n`n");
					$guild['merits']++;
					if (is_module_active('alignment')) {
						require_once("./modules/alignment/func.php");
						align("+1");
					}
				}else{
					output("`n`b`&By sending an innocent, you have wasted energy!`b`n`n");
					$session['user']['hitpoints']-=$hploss;
					if ($session['user']['hitpoints']<=$hploss){
						debuglog("died from sending an innocent.");
						$session['user']['hitpoints']=0;
						$session['user']['alive']=FALSE;
						redirect("runmodule.php?module=holyguild&op=dead&op2=forest");
					}
				}
			}
		}
		break;
	case "dragonkilltext":
		$guild['atk'] = 0;
		$guild['def'] = 0;
		$guild['hp'] = 0;
		break;
	case "bioinfo":
		$grab = unserialize(get_module_pref("holyguild_prefs","holyguild",$args['acctid']));
		if ($grab['apply']){
			output_notl("`n");
			output("`&%s `7is a member of the `&Holy Redeemer's Guild`7.`n",$args['name']);
		}
		break;
	}
	set_module_pref("holyguild_prefs",serialize($guild));
	return $args;
}
function holyguild_run(){
	global $session;
	$special = get_module_setting("special");
	$gems = get_module_setting("gems");
	$atkdef = get_module_setting("atkdef");
	$guild = unserialize(get_module_pref("holyguild_prefs"));
	$merits = $guild['merits'];
	$apply = $guild['apply'];
	$op = httpget('op');
	page_header("Holy Redeemer's Guild");
	
	switch ($op){
		case "enter":
			output("`7You wander into a grand building, with a vaulted ceiling completely lit by a single flame in the center of the main hall.");
			output("Upon further inspection, the flame has no fuel and is floating in midair.");
			output("\"`&Enjoying the sights?`7\" a mysterious voice sounds.`n`n");
			if (!$apply) {
				output("`7You take a closer look at the figure and see large wings spanning out from its back.");
				output("\"`&My name is Seraph and this is the Holy Redeemer's Guild.");
				output("It is our goal to give the creatures of the forest a proper sending to the hereafter.");
				output("Do you wish to join us?`7\"");
				addnav("Choices");
				if ($session['user']['gems'] > 1){
					addnav("Yes","runmodule.php?module=holyguild&op=yes");
				}else{
					output("`n`n`7As you have no gems on your person, Seraph sees this and says, \"`&Come back when you have some gems.`7\"");
				}
				addnav("Not Today","runmodule.php?module=holyguild&op=no");
			}else{
				output("`&Seraph `7walks up to you and smiles, \"`&What business do you have with the Guild today?`7\"");
				addnav("Proceed");
				addnav("Chambers","runmodule.php?module=holyguild&op=inner");
				addnav("Leave the Guild","runmodule.php?module=holyguild&op=giveup");
			}
			break;
		case "yes":
			$take = (int)($session['user']['gems']/2);
			output("`7The figure introduces himself as `&Seraph`7, the Archangel.");
			output("\"`&It is our duty to repair the balance in this realm.");
			output("We are the pure souls that venture forth to smite evil.");
			output("In order to keep this building in order, you will have to contribute %s gems.`7\"",$take);
			output("He appears next to you, with %s gems in his hand, \"`&Thank you.`7\"",$take);
			$session['user']['gems']-=$take;
			if (is_module_active('alignment')) {
				output("You already feel your soul being lifted...");
				require_once("./modules/alignment/func.php");
				align("+50");
			}
			$guild['apply'] = 1;
			$guild['manygems'] = $take;
			$guild['merits'] = 0;
			break;
		case "no":
			output("`&Seraph `7stares at you and shrugs, \"`&Hopefully you will come around before it is too late.`7\"");
			output("He snaps his fingers and disappears.");
			break;
		case "dead":
			if ($op2 == "forest") output("You expended too much energy trying to convert an already pure soul.");
			if ($op2 == "give") output("The pain from the good leaving your body has left you crippled.");
			output("`&Seraph `7draws in the sand and a portal opens, a dark lord striding out from it.");
			addnav("Return to the Shades","shades.php");
			blocknav("village.php");
			blocknav("runmodule.php?module=holyguild&op=inner");
			break;
		case "giveup":
			if (httpget('confirm')){
				output("`&Seraph `7shakes his head and says, \"`&So be it...`7\"");
				output("He presses his thumb to your forehead, extracting all the merits from your memories.");
				output("Your limbs fall limp, as does your body.");
				output("`&Seraph `7lays a tiny pouch on your chest, containing %s gems.`n`n",$guild['manygems']);
				if ($merits > 0){
					output("`7\"`&I see that you still have some unspent merits.");
					output("For that, I shall spare your body from death.`7\"");
					$guild['apply'] = 0;
					$guild['merits'] = 0;
				}else{
					output("`7\"Tsk tsk... no merits on your person.");
					output("Most unfortunate, as I know have to take your life to make up for it...");
					output("Goodbye, faithful servant.`&\"");
					$guild['apply'] = 0;
					$guild['merits'] = 0;
					$session['user']['hitpoints']=0;
					$session['user']['alive']=false;
					addnav("Death...","runmodule.php?module=holyguild&op=dead&op2=give");
					blocknav("village.php");
				}
				$session['user']['gems']+=$guild['manygems'];
				$guild['manygems'] = 0;
				blocknav("runmodule.php?module=holyguild&op=inner");
			}else{
				output("`&Seraph `7taps his foot with an odd look on his face.");
				addnav("Are you sure?");
				addnav("Yes","runmodule.php?module=holyguild&op=giveup&confirm=1");
				addnav("No","runmodule.php?module=holyguild&op=inner");
			}
			break;
		case "inner":
			output("`&Seraph`7 leads you into the deep chambers of the Guild.");
			output("All of a sudden, he disappears from in front of you and reappears behind a counter.");
			output("Snapping his fingers, sparkling lights fly out of your skull and arrange themselves.");
			output("Before you, a glowing number, %s, is hovering.",$merits);
			output("\"`&Is there anything special you wish to spend your merits on?`7\"`n`n");
			if (!$merits) output("`&Seraph `7arches a brow, \"`&You have no use being here... might as well leave.`7\"");
			addnav("Spend Merits");
			if ($merits >= $special) 
				addnav(array("Increase Specialty Uses - %s Merits",$special),"runmodule.php?module=holyguild&op=special");
			if ($merits >= $gems) 
				addnav(array("Forge a Gem - %s Merits",$gems),"runmodule.php?module=holyguild&op=gems");
			if ($merits >= $atkdef){
				if (!$guild['atk']) 
					addnav(array("Increase Attack - %s Merits",$atkdef),"runmodule.php?module=holyguild&op=stat&type=atk");
				if (!$guild['def']) 
					addnav(array("Increase Defense - %s Merits",$atkdef),"runmodule.php?module=holyguild&op=stat&type=def");
				if (!$guild['hp']) 
					addnav(array("Increase Hitpoints - %s Merits",$atkdef),"runmodule.php?module=holyguild&op=stat&type=hp");
			}
			if ($guild['atk'] && $guild['def'] && $guild['hp'])
				output("`&Seraph `7 shakes his head, as he sees you glance towards the attack, defense and hitpoints modifier.");
			break;
		case "special":
			output("`&Seraph `7 nods in agreement, \"`&So, you wish to increase your specialty uses?");
			output("That is all well and good, but it will cost %s merits.",$special);
			output("Of course, you already knew that... so, I shall be taking them now.`7\"");
			output("`&Seraph `7reaches his hand out and extracts the merits from you.");
			output("You have gane a specialty usage point.");
			$mods = modulehook("specialtymodules");
			$name = $mods[$session['user']['specialty']];
			increment_module_pref("skill", 3, $name);
			increment_module_pref("uses", 1, $name);
			$guild['merits']-=$special;
			break;
		case "gems":
			output("`&Seraph `7looks at you and smiles, \"`&Crafting a gem for someone special?");
			output("Quite the act of kindness... yet, most wish only to make them for themselves.");
			output("Ah well, their loss.`7\"");
			output("`&Seraph `7extracts %s merits and hands over a gem.",$gem);
			$guild['merits']-=$gems;
			$session['user']['gems']++;
			break;
		case "stat":
			switch (httpget('type')){
				case "atk":
					output("`&Seraph `7places a hand on your shoulder, noting your %s merits.",$merits);
					output("\"`&Ye wish to trade in %s of those merits for a wee bit more strength, eh?",$atkdef);
					output("So be it...`7\"");
					output("`&Seraph `7nods and extracts %s merits from you, as you feel your muscles bolstering.",$atkdef);
					output("After the ceremony, you flex your bicep and feel the raw strength flow through you.");
					$session['user']['attack']++;
					$guild['atk'] = 1;
					break;
				case "def":
					output("`&Seraph `7closes his eyes, measuring the thickness of your skin.");
					output("\"`&So, you wish to be able to withstand stronger attacks?");
					output("Then you have come to the right place.");
					output("This service shall cost %s merits, but you already know that.`7\"",$atkdef);
					output("`&Seraph `7reaches his hand forth and grabs you around the chest, lifting you high.");
					output("Your heart starts to race, as you notice your skin getting heavier.");
					output("`&Seraph `7steps back and fires an arrow at you, only to see it glance of your skin.");
					$session['user']['defense']++;
					$guild['def'] = 1;
					break;
				case "hp":
					output("`7\"`&Endurace is what you want, eh?`7\"");
					output("`&Seraph `7wanders out and hands you a small vial.");
					output("You take it as `&Seraph `7removes %s merits from your mind.",$atkdef);
					output("Downing the vial, your heart starts pacing and racing.");
					output("Your eyes focus and unfocus, until it is all over.`n`n");
					output("You have gained %s more maximum hitpoints.",get_module_setting("hpgain"));
					$guild['hp'] = 1;
					$session['user']['maxhitpoints']+=get_module_setting("hpgain");
					break;
			}
			$guild['merits']-=$atkdef;
			break;
}			
if ($merits >= 1 && $op != "inner" && $op != "enter"){
	addnav("Continue");
	addnav("Chambers","runmodule.php?module=holyguild&op=inner");
}
addnav("Leave");
villagenav();
set_module_pref("holyguild_prefs",serialize($guild));
page_footer();
}
?>