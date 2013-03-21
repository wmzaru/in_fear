<?php
//	Author:   Robert Riochas
//	Website:  maddrio.com
//	Version:  1.4 updates code

function smallgirl_getmoduleinfo(){
	$info = array(
		"name"=>"Small Girl",
		"version"=>"1.4",
		"author"=>"`2Robert",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?topic=2215.0",
		"description"=>"A small girl is lost the forest",
	);
	return $info;
}

function smallgirl_install(){
	if (!is_module_active('smallgirl')){
		output("`^ Installing Small Girl - forest event `n`0");
	}else{
		output("`^ Up Dating Small Girl - forest event `n`0");
	}
	module_addeventhook("forest","return 100;");
	return true;
}

function smallgirl_uninstall(){
	output("`^ Un-Installing Small Girl - forest event `n`0");
	return true;
}

function smallgirl_dohook($hookname,$args){
	return $args;
}

function smallgirl_runevent($type){
	global $session;
	$op = httpget('op');
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:smallgirl";
	if ($op=="" || $op=="search"){
	output("`n`n`2 You come upon a young girl in the forest.  \"`^ I'm lost can you show me the way back to `3 the Village? `2\" she asks. ");
	output("`n`n`& What will you do?");
	addnav("the small girl");
	addnav("(W) Walk her", $from."op=walk");
	addnav("(L) Leave her there", $from."op=dont");
}elseif ($op=="walk"){
	output("`n`n`2 You walk the small girl to the `3Village`2. ");
	output("`n`n The child is grateful to be back home again and hands you a small shiny rock she said she found in the forest. ");
	$session['user']['specialinc'] = "";
	$session['user']['turns']--;
	if ($session['user']['gems']>=3){
		output("`n`n Looking at the rock you discover that ...`^");
		switch(e_rand(1,10)){
			case 1:
			output(" the shiny rock is a Gem! ");
			$session['user']['gems']+=1;
			debuglog("got `^1 gem `0from a small girl in forest ");
			break;
			case 2: case 3: case 4: case 5: case 6: case 7: case 8: case 9:
			output(" the rock is shiny indeed but there is nothing else special about it. ");
			break; 
			case 10:
			output(" the rock is shiny indeed and as you hold it, a warm feeling goes through your body. ");
			$session['user']['charm']++; 
			$session['user']['hitpoints']++;
			break; 
		}
		output("`n`n`2  You thank her for her kindness and return to the forest. ");
	}else{  
		output(" Looking at the rock you discover that the shiny rock is a Gem! ");
		output("`n`n`2 You thank her for her kindness and return to the forest. ");
		debuglog("got `^1 gem `0from a small girl in forest ");
		$session['user']['gems']++; 
	} 
}else{ 
	output("`n`n`2 Not wanting to take the time to help a small child, you leave her there and continue on your way. ");
	output("`n`n As you walk away, you are hit in the head with a small rock from behind. ");
	output("`n`n You turn and give chase after the little girl but she is too quick and evades your pursuit. ");
	$session['user']['charm']--;
	$session['user']['specialinc'] = "";
	$session['user']['hitpoints'] = round($session['user']['hitpoints']*.95);
}
}

function smallgirl_run(){
}
?>