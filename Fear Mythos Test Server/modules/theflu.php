<?php

function theflu_getmoduleinfo(){
	$info = array(
		"name"=>"The Flu",
		"version"=>"1.3",
		"author"=>"MR Zone",
		"download"=>"http://9zr.net/downloads/logd/theflu.zip",
		"category"=>"Forest Specials",
		"settings"=>array(
			"Flu Event Settings,title",
			"fluchance"=>"Chance to get the Flu,range,20,100,5|30",
			"fluclanchance"=>"Chance to get the Flu from another clansman,range,0,100,10|30",
			"buffname"=>"Name for the Flu Sickness|`6Flu Sickness",
			"flulast"=>"# of Days Flu lasts,range,1,6,1|3",
			"dayssafe"=>"Days user is safe from Flu when infection ends,range,1,20,1|3",
			"defeffect"=>"Amount of Defense Flu will deduct|.95",
			"atkeffect"=>"Amount of Attack Flu will deduct|.98",
			"trainapply"=>"Does flu affect users when fighting Masters,bool|1",
			"pvpapply"=>"Does flu affect users when fighting PVP,bool|1",
			
		),
		"prefs"=>array(
			"Flu preferences,title",
			"hasflu"=>"Is user infected?,bool|0",
            "fludays"=>"days left of flu infection,0",
            "safedaysleft"=>"safedaysleft,0",
		)
	);
	return $info;
}
function theflu_install(){
	module_addeventhook("forest", "return 80;");
	module_addeventhook("travel", "return 25;");
	module_addhook("newday");
	return true;
}

function theflu_uninstall(){
	return true;
}

function theflu_dohook($hookname,$args){
	global $session;
	$hasflu = get_module_pref("hasflu");
	$fludays = get_module_pref("fludays");
	$clanid=$session["user"]["clanid"];
	
	switch($hookname){
		case "newday": 	
			
			if ($hasflu && $fludays >0){
				set_module_pref("fludays",get_module_pref("fludays")-1);
				set_module_pref("hasflu",1);
				include("modules/lib/flu.php");
				theflu_flueffect(2,"<br>cough... cough... <span class='colLtYellow'>Today you are still Infected with the </span><span class='colLtRed'>FLU.!!</span> cough... cough...<br>","");
				if ($session['user']['superuser']&& SU_EDIT_USERS) { //Line Taken from Lostruins by DaveS
					rawoutput("days left for flu: ". get_module_pref("fludays"));
				}
			}elseif($hasflu && ($fludays==0)){
				include("modules/lib/flu.php");
				call_flucure("yes","cure");
			}elseif(get_module_pref("safedaysleft")>0){
				set_module_pref("safedaysleft",get_module_pref("safedaysleft")-1);
				if ($session['user']['superuser']&& SU_EDIT_USERS) { //Line Taken from Lostruins by DaveS
					rawoutput("days safe from the flu: ". get_module_pref("safedaysleft"));
				}
				
			}elseif(($clanid>0) && (!$hasflu)){
				
				$clanid=$session["user"]["clanid"];
				output($clanid);
				$gflu = e_rand(0, 100);
				if ($gflu<=get_module_setting("fluclanchance")){
					$sql = "SELECT acctid,name,clanid,value FROM ". db_prefix("accounts") ." INNER JOIN ". db_prefix("module_userprefs") ." ON acctid=userid where modulename='theflu' AND setting='hasflu' AND value=1 
						and clanid=". $clanid ." and acctid<>". $clanid  ." group by acctid";
					$res = db_query($sql);
					$fluvalue = "";
					$row = db_fetch_assoc($res);
					$fluvalue = $row['value'];
					if ($fluvalue==1){
						include("modules/lib/flu.php");
						theflu_flueffect(1,"<br><span class='colLtYellow'>Today you feel a little </span><span class='colLtRed'> ill</span><span class='colLtYellow'>, You caught the</span><span class='colLtRed'> FLU.</span><span class='colLtYellow'> You remember that a fellow clansmen was sick... </span>","<span class='colLtYellow'><br>You start to feel a little ill, but it soon passes. </span>");
					}
				}	
			}
			break;
			/*case "lodge":
				addnav("Flu Test","runmodule.php?module=chronosgem&op=lodge");
			break;*/
	}
	
	return $args;
}

function theflu_runevent($type,$link)
{
	global $session;
	
	$randpass = 0;
	if (e_rand(0, 100)<=get_module_setting("fluchance")){
		$randpass = 1;
		}
		include("modules/lib/flu.php");
		theflu_flueffect($randpass,"<span class='colLtYellow'>Wondering around, you start Sneezing, wheezing, Then coughing. Ohh No!!! You caught the</span> <span class='colLtRed'>FLU</span>.<br>","<span class='colLtYellow'>You start to feel a little ill, but it soon passes.</span><br>");
}

function theflu_run(){
    global $session;
	page_header("The FLU");
	
	addnav("Refresh","runmodule.php?module=theflu&op=super");
	$op = httpget('op');
		
	//if ($op == "super") {
	//	include("modules/lib/flu.php");
	//	theflu_flueffect(1,"`^Super User infection!!! You caught the`$ FLU. `0","");
	//}
	villagenav();
	page_footer();
}
?>