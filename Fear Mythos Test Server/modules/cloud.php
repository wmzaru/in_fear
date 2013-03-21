<?php

//Idea and programming
//Morpheus aka Apollon & Lilith
//2005 for logd.at(LoGD 0.9.7 +jt ext (GER) 3)
//Programming for 1.0.X BansheeElhayn
//Translation by Apollon
//Mail to Morpheus@magic.ms or Apollon@magic.ms or Agent.Herby@web.de
//Dedicated to my beloved little flower
//Thanks to Elessa for helping me with the translation

function cloud_getmoduleinfo(){
	$info = array(
		"name"=>"The cloudisland",
		"version"=>"1.0",
		"author"=>"Apollon und BansheeElhayn",
		"category"=>"Castle",
		"download"=>"http://dragonprime.net/",
		"requires"=>array(
		"castle"=>"1.2 by Apollon",
		),
	);
	return $info;
}
function cloud_install(){
		output("Installing cloudisland.");
		module_addhook("castle");
		module_addhook("castle_desc");
		module_addhook("moderate");
		return true;
}

function cloud_uninstall(){
	output("Uninstall Cloudisland.");
	return true;
}

function cloud_dohook($hookname, $args){
	global $session;
	switch($hookname){
		case ("castle"):
		addnav("To the Castle's Garden","runmodule.php?module=cloud");
		break;
		case ("castle_desc"):
		output("A small path leads from the castle yard to the gardens which are well tended by the groundskeepers.");
		output("The pathways are lined with blooming flowers and trees. At the end of the longest path lies a pond with a pier into its middle. You are not able to see much because of the fog that always lies on the pond.`n");
		break;
		case "moderate":
		$args['cloud'] = translate_inline("Cloudisland");
		break;
	}
	return $args;
}
function cloud_run(){
	global $session;
	$op=httpget('op');
	require_once "common.php";
	require_once("lib/commentary.php"); 
	page_header("The Castle Garden");
    	output("`c`b`@The castle garden`b`c`n");
	if ($op==""){
        output("`@You walk along the shore of the pond to a small pier that leads into the middle of the pond, which always lies in a thick fog.");
        output("Carefully you walk along the pier to the middle until the fog breaks. You have reached a small island where the weather is always nice and never changes.");
        output("The sky above you is blue, the air is clear");
	switch(e_rand(1,10)){
		case 1:
		output("and the birds are singing songs of happiness. Some little `6fairies `@are doing their dance to their wonderful songs.`n`n");
		break;
		case 2:
		output("and you walk along this wonderful place straight to the `&gazebo`@.`n`n");
		$session['user']['charm']+=1;//some secret award for role players
		break;
		case 3:
		output("and a squirrel crosses your way, looks at you, blinks its friendly eyes and runs to the next tree nearby.`n`n");
		break;
		case 4:
		output("and two `&swans `@in love waddle over the grass towards the water of the pond, stepping in to take a swim.`n`n");
		break;
		case 5:
		output("and a `6mother duck `@leads her children to the pond for their first swimming lesson.`n`n");
		break;
		case 6:
		output("and brilliant butterflies dancing in the air fill it with lively `3c`4o`#l`6o`2u`Qr`\$s`@.`n`n");
		break;
		case 7:
		output("and your  `\$heart `@begins to beat like a drum at the view of this beauty.`n`n");
		$session['user']['charm']+=1;//some secret award for role players
		break;
		case 8:
		output("and you feel like you were `6new born`@ in paradise.`n`n");
		break;
		case 9:
		output("and you think this must be the island of the `^gods`@, such a wonderful and inspiring place .`n`n");
		$session['user']['hitpoints']*=1.01;//some secret award for role players
		break;
		
		case 10:
		output("and you feel absolutely `6soulful `@that you found such a wonderful place.`n`n");
		break;
		
		}
	addnav("Walk");
	addnav("Forward","runmodule.php?module=cloud&op=isel");
	}
	else if ($op=="isel"){
		page_header("The Cloudisland");
		output("`c`b`@The cloudisland`b`c`n");
        	output("In the middle of the island is a gazebo, surrounded by trees which are softly caressed by the wind telling them stories of great love.");
        	output("The shore is made of pure white sand. You have the sensation of walking on clouds as your feet skim its soft surface.`n");
        	output("Everywhere beautiful flowers grow and a small rill makes its way through the pond.  You glimpse small fairies at some places dancing happily.");
        	output("In the distance you see two unicorns standing peacefully together while their young ones are playing funny games.`n`n");
        	output("`2One of the fairies flies to you and reminds you that this is a place for peaceful roleplay only.`n`n");
        	addcommentary();
        	viewcommentary("cloud","Whisper",20,"whispers");
        	addnav("Walk");
        	addnav("Back to the Castle Yard","runmodule.php?module=castle");
        	page_footer();
		}
page_footer();
}
?>