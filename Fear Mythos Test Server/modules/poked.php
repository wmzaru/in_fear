<?php
# Poked 1.0
# Written by Robert of maddrio.com
# converted from an v097 forest event we use

function poked_getmoduleinfo(){
	$info = array(
        "name"=>"Poked",
        "version"=>"1.0",
        "author"=>"`2Robert",
        "download"=>"http://dragonprime.net/index.php?topic=2215.0",
        "category"=>"Forest Specials",
        "settings"=>array(
		"Poked - Settings,title",
		"name1"=>"Name of person player was poked by? ,|`6Seth",
		"name2"=>"Name of person player was poked by? ,|`%Violet",
		"name3"=>"Name of person player was poked by? ,|`3Cedrik",
		"name4"=>"Name of person player was poked by? ,|`4Ramius",
		"name5"=>"Name of person player was poked by? ,|`&Sir Robert",
		"reward"=>"How much charm, gold, HP to gain or lose?,range,1,10,1|1",
		),
	);
	return $info;
}
function poked_install(){
	if (!is_module_active('poked')){
		output("`^ Installing: Poked - forest event `n`0");
	}else{
		output("`^ Up-dating: Poked - forest event `n`0");
	}
        module_addeventhook("forest","return 10;");
	return true;
}

function poked_uninstall(){
	output("`^ Un-Installing: Poked - forest event `n`0");
	return true;
}

function poked_dohook($hookname,$args){
	return $args;
}

function poked_runevent($type){
global $session;
$name = $session['user']['name'];
if ($session['user']['sex']==0){ $sex="m'lord"; }else{ $sex="m'lady";}
$chance=e_rand(1,5);
if ($chance==1){ $who=get_module_setting("name1");}
if ($chance==2){ $who=get_module_setting("name2");}
if ($chance==3){ $who=get_module_setting("name3");}
if ($chance==4){ $who=get_module_setting("name4");}
if ($chance==5){ $who=get_module_setting("name5");}
switch (e_rand(1,8)) {
	case 1: case 5:
	output("`n`n`0 %s `2 appears suddenly, says `6 G'day %s ",$who,$sex);
	output("`n`n`2 pokes you and runs away laughing. ",$who);
	addnews("`0 $name `#was poked by $who`0");
	poked();
	break;
	case 2: case 6:
	output("`n`n`2 A `% Fairy  `2 appears suddenly, pokes you and flies away giggling. ");
	break;
	case 3: case 7:
	output("`n`n`2 A `3small Boy `2 suddenly appears, pokes you, then runs away quickly. "); 
	break;
	case 4: case 8:
	output("`n`n`2 A `@Goblin `2 appears suddenly, pokes you and runs away laughing hysterically. "); 
	break;
}

}
function poked_run(){
}
function poked(){
	$reward = get_module_setting("reward");
	$random=e_rand(1,5);
	if ($random == 1){
		output("`n`n`2 You smile as you feel a sudden surge in charm. ");
		$session['user']['charm']+= $reward;
	}elseif ($random == 2){
		if ($session['user']['hitpoints'] > $reward){
			output("`n`n`2 OUCH! ..that poke sort of hurt a bit, you lost %s HP. ",$reward);
			$session['user']['hitpoints']-= $reward;
		}else{
			output("`n`n`2 You are in such ill health that being poked had no effect. ");
		}
	}elseif ($random == 3){
		output("`n`n`2 As they run away, you notice they dropped`6 %s gold`2, which you pick up.",$reward);
		$session['user']['gold']+= $reward;
	}else{
		output("`n`n`2 You stand there clueless as to what just happened or why. ");
	}
}
?>