<?php

function caricature_getmoduleinfo(){
	$info = array(
		"name"=>"Cory's Caricatures",
		"author"=>"Chris Vorndran<br>`6Idea by: `@cory",
		"version"=>"1.0",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1044",
		"settings"=>array(
			"Caricature Settings,title",
				"buff-rounds"=>"How many rounds does the caricature buff last?,int|50",
				"buff-strength"=>"How strong is the defensive buff?,floatrange,1,2,.05|1.3",
				"dks"=>"How many DKs does the caricature last?,int|2",
				"gold"=>"How much gold does a caricature cost?,int|20",
				"This value is multiplied by (Dragonkills/dks-last) then the user's charm is added.,note",
				"Example: A character with 50 charm with 3 DKs will have (gold*1.5)+50.,note",
				"hof"=>"Display a Hall of Fame page?,bool|1",
				"caricatureloc"=>"Where is the Caricature Shop,location|".getsetting("villagename",LOCATION_FIELDS),
			),
		"prefs"=>array(
			"Caricature Preferences,title",
				"has"=>"Does this user have a caricature?,bool|0",
				"dks-since"=>"How many DKs has this user experienced since their caricature was made?,int|0",
				"cost"=>"How much did this user's caricature cost?,int|0",
			),
		);
	return $info;
}
function caricature_install(){
	module_addhook("village");
	module_addhook("changesetting");
	module_addhook("dragonkilltext");
	module_addhook("footer-hof");
	module_addhook("newday");
	return true;
}
function caricature_uninstall(){
	return true;
}
function caricature_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "village":
			if ($session['user']['location'] == get_module_setting("caricatureloc")){
				tlschema($args['schemas']['marketnav']);
				addnav($args['marketnav']);
				tlschema();
				addnav(" Cory's Caricatures","runmodule.php?module=caricature&op=enter");
			}
			break;
		case "changesetting":
			if ($args['setting'] == "villagename"){
				if ($args['old'] == get_module_setting("caricatureloc")){
					set_module_setting("caricatureloc",$args['new']);
				}
			}
			break;
		case "dragonkilltext":
			if (get_module_pref("dks-since") >= get_module_setting("dks")){
				output("`@You look down and notice that your caricature has been burned.");
				output("Perhaps you should go and get a new one.`n");
				set_module_pref("has",0);
				set_module_pref("dks-since",0);
				set_module_pref("cost",0);
			}else{
				increment_module_pref("dks-since",1);
			}
			break;
		case "footer-hof":
			if (get_module_setting("hof")){
				addnav("Warrior Rankings");
				addnav("Best Caricature","runmodule.php?module=caricature&op=hof");
			}
			break;
		case "newday":
			if (get_module_pref("has")){
				output("`n`@You awaken and pull out your caricature.");
				output("Instantly, you begin to feel your resolve bolster.`n`0");
				apply_buff("caricature",array(
					"name"=>"`@Caricature`0",
					"rounds"=>get_module_setting("buff-rounds"),
					"roundmsg"=>"`@Looking upon your caricature, your defense is bolstered.`0",
					"wearoff"=>"`\$The aura from your caricature begins to diminish, as does your defense.`0",
					"defmod"=>get_module_setting("buff-strength"),
					"schema"=>"module-caricature",
				));
			}
			break;
		}
	return $args;
}
function caricature_run(){
	global $session;
	$op = httpget('op');
	$gold = (get_module_setting("gold")*($session['user']['dragonkills']/get_module_setting("dks"))+$session['user']['charm']);
	page_header("Cory's Caricatures");
	
	switch ($op){
		case "enter":
			output("`#Walking down to the edge of town, you notice a tiny shop sitting in the far corner.");
			output("Having see the shop before, but never having gone inside, you decide that today is a good day.");
			output("The shopkeeper greets you warmly and takes a second to look you over.`n`n");
			if (get_module_pref("has")){
				output("\"`@I'm sorry, but it seems that you already have one of my caricatures,`#\" Cory says.");
				output("\"`@But, do return once the effects have worn off. I will gladly make you a new one.`#\"");
			}else{
				output("`#Clasping his hands together, Cory takes you into the back room.");
				output("You notice the many pieces of art hanging from the walls of the shop.");
				output("Cory says, \"`@In order to get a caricature of your own, you must pay a fee of `^%s `@gold.`#\"",$gold);
				addnav(array("Caricature (%s Gold)",$gold),"runmodule.php?module=caricature&op=paint");
			}
			break;
		case "paint":
			output("`#His lips part and his teeth begin to beam in an amazing smile.");
			output("He reaches into his napsack and removes a brush, canvas and an easel.");
			output("Cory's face begins to get flecked with paint, as the brush dances across the canvas.");
			output("After a couple of minutes, he turns it around to let you look at it.");
			if ($session['user']['gold'] >= $gold){
				output("`#He hands over the caricature to you, then he reaches into your pouch and extracts `^%s `#gold.",$gold);
				output("\"`@Twas a pleasure doing business with ye,`#\" he says and smiles.");
				$session['user']['gold'] -= $gold;
				set_module_pref("cost",$gold);
				set_module_pref("has",1);
			}else{
				output("`#His eyebrow arches in a terrible fashion, as you don't have enough gold.");
				output("He flings his brush at you and yells, \"`@Get out of my shop and come back with the money.`#\"");
			}
			break;
		case "hof":
			// Top 10 Listing
			$mu = db_prefix("module_userprefs");
			$ac = db_prefix("accounts");
			$sql = "SELECT $ac.name, ($mu.value+0) AS cost
					FROM $ac
					INNER JOIN $mu ON $ac.acctid=$mu.userid
					WHERE ($mu.setting = 'cost' AND $mu.modulename = 'caricature' AND $mu.value > '0')
					ORDER BY cost DESC LIMIT 10";
			$result = db_query($sql);
			$rank = translate_inline("Rank");
			$name = translate_inline("Name");
			$cost = translate_inline("Cost");
			output("`b`c`@Top 10 Greatest Caricatures in the Land`c`b`n`n");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' width='75%' align='center' bgcolor='#999999'>");
			rawoutput("<tr class='trhead'><td>$rank</td><td>$name</td><td>$cost</td></tr>");
			if (db_num_rows($result)>0){
				$i = 0;
				while($row = db_fetch_assoc($result)){
					if ($row['name']==$session['user']['name']){
						rawoutput("<tr class='trhilight'><td style='text-align:center;'>");
					}else{
						rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td style='text-align:center;'>");
					}
					$j = $i+1;
					output_notl("$j.");
					rawoutput("</td><td style='text-align:center;'>");
					output_notl("`&%s`0",$row['name']);
					rawoutput("</td><td style='text-align:center;'>");
					output_notl("`c`b`Q%s`c`b`0",$row['cost']);
					rawoutput("</td></tr>");
					$i++;
				}
			}
			rawoutput("</table>");
			break;
	}
	if ($op == "hof"){
		addnav("Return to HoF","hof.php");
	}else{
		villagenav();
	}
page_footer();
}
?>