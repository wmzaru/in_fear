<?php
# Gypsy Camp  21April2005
# Author: Robert of Maddrio dot com
# Converted from a 097 forest event
# Last Updated: 28Jan2007

function gypsycamp_getmoduleinfo(){
	$info = array(
	"name"=>"Gypsy Camp",
	"version"=>"1.4",
	"author"=>"`2Robert",
	"category"=>"Forest Specials",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
	"settings"=>array(
		"Gypsy Camp Settings,title",
		"hpoints"=>"Min/Max hitpoint loss/gain.,range,1,25,1|2",
		"cpoints"=>"Min/Max charm loss/gain.,range,1,25,1|2",
		),
	);
	return $info;
}
function gypsycamp_install(){
	if (!is_module_active('gypsycamp')){
		output("`^ Installing module Gypsy Camp `n");
	}else{
		output("`^ Up Dating module Gypsy Camp`n");
	}
	module_addeventhook("forest","return 100;");
	return true;
}
function gypsycamp_uninstall(){
	output("`n`^ Un-Installing module Gypsy Camp `0`n");
	return true;
}
function gypsycamp_dohook($hookname,$args){
	return $args;
}
function gypsycamp_runevent($type){
	global $session;
	$from = "forest.php?";
	$hp = get_module_setting("hpoints");
	$ch = get_module_setting("cpoints");
	$session['user']['specialinc'] = "module:gypsycamp";
	$op = httpget('op');

if ($op=="" || $op=="search"){
output("`n`3`c`bGypsy Camp`b`c `n`n");
output(" `2You stumble through the Forest, and have come upon a small clearing.`n");
output(" You found a `3Gypsy Camp`2! `n"); 
output(" `2You can see Covered Wagon's and Donkey Carts. The campfire is blazing.`n");
output(" There is a flaming torch near every Covered Wagon. `n"); 
output(" The `3Gypsy's `2are now aware of you within their camp, some go into their Wagon, others smile at you. `n"); 
output(" A `3Gypsy woman `2offers you something to drink and eat from the table near the campfire. `n"); 

addnav(" Gypsy Camp "); 
addnav("");
addnav(" Eat "); 
addnav("(B) Bread",$from."op=bread");  
addnav(" Drink ");  
addnav("(H) Bucket of Water",$from."op=water");   
addnav("(W) Goblet of Wine",$from."op=wine"); 
addnav("(S) Spanish Fly",$from."op=fly"); 
addnav(" other ");
addnav("(L) Blind Gypsy",$from."op=blind"); 
addnav("(G) Gypsy Dancer",$from."op=dancer"); 
addnav("(M) Myra the Gypsy",$from."op=myra"); 
addnav("(V) Vlad the Gypsy",$from."op=vlad"); 
addnav(" Leave "); 
addnav("(R) Return to Forest",$from."op=leave");

}elseif ($op=="bread"){
	output("`n`n`2 You grab some Bread from the table.  Famished by hunger you eat the whole loaf. ");
    if (e_rand(0,1)==0){
       output("`n`n`2 You feel a `isurge of power`i enter your body! ");
       $session['user']['hitpoints']+=$hp;
    }else{
       output("`n`n`2 You begin to think you ate too much! ");
       output("`n You no longer have the strength to fight so much today! `n`n`& You lose time for 1 Forest Fight."); 
       $session['user']['turns']-=1;
    }
    addnav("(R) Return to Forest","forest.php");
    $session['user']['specialinc'] = "";
}elseif ($op=="water"){
	output("`n`n`# You grab a ladle and drink from the Bucket of Water on the table. ");
	if (e_rand(0,1)==0){ 
       output("`n`n Quenching your thirst, you feel quite refreshed! `n`n`& Your hitpoints are restored."); 
       $session['user']['hitpoints'] = $session['user']['maxhitpoints'];  
    }else{ 
       output("`n`n`\$ Yuk! `# You suddenly realize you just drank used Bath Water! `n`n`& You lost some hitpoints."); 
       $session['user']['hitpoints']-=$hp;
       if ($session['user']['hitpoints']<1){ $session['user']['hitpoints']=1;}
    } 
    addnav("(R) Return to Forest","forest.php");
    $session['user']['specialinc'] = "";
}elseif ($op=="wine"){
	output("`n`n`2 You drink from the `3Goblet of Wine `2from upon the table. ");
	if (e_rand(0,1)==0){ 
       output("`n`n`2 Quenching your thirst, you feel a sense of power enter your body! `n`n`& Your hitpoints have increased."); 
       $session['user']['hitpoints']+=$hp; 
    }else{ 
       output("`n`n`\$ Yuk! `2 You feel something slimy and ALIVE go down your throat! You feel sick, very sick! `n`n`& You lost some hitpoints."); 
       $session['user']['hitpoints']-=$hp;
       if ($session['user']['hitpoints']<1){ $session['user']['hitpoints']=1;}
    } 
    addnav("(R) Return to Forest","forest.php");
    $session['user']['specialinc'] = "";
}elseif ($op=="fly"){
	output("`n`n`2 You wonder over to the table and see a vial of `4Spanish Fly`2, `n you look around and quickly take the vial and drink the contents! ");
	if (e_rand(0,1)==0){
       output("`n`n`\$ OH NO!! `2 You read the label again and it say's `b Spanish Flu `bnot `i Fly`i, `n`6 You feel like crap and cannot fight so well! ");
       $session['user']['hitpoints'] -= 5;
       if ($session['user']['hitpoints']<1){ $session['user']['hitpoints']=1;}
       $flu = array(
	"name"=>"`4Spanish Flu",
	"rounds"=>20,
	"defmod"=>0.8,
	"atkmod"=>0.8,
	"wearoff"=> "You begin to feel better",
	"roundmsg"=>"Your sickness makes you very weak.",
	"schema"=>"module-gypsycamp",
	);
apply_buff('flu',$flu);
    }else{ 
       output("`n`n`^ WOW! To your amazement - You feel like you can take on the world! "); 
       $session['user']['hitpoints'] += 5;
       $fly = array(
	"name"=>"`6Spanish Fly",
	"rounds"=>20,
	"defmod"=>1.2,
	"atkmod"=>1.2,
	"wearoff"=> "You begin to feel normal again.",
	"roundmsg"=>"You are are feeling vigorous!",
	"schema"=>"module-gypsycamp",
	);
apply_buff('fly',$fly);
    } 
    addnav("(R) Return to Forest","forest.php");
    $session['user']['specialinc'] = "";
}elseif ($op=="blind"){
	output("`n`n`3 A Blind Gypsy `2sitting at the table offer's your fortune if he is allowed to touch your face, `n seeing no harm in the old man you accept his offer. `n`n");
	if (e_rand(0,1)==0){
       output(" He feels around your face and says `3you are a very lucky person`2. `n You sense something mystical has just happened and feel VERY refreshed and anew!"); 
       $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
       $session['user']['turns']+=3;
    }else{
       output(" He feels around your face and says `i`3you are having a really bad day`2`i. `n You sense something mystical has just happened and feel VERY depressed and tired!"); 
       $session['user']['hitpoints'] -= $hp;
       $session['user']['turns']-=3;
       if ($session['user']['turns']<0){$session['user']['turns']=0;}
    }
    addnav("(R) Return to Forest","forest.php");
    $session['user']['specialinc'] = "";
}elseif ($op=="dancer"){
	if (e_rand(0,1)==0){ 
       output("`n`n`2 You notice and walk over to a beautiful young `3Gypsy Dancer`2.  While dancing about the campfire, she see's you admiring her dance and smiles upon you!`n`n`& You are more charming than you thought. "); 
       $session['user']['charm']+=$ch;  
    }else{ 
       output("`n`n`2 You notice and walk over to a beautiful young `3Gypsy Dancer`2.  While she is dancing about the campfire, you carelessly throw your nasty tasting wine upon the campfire which `4burst Flames `2and singe the `3Gypsy Dancer`2, she gives you the `3`iEvil Eye`2!`i `&`n`n You are less charming than before!");
       $session['user']['charm']-=$ch;
    }
    addnav("(R) Return to Forest","forest.php");
    $session['user']['specialinc'] = "";
}elseif ($op=="myra"){
	output("`n`n`3 Myra the Gypsy `2offer's you a hot bath. Being in the forest for quite some time, `nyou are in need of one and accept her offer. ");
	if (e_rand(0,1)==0){
       output("`n After bathing in a wooden bath tub, you feel really refreshed! `n`n`& You gain a few hitpoints."); 
       $session['user']['hitpoints']+=$hp;
    }else{
       output("`n While getting out of the wooden bath tub, you slip and hurt yourself! `n`n`& You lost a few hitpoints."); 
       $session['user']['hitpoints']-=$hp;
    }
    addnav("(R) Return to Forest","forest.php");
    $session['user']['specialinc'] = "";
}elseif ($op=="vlad"){
	output("`n`n`3 Vlad the Gypsy `2admires the scarf your wearing. He offers you `^20 gold `2for it. ");
	if (e_rand(0,1)==0){ 
       output("`n`n Not liking the old scarf anyway you accept his offer! `n`n`& You sold the Scarf for 20 gold."); 
       $session['user']['gold']+=20;
    }else{
       output("`n`n While taking off the scarf, you lose your balance, trip and fall into the campfire! `n`n`& You are injured and a few hitpoints."); 
       $session['user']['hitpoints']-=$hp;
       if ($session['user']['hitpoints']<1){ $session['user']['hitpoints']=1;}
    }
    addnav("(R) Return to Forest","forest.php");
    $session['user']['specialinc'] = "";
}else{
output("`n`n`2 You decide you do not have the time to visit with Gypsys today, however as you try to leave, they attempt to convince you to stay for awhile. `n You finally are able to leave the `3Gyspy Camp`2, losing time for 1 forest fight.");
addnav("(R) Return to Forest","forest.php");
$session['user']['specialinc'] = "";
$session['user']['turns']--;
}
}
function gypsycamp_run(){
}
?>