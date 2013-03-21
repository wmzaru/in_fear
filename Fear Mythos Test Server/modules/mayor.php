<?php
/* Mayors House  29April2005
   Author: Robert of Maddrio dot com
   Converted from 097 for use with v1.0
   This WILL expand in future updates
*/
require_once("lib/commentary.php");
require_once("lib/villagenav.php");
//tlschema("mayor");

function mayor_getmoduleinfo(){
	$info = array(
	"name"=>"Mayors House",
	"version"=>"1.2",
	"author"=>"`2Robert",
	"category"=>"Village",
	"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=803",
	"settings"=>array(
		"Mayors House - Settings,title",
		"where"=>"Where is the Mayors House located?,location|".getsetting("villagename", LOCATION_FIELDS),
		"clerk"=>"What Race is the town clerk?,|Troll",
		"clerkname"=>"What is the town clerk name?,|Teddy",
		"forumurl"=>"URL for this games LotGD forum?,|http://www.mywebsite.com/forum",
		"notice1"=>"Have a town notice to post?,| none at the moment - all is well",
		"notice2"=>"Have a second notice to post?,| none at the moment - all is well",
		"staff"=>"Main Admin for this game?,|Admin Robert ",
		"staff1"=>" Admin for this game?,|Admin:  ",
		"staff2"=>" Moderators for this game?,|Moderators:  ",
		),
	);
	return $info;
}

function mayor_install(){
	if (!is_module_active('mayor')){
		output("`^ Installing module Mayor's House `n");
	}else{
		output("`^ Up Dating module Mayor's House`n");
	}
	module_addhook("village");
	module_addhook("moderate");
    return true;
}
function mayor_uninstall(){
	output("`n`Q Uninstalling Mayor's House Module.`n");
	return true;
}

function mayor_dohook($hookname,$args){
	switch($hookname){
	case "newday-runonce":
		$key = array_rand($vloc);
		set_module_setting('place',$key);
		break;
	case "village":
		$where = get_module_setting('where');
		tlschema($args['schemas']['marketnav']);
		addnav($args['marketnav']);
		tlschema();
		addnav("Mayor's House","runmodule.php?module=mayor");
		break;
	case "moderate":
		$args['mayor'] = 'Mayors House';
		break;
    }
return $args;
}

function mayor_run(){
    global $session;
    $op = httpget('op');
    $from = "runmodule.php?module=mayor&";
    $clerkrace = get_module_setting("clerk");
    $clerkname = get_module_setting("clerkname");
    $forumurl = get_module_setting("forumurl");
    $notice1 = get_module_setting("notice1");
    $notice2 = get_module_setting("notice2");
    $staff = get_module_setting("staff");
    $staff1 = get_module_setting("staff1");
    $staff2 = get_module_setting("staff2");
    page_header("Mayors House");
    output("`c<font size='+1'>`3 Mayors House</font>`c`0",true);
    addnav(" exit ");
    addnav("(R) Return to Village","village.php");
    addnav(" Mayors House ");
    addnav("(1) Staff List",$from."op=staff");
    
if ($op==""){
output("`n`n`2 You walk a path that leads up a small Hill, overlooking the village is the Mayors House. ");
output(" There is flowerbeds and topiary hedges along the way.`n`n");
output(" Upon entering the Mayor's House you find a place for towns people and Dragon Slayer's ");
output(" to tell of the trouble's they encounter within the Village or Forest `n`n");
output(" You notice the clerk behind the counter is a %s. `n",$clerkrace);
output(" %s the %s say's you also can make a *`q Petition for Help `2* elsewhere if public viewing is not best. ",$clerkname,$clerkrace);
output("  He also suggests that you read the `q MoTD `2 for the latest realm info.`n`n");
output("`q Town Notice: `#The forum for this LotGD is found at: %s `n`n",$forumurl);
output("`# Town Notice 1: `# %s `n`n",$notice1);
output("`# Town Notice 2: `# %s `n`n",$notice2);
addcommentary();
output("`n`b`^As you approach the clerk to report a game error...`b`n");
viewcommentary("mayor","`^Found a problem?`3",20,"reports");
}
if ($op=="staff"){ 
output("`n`^ You inquire about the Admin for this game, `n the clerk says the following are on staff here, `n`n`0 %s `n %s `n %s ",$staff,$staff1,$staff2);
addnav("(2) to front office",$from."op=");
}

page_footer();
}
?>