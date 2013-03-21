<?php
#  Small Hut  July 2007
#  Author: Robert of maddrio dot com
#  Converted from a 097 forest event

function smallhut_getmoduleinfo(){
	$info = array(
	"name"=>"Small Hut",
	"version"=>"1.0",
	"author"=>"`2Robert",
	"category"=>"Forest Specials",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
	"settings"=>array(
		"Small Hut - forest event Settings,title",
		"minhp"=>"Min hitpoint gain.,range,1,20,1|1",
		"maxhp"=>"Max hitpoint gain.,range,2,50,2|10",
		),
	);
	return $info;
}
function smallhut_install(){
	if (!is_module_active('smallhut')){
		output("`^ Installing Small Hut - forest event `n`0");
	}else{
		output("`^ Up Dating Small Hut - forest event `n`0");
	}
	module_addeventhook("forest","return 100;");
	return true;
}
function smallhut_uninstall(){
	output("`^ Un-Installing Small Hut - forest event `n`0");
	return true;
}
function smallhut_dohook($hookname,$args){
	return $args;
}
function smallhut_runevent($type){
	global $session;
	$min = get_module_setting("minhp");
	$max = get_module_setting("maxhp");
	$amt = (e_rand($min,$max));
	if ($session['user']['sex']==0){ $sex="m'lord"; }else{ $sex="m'lady";}
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:smallhut";
	$op = httpget('op');

	if ($op=="" || $op=="search"){
		output("`n`2 You come upon a small hut.");
		if ($session['user']['hitpoints'] < $session['user']['maxhitpoints']){
			output("`n`n You sense no danger. `n`n`& What will you do? ");
			addnav(" the small hut ");
			addnav("(E) Enter the hut",$from."op=enterhut");
		}else{
			output("`n`2 You are full of vigor and have no time to waste, so you decide to continue your journey. ");
                }
	}elseif ($op=="enterhut"){
		switch(e_rand(1,5)){
			case 1:
			output("`n`2 BAH! This hut is empty and deserted ...for some time by the looks of it. ");
			$session['user']['specialinc'] = "";
			break;
			case 2:
			output("`n`2 Inside you discover bones lying on a mattress made of straw. ");
			output("`n`n The resident of the hut passed away ..you think to yourself. ");
			output("`n`n Looking around, you do not find anything of value. ");
			$session['user']['specialinc'] = "";
			break;
			case 3:
			output("`n`2 Inside you find a Peasant Woman who is startled but gives you a warm smile and says,  ");
			output("`n`n`6 G'day $sex, would you like a cup of Dandelion tea? I am having one myself.  ");
			output(" Please do sit down and have a cup of tea with me before you go. Will you?  ");
			addnav("(D) Drink some tea",$from."op=drinktea");
			$session['user']['specialinc'] = "module:smallhut";
			break;
			case 4:
			output("`n`2 Inside you find several old Bows, dozens of warped and broken arrows stacked in one corner.  ");
			output("`n`n mmmm.... a Hunter must live in this hut, you think to yourself.  ");
			output("`n`n An old straw mattress that is covered with a thin blanket, is in the other corner.  ");
			output("`n`n You notice the fireplace has a few burning embers, so whoever lives here left not too long ago.  ");
			output("`n`n Looking further, you do not notice anything of value.  ");
			$session['user']['specialinc'] = "";
			break;
			case 5:
			output("`n`2 Inside you find a Peasant woman who is hacking and coughing terribly.  ");
			output("`n`n She says she needs to lie down for awhile and if you would please let her rest.  ");
			output("`n`n She looks awful and you are no healer, so you leave her be and go on your way.  ");
			$session['user']['specialinc'] = "";
			break;
		}
	}elseif ($op=="drinktea"){
		output("`n`n`2 You cheerfully accept the tea, as it may give you the boost you need right now. ");
		switch(e_rand(1,5)){
			case 1:
			output("`n`2 You notice the tea is very weak but what can you expect from Peasants who have nothing. ");
			output("`n`n You thank the woman for her kindness and state you must be on your way. ");
			$session['user']['hitpoints']+=$min;
			break;
			case 2: case 3:
			output("`n`2 You find the Dandelion Tea to be quite delicious and refreshing! ");
			output("`n`n You thank the woman for her kindness and notice you feel much better. ");
			$session['user']['hitpoints']+=$amt;
			break;
			case 4:
			output("`n`2 Much to your surprise, you discover the Dandelion Tea has been sweetened with Honey, this tea is not only refreshing but also makes you feel so much better than you did before! ");
			$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
			break;
			case 5:
			output("`n`2 You try not to gag, as you discover how horrible the tea is. You quickly finish it so not to offend the woman's kindness and state you must be on your way.  ");
			output("`n`n The good news is, you dont feel any better or worse than you did before.  ");
			break;
		}
		$session['user']['specialinc'] = "";
	}
	addnav(" exit ");
	addnav("(R) Return to Forest","forest.php?");
}
function smallhut_run(){
}
?>