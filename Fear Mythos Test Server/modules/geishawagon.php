<?php

function geishawagon_getmoduleinfo(){
	$info = array(
		"name"=>"Geisha Wagon",
		"version"=>"1.0",
		"author"=>"`6Harry B and Kenny Chu",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/users/Harry%20B/geishawagon.zip",
	);
	return $info;
}

function geishawagon_install(){
	module_addeventhook("forest", "return 100;");
	return true;
}

function geishawagon_uninstall(){
	return true;
}

function geishawagon_dohook($hookname,$args){
	return $args;
}

function geishawagon_runevent($type)
{
	require_once("lib/increment_specialty.php");
	global $session;
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:geishawagon";

	$op = httpget('op');
	if ($op=="" || $op=="search"){
		output("`n`2 You encounter a traveling wagon full of Geisha. They stop and giggle. ");
		output("`n`n\"`^For a gem, we can give you our service`6\", says a lovely Geisha.");
		output("`n`n`& What will you do?");
		addnav("Give a gem", $from."op=give");
		addnav("Don't pay - leave!", $from."op=dont");
	}elseif ($op=="give"){
		$session['user']['specialinc'] = "";
		if ($session['user']['gems']>0){
		output("`n`n`2 You dig out of your gems and hand it to the Geisha.");
		output("`n`n She thanks you and says to step into the wagon.");
		output("`n`n You step into the wagon to discover `2");
		$session['user']['gems']--;
		debuglog("gave 1 gem to the geisha girls");
		switch(e_rand(1,8)){
		case 1: case 2:
			output(" that these girls are most professional and have satisfied your every desire.");
			output(" `n`n You feel strong as you head back to the forest");
			$session['user']['turns']+=10;
			$session['user']['hitpoints']+=10;
			break;
		case 3: case 4:
			output(" these girls are not only pretty and professional, they give souvenirs also!");
			output(" `n`n You take the scented undies and sell it to a wandering farmboy for some gems!");
			$session['user']['gems']+=5;
			debuglog(" sold geisha undies for some gems");
			break;
		case 5: case 6:
			output(" these girls are not only pretty and professional, they give potions also!");
			output(" `n`n Drinking the potion you feel much stronger!");
			$session['user']['maxhitpoints']+=5;
			$session['user']['hitpoints']+=5;
			break;
		case 7: case 8:
		output(" these girls are not only pretty and professional, they give magic potions also!");
		output(" `n`n Drinking the potion you feel much wiser!");
			increment_specialty("`^");
			increment_specialty("`^");
			increment_specialty("`^");
			break;
			}
		}else{
		output("`n`n`2You reach into your pouch and realize you have not enough to pay.");
		output("`n`n The Geisha looks sadly upon you and takes out her pouch and gives a few gems.");
		output("`n`n Accepting her kindness, you promise to pay back as soon as you can. ");
		$session['user']['gems']+=2;
		}
		output("`0");
	}else{
		output("`2 You are too tired to be pleasured today, you humbly excuse yourself and leave. ");
		output("`n`n You feel like you did the right thing. ");
		$session['user']['charm']+=5;
		$session['user']['specialinc'] = "";
	}
}

function geishawagon_run(){
}
?>  