<?php


function drunkstumble_getmoduleinfo(){
    $info = array(
        "name"=>"Drunk Stumble",
        "version"=>"1.2",
        "author"=>"Qwyxzl",
        "category"=>"Village Specials",
        "download"=>"http://www.geocities.com/qwyxzl/drunkstumble.zip",
        "requires"=>array(
			"drinks"=>"1.0|core module"
		),
        "settings"=>array(
        	"Stumble - Settings,title",
        	"dkmin"=>"At what dk does the chance for stumbling appear?,int|0",
			"levelmin"=>"At what level does the chance for stumbling appear?,range,1,15,1|1",
			"dkmax"=>"At what dk does the chance for stumbling disappear(0 for never)?,int|0",
			"howoften"=>"How often should this special occur?,range,5,100,5|50",
			"maxstumbles"=>"How many times can a player stumble in one day (0 for infinite)?,int|3",
			"note"=>"The amount of gold loss is range going from one half the level of drunkeness`n to that same number plus the value below.,note",
			"goldloss"=>"What is the range of gold can a player lose?,int|100",
			"gemloss"=>"What is the max number of gems a player can lose(least is 1)?,range,1,10,1|1",
			"charmloss"=>"What is the max number of charm a player can lose(least is 1)?,range,1,10,1|1",
			"addtonews"=>"Add message to news on loss of charm?,bool|0"
        ),
		"prefs"=>array(
			"numstumbles"=>"Number of times player has stumbled today,int|0"
		)
    );
    return $info;
}


function drunkstumble_install(){	
	module_addhook("newday");
	module_addeventhook("village", "return get_module_setting(\"howoften\",\"drunkstumble\");");
    return true;
}


function drunkstumble_uninstall(){
    return true;
}


function drunkstumble_dohook($hookname,$args){
    switch($hookname){
    	case "newday":
			set_module_pref("numstumbles",0);
			break;
    }    
	return $args;
}


function does_stumble(){
	global $session;
	$dk = $session['user']['dragonkills'];
	$min = get_module_setting("dkmin");
	$max = get_module_setting("dkmax");
	$maxfalls = get_module_setting("maxstumbles");
	
	if($maxfalls != 0){
		if(get_module_pref("numstumbles") >= $maxfalls){
			return false;
		}
	}
	if($max != 0){
		if($min < 0){
			$min = 0;
		}
		if($max < $min){
			return false;
		}
		if($dk < $min){
			return false;
		}
		if($dk == $min){
			if($dk < get_module_setting("levelmin")){
				return false;
			}
		}
		if($dk > $max){
			return false;
		}
	}
	increment_module_pref("numstumbles", 1);
	return true;
}


//The event descriptions are based on suggestions by Shiqra
function drunkstumble_runevent($type) {
    global $session;
	$session['user']['specialinc'] = "module:stumble";
	$op = httpget('op');
	$debugtext = "";
	
    switch($op){
	    case "continue":
	    	$session['user']['specialinc'] = "";
	    	break;
	    case "":
			$howdrunk = get_module_pref("drunkeness", "drinks");
			$whathappens = e_rand(1,100) + $howdrunk;
			$debugtext = "eventroll:$whathappens";
			if(!does_stumble()){
				break;
			}
			if($whathappens <= 70){
				$chance = e_rand(1,100);
				$debugtext .= " ,<=70, how drunk:$howdrunk, chance to lose charm:$chance";
				output("You trip over a rock and look around hoping no one saw you`n");
				if($chance <= $howdrunk){
					$debugtext .= lose_charm("tripped over a rock");
				}
			}else if($whathappens <= 120){
				$eventname = get_name();
				$maxloss = get_module_setting("goldloss");
				if($maxloss < 0){
					$maxloss = 0;
				}
				$maxloss += ($howdrunk / 2);
				$amountgoldloss = e_rand($howdrunk / 2, $maxloss);
				if($session['user']['gold'] < $amountgoldloss){
					$amountgoldloss = $session['user']['gold'];
				}
				$session['user']['gold'] -= $amountgoldloss;
				$debugtext .= ", <=120, goldloss:$amountgoldloss";
				output("You stagger into %s`0, who says `!\"Hey watch where you are going you clumsy oaf!\"`0`n",$eventname);
				if($amountgoldloss > 0){
					output("You don't notice until later one of your pouches of gold is missing!`nYou lose %s `6gold`0!`n", $amountgoldloss);
				}
				$debugtext .= lose_charm("rudely bumped into someone");
			}else if($whathappens <= 170){
				$maxloss = get_module_setting("gemloss");
				if($maxloss < 1){
					$maxloss = 1;
				}
				$amountgemloss = e_rand(1, $maxloss);
				if($session['user']['gems'] < $amountgemloss){
					$amountgemloss = $session['user']['gems'];
				}
				$session['user']['gems'] -= $amountgemloss;
				$debugtext .= ", <=170, gemloss:$amountgemloss";
				if($amountgemloss = 1){
					$pluralornot = "";
				}else{
					$pluralornot = "s";
				}
				$pluralornot = translate_inline($pluralornot);
				output("You trip over your own feet and fall face first in the street.`n");
				if($amountgemloss > 0){
						output(" The fall loosens your belt pouch, scattering %s gem%s into the crowd!`n",$amountgemloss, $pluralornot);
					}
				if($session['user']['sex'] != SEX_MALE){
					$debugtext .= lose_charm("tripped over her own feet");
				}else{
					$debugtext .= lose_charm("tripped over his own feet");
				}
			}else{
				$debugtext .= ", >170";
				if($session['user']['turns'] > 0){
					$session['user']['turns']--;
					$debugtext .= ", lost a turn";
					output("You lurch unsteadily down the street, barely able to see straight. You stumble and fall into a horse trough. You lose a turn whilst unconcious!`n");
				}else{
					$maxgoldloss = get_module_setting("goldloss");
					if($maxgoldloss < 0){
						$maxgoldloss = 0;
					}
					$maxgoldloss += $howdrunk;
					$amountgoldloss = e_rand($howdrunk, $maxgoldloss);
					if($session['user']['gold'] < $amountgoldloss){
						$amountgoldloss = $session['user']['gold'];
					}
					$session['user']['gold'] -= $amountgoldloss;
					$debugtext .= ", goldloss:$amountgoldloss";
					
					$maxgemloss = get_module_setting("gemloss");
					if($maxgemloss < 1){
						$maxgemloss = 1;
					}
					$amountgemloss = e_rand(1, $maxgemloss);
					if($session['user']['gems'] < $amountgemloss){
						$amountgemloss = $session['user']['gems'];
					}
					$session['user']['gems'] -= $amountgemloss;
					$debugtext .= ", <=170, gemloss:$amountgemloss";
					if($amountgemloss = 1){
						$pluralornot = "";
					}else{
						$pluralornot = "s";
					}
					$pluralornot = translate_inline($pluralornot);
					output("You lurch unsteadily down the street, barely able to see straight. You stumble and fall into a horse trough. You are robbed whilst unconcious!`n");
					if($amountgoldloss > 0){
						output("You lose %s `6gold!`0`n", $amountgoldloss);
					}
					if($amountgemloss > 0){
						output("You lose %s gem%s!`n",$amountgemloss, $pluralornot);
					}
					if($amountgoldloss == 0 && $amountgemloss == 0){
						output("Finding nothing of value the robbers just leave you sleeping there.");
					}
				}
				$debugtext .= lose_charm("passed out in a horse trough");
			}
			output_notl("`n`n");
			addnav("Continue","village.php?op=continue");
			//$session['user']['specialinc'] = "";
			break;
	}	
	if($debugtext != ""){
		debuglog("{$session['user']['name']} $debugtext");
	}
}


function drunkstumble_run(){
}


function lose_charm($event){
	global $session;
	
	$amountcharmloss = e_rand(1, get_module_setting("charmloss"));
	if($session['user']['charm'] < $amountcharmloss){
		$amountcharmloss = $session['user']['charm'];
	}
	$charmname = get_name();
	if($amountcharmloss > 0){
		$session['user']['charm']-= $amountcharmloss;
		$message = translate_inline("points at you and giggles. The rest of the crowd joins in. Your face burns with embarrassment. You lose charm!");
	}else{
		$message = translate_inline("points at you and giggles. The rest of the crowd joins in. You would be embarrassed but you have no charm to lose!");
	}
	output_notl("%s`0 %s", $charmname, $message);
	if(get_module_setting("addtonews")){
		$event = translate_inline($event);
		if($session['user']['sex'] != SEX_MALE){
			$heshe = "she";
		}else{
			$heshe = "he";
		}
		$heshe = translate_inline($heshe);
		addnews("%s was seen pointing and giggling at %s because %s %s", $charmname, $session['user']['name'], $heshe, $event);
	}
	return ", charm loss:$amountcharmloss";
}


function get_name(){
	global $session;
	
	$sql = "SELECT creaturename FROM " . db_prefix("masters") . " ORDER BY RAND() LIMIT 1";
	$result = db_query($sql);
	$result = db_fetch_assoc($result);
	$master = stripslashes($result['creaturename']);
	if($master == ""){
		$master = "`#MightyE";
	}
	$sql = 'SELECT name FROM `'.db_prefix('accounts').'` ORDER BY RAND() LIMIT 1';
	$result = db_query($sql);
	$result = db_fetch_assoc($result);
	$player = stripslashes($result['name']);
	if($player == "" || $player == $session['user']['name']){
		$player = $master;
	}
	$who = array(
		translate_inline(getsetting('barkeep','`tCedrik')),
		translate_inline(getsetting("bard", "`^Seth")),
		translate_inline(getsetting("barmaid", "`%Violet")),
		translate_inline("`#MightyE"),
		translate_inline($master),
		translate_inline($master),
		translate_inline($player),
		translate_inline($player),
		translate_inline($player)
	);
	return $who[e_rand(0, count($who)-1)];
}


?>