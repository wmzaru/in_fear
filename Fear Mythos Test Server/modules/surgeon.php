<?php
// addnews ready
// translator ready
// mail ready

function surgeon_getmoduleinfo() {
	$info = array(
		"name" => "Plastic surgeon",
		"version" => "1.0",
		"author" => "Moi",
		"category" => "Village",
		"settings" => array(
			"Plastic surgeon settings,title",
			"gain" => "Amount of charm that can be gained or lost,range,1,10,1|5",
			"heal" => "Number of days required to heal a successful operation,range,4,50,1|20",
			"healfail" => "Number of days required to heal a failed operation,range,4,50,1|30",
			"cost"=>"Cost in gems per operation,range,2,30,1|10",
			"psloc"=>"Where does the surgeon appear,location|".getsetting("villagename", LOCATION_FIELDS),
			"chance"=>"Chance for operation to succeed,range,0,100,1|60",
			"chancefail"=>"Chance for operation to fail,range,0,100,1|10",
		),
		"prefs" => array(
			"Plastic surgeon User Preferences,title",
			"lastop" => "Days remaining for player to heal,int|0",
			"res" => "Does last operation succeed,bool|0",
		),
	);
	return $info;
}

function surgeon_install() {
	module_addhook("newday");
	module_addhook("village");
	module_addhook("changesetting");
	module_addhook("validatesettings");
	return true;
}

function surgeon_uninstall() {
	return true;
}

function surgeon_dohook($hookname,$args) {
	global $session;
	
	switch($hookname) {
		case "changesetting":
			if ($args['setting'] == "villagename") {
				if ($args['old'] == get_module_setting("psloc")) {
					set_module_setting("psloc", $args['new']);
				}
			}
			break;
		case "validatesettings":
			if ($args['chance'] + $args['chancefail'] > 100) {
				$args['validation_error'] = "The two chances must be <= 100.";
			}
			break;
		case "newday":
			$lastop = get_module_pref("lastop");
			$res = get_module_pref("res");
			if ($lastop > 1) {
				if ($res == 1) {
					$maxtime = get_module_setting("heal");
					$mult = 0;
					if ($lastop > $maxtime*.95) {
						$mult = 0.7;
					} elseif ($lastop > $maxtime*.8) {
						$mult = 0.8;
					} elseif ($lastop > $maxtime*.7) {
						$mult = 0.9;
					} elseif ($lastop > $maxtime*.66) {
						$mult = 0.95;
					}
					output("`n`6Your scar is gradually healing.`n");
					if ($mult) {
						output("`&You `\$lose`& some hitpoints due to the pain.`n");
						$session['user']['hitpoints'] *= $mult;
						if ($session['user']['hitpoints'] <= 1) {
							$session['user']['hitpoints'] = 1;
						}
					}
					$lastop -=1;
					set_module_pref("lastop",$lastop);
				} elseif ($res == 0) {
					$maxtime = get_module_setting("healfail");
					$mult = 0;
					if ($lastop > $maxtime*.95) {
						$mult = 0.7;
					} elseif ($lastop > $maxtime*.8) {
						$mult = 0.8;
					} elseif ($lastop > $maxtime*.7) {
						$mult = 0.9;
					} elseif ($lastop > $maxtime*.66) {
						$mult = 0.95;
					}
					output("`n`6Your scar is gradually healing.`n");
					if ($mult) {
						output("`&You `\$lose`& some hitpoints due to the pain.`n");
						$session['user']['hitpoints'] *= $mult;
						if ($session['user']['hitpoints'] <= 1) {
							$session['user']['hitpoints'] = 1;
						}
					}
					$lastop -=1;
					set_module_pref("lastop",$lastop);
				}
			} elseif (get_module_pref("lastop") == 1) {
				set_module_pref("lastop",0);
				output("`n`6Your scar has completely healed.`n");
			}
			break;
		case "village":
			if ($session['user']['location'] == get_module_setting("psloc")) {
				tlschema($args['schemas']['marketnav']);
				addnav($args['marketnav']);
				tlschema();
				addnav("Black Jack's Clinic","runmodule.php?module=surgeon");
			}
			break;
	}
	return $args;
}

function surgeon_run() {
	global $session;
	$op = httpget("op");
	$cost=get_module_setting("cost");
	$lastop=get_module_pref("lastop");
	$gain=get_module_setting("gain");

	page_header("Plastic Surgeon");
	output("`)`c`bBlack `&Jack's Clinic`b`c");

	if ($op == "" && $lastop==0) {
		output("`7A black & white haired man looks up from a medical file and smiles at you as you enter the clinic.");
                output("His name can be read on his white coat: `)Black `&Jack`7.`n`n");
                output("\"`&Good day, dear %s`&! So lovely to see you. What can I do for you ?`7\"`n`n", $session['user']['name']);
		addnav("Get operated","runmodule.php?module=surgeon&op=yes");
		addnav("Don't get operated","runmodule.php?module=surgeon&op=nope");
	} elseif ($op == "instaheal") {
		output("The rawness of your scar, and its pain, fades, as if by magic!");
		set_module_pref("lastop",0);
		addnav("Return to Clinic", "runmodule.php?module=surgeon");
	} elseif ($op == "") {
		output("`7You think about the pain of the last operation you had done.`n`n");
		output("Perhaps one day, you can think about getting another.");
		if ($session['user']['superuser'] & SU_DEVELOPER) {
			addnav("InstaHeal", "runmodule.php?module=surgeon&op=instaheal");
		}
		villagenav();
	} elseif ($op == "yes") {
		if ($session['user']['gems']>=$cost) {
			output("Ok ! Let's go ! The price is %s gems.\"`n`n", $cost);
			addnav(array("Pay %s gems", $cost),"runmodule.php?module=surgeon&op=full");
			addnav("Don't get operated today","runmodule.php?module=surgeon&op=nope");
		} else {
			output("`7You suddenly realize you don't have %s gems.`n`n",$cost);
			output("\"`&Ah ! No gems, no operation !`7\"`n`n");
			output("`7You realize you don't have much choice in the matter.");
			villagenav();
		}
	} elseif ($op == "nope") {
		output("`7You're more than a little afraid of getting operated, and you just want to get out of there.`n`n");
		output("`)Black `&Jack `7thanks you for visiting.");
		villagenav();
	} else {
					$r = e_rand(1,100);
			$chance = get_module_setting("chance");
			$chancefail = get_module_setting("chancefail");
			if ($r <= $chance){
				$session['user']['gems']-=$cost;
				$session['user']['hitpoints']*=0.2;
				if ($session['user']['hitpoints']<=1) {
					$session['user']['hitpoints']=1;
				}
				$session['user']['charm']+=$gain;
				set_module_pref("res",1);

				$heal= get_module_setting("heal");
				$lastop=$heal + e_rand(-3, +3);
				if ($lastop < 3) $lastop = 3;

				output("`7The operation was successful ! You gain %s charm !`n`n",$gain);
				output("`7You're far too sore to move very fast until it heals properly.`n`n");
				output("You `\$lose`7 a lot of your hitpoints!`n`n");
				set_module_pref("lastop",$lastop);
				if ($session['user']['superuser'] & SU_DEVELOPER) {
					addnav("InstaHeal", "runmodule.php?module=surgeon&op=instaheal");
				}
				villagenav();
			}else if ($r <= $chance+$chancefail) { //fail
				$session['user']['gems']-=$cost;
				$session['user']['hitpoints']*=0.2;
				if ($session['user']['hitpoints']<=1) {
					$session['user']['hitpoints']=1;
				}
				$session['user']['charm']-=$gain;
				set_module_pref("res",0);

				$healfail= get_module_setting("healfail");
				$lastop=$healfail + e_rand(-3, +3);
				if ($lastop < 3) $lastop = 3;

				output("`7The operation failed ! You lose %s charm !`n`n",$gain);
				output("`7You're far too sore to move very fast until it heals properly.`n`n");
				output("You `\$lose`7 a lot of your hitpoints!`n`n");
				set_module_pref("lastop",$lastop);
				if ($session['user']['superuser'] & SU_DEVELOPER) {
					addnav("InstaHeal", "runmodule.php?module=surgeon&op=instaheal");
				}
				villagenav();
			} else {
				output("`)Black `&Jack `7suddenly disappears and can't be found anywhere.");
				villagenav();
			}
	}
	page_footer();
	
}
?>