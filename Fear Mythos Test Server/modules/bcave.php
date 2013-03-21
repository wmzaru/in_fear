<?php
#  Brimstone Cave  19April2005
#  Author: Robert of Maddrio.com and Talisman of DragonPrime.net
#  Something to amuse players while dead
#  Version 1.1 added settings, updated 3July2006

function bcave_getmoduleinfo(){
	$info = array(
	"name"=>"Brimstone Cave",
	"version"=>"1.1",
	"author"=>"`2Robert`7/`4Talisman",
	"category"=>"Graveyard",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
		"settings"=>array(
			"Brimstone Cave - Settings,title",
			"who1"=>"first - 'who' wrote their name on the wall?,|Sir Robert of Camelot",
			"who2"=>"second - 'who' wrote their name on the wall?,|the Talisman",
			"message"=>"write your own message on the wall,|tis a sad day to die",
		),
	);
	return $info;
}

function bcave_install(){
	if (!is_module_active('bcave')){
		output("`^ Installing Brimstone Cave `n`0");
	}else{
		output("`^ Up Dating Brimstone Cave `n`0");
	}
	module_addhook("shades");
	return true;
}
function bcave_uninstall(){
	return true;
}

function bcave_dohook($hookname,$args){
	switch($hookname){
	case "shades":
		addnav("Places");
		addnav("Brimstone Cave","runmodule.php?module=bcave");
		break;  
	}
return $args;
}

function bcave_run(){
	global $session;
	$op = httpget('op');
	$from = "runmodule.php?module=bcave&";
	page_header("Brimstone Cave");
	rawoutput("<font size='+1'>");
	output("`c`4 Brimstone Cave `c`0");
	rawoutput("</font>");
	addnav(" things to do ");  
	addnav("(1) Finger Wrestle",$from."op=wrestle");  
	addnav("(2) Grab Shovel",$from."op=shovel");  
	addnav("(3) Flip a coin",$from."op=coin");  
	addnav("(4) Hum a tune",$from."op=hum");  
	addnav("(5) Pick up Rocks",$from."op=rocks"); 
	addnav("(6) Read etchings",$from."op=walls"); 
	addnav("(7) Read another etching",$from."op=another");
	addnav("(8) Sit Down",$from."op=sit"); 
	addnav("(9) Walk Around",$from."op=walk"); 
	addnav(" exit cave ");
	addnav("(R) Return to Shades","shades.php");
	if ($op==""){
	output("`n`n You wander around the Graveyard and come upon a Brimstone Cave. "); 
	output("`n Bored out of your mind you decided to enter this place and look around.  ");
}
if ($op=="walk"){ output("`n You are bored and decide to walk in circles, now you are dizzy. ");
}
if ($op=="sit"){
	    output("`n You see a large stone that is shaped like a chair and decide to sit down. `n");
	    output(" As you sit, it seems to be quite comfortable. You drift to sleep and fall out of your chair. ");
}
if ($op=="rocks"){ output("`n Seeing many colored rocks on the ground, you decide to pick some up and look at them. ");
}
if ($op=="hum"){ output("`n This place reminds you of one of your favorite tune's, you start humming it. ");
}
if ($op=="wrestle"){ 
	output("`n You fold the fingers of both hands together and prepare for the battle of the wrestling thumbs! ");
	output(" You WIN!!!!  Pity there's no prize. Shortly afterward, this little game of yours becomes boring. ");
}
if ($op=="coin"){ output("`n You take a coin from your pocket and begin to flip it in the air - calling out HEADS or TAILS, you tire easy from this little game of yours! ");
}
if ($op=="walls"){
	output("`n You look upon a wall and notice someone etched into it . . ");
	switch(e_rand(1,7)){ 
		case 1: output("`%\"beware of Gorlock\" "); break;
		case 2: output("`%for a good time call: 555-3525 "); break;
		case 3: output("`%LotGD Rocks! "); break;
		case 4: output("`%I would rather be alive than be here "); break;
		case 5: output("`% %s was here ",get_module_setting("who1")); break;
		case 6: output("`% %s was here ",get_module_setting("who2")); break;
		case 7: output("`% %s ",get_module_setting("message")); break;
	}
}
if ($op=="another"){
	output("`n As you near another wall, you can make out what appears to be a head looking over a wall, ");
	output(" next to the words \"`&Kilroy wuz here\". ");
}
if ($op=="shovel"){
	output("`n You found an old shovel near a small pit that someone else dug. ");
	output(" Seeing how hard the soil is here, you give up any effort to continue digging this hole.");
}
page_footer();
}
?>