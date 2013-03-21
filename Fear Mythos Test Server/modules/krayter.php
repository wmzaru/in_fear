<?php
function krayter_getmoduleinfo(){
	$info = array(
"name"=>"City - Krayter",
"version"=>"1.1",
"author"=>"`2Shane`0, for <a href='http://lotgd-aljerer.com.au/' target='_blank'>`i`5A`vr`Va`5b`ve`Vl`5l`ve`i</a>",
"category"=>"Cities",
"settings"=>array(
	"Krayter - Main Settings,title",
"name"=>"City Name:,text|Krayter",
"desc"=>"City Description:,textarea|`\$`c`@`bThe village of Krayter`b`@`c`n`n`2 The residents of Krayter busy themselves with various tasks of everyday life.  Small farm animals scurry underfoot as you make your way through the sparce buildings.`n`nMost of the villagers of Krayter seem to stare at you.  Unsure of who you are, they appear quite suspious of your intent.  Some stop what they are doing just to watch you pass by.`n`nYou get the feeling as if you are quite out of place here.`n`n",
"clock"=>"Text to display tell user of the time:,text|`7A small sundial in the center of the village tells you that the time is `&%s`7.`n",
"`^Use `@%s `^for the time.,note",
"dks"=>"DKs needed to access this city:,int|0",
"sayline"=>"Village Say-Line:,text|says",
"talk"=>"Talk(appears above commentary):,text|`n`@Nearby some villagers talk:`n",
	"Krayter - Location Settings,title",
"forest"=>"Link to the forest?,bool|1",
"lodge"=>"Link to the lodge?,bool|1",
"clan"=>"Link to the clans?,bool|1",
"bank"=>"Link to the bank?,bool|1",
"weapons"=>"Link to the weapons shop?,bool|1",
"armor"=>"Link to the armor shop?,bool|1",
"pvp"=>"Link to pvp?,bool|1",
"`#More locations may be added in with later versions.,note",
	"Krayter - External Modules Settings,title",
"`^The following will do nothing if the appropriate modules are not installed.,note",
"`^If you do not have the module then do not worry about it.,note",
"house"=>"Block Housing?{`^house.php`0},bool|",
"dwellings"=>"Block Dwellings?{`^dwellings.php`0},bool|",
"cities"=>"Block Mutliple Cities?{`^cities.php`0},bool|",
"vote"=>"Block Player Votes?{`^vote.php`0},bool|1",
"`#More modules may be supported in later versions.,note",
),
"prefs"=>array(
	"Krayter City Prefs,title",
"lastcity"=>"Users Last City:,text|",
"For Returning to last village/city.,note",
),
);
return $info;
}
function krayter_install(){
	module_addhook("villagetext");
	module_addhook("village");
	module_addhook("travel");
	module_addhook("validlocation");
	module_addhook("moderate");
	module_addhook("changesetting");
	module_addhook("pvpstart");
	module_addhook("pvpwin");
	module_addhook("pvploss");
	module_addhook("mountfeatures");
	module_addhook("charstats");
return true;
}
function krayter_uninstall(){
	$homevil = getsetting("villagename", LOCATION_FIELDS);
	$krayter = get_module_setting("name");
	$sql = "UPDATE ".db_prefix("accounts")." SET location = '$homevil' WHERE location = '$krayter'";
	db_query($sql);
return true;
}
function krayter_dohook($hookname,$args){
	global $session,$resline;
	$city = get_module_setting("name");
	switch($hookname){
case "pvpwin":
	if ($session['user']['location'] == $city) {
		$args['handled']=true;
		addnews("`4%s`3 defeated `4%s`3 in fair combat near the campfire in %s.", $session['user']['name'],$args['badguy']['creaturename'], $args['badguy']['location']);
	}
break;
case "pvploss":
	if ($session['user']['location'] == $city) {
		$args['handled']=true;
		addnews("`%%s`5 has been slain while attacking `^%s`5 near the campfire in `&%s`5.`n%s`0", $session['user']['name'], $args['badguy']['creaturename'], $args['badguy']['location'], $args['taunt']);
	}
break;
case "pvpstart":
	if ($session['user']['location'] == $city) {
		$args['atkmsg'] = "`4You wander through the village gates, to where a large group of warriors are singing around the campfire. At the edges of the scrub, in the darkness and away from the others, some foolish warriors have bedded down for the night...`n`nYou have `^%s`4 PvP fights left for today.`n`n";
		$args['schemas']['atkmsg'] = 'module-krayter';
	}
break;
case "changesetting":
	// Ignore anything other than villagename setting changes
	if ($args['setting']=="villagename" && $args['module']=="krayter") {
		if ($session['user']['location'] == $args['old']) {
			$session['user']['location'] = $args['new'];
		}
		$sql = "UPDATE " . db_prefix("accounts") . " SET location='" .
			$args['new'] . "' WHERE location='" . $args['old'] . "'";
		db_query($sql);
	}
break;
case "validlocation":
	$args[$city]="village-krayter";
break;
case "moderate":
	if (is_module_active("cities")) {
		tlschema("commentary");
		$args["village-krayter"]=sprintf_translate("%s Village", $city);
		tlschema();
	}
break;
case "villagetext":
	if ($session['user']['location'] == $city){
		$desc = get_module_setting("desc");
		$clock = get_module_setting("clock");
		$sayline = get_module_setting("sayline");
		$talk = get_module_setting("talk");
		$args['text']=$desc;
		$args['schemas']['text'] = "module-citykrayter";
		$args['clock']=$clock;
		$args['schemas']['clock'] = "module-citykrayter";
			if (is_module_active("calendar")) {
				$args['calendar'] = "`n`2Overheard whispers suggest that it is `&%s`2, `&%s %s %s`2.`n";
				$args['schemas']['calendar'] = "module-citykrayter";
			}
		$args['title']=array("%s Village", $city);
		$args['schemas']['title'] = "module-krayter";
		$args['sayline']=$sayline;
		$args['schemas']['sayline'] = "module-krayter";
		$args['talk']=$talk;
		$args['schemas']['talk'] = "module-krayter";
		$args['newest'] = "";
	
		//block all the multicity navs and modules. configure as needed for your server
			if (get_module_setting("lodge") == 0){
		blocknav("lodge.php");
			}
			if (get_module_setting("weapons") == 0){
		blocknav("weapons.php");
			}
			if (get_module_setting("armor") == 0){
		blocknav("armor.php");
			}
			if (get_module_setting("clan") == 0){
		blocknav("clan.php");
			}
			if (get_module_setting("pvp") == 0){
		blocknav("pvp.php");
			}
			if (get_module_setting("forest") == 0){
		blocknav("forest.php");
			}
			if (get_module_setting("bank") == 0){
		blocknav("bank.php");
			}
			
			if (get_module_setting("house") == 1){
		blockmodule("house");
			}
			if (get_module_setting("dwellings") == 1){
		blockmodule("dwellings");
			}
			if (get_module_setting("cities") == 1){
		blockmodule("cities");
			}
			if (get_module_setting("vote") == 1){
		blockmodule("vote");
			}
			
			$args['schemas']['newest'] = "module-krayter";
			$args['gatenav']="Village Gates";
			$args['schemas']['gatenav'] = "module-krayter";
			$args['fightnav']="Nearby Forest";
			$args['schemas']['fightnav'] = "module-krayter";
			$args['marketnav']="Village Market";
			$args['schemas']['marketnav'] = "module-krayter";
			$args['tavernnav']="Drunkard's Lane";
			$args['schemas']['tavernnav'] = "module-krayter";
			$args['section']="village-krayter";
			$args['infonav']="Village Council";
			$args['schemas']['infonav'] = "module-krayter";
		}
break;
case "village":
	global $playermount;
		if ($session['user']['dragonkills'] >= get_module_setting("dks")){
	if ($session['user']['location']==$city){
		tlschema($args['schemas']['gatenav']);
		addnav($args['gatenav']);
		tlschema();
		addnav(array("Travel to %s",get_module_pref("lastcity")),"runmodule.php?module=krayter&op=travel&travel=from");
	}else{
		tlschema($args['schemas']['gatnav']);
		addnav($args['gatenav']);
		tlschema();
		addnav(array("%s",get_module_setting("name")),"runmodule.php?module=krayter&op=travel&travel=to");
	}
		}
break;
	}
return $args;
}
function krayter_run(){
	global $session;
	page_header("Travel");
	$op=httpget('op');
	$travel=httpget('travel');
if ($op=="travel"){
	output("`@You decide you would like to leave %s`@.",$session['user']['location']);
	addnav("Continue","village.php");
	if ($travel=="from"){
		$session['user']['location'] = get_module_pref("lastcity");
		output("`@You grab your bags and head out. Eventually, after a long journey, you find yourself in the city of %s`@. You head for the village square and somewhere to put your bags!",$session['user']['location']);
	}
	if ($travel=="to"){
		set_module_pref('lastcity',$session['user']['location']);
		$session['user']['location'] = get_module_setting("name");
		output("`@You grab your bags and head out. Eventually, after a long journey, you find yourself in the city of %s`@. You head for the village square and somewhere to put your bags!",$session['user']['location']);
	}
}
page_footer();
}
?>