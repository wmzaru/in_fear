<?php

function villagenews_getmoduleinfo(){
	$info = array(
		"name"=>"Village News",
		"version"=>"1.05",
		"author"=>"`#Lonny Luberts, `&modified by Oliver Brendel`ntlschema set at news for better translating",
		"category"=>"PQcomp",
		"download"=>"http://www.pqcomp.com/modules/mydownloads/visit.php?cid=3&lid=23",
		"prefs"=>array(
			"Village News Module User Preferences,title",
			"user_villnews"=>"Display Latest News in the Village,bool|1",
		),
		"settings"=>array(
			"Village News Module Settings,title",
			"showhome"=>"Show news on Home Page,enum,0,No,1,Above Login,2,Below Login",
			"newslines"=>"Number of news lines to display in the villages,int|4",
		),
	);
	return $info;
}

function villagenews_install(){
	if (!is_module_active('villagenews')){
		output("`4Installing Village News Module.`n");
	}else{
		output("`4Updating Village News Module.`n");
	}
	module_addhook("village-desc");
	module_addhook("index");
	module_addhook("footer-home");
	return true;
}

function villagenews_uninstall(){
	output("`4Un-Installing Village News Module.`n");
	return true;
}

function villagenews_dohook($hookname,$args){
switch($hookname){
	case "village-desc":
		if (get_module_pref('user_villnews')){
		    tlschema("news");
			output("`n`2`c`bLatest News`b`c");
			output("`2`c-=-=-=-=-=-=-=-`c");
			$sql = "SELECT newstext,arguments FROM ".db_prefix("news")." ORDER BY newsid DESC LIMIT ".get_module_setting('newslines');
			$result = db_query($sql) or die(db_error(LINK));
			for ($i=0;$i<get_module_setting('newslines');$i++){
				$row = db_fetch_assoc($result);
				if ($row['arguments']>""){
			$arguments = array();
			$base_arguments = unserialize($row['arguments']);
			array_push($arguments,$row['newstext']);
			while (list($key,$val)=each($base_arguments)){
				array_push($arguments,$val);
			}
			$newnews = call_user_func_array("sprintf_translate",$arguments);
			}else{
				$newnews = $row['newstext'];
			}
				output("`c %s `c",$newnews);
				if ($i <> get_module_setting('newslines')) output("`2`c-=-=-=-=-=-=-=-`c");
			}
			output("`n");
		   	tlschema("user");
		}
	break;
	case "index":
		if (get_module_setting('showhome') == 1){
		    tlschema("news");
		    output("`n`2`bLatest News`b`n");
			output("`2-=-=-=-=-=-=-=-`n");
			$sql = "SELECT newstext,arguments FROM ".db_prefix("news")." ORDER BY newsid DESC LIMIT ".get_module_setting('newslines');
			$result = db_query($sql) or die(db_error(LINK));
			for ($i=0;$i<get_module_setting('newslines');$i++){
				$row = db_fetch_assoc($result);
				if ($row['arguments']>""){
			$arguments = array();
			$base_arguments = unserialize($row['arguments']);
			array_push($arguments,$row['newstext']);
			while (list($key,$val)=each($base_arguments)){
				array_push($arguments,$val);
			}
			$newnews = call_user_func_array("sprintf_translate",$arguments);
			}else{
				$newnews = $row['newstext'];
			}
				output(" %s `n",$newnews);
				if ($i <> get_module_setting('newslines')) output("`2-=-=-=-=-=-=-=-`n");
			}
			output("`n");
		    tlschema();
		}
	break;
	case "footer-home":
		if (get_module_setting('showhome') == 2){
		    tlschema("news");
			output("`n`2`c`bLatest News`b`c");
			output("`2`c-=-=-=-=-=-=-=-`c");
			$sql = "SELECT newstext,arguments FROM ".db_prefix("news")." ORDER BY newsid DESC LIMIT ".get_module_setting('newslines');
			$result = db_query($sql) or die(db_error(LINK));
			for ($i=0;$i<get_module_setting('newslines');$i++){
				$row = db_fetch_assoc($result);
				if ($row['arguments']>""){
			$arguments = array();
			$base_arguments = unserialize($row['arguments']);
			array_push($arguments,$row['newstext']);
			while (list($key,$val)=each($base_arguments)){
				array_push($arguments,$val);
			}
			$newnews = call_user_func_array("sprintf_translate",$arguments);
			}else{
				$newnews = $row['newstext'];
			}
				output("`c %s `c",$newnews);
				if ($i <> get_module_setting('newslines')) output("`2`c-=-=-=-=-=-=-=-`c");
			}
		    tlschema();
		}

	break;
}
	return $args;
}
?>