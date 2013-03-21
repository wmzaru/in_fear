<?php

// Many thanks to Talisman for helping with translation info
// Hopefully I've got it all good now but looking forward to feedback

require_once("lib/villagenav.php");
require_once("lib/http.php");

function lowspirits_getmoduleinfo() {
	$info = array(
		"name"=>"Low Spirits",
		"version"=>"1.3",
		"author"=>"Deedee and Mentaloid",
		"category"=>"Inn",
		"download"=>"http://dragonprime.net/index.php?action=viewfiles;user=Deedee",
		"settings"=>array(
			"Low Spirits,title",
			"cost"=>"How many gems does this action cost,int|2",
			"wenches"=>"Wenches?|Ruby,Sophia,Eliza,Charity,Elvira",
			"gigolos"=>"Gigolos?|Bertram,Cecil,Horatio,Victor,Cosmo",
			"excuses"=>"Excuses?|I've got a headache:I'm too tired:These dragon attacks just have me feeling too nervous:I think Cedrik slipped something in my drink that made me sleepy:I'm not in the mood",
			"excuses2"=>"Excuses2?|you're in very high spirits, I think you should be the one cheering me up!:come back tomorrow and try again, you're already in very high spirits today and don't need cheering up:I'm just not up to cheering your spirits today"
		),
		"prefs"=>array(
			"Low Spirits,title",
			"been"=>"How many times have they been cheered up today,int|0",
		),
	);
	return $info;
}

function lowspirits_install() {
	module_addhook("inn");
	module_addhook("newday");
	return true;
}

function lowspirits_uninstall() {
	return true;
}

function lowspirits_dohook($hookname,$args) {
	global $session;
	switch($hookname){
	case "inn":
		addnav("Things to do");
		if ($session['user']['sex']==SEX_MALE){
			$victims = get_module_setting("wenches");
			$victims .= ',Daydream,Saucy';
		} else {
			$victims = get_module_setting("gigolos");
			$victims .= ',Whitlock,Marcus,MightyE,Kendaer,Talisman';
		}
		$victims = explode(',',$victims);
		$victim = $victims[rand(0,count($victims)-1)];
		addnav(array("Go upstairs with %s",$victim), "runmodule.php?module=lowspirits&victim=".$victim);
		break;
	case "newday":
		set_module_pref("been",0);
		break;
	}
	return $args;
}

function lowspirits_run() {
	global $session;
	$partner = httpget('victim');
	$visits = 1;
	$cost=get_module_setting("cost");
	$been=get_module_pref("been");
	$iname = getsetting("innname", LOCATION_INN);
	tlschema("inn");
	page_header($iname);

	output("`5`c`b $iname`b`c",true);
	tlschema();

	if ($been >= $visits) {
		$excuses = get_module_setting("excuses");
		$excuses = explode(':',$excuses);
		$excuse = $excuses[rand(0,count($excuses)-1)];
		output("`n`@%s says,`n`n \"Sorry %s`@, %s`@, so I can't cheer you up today.\"`0",$partner,$session['user']['name'],$excuse);
	} else {
		if ($session['user']['gems'] < $cost) {
			output("`n`@%s says,`n`n \"Sorry %s`@, you don't have enough gems to go upstairs.\"`0",$partner,$session['user']['name']);
		} else {
			lowspirits_cheerup($partner,$cost);
			$been++;
			set_module_pref("been",$been);
		}
	}

	addnav("Where to?");
	addnav("I?Return to the Inn","inn.php");
	villagenav();
	page_footer();
}

function lowspirits_cheerup($partner,$cost) {
	global $session;

	if ($session['user']['spirits'] > 1) {
		$excuses = get_module_setting("excuses2");
		$excuses = explode(':',$excuses);
		$excuse = $excuses[rand(0,count($excuses)-1)];
		output("`n`@%s says,`n`n \"Sorry %s`@, %s`@\"`0",$partner,$session['user']['name'],$excuse);
	} else {
		output("`n`@%s pockets your %s gems and pats the bed, saying \"I'm so glad you came up to see me %s`@.\"`0",$partner,$cost,$session['user']['name']);
		$floor = $session['user']['spirits'] + 1;
		if ($floor > 2) {$floor = 2;}
		$newspirit = rand($floor,2);

		output("`@%s cheers up your spirits in the oldest and best way there is.  Your resolve to `\$fight `@until the Dragon is dead has been increased along with your spirits!`0",$partner);
		$session['user']['turns'] += ($newspirit - $session['user']['spirits']);
		$session['user']['spirits'] = $newspirit;
		$session['user']['gems'] -= $cost;
	}
}		
?>