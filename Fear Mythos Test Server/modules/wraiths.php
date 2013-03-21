<?php

// Wrongful Wraiths by Robert Riochas
// http://maddrio.com
// For LotGD version 1.x

require_once("lib/http.php");
require_once("lib/villagenav.php");

function wraiths_getmoduleinfo(){
	$info = array(
	"name"=>"Wrongful Wraiths",
	"version"=>"1.2",
	"author"=>"`2Robert",
	"category"=>"Village",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
	"settings"=>array(
		"Wraiths House of Spanking - Settings,title",
		"spanksperday"=>"Visits to shop allowed each day,int|1",
		"spankcost"=>"Price for Spanking in gold,range,10,1000,10|100",
		"wraithloc"=>"Where does the Spanking Shop appear,location|".getsetting("villagename", LOCATION_FIELDS),
		"minturns"=>"Minimum turns to give,range,1,25,1|1",
		"maxturns"=>"Maximum turns to give,range,1,50,1|2",
		"minhp"=>"Minimum hp to give,range,1,100,1|5",
		"maxhp"=>"Maximum hp to give,range,1,200,1|10",
		"mincharm"=>"Minimum charm to give,range,1,5,1|1",
		"maxcharm"=>"Maximum charm to give,range,1,10,1|2"
		),
	"prefs"=>array(
		"totaltoday"=>"How many spankings did they buy today?,int|0",
		"totaldk"=>"Total times the player used this shop.,int|0"
		)
	);
	return $info;
}

function wraiths_install(){
	if (!is_module_active('wraiths')){
		output("`^ Installing module Wrongful Wraith `n");
	}else{
		output("`^ Up Dating module Wrongful Wraith`n");
	}
	module_addhook("village");
	module_addhook("changesetting");
	module_addhook("newday");
return true;
}

function wraiths_uninstall(){
	output("Uninstalling Wrongful Wraiths `n");
	return true;
}

function wraiths_dohook($hookname,$args){
global $session;
	switch($hookname){
		case "changesetting":
			if ($args['setting'] == "villagename") {
				if ($args['old'] == get_module_setting("wraithloc")) {
					set_module_setting("wraithloc", $args['new']);
				}
			}
			break;
		case "village":
			if ($session['user']['location'] == get_module_setting("wraithloc")) {
				tlschema($args['schemas']['marketnav']);
				addnav($args['marketnav']);
				tlschema();
				addnav("Wrongful Wraiths","runmodule.php?module=wraiths&op=enter");
			}
			break;
		case "newday":
			set_module_pref("totaltoday",0);
			break;
		}
	return $args;
}
function wraiths_run(){
	global $session;
	page_header("Wraiths House of Spanking");
	output("<h2>`c`b`2Wraiths House of Spanking`b`c`n</h2>",true);
	$op = httpget('op');
	$today = get_module_pref("totaltoday");
	$perday = get_module_setting("spanksperday");
	$spankcost = get_module_setting("spankcost");
	$name=$session['user']['name'];
	$armor=$session['user']['armor'];
	if ($op == "enter"){
		if ($today >= $perday) { // daily use check
			output("`& Wraith `7strokes his goat tee and smiles at you. `n`n");
			output("`6 I hope you enjoyed your spanking, come back again tomorrow for more. ");
		}else{
			output("`2 You enter into Wrongful Wraith's House of Spanking.");
			output("`n`n A shoppe which caters to the kinky side of peoples nature.");
			if ($session['user']['sex']==0){
				output("`n`n A half dozen or so young maidens giggle as they see you enter the shop. ");
				output("`n`n Each one smiles and flutter their eyes trying to catch your attention. ");
				output("`n`n Wraith strokes his goat-tee and asks, `6 Which of these fair maidens would you like to spank? ");
				output("`n`n The cost to indulge in such pleasure is only `^ %s `6 in gold. ",$spankcost);
			}else{
			output("`n`n A half dozen or so muscular handsome men are sitting in chairs along the wall. ");
			output("`n`n Each one smiles hoping to catch your attention.");
			output("`n`n Wraith strokes his goat-tee and asks, `6 Which of these handsome men would you like to be spanked by? ");
			output("`n`n The cost to indulge in such pleasure is only `^ %s `6 in gold. ",$spankcost);
			}
		if ($session['user']['gold'] < $spankcost){ // cost check, no navs unless they have the cost
			output("`n`n`2 Wraith then sighs and says, `n`0 $name `6 I see you have a cash flow problem, do come back when you have enough gold! ");
		}else{
			addnav(" Spanking ");
			addnav("(P) Pay gold","runmodule.php?module=wraiths&op=pay");
		}
		}
	}elseif ($op == "pay"){
		output("`n`n`2 You give Wraith %s gold and he then says for you to select. ",$spankcost);
		$session['user']['gold']-=$spankcost;
		debuglog(" spent $spankcost gold at Wraiths House of Spanking ");
		$today ++ ;
		set_module_pref("totaltoday",$today);
		set_module_pref("totaldk",$today);
		if ($session['user']['sex']==0){
			addnav(" Select a Maiden ");
			addnav("(1) Amazon Abby","runmodule.php?module=wraiths&op=1st");
			addnav("(2) Cheerful Chrissy","runmodule.php?module=wraiths&op=2nd");
			addnav("(3) Dainty Debbie","runmodule.php?module=wraiths&op=3rd");
			addnav("(4) Full-figured Flo","runmodule.php?module=wraiths&op=4th");
			addnav("(5) Sultry Susan","runmodule.php?module=wraiths&op=5th");
		}else{
			addnav(" Select a Stud ");
			addnav("(1) Bearded Brad","runmodule.php?module=wraiths&op=brad");
			addnav("(2) Fat Frank","runmodule.php?module=wraiths&op=frank");
			addnav("(3) Handsome Hank","runmodule.php?module=wraiths&op=hank");
			addnav("(4) Marvelous Marvin","runmodule.php?module=wraiths&op=marvin");
			addnav("(5) Smiling Sam","runmodule.php?module=wraiths&op=sam");
		}
	}elseif ($op == "1st"){
		output("`n`2 You select Amazon Abby. ");
		output("`n`n Who takes off her granny panties, bends over exposing her bottom.");
		spank();
	}elseif ($op == "2nd"){
		output("`n`2 You select Cheerful Chrissy. ");
		output("`n`n Who doesnt take off her G-String, bends over exposing her bottom.");
		spank();
	}elseif ($op == "3rd"){
		output("`n`2 You select Dainty Debbie. ");
		output("`n`n Who takes off her cotton panties, bends over exposing her bottom.");
		spank();
	}elseif ($op == "4th"){
		output("`n`2 You select Full-figured Flo. ");
		output("`n`n Who takes off her corset, garter belt and undies, bends over exposing her bottom.");
		spank();
	}elseif ($op == "5th"){
		output("`n`2 You select Sultry Susan.");
		output("`n`n Who is not wearing any panties, bends over exposing her bottom.");
		spank();
	}elseif ($op == "brad"){
		output("`n`2 You select Bearded Brad.");
		output("`n`n You take off your %s, slide down your undies, bend over exposing your bottom.",$armor);
		spank();
	}elseif ($op == "frank"){
		output("`n`2 You select Fat Frank. ");
		output("`n`n You take off your %s, slide down your undies, bend over exposing your bottom.",$armor);
		spank();
	}elseif ($op == "hank"){
		output("`n`2 You select Handsome Hank.");
		output("`n`n You take off your %s, slide down your undies, bend over exposing your bottom.",$armor);
		spank();
	}elseif ($op == "marvin"){
		output("`n`2 You select Marvelous Marvin.");
		output("`n`n You take off your %s, slide down your undies, bend over exposing your bottom.",$armor);
		spank();
	}elseif ($op == "sam"){
		output("`n`2 You select Smiling Sam.");
		output("`n`n You take off your %s, slide down your undies, bend over exposing your bottom.",$armor);
		spank();
	}
addnav(" exit shoppe ");
if ($op != "enter") addnav("(S) Store Front","runmodule.php?module=wraiths&op=enter");
villagenav();
page_footer();
}

function spank(){
global $session;
$mint = get_module_setting("minturns");
$maxt = get_module_setting("maxturns");
$tamt = e_rand($mint, $maxt);
$minh = get_module_setting("minhp");
$maxh = get_module_setting("maxhp");
$hamt = e_rand($minh, $maxh);
$minc = get_module_setting("mincharm");
$maxc = get_module_setting("maxcharm");
$camt = e_rand($minc, $maxc);
	if ($session['user']['sex']==0){
		switch(e_rand(1,6)){
		case 1: case 4:
		output("`n`n`2 The maiden squeals with delight as you paddle her bottom! ");
		output("`n`n Your spanking has given her delight she has never known before. ");
		output("`n`n When its all over she hugs you then opens her purse and hands you a vail. ");
		output("`n`n You drink down the vial and find you have %s more forest fights! ",$tamt);
		$session['user']['turns']+=$tamt;
		break;
		case 2: case 5:
		output("`n`n`2 The maiden shreaks with delight as you paddle her bottom. ");
		output("`n`n When it is all over, she hands you a vial which ");
		output("`n`n increase's your overall strength. ");
		$session['user']['hitpoints']+=$hamt;
		break;
		case 3: case 6:
		output("`n`n`2 You begin to very gently paddle her bottom. ");
		output("`n`n She squeal's with delight with every strike of the paddle. ");
		output("`n`n When it is all over, she gives you a gentle kiss on the cheek. ");
		output("`n`n Blushing a bit ...you smile at her with a certain amount of gained charm. ");
		$session['user']['charm']+=$camt;
		break;
		}
	}else{
		switch(e_rand(1,6)){
		case 1: case 4:
		output("`n`n`2 The handsome stud paddle's your bottom! ");
		output("`n`n When it is all over he says you have the finest looking bottom he has ever paddled! ");
		output("`n`n While Wraith is not looking, `n`n he hands you a vial and says `i Thank You for giving HIM `i so much pleasure! ");
		output("`n`n You drink down the vial and find you have %s more forest fights! ",$tamt);
		$session['user']['turns']+=$tamt;
		break;
		case 2: case 5:
		output("`n`n`2 The handsome stud swiftly paddle's your bottom. ");
		output("`n`n When it is all over, he smiles and hands you a vial which ");
		output("`n`n increase's your overall strength. ");
		$session['user']['hitpoints']+=$hamt;
		break;
		case 3: case 6:
		output("`n`n`2 The handsome stud gently paddle's your bottom. ");
		output("`n`n You squeal with delight with every strike of the paddle. ");
		output("`n`n When it is all over, he gives you a gentle kiss on the cheek. ");
		output("`n`n Blushing a bit ...you smile at him with a certain amount of gained charm. ");
		$session['user']['charm']+=$camt;
		break;
		}
	}
}
?>