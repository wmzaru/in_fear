<?php
// addnews ready
// mail ready
// translator ready
// OASIS - by Zachery Delafosse
// Random event in the graveyard
// Sprite offers a player Gold, Gems, or Wisdom
// If they choose gold or gems, she gets mad and leaves
// If wisdom is chosen, they will get 2 of these
// 1/4 chance of getting all 3.

function oasis_getmoduleinfo(){
	$info = array(
		"name"=>"oasis",
		"version"=>"1.0",
		"author"=>"Zachery Delafosse",
		"category"=>"Graveyard Specials",
	);
	return $info;
}

function oasis_install(){
	module_addeventhook("graveyard", "return 60;");
	return true;
}

function oasis_uninstall(){
	return true;
}

function oasis_dohook($hookname,$args){
	return $args;
}

function oasis_runevent($type)
{
	global $session;
	// We assume this event only shows up in the forest currently.
	$from = "graveyard.php?";
	$session['user']['specialinc'] = "module:oasis";

	$op = httpget('op');
	if ($op==""||$op=="search"){
		//First Visit
		addnav("Approach the oasis","graveyard.php?op=oasis");
		addnav("Leave it alone","graveyard.php?op=leaveoasis");
		output("You unsuspectingly find yourself in front of an oasis...`nPerhaps a mirage, or perhaps riches... or even a trap!`0");
	}else if ($op=="oasis"){
		output("`^As you approach the oasis, the beautiful waters stir...`n `n`%");
            output("A sprite rises from the pool and chants:`n`!");
		output("Step forward, valiant one,`n");
		output("For a brief moment of rest.`n");
		output("Of the 3 gifts I hold in my hands,`n");
		output("Select which you think is best.`n");
		output("Choose wisely, mortal being`n");
		output("Your blessing or your curse.`n");
		output("The choice you make may change your life,");
		output("For the better or for the worse.");
		addnav("Ask for gold","graveyard.php?op=gold");
		addnav("Ask for gems","graveyard.php?op=gems");
		addnav("Ask for wisdom","graveyard.php?op=wisdom");
	}else if ($op=="leaveoasis"){
		$session['user']['specialinc']="";
		output("`#Unknowing of what awaits you at the oasis, you step back and run away... `n`@What a coward!`0");
$session['user']['specialinc']="";
	}else if ($op=="gold"||$op=="gems"){
		output("`^The sprite stares in rage...`n`#`%Fool!`nIs money all you care for? You are nothing more than a whisper, yet you still seek corruption.`0");
		$session['user']['soulpoints']=0;
		$session['user']['gravefights']=0;
$session['user']['specialinc']="";
output("`0");
	}else if ($op=="wisdom"){
		output("`^The sprite smiles...`n `n`#`%You turned away gold and gems... You shall now have...`n`n");
$noget=e_rand(1,4);
if($noget!=1){
            $gemget=e_rand(2,4);
		$session['user']['gems']=$session['user']['gems']+$gemget;
output("`!$gemget gems`n`n");
}if($noget!=2){
            $gpget=e_rand(300,1500);
		$session['user']['gold']=$session['user']['gold']+$gpget;
output("`^$gpget gold`n`n");
}if($noget!=3){
		addnav("Continue","newday.php");
output("`!A new life");
}
$session['user']['specialinc']="";
$op="leve";
output("`0");
	}//else{
//$session['user']['specialinc']="";
//$op="leve";
//output("`0");
	//}
}

function oasis_run(){
}
?>


