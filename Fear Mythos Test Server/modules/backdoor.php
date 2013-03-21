<?php

function backdoor_getmoduleinfo(){
	$info = array(
	"name"=>"Inn Backdoor",
	"author"=>"`2Robert",
	"version"=>"1.2",
	"category"=>"Inn",
	"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=764",
	"settings"=>array(
		"the Inn Backdoor Settings,title",
		"male"=>"Name of male seen kissing?,|Seth",
		"female"=>"Name of female seen kissing?,|Violet",
		"barkeep"=>"Name of the Bartender in Inn?,|Cedrik"
		)
	);
	return $info;
}

function backdoor_install(){
	module_addhook("inn");
	return true;
}

function backdoor_uninstall(){
	return true;
}

function backdoor_dohook($hookname, $args){
	switch($hookname){
	case "inn":
	addnav("Things to do");
	addnav("O? Open the Backdoor","runmodule.php?module=backdoor");
	break;
	}
	return $args;
}

function backdoor_run(){
	global $session;
	$iname = getsetting("innname", LOCATION_INN);
	$male = get_module_setting("male");
	$female = get_module_setting("female");
	$barkeep = get_module_setting("barkeep");
	page_header($iname);
	
	output("`n`n`& You open the back door, `n`n step outside and discover ");
	addnav("(R) Return to Inn","inn.php");
		switch(e_rand(1,15)){
		case 1: output(" a cat being chased by a dog."); break;
		case 2: output(" that the back alley smells funny."); break;       
		case 3: output(" the weather may change soon."); break;
		case 4: output(" some empty Ale kegs."); break;
		case 5: output(" it was warmer inside than it is outside."); break;
		case 6: output(" %s and %s kissing.",$female,$male); break;
		case 7: output(" you just stepped in poo. You feel less charming.");
		$session['user']['charm']--;
		if ($session['user']['charm']<0) $session['user']['charm']=0;
		break;
		case 8: output(" a bird has just poo'd on you. You feel less charming.");
		$session['user']['charm']--;
		if ($session['user']['charm']<0) $session['user']['charm']=0;
		break;
		case 9: output(" nothing here but garbage cans."); break;
		case 10: case 11: case 12:
		output(" a gold coin on the ground, Lucky You!");
		$session['user']['gold']++;
		debuglog(" found 1 gold coin opening the Inn backdoor ");
		break;
		case 11: output(" %s is yelling for you to close the bloody door! ",$barkeep); break;
		case 12: output(" %s is sitting on a Keg and drinking an ale. ",$male); break;
		case 13: output(" %s is sitting on a Keg and waves hello to you. ",$female); break;
		case 14: output(" a mouse running away. "); break;
		case 15: output(" you startled a fat rat who bites your ankle. ");
		if ($session['user']['turns']>=1){
			output("`n You lose time for 1 forest fight while you recover. ");
			$session['user']['turns']--;
		}else{
			output("`n Ouch, that must have hurt! "); 
		}
		break;
	}
page_footer();
}
?>