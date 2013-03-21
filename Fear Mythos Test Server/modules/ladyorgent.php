<?php


function ladyorgent_getmoduleinfo(){
    $info = array(
        "name"=>"Lady or Gentleman",
        "version"=>"1.0",
        "author"=>"Qwyxzl",
        "category"=>"Village Specials",
        "download"=>"http://www.geocities.com/qwyxzl/ladyorgent.zip",
        "settings"=>array(
        	"Lady or Gentleman - Settings,title",
        	"dkmin"=>"At what dk does the chance for lady or gent to drop item appear?,int|0",
			"levelmin"=>"At what level does the chance for lady or gent to drop item appear?,range,1,15,1|1",
			"dkmax"=>"At what dk does the chance for lady or gent to drop item disappear(0 for never)?,int|0",
			"note"=>"If DK maximum is greater than zero the chance for reward gets smaller the closer the player gets to the max.,note",
			"chance"=>"What is the chance for a reward if DK maximum = 0?,range,5,100,5|50",
        	"gemchance"=>"What is the chance of player getting a gem if Item drops?,range,5,100,5|100",
        	"align"=>"What is the chance for affecting alignment if installed?,range,5,100,5|50"
        ),
		"prefs"=>array(
			"gotreward"=>"Has player received a gem today?,bool|0",
		)
    );
    return $info;
}


function ladyorgent_install(){	
	module_addhook("newday");
	module_addhook("changesetting");
	module_addeventhook("village", "return 100;");
    return true;
}


function ladyorgent_uninstall(){
    return true;
}


function ladyorgent_dohook($hookname,$args){
    switch($hookname){
    	case "newday":
			set_module_pref("gotreward",false);
			break;
    }    
	return $args;
}


function does_item_drop(){
	global $session;
	$min = get_module_setting("dkmin");
	$max = get_module_setting("dkmax");
	$dk = $session['user']['dragonkills'];
	
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
			if($session['user']['level'] < get_module_setting("levelmin")){
				return false;
			}
		}
		if($dk > $max){
			return false;
		}
		$range = $max - $min;
		if($range != 0){
			$place = $range - ($dk - $min);
			$percent = round(100 * ($place/$range));
			if(e_rand(1,100) > $percent){
				return false;
			}
		}
	}else{
		if(e_rand(1,100) > get_module_setting("chance")){
			return false;
		}
	}
	return true;
}


function ladyorgent_runevent($type) {
    global $session;
	$session['user']['specialinc'] = "module:ladyorgent";
	$op = httpget('op');
	$debugtext = "";
	
	if($session['user']['sex'] != SEX_MALE){
		$item = "ornament";
		$person = "gentleman";
		$himher = "him";
		$message = "`&A handsome and dashing gentleman enters the square bowing to the ladies and tipping his hat,";
		$message .= " which is sporting a sparkly `@green dragon`& ornament, in greetings to the gentlemen.";
		$dropmessage = " You see the `@ornament`& slip off the hat and fall into the dust!";
		$noreward = "`n`!\"Thank you ever so much beautiful Lady.\"";
		$rewardmessage .= " `&He reaches into his pocket,";
		$away = " `&He then lifts your hand to his lips and kisses it.";
		$away .= "`n`!\"I am eternally gratefull to you for the return of my ornament. It was given to me by a dear friend.\"";
		$away .= "`n`&You smile as he wanders away.`n`n";
		$noreward .= $away;	
   	}else{
	   	$item = "earring";
		$person = "lady";
		$himher = "her";
	   	$message = "`&A beautiful and elegant lady enters the square curtsying to the gentlemen and waving to all";
		$message .= " the ladies present. She is wearing a wonderful set of sparkly `@green dragon`& earrings.";
		$dropmessage = " You see one of the `@earrings`& slip from her ear and fall into the dust.";
		$noreward = "`n`%\"Thank you ever so much kind Sir.\"";
		$rewardmessage .= " `&She reaches into her purse,";
		$away = " `&She then gives you a quick kiss on the cheek.";
		$away .= "`n`%\"I am eternally grateful to you for the return of my earring. It was given to me by a dear friend.\"";
		$away .= "`n`&You smile as she wanders away.`n`n";
		$noreward .= $away;
	}
	$message = translate_inline($message);
	$dropmessage = translate_inline($dropmessage);
	$rewardmessage = translate_inline($rewardmessage);
	$noreward = translate_inline($noreward);
	$away = translate_inline($away);
	$item = translate_inline($item);
	$person = translate_inline($person);
	$himher = translate_inline($himher);
    switch($op){
	    case "":
    		output_notl("%s", $message);
    		if(!get_module_pref("gotreward") && does_item_drop()){
	    		output_notl("%s", $dropmessage);
    			addnav(array("Leave %s",$item),"village.php?op=leave");
    			addnav(array("Pick up %s",$item),"village.php?op=pick");
			}else{
				$session['user']['specialinc'] = "";
			}
			break;
		case "leave":
			output("`&You cannot take the time out of your busy day to pick up trinkets for others.`n`n");
			$session['user']['specialinc'] = "";
			break;
		case "pick":
			output("`&You walk over and pick up the %s. Looking at it closer you think it might be valuable.",$item);
			addnav(array("Return %s",$item),"village.php?op=return");
    		addnav(array("Keep %s",$item),"village.php?op=keep");
    		break;
		case "return":
			$debugtext = "Returned item";
			addnews("%s was seen returning a %s to its owner,",$session['user']['name'], $item);
			output("`&You run after the %s and finally catch up to %s.",$person, $himher);
			output("`n`%\"You dropped this.\"`& you say, and hand back the %s.",$item);
			if(is_module_active("alignment")){
				$roll = e_rand(0,99);
				$chance = get_module_setting("align");
				$debugtext .= " align roll: $roll align chance: $chance";
				if($roll < $chance){
					$debugtext .= " align went up";
					increment_module_pref("alignment", 1, "alignment");
				}
			}
			$roll = e_rand(1,100);
			$chance = get_module_setting("gemchance");
			$debugtext .= " gem roll: $roll gem chance: $chance";
			if($roll <= $chance){
				$debugtext .= " got gem";
				output("%s pulls out a gem and hands it to you.`n",$rewardmessage);
				output_notl("%s",$away);
				$session['user']['gems']++;
				set_module_pref("gotreward", true);
			}else{
				output_notl("%s",$noreward);
			}
			$session['user']['specialinc'] = "";
			break;
		case "keep":
			$debugtext = "Kept item";
			addnews("%s was overheard saying `%\"Finders keepers, losers weepers.\"",$session['user']['name']);
			output("`&It would have been too hard to find %s in the crowd anyway, you think.", $himher);
			output("`nYou examine the %s closer and discover that",$item);
			if(is_module_active("alignment")){
				$roll = e_rand(0,99);
				$chance = get_module_setting("align");
				$debugtext .= " align roll: $roll align chance: $chance";
				if($roll < $chance){
					$debugtext .= " align went down";
					increment_module_pref("alignment", -1, "alignment");
				}
			}
			
			$roll = e_rand(1,100);
			$chance = get_module_setting("gemchance");
			$debugtext .= " gem roll: $roll gem chance: $chance";
			if($roll <= $chance){
				$debugtext .= "    got gem";
				output(" it contains a gem!`n`n");
				$session['user']['gems']++;
				set_module_pref("gotreward", true);
			}else{
				output(" it has no real value whatsoever.`n`n");
			}
			$session['user']['specialinc'] = "";
			break;
	}
	if($debugtext != ""){
		debuglog("{$session['user']['name']}    $debugtext");
	}
}


function ladyorgent_run(){
}


?>