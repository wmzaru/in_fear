<?php

// This is a module to make the game slightly easier for no / low DK players

function farmiehelp_getmoduleinfo(){
	$info = array(
		"name"=>"Farmie Help",
		"version"=>"1.01",
		"author"=>"Enhas",
		"category"=>"Administrative",
		"download"=>"http://dragonprime.net/users/Enhas/farmiehelp.txt",
		"settings"=>array(
			"Farmie Help Settings,title",
			"dklimit"=>"DK limit for receiving help,range,0,4,1|0",
                  "attackpercent"=>"Percent to decrease enemy attack by,range,0,25,5|10",
                  "defensepercent"=>"Percent to decrease enemy defense by,range,0,25,5|10",
                  "pvpapply"=>"Will players also get the help in PvP fights,bool|0",
		),
	);
	return $info;
}

function farmiehelp_install(){
	module_addhook("newday");
	return true;
}

function farmiehelp_uninstall(){
	return true;
}

function farmiehelp_dohook($hookname,$args){
      global $session;
      switch($hookname){
	case "newday":
		if ($session['user']['dragonkills'] > get_module_setting("dklimit")){
			break;
                  }else{
			apply_buff("farmiehelp",array(
				"name"=>"",
                        "badguyatkmod"=>(1-(get_module_setting("attackpercent") / 100)),
                        "badguydefmod"=>(1-(get_module_setting("defensepercent") / 100)),
				"allowinpvp"=>get_module_setting("pvpapply"),
				"allowintrain"=>1,
				"rounds"=>-1,
				"schema"=>"module-farmiehelp",
				)
			);
		}
            
		break;
       }
	
	return $args;
}
?>