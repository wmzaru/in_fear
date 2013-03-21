<?php

function cookie_getmoduleinfo(){
	$info = array(
		"name"=>"Thalia's Cookie Stand",
		"author"=>"Chris Vorndran",
		"version"=>"1.2",
		"category"=>"Village Specials",
		"download"=>"http://dragonprime.net/users/Sichae/cookie.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
			"prefs"=>array(
				"count"=>"Has user had a cookie today?,bool|0",
			),
		);
	return $info;
}
function cookie_install(){
	module_addeventhook("village","\$count=get_module_pref(\"count\", \"cookie\");return (\$count?0:50);");
	module_addhook("newday");
	return true;
}
function cookie_uninstall(){
	return true;
}
function cookie_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "newday":
			clear_module_pref("count");
			break;
	}
	return $args;
}
function cookie_runevent($type){
	global $session;
	$session['user']['specialinc'] = "module:cookie";
	switch ($type){
		case "village":
			$op = httpget('op');
			$from = "village.php?";
			switch ($op){
				case "":
					if (get_module_pref("count") == 0){
						output("`QIn the air, you smell delectable treats, such soft aromas tingling your nose.");
						output("Your eyes dashing around, you see a small cart, with a beautiful girl standing next to it.");
						output("She looks at you and smiles, \"`6Would you like a free sample?`Q\"");
						addnav("Yes",$from."op=cookie");
						addnav("No, Not Today",$from."op=nope");
					}else{
						$session['user']['specialinc'] = "";
						output("`QThalia arches a brow.");
						output("\"`6Haven't I seen you here before?`Q\"");
						output("She begins to get flustered, and then hurls a stone at your head.");
						output("You dodge it, and then run off screaming.`n`n`0");
					}
					break;
				case "cookie":
					$session['user']['specialinc'] = "";
					set_module_pref("count",1);
					$car = translate_inline(array("chocolate chip","oatmeal","sugar","peanut butter","almond"));
					$cookie = $car[mt_rand(0,4)];
					output("`QThe girl smiles, and introduces herself as Thalia.");
					output("She picks up a %s cookie, and hands it to you.",$cookie);
					output("You stuff it into your mouth and chew on it happily.");
					output("Thalia smirks at you, and then rolls her eyes.");
					output("\"`6Umm %s, you have some on the edge of your mouth...`Q\"", translate_inline($session['user']['sex']==1?"Madam":"Mister"));
					switch (e_rand(1,5)){
						case 1:
							$val = e_rand(1,3);
							output("`QThalia laughs at you, trying to stifle her chuckles.");
							output("You feel quite embarassed, and run off crying.");
							output("She shrugs and returns to eating cookies.");
							output("`n`nYou lose `5%s `QCharm.",$val);
							$session['user']['charm']-=$val;
							debuglog("lost $val charm to embarassment");
							break;
						case 2:
							$val = e_rand(1,4);
							output("`QThalia helps you out, and brushes the crumbs away.");
							output("BUT, one of her nails scratches you across the face.");
							output("`n`nYou lose `\$%s `QHitpoints.",$val);
							$session['user']['hitpoints']-=$val;
							if ($session['user']['hitpoints'] < 0) $session['user']['hitpoints'] = 0;
							debuglog("lost $val hitpoints to nail scratch");
							break;
						case 3:
							$val = e_rand(1,100);
							output("`QThalia reaches over, and brushes the crumbs from your face.");
							output("You feel something moving near your gold bag, but you disregard it.");
							output("Thalia smiles, and then walks back to her cart.");
							if ($session['user']['gold'] >= $val){
								$session['user']['gold']-=$val;
								if ($session['user']['gold'] < 0) $session['user']['gold'] = 0;
								output("`n`n`QYou turn and begin walking... then notice that `^%s `QGold is missing.",$val);
								debuglog("lost $val gold to Thalia the Thief");
							}elseif ($session['user']['gems'] >= 1){
								$session['user']['gems']--;
								output("`n`n`QYou turn and begin walking... then notice that `%1 `QGem is missing.");
								debuglog("lost 1 gem to Thalia the Thief");
							}else{
								$session['user']['charm']++;
								output("`n`n`QYou turn around and begin walking.");
								output("Thalia smiles and says, \"`6Seeya.`Q\"");
								output("Then, you gain `%1 `QCharm, for no reason.");
								debuglog("gained one charm from Thalia");
							}
							break;
						case 4:
							$val = e_rand(1,3);
							$ff = translate_inline($val==1?"Fight":"Fights");
							output("`QThalia sits down and stares at you.");
							output("Her eyes go wide, and then she pops her neck.");
							output("She starts humming a song, \"`6I know a song that gets on everybody's nerves...`Q\"");
							output("Thoroughly annoyed, you stand and depart.");
							output("You feel as if you want to pummel the living snot out of something.");
							output("`n`nYou gain `@%s `QForest %s.",$val,$ff);
							$session['user']['turns']+=$val;
							debuglog("gained $val forest fights from Thalia's Annoying Nature");
							break;
						case 5:
							$val = e_rand(1,3);
							output("`QThalia smiles and looks at you.");
							output("She purses her lips in thought, and then lunges at you with some scissors.");
							output("She quickly grooms your hair, and stands back.");
							output("Looking at you appreciatively, she nods.");
							output("`n`nYou gain `5%s `QCharm.",$val);
							$session['user']['charm']+=$val;
							debuglog("got $val charm from Thalia's Haircut");
							break;
					}
					output("`n`0");
					break;
				case "nope":
					$session['user']['specialinc'] = "";
					output("`QThalia shrugs, and then returns to her stand.");
					output("She furrows her brows, \"`6How could anyone deny a cookie..?`Q\"");
					output("She blows a raspberry and grins, \"`6More for me!`Q\"`n`n");
					break;
			}
		break;
	}
}
?>