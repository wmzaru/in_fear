<?php

function theflu_applybuff(){
		apply_buff('flu',array("name"=>get_module_setting("buffname"),"rounds"=>-1,
		"defmod"=>get_module_setting("defeffect"),
		"atkmod"=>get_module_setting("atkeffect"),
		"allowinpvp"=>get_module_setting("pvpapply"),
		"allowintrain"=>get_module_setting("trainapply"),
		));
}

function theflu_flueffect($xeffect,$getmessage,$notmessage){
	global $session;
	$gflu = true; //if ture, will make palyer sick
	
	if ($xeffect==0){ //a event tried to infect a user but failed, so show message
		output($notmessage);
		return false;
	}
	if (get_module_pref("safedaysleft","theflu")>0) 
		$gflu = false;
	/*if ($xeffect==2) 
		$gflu = true;*/
	if ($gflu){
		if (($xeffect==1) && (get_module_pref("hasflu","theflu")!=1)) //if first time player has been effected this time
			set_module_pref("fludays",get_module_setting("flulast","theflu"),"theflu");
		set_module_pref("hasflu",1,"theflu");
		theflu_applybuff();
		rawoutput($getmessage);
		debuglog("Got the Flu");
	}else{
		rawoutput($notmessage);
	}
	return true;
}

function theflu_poison($getmessage,$notmessage,$userid){
	global $session;
	$gflu = true; //if ture, will make palyer sick

	if ((get_module_pref("safedaysleft","theflu",$userid)>0) || (get_module_pref("hasflu","theflu",$userid)==1)) 
		$gflu = false;
	if ($gflu==true){
		if (($xeffect==1) && (get_module_pref("hasflu","theflu",$userid)!=1)) //if first time player has been effected this time
			
		//set_module_pref("fludays",4,"theflu",$userid);
		set_module_pref("hasflu",1,"theflu",$userid);
		
		rawoutput($getmessage);
		debuglog("Got the Flu");
	}else{
		rawoutput($notmessage);
	}
	return true;
}

function theflu_flucure($messageon,$buff){ 
	//$messageon : no =do not feel better display message; $buff : cure = remove buff
	set_module_pref("fludays",0);
	set_module_pref("hasflu",0);
	set_module_pref("safedaysleft",get_module_setting("dayssafe"));
	if ($messageon!="no")
		output("`nToday, You fell much better. You have overcome the flu. `0`n");
	if ($buff=="cure")
		apply_buff('flu',"");
}

function call_flucure($messageon,$buff){ 
	//$messageon : no =do not feel better display message; $buff : cure = remove buff
	set_module_pref("fludays",0,"theflu"); //These lines
	set_module_pref("hasflu",0,"theflu"); //These lines
	set_module_pref("safedaysleft",get_module_setting("dayssafe","theflu"),"theflu");
	if ($messageon!="no")
		output("`nToday, You fell much better. You have overcome the flu. `0`n");
	if ($buff=="cure")
		apply_buff('flu',"");
}

?>