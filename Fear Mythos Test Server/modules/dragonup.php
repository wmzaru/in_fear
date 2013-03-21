<?php
/**
* Version:      1.1
* Date:         November 19, 2005
* Author:       Kevin Hatfield - Arune http://www.dragonprime.net
* LOGD VER:     Module for 1.1+
*
*/
//require_once("lib/http.php");
function dragonup_getmoduleinfo(){
        $info = array(
                "name"=>"Dragon PowerUp",
               "author"=>"<a href=\"http://logd.ecsportal.com\" target=_new>Kevin Hatfield changes by Oliver Brendel</a>",
                "version"=>"1.1",
                "category"=>"Dragon",
				"vertxtloc"=>"http://www.dragonprime.net/users/khatfield/",
				"download"=>"http://dragonprime.net/users/khatfield/dragonup.zip",
				"description"=>"Allows the ability to randomize dragon encounters.",
                "settings"=>array(
                "Dragon Buff Module Settings,title",
					"dragonone"=>"Dragon #1 Name:,text|Green Dragon of Kemsley",
					"dragononew"=>"Dragon #1 Weapon:,text|Ravaging Claws",
					"dragontwo"=>"Dragon #2 Name:,text|Green Dragon of the Keep",
					"dragontwow"=>"Dragon #2 Weapon:,text|Blinding Poison",
					"dragonthree"=>"Dragon #3 Name:,text|Green Dragon of the North",
					"dragonthreew"=>"Dragon #3 Weapon:,text|Freezing Winds",
					"dragonfour"=>"Dragon #4 Name:,text|Green Dragon of Kemsley",
					"dragonfourw"=>"Dragon #4 Name:,text|Fiery Breath of Despair",
					"dragonfive"=>"Dragon #5 Name:,text|Green Dragon of Lorz",
					"dragonfivew"=>"Dragon #5 Name:,text|Diseased Breath",
                     ),

                );
        return $info;
}
function dragonup_install(){
        if (!is_module_active('dragonup')){
                output("`4Installing Dragon PowerUp Module.`n");
        }else{
                output("`4Updating Dragon PowerUp Module.`n");
        }
        module_addhook("buffdragon");
        return true;
}
function dragonup_uninstall(){
        return true;
}
function dragonup_dohook($hookname,$args){
   global $session;
   if ($hookname == "buffdragon" && $session['user']['dragonkills'] > 1) {
    switch(e_rand(1,5)){
    case 1:
		$dragonname=(get_module_setting('dragonone'));
		$dragonweapon=(get_module_setting('dragononew'));
		$myhp=e_rand(1,301);
		$args['creaturehealth']=($session['user']['hitpoints'] + $myhp);
	break;
	case 2:
		$dragonname=(get_module_setting('dragontwo'));
		$dragonweapon=(get_module_setting('dragontwow'));
		$args['creaturelevel']="17";
		$myhp=e_rand(-145,$session['user']['dragonkills']);
		$args['creaturehealth'] += ($session['user']['dragonkills'] + $myhp);
	break;
	case 3:
		$dragonname=(get_module_setting('dragonthree'));
		$dragonweapon=(get_module_setting('dragonthreew'));
		$args['creaturelevel']="17";
		$args['creatureattack']=max($args['creatureattack']-$session['user']['dragonkills']/2,0); 
	break;
	case 4:
		$dragonname=(get_module_setting('dragonfour'));
		$dragonweapon=(get_module_setting('dragonfourw'));
		$args['creaturehealth']+=($session['user']['dragonkills']);
	break;
	case 5:
		$dragonname=(get_module_setting('dragonfive'));
        $dragonweapon=(get_module_setting('dragonfivew'));
        $args['creaturehealth']+=($session['user']['dragonkills']);
        break;
	}
        $args['creaturename']= $dragonname; 
        $args['creatureweapon']=$dragonweapon;
		output("`4Oh No! It's not the Green Dragon... it is the `\$%s`4 that awaits you inside!",$dragonname);
		debuglog("has fought against $dragonname instead of the Green Dragon.");
   }
   return $args;
}
function dragonup_run(){
}
?>