<?php

function extrav_getmoduleinfo(){
	$info = array(
		"name"=>"Exhausted Traveller",
		"author"=>"Chris Vorndran",
		"version"=>"0.66",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=91",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"Allows for a user to be able to trade their mount upon meeting the person. Yet, the mount is covered until a player leaves or trades, so it is entirely random.",
			"settings"=>array(
				"dkbase"=>"Are tradeable mounts constrained by dk costs,bool|1",
				"mult"=>"Rate at which tradein is evaluated,floatrange,0,1,.02|.66",
				"ch"=>"Chance of encountering the traveller?,range,1,100,1|50",
			),
			"prefs-mounts"=>array(
				"Exhausted Traveller Mount Prefs,title",
				"ist"=>"Is this mount one of the mounts that can be carried by the Exhausted Traveller,bool|0",
			),
		);
	return $info;
}
function extrav_install(){
	module_addeventhook("travel", "return 100;");
	module_addeventhook("forest", "return 100;");
	return true;
	}
function extrav_uninstall(){
	return true;
}
function extrav_runevent($type,$link){
	global $session;

	$from = $link;
	$session['user']['specialinc'] = "module:extrav";
	$op = httpget('op');
// This is to aquire the User's Mount
	$mid = $session['user']['hashorse'];
	$sql = "SELECT mountname,mountcostgems,mountcostgold FROM ".db_prefix("mounts")." WHERE mountid='$mid'";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	$ge = $row['mountcostgems'];
	$go = $row['mountcostgold'];
	$mount = $row['mountname'];
// This is to aquire the Trading Mount
if (get_module_setting("dkbase") == 1){
	$sqla = "SELECT mountname,mountid,mountcostgems,mountcostgold  FROM ".db_prefix("mounts")." INNER JOIN ".db_prefix("module_objprefs")." ON mountid=objid WHERE modulename='extrav' AND objtype='mounts' AND setting='ist' AND value=1 AND mountactive=1 AND mountdkcost <= '".$session['user']['dragonkills']."' ORDER BY RAND(".e_rand().") LIMIT 1";
}else{
	$sqla = "SELECT mountname,mountid,mountcostgems,mountcostgold FROM ".db_prefix("mounts")." INNER JOIN ".db_prefix("module_objprefs")." ON mountid=objid WHERE modulename='extrav' AND objtype='mounts' AND setting='ist' AND value=1 AND mountactive=1 ORDER BY RAND(".e_rand().") LIMIT 1";
}
	$resa = db_query($sqla);
	$rowa = db_fetch_assoc($resa);
	$tmount = $rowa['mountname'];
	$tid = $rowa['mountid'];
	$gea = $rowa['mountcostgems'];
	$goa = $rowa['mountcostgold'];
	$gold = round(abs($go-$goa)*get_module_setting("mult"));
	$gems = round(abs($ge-$gea)*get_module_setting("mult"));

	switch ($op){
		case "":
		case "search":
			if ($mount != NULL && $tmount != NULL && e_rand(1,100) < get_module_setting("ch")){
				output("`2You stumble into a clearing, and looks left to right.");
				output("A small rustling is heard in the bushes, and your eyes dart.");
				output("Out from the bushes, a tall man walks, with an animal to his left.");
				output("Looking upon the animal, you notice that it has a blanket over it's back, and the man begins to speak.`n`n");
				output("\"`#This ere be my faithful mount.");
				output("It has always served me in battle... and I trust that it shall serve you well...");
				output("That is, if ye are willing to trade.");
				output("Ya know, because you never know if this mount will come in handy, over that one ya got there.`2\"");
				output("He points directly at your `^%s`2.`0",$mount);
				addnav("Choices");
				addnav("Trade",$from."op=trade");
				addnav("Depart",$from."op=depart");
			}else{
				$session['user']['specialinc'] = "";
				output("`2You wander into a clearing... but realize that you left your keys behind.");
				output("So, you depart, feeling no need to look around.");
				output("You can hear the soft neighing of a creature in the bushes, but you disregard it.");
			}
			break;
		case "trade":
			$session['user']['specialinc'] = "";
		if ($tmount != NULL && $tid != $mid){
				output("`2The tall man nods, and unveils the animal.");
				output("It turns out to be a `^%s`2.",$tmount);
				output("He looks at you, \"`#Tis a foine beast ye got ere...`2\"");
				output("He lets the beast over to you, and then takes your `^%s `2and departs into the forest.",$mount);
				output("You look at your new creature and shrug... departing into the forest.");
				$session['user']['hashorse'] = $tid;
				output("Your `^old mount `2canters off to Merick's Stables, needing much rest...`0");
			if ($go > $goa){
				$session['user']['gold']+=$gold;
				$goldtext = sprintf("`2gives you `^%s `2gold",$gold);
			}else{
				$session['user']['gold']-=$gold;
				$goldtext = sprintf("`2takes `^%s `2gold from you",$gold);
			}
			if ($ge > $gea){
				$session['user']['gems']+=$gems;
				$gemstext = sprintf("`2gives you `5%s `2gems",$gems);
			}else{
				$session['user']['gems']-=$gems;
				$gemstext = sprintf("`2takes `5%s `2gems from you",$gems);
			}
			if ($session['user']['gold'] < 0){
				$session['user']['gold']=0;
				$goldtext = sprintf("tries to take `^%s `2gold, but sees that you have none more to give",$gold);
			}
			if ($session['user']['gems'] < 0){
				$session['user']['gems']=0;
				$gemstext = sprintf("tries to take `5%s `2gems, but sees that you have none more to give",$gems);
			}
			output("`n`n`2The man runs back, %s, and %s.",$goldtext,$gemstext);
			output("\"`#This is to pay for that mount you gave me.`2\"");
		}else{
			output("`2The man looks at you and then pulls the tarp off.");
			output("There is no mount underneath it... and he smiles.");
			output("\"`#Tis good, no? I wish to become a magician...");
			output("I really had you going there for a second`2\" he smiles.");
			output("You shake your fist at him and depart.");
		}
			break;
		case "depart":
			$session['user']['specialinc'] = "";
			output("`2The man looks at you walk off...");
			output("\"`#You will be regretting this...`2\"");
			output("You look back, and watch him stumble over a rock, and the animal's tarp comes off.");
			output("You can see that what he wanted to trade, was a `^%s`2.`0",$tmount);
			break;
	}
}
?>