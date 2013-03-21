<?php

/*
 * Title:       Random Forest Healer
 * Date:	Aug 01, 2004
 * Version:	1.1
 * Author:      Joshua Ecklund
 * Email:       m.prowler@cox.net
 * Purpose:     Add a random event to the forest which will randomly choose to:
 *              1) Heal the player for random number of hitpoints
 *              2) Take away a random number of hitpoints
 *              3) Take away a forest fight if player is fully healed
 *
 * --Change Log--
 *
 * Date:    	Jul 30, 2004
 * Version:	1.0
 * Purpose:     Initial Release
 *
 * Date:        Aug 01, 2004
 * Version:     1.1
 * Purpose:     Various changes/fixes suggested by JT Traub (jtraub@dragoncat.net)
 *
 */

function forestheal_getmoduleinfo(){
	$info = array(
		"name"=>"Random Forest Healer",
		"version"=>"1.1",
		"author"=>"Joshua Ecklund",
		"category"=>"Forest Specials",
                "download"=>"http://dragonprime.net/users/mProwler/forestheal.zip",
		"settings"=>array(
			"Forest Healer Event Settings,title",
                        "percenthurt"=>"Chance to get hurt,range,0,100,1|10"
                )
	);
	return $info;
}

function forestheal_install(){
	module_addeventhook("forest", "return 100;");
	return true;
}

function forestheal_uninstall(){
	return true;
}

function forestheal_dohook($hookname,$args){
	return $args;
}

function forestheal_runevent($type)
{
	global $session;

	$chance = get_module_setting("percenthurt");
	$roll = e_rand(1, 100);

	if ($roll <= $chance && $session["user"]["hitpoints"] > 1) {

		output("`n`%You encounter an `)`bEVIL`b `%healer in the forest.  She looks at you, then runs away into the forest.`n`n");
                $takehit = e_rand(1,$session["user"]["hitpoints"]-1);
                $session["user"]["hitpoints"] -= $takehit;
                output("`\$Your health is reduced by `&$takehit`$ points.`n`&");

	} else {

        	output("`n`%You encounter a healer in the forest.  She looks at you, then runs away into the forest.`n`n");

                if ($session["user"]["maxhitpoints"] - $session["user"]["hitpoints"] > 0) {
	                $takehit = e_rand(0,$session["user"]["maxhitpoints"] - $session["user"]["hitpoints"]);
                        if ($takehit < 1) {
                                output("`^You feel no healthier than you did before.`n");
                        } else {
		                $session["user"]["hitpoints"] += $takehit;
        		        output("`^You are healed for `&$takehit `^points.`n");
                        }
                } else {
                	output("`^You were already at full health.`nYou notice one of your forest fights is gone.`n");
                        $session["user"]["turns"]--;
                }
	}
}

function forestheal_run(){
}
?>
