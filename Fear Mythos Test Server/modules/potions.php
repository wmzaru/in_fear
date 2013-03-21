<?php
//0.9.8 conversion by Frederic Hutow<br />Contributions JT Traub
function potions_getmoduleinfo(){
	$info = array(
		"name"=>"Portable Potions",
		"version"=>"1.54",
		"author"=>"`#Lonny Luberts",
		"category"=>"PQcomp",
		"download"=>"http://www.pqcomp.com/modules/mydownloads/visit.php?cid=3&lid=12",
		"vertxtloc"=>"http://www.pqcomp.com/",
		"prefs"=>array(
			"Portable Potions Module User Preferences,title",
			"potion"=>"Number of potions carried,range,0,5,1|1",
			"restrict"=>"Restrict Potions Use on Current Page,bool|0",
			"restorepage"=>"Restore Page,viewonly"
		),
		"settings"=>array(
			"Portable Potions Module Settings,title",
			"movehealer"=>"Move Healer from forest to village,bool|1",
		),
	);
	return $info;
}

function potions_install(){
	if (!is_module_active('potions')){
		output("`4Installing Portable Potions Module.`n");
	}else{
		output("`4Updating Portable Potions Module.`n");
	}
	module_addhook("charstats");
	module_addhook("dragonkill");
	module_addhook("header-healer");
	module_addhook("village");
	module_addhook("forest");
	module_addeventhook("forest", "require_once(\"modules/potions.php\"); return potions_chance();");
	module_addeventhook("travel", "return 25;");
	return true;
}

function potions_uninstall(){
	output("`4Un-Installing Portable Potions Module.`n");
	return true;
}

function potions_chance(){
    global $session;
    $upotion = get_module_pref("potion", "potions");
    if ($upotion >= 5 or $session['user']['dragonkills'] >= 10)
        return 0;
    return 100;
}

function potions_dohook($hookname,$args){
	global $session;
	if (get_module_setting("movehealer") == "1"){
			$ret="village.php";
		}else{
			$ret="forest.php";
		}
	switch($hookname){
	case "forest":
		if (get_module_setting("movehealer") == "1") blocknav("healer.php");
	break;
	case "village":
		$city = getsetting("villagename", LOCATION_FIELDS);
		$capital = $session['user']['location']==$city;
		if (is_module_active('cities') and $capital){
		}else{
			if (get_module_setting("movehealer") == "1"){
				tlschema($args['schemas']['fightnav']);
				addnav($args['fightnav']);
				tlschema();
				addnav("H?Healer's Hut","healer.php?return=".$ret);
			}
		}
	break;
	case "header-healer":
		$op = httpget('op');
		$loglev = log($session['user']['level']);
		$potioncost = ($loglev * ($session['user']['maxhitpoints'])) + ($loglev*10);
		$upotion  = get_module_pref("potion");
		if (get_module_setting("movehealer") == "1") blocknav("forest.php");
		if ($potioncost == 0) $potioncost=$session['user']['dragonkills']*5;
		if ($op == "buyheal"){
			output("The Healer eagerly snatches your gold and tosses you a vile with a strange liquid in it.`n");
			output("You carefully place it in your pack.");
			$session['user']['gold']-=round(100*$potioncost/100,0);
			set_module_pref('potion', ++$upotion);
			addnav("Continue","healer.php?return=".$ret);
		}
		if ($op == ""){
		if ($session['user']['gold'] >= round(100*$potioncost/100,0) and $upotion<5){
			output("`2Special Potion!");
			output("`3Healing Potion`7 costs `6%s gold`7. You can carry up to 5 with you to heal yourself.`n`n", round(100*$potioncost/100, 0));
			addnav("Specials");
			addnav("Healing Potion","healer.php?op=buyheal&return=".$ret);
		}
		}
	break;
	case "charstats":
		if ($session['user']['alive'] ==1){
			global $SCRIPT_NAME;
			$currentpage=$SCRIPT_NAME;
			$currentpage2 = "";
			$argspq = $_SERVER['argv'];
			for ($i=0;$i<$_SERVER['argc'];$i+=1){
			    if (strchr($argspq[$i],"&c=")) $argspq[$i] = str_replace(strstr($argspq[$i],"&c="),"",$argspq[$i]);
			    $currentpage2.=$argspq[$i];
			}
			if (strstr($currentpage2,"worldmapen")) $currentpage = "runmodule.php?module=worldmapen&op=continue";
			
			if (get_module_pref('restrict')) {
				$restrict = true;
				set_module_pref('restrict', 0);
			} else {
				set_module_pref('restorepage', $currentpage);
				$restrict = false;
			}
	
			global $badguy;
			$upotions = get_module_pref('potion');
			for ($i=0;$i<6;$i+=1){
				if ($upotions > $i){
					if ($badguy['creaturehealth'] > 0 or $session['user']['alive']==0 or strstr($currentpage, "newday") or strstr($currentpage, "bio") or strstr($currentpage, "dragon") or (strstr($currentpage, "runmodule") and !strstr($currentpage, "worldmapen")) or $restrict){
						$potion.="<img src=\"./images/potion.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 20px;\">";
					}else{
						$canuse = true;
						$potion.="<a href=\"runmodule.php?module=potions&op=use\"><img src=\"./images/potion.gif\" title=\"\" alt=\"\" style=\"border: 0px solid ; width: 14px; height: 20px;\"></a>";
					}
				}else{
					$potion.="<img src=\"./images/potionclear.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 20px;\">";
				}
			}
			if ($canuse) {
				addnav("","runmodule.php?module=potions&op=use");
			}
	
			setcharstat("Click and use Items","Healing Potions", $potion);
		}
		break;
	case "dragonkill":
		set_module_pref('potion', 1);
		break;
	}
	return $args;
}

function potions_run(){
	global $session;

	$upotion  = get_module_pref("potion");
	set_module_pref('restrict', 1);
	if (is_module_active('usechow')) {
		set_module_pref('restrict', 1, 'usechow');
	}

	$op = httpget('op');

	page_header("Potion");
	output("`c`b`&Use a Potion`0`b`c`n`n");
	if ($session['user']['hitpoints'] > 0){}else{
		redirect("shades.php");
	}
	$rp = get_module_pref("restorepage");
	$x = max(strrpos("&",$rp),strrpos("?",$rp));
	if ($x>0) $rp = substr($rp,0,$x);
	if (substr($rp,0,10)=="badnav.php" or substr($rp,0,10)=="newday.php"){
		addnav("Continue","village.php");
	}else{
		addnav("Continue",preg_replace("'[?&][c][=].+'","",$rp));
	}
	set_module_pref('potion', $upotion - 1);
	if ($session['user']['hitpoints']==$session['user']['maxhitpoints']){
		output("You down the potion, just as you realize that you didn't need it!`n");
	}else{
		output("You down your potion, and feel the healing progress throughout your body!");
		$session['user']['hitpoints']=$session['user']['maxhitpoints'];
	}
	page_footer();
}

function potions_runevent($type){
        $upotion  = get_module_pref("potion");
        rawoutput("<a><img src=\"./images/potion.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 20px;\"></a>");
        output("`# Lucky Day! You find a healing potion!`n");
        if ($upotion < 5){
        	output("`3You toss the potion in your pocket.`n");
	        set_module_pref('potion',$upotion + 1);
    	}else{
	    	output("`3Too bad you are already carrying your limit!`n");
    	}
}
?>