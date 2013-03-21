<?php
//How to increase/decrease Bonus Interest in a module:
//Add the following line to increase Bonus Interest by 1 percentage point:
//increment_module_pref("interest",1,"interest");
//Add the following line to decrease Bonus Interest by 1 percentage point:
//increment_module_pref("interest",-1,"interest");

function interest_getmoduleinfo(){
	$info = array(
		"name"=>"Bonus Interest",
		"version"=>"1.0",
		"author"=>"DaveS, idea by tarepanda",
		"category"=>"Administrative",
		"download"=>"",
		"settings"=>array(
			"Bonus Interest,title",
			"Note: If you want to turn off the Standard Interest You will need to go set: Game Settings: Bank Settings: 'Over what amount of gold does the bank cease paying interest?' to 1,note",
			"newday"=>"Award Bonus Interest only on system newdays or every newday?,enum,0,System Newdays,1,Every Newday|0",
			"dk"=>"Reset Bonus Interest amount with each dk?,bool|0",
			"max"=>"Max Bonus Interest Rate (%),int|10",
			"gib"=>"Over what amount of gold does the bank cease paying Bonus interest? (0 for unlimited),int|10000",
			"forest"=>"Chance to encounter Elessa the Banker in the forest:,range,1,100,1|100",
			"help"=>"Amount of gold needed to give Elessa to increment Bonus Interest?,int|300",
		),
		"prefs"=>array(
			"Bonus Interest Preferences,title",
			"paid"=>"Is player due their interest today if tied to system newday?,bool|0",
			"interest"=>"What bonus interest rate should the player receive today?,int|0",
			"forest"=>"Has the player had the forest encounter today?,int|0",
		),
	);
	return $info;
}
function interest_chance() {
	if (get_module_pref("forest",'interest')==1) $ret=0;
	else $ret= get_module_setting('forest','interest');
	return $ret;
}
function interest_install(){
	module_addeventhook("forest","require_once(\"modules/interest.php\");
	return interest_chance();");
	module_addhook("footer-bank");
	module_addhook("newday");
	module_addhook("newday-runonce");
	module_addhook("dragonkill");
	return true;
}
function interest_uninstall(){
	return true;
}
function interest_dohook($hookname,$args){
	global $session;
	switch($hookname){
		case "footer-bank":
			$op = httpget('op');
			if (get_module_pref("interest")>0 && $op==""){
				output("`n`n`6You ask about your `iBonus Interest Rate`i and `@Elessa`6 tells you that currently you're at `^%s%%`6.",get_module_pref("interest"));
			}
		break;
		case "newday-runonce":
			if (get_module_pref("newday")==0){
				$sql = "update ".db_prefix("module_userprefs")." set value=1 where value=0 and setting='paid' and modulename='interest'";
				db_query($sql);
			}
		break;
		case "newday":
			if (get_module_pref("paid")==1 || get_module_setting("newday")==1){
				set_module_pref("paid",0);
				if (get_module_pref("interest")>get_module_setting("max")) set_module_pref("interest",get_module_setting("max"));
				$interest=get_module_pref("interest");
				output("`n`6Since you developed a relationship with the banker, you receive a special interest rate for today of `^%s%%`6.`n",$interest);
				$gold=round(($interest/100)*$session['user']['goldinbank']);
				if ($session['user']['goldinbank']>=get_module_setting("gib") && get_module_setting("gib")>0){
					output("Unfortunately, the bank will not pay Bonus Interest on accounts equal or greater than `^%s`6 to retain solvency.`n", get_module_setting("gib"));
				}elseif ($gold>0){
					output("You gain `^%s gold`6 in interest today.`n",$gold);
					$session['user']['goldinbank']+=$gold;
				}else{
					output("Unfortunately, you don't have enough gold in the bank to earn any interest on.`n");
				}
			}
			set_module_pref("forest",0);
		break;
		case "dragonkill":
			if (get_module_setting("dk")==1) set_module_pref("interest",0);
		break;
	}
	return $args;
}
function interest_runevent($type) {
	global $session;
	$op = httpget('op');
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:interest";
	if ($op=="give"){
		$give=get_module_setting("help");
		output("`6Feeling generous, you hand over `^%s gold`6 to `@Elessa`6 and she smiles.`n`n`@'Thank you!",$give);
		increment_module_pref("interest",1,"interest");
		$session['user']['gold']-=$give;
		if (get_module_pref("interest")==1) {
			output("I'm going to give you a special gift,' Elessa `6 explains. `@'I'm going to give a `iBonus Interest Rate`i to you.");
			output("Starting tomorrow, you'll gain an additional `^1%`@ interest on all the money you have in the bank.");
		}else{
			output("I'm going to increase your `iBonus Interest Rate`i another point,' Elessa `6 explains. `@'It's now up to `^%s%%`@!",get_module_pref("interest"));
		}
		if (get_module_setting("dk")==1) output("Remember, this Bonus Interest Rate will reset back to zero when you kill the `bGreen Dragon`b.");
		output("Thank you once again,'`6 she says.");
		output("`n`nIt seems like you've helped save the bank!");
		debuglog("incremented Bonus Interest Rate by giving $give gold to Elessa in the Forest.");
		addnews("%s `6 helped save the bank by giving money to `@Elessa`6.",$session['user']['name']);
		$session['user']['specialinc']="";
	}elseif($op=="dont"){
		output("`6Not feeling like you want to help a bank by giving it money, you decline to give any money.`n`n");
		if (get_module_pref("interest")>0) {
			output("`@'I'm very disappointed in you,'`6 says `@Elessa`6. `@'In fact, I think I'm going to decrease your Current Bonus Interest by a point.'");
			increment_module_pref("interest",-1,"interest");
			if (get_module_pref("interest")==0) output("`n`n`6She hands you a slip of paper and you read that your Bonus Interest Rate is now zero.");
			else  output("`n`n`6She hands you a slip of paper and you read that your Bonus Interest Rate is now `^%s%%`6.",get_module_pref("interest"));
		}
		$session['user']['specialinc']="";
	}else{
		if ($session['user']['gold']>=get_module_setting("help") && get_module_pref("interest")<get_module_setting("max")){
			output("`6You are wandering through the forest when you see a forlorn-appearing woman pacing back and forth.");
			output("`n`n`#'What's the matter?'`6 you ask.");
			output("`n`nThe woman turns and you instantly recognize her as `@Elessa the Banker`6.  She looks at you for a second, almost as if she's questioning whether to tell you her problems or not.");
			output("She finally gives in and starts to tell you her story.");
			output("`n`n`@'I'm a little concerned about the bank's solvency.  One of our biggest investors has just withdrawn their funds. I'm trying to raise money.  Would you be willing to donate `^%s gold`@ to help?'`6 she asks.",get_module_setting("help"));
			addnav("Give her the Gold", $from."op=give");
			addnav("Don't give her any Gold",$from."op=dont");
		}else{
			if (get_module_setting("help")>250) $help=250;
			else $help=get_module_setting("help");
			$gold=e_rand(1,$help);
			$session['user']['gold']+=$gold;
			$session['user']['turns']--;
			output("`6You find `^%s gold`6 scattered on the forest floor.  You `@spend a turn`6 picking up all the money.",$gold);
			$session['user']['specialinc']="";
			debuglog("gained $gold gold by spending a turn picking up gold on the forest floor.");
		}
		set_module_pref("forest",1);
	}
}
function interest_run(){
}
?>