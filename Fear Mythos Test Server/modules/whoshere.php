<?php
function whoshere_getmoduleinfo(){
	$info = array(
		"name"=>"Who's Here",
		"version"=>"1.79",
		"author"=>"`#Lonny Luberts- updated by sixf00t4",
		"category"=>"PQcomp",
		"download"=>"http://www.pqcomp.com/modules/mydownloads/visit.php?cid=3&lid=26",
		"vertxtloc"=>"http://www.pqcomp.com/",
		"settings"=>array(
			"Who's Here Module Settings,title",
			"Active in the following locations,note",
			"village"=>"Village,bool|1",
			"forest"=>"Forest,bool|1",
			"gardens"=>"Gardens,bool|1",
			"graveyard"=>"Graveyard,bool|1",
			"gypsy"=>"Gypsy,bool|1",
			"inn"=>"Inn,bool|1",
			"lodge"=>"Lodge,bool|1",
			"stables"=>"Stables,bool|1",
			"rock"=>"Rock,bool|1",
			"superuser"=>"Superuser,bool|1",
		),
		"prefs"=>array(
			"Who's Here User Preferences,title",
			"playerloc"=>"Player Location,text|",
			),
	);
	return $info;
}

function whoshere_install(){
	if (!is_module_active('whoshere')){
		output("`4Installing Who's Here Module.`n");
	}else{
		output("`4Updating Who's Here Module.`n");
	}
	module_addhook("forest");
	module_addhook("village");
	module_addhook("gardens");
	module_addhook("graveyard");
	module_addhook("gypsy");
	module_addhook("inn");
	module_addhook("lodge");
	module_addhook("stables");
	module_addhook("rock");
	module_addhook("header-superuser");
	module_addhook("header-news");
	module_addhook("newday");
	return true;
}

function whoshere_uninstall(){
	output("`4Un-Installing Who's Here Module.`n");
	return true;
}

function whoshere_dohook($hookname,$args){
global $session;
global $_SERVER;
global $REQUEST_URI;
global $SCRIPT_NAME;
if ($hookname == "newday"){
	$sql = "SELECT acctid FROM ".db_prefix("accounts")." WHERE loggedin = 1 and laston < '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."'";
	$result = db_query($sql);
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		db_query("UPDATE ".db_prefix("accounts")." SET loggedin = 0 WHERE acctid = '".$row['acctid']."'");
	}
}
set_module_pref('playerloc', $SCRIPT_NAME);
$j=0;
$yn=str_replace(".php","",$SCRIPT_NAME);
if (get_module_setting($yn) and $SCRIPT_NAME <> "news.php"){
output("`n`@Who Else is here:`n");
$sql1 = "SELECT userid FROM ".db_prefix("module_userprefs")." LEFT JOIN ".db_prefix("accounts")." ON (acctid = userid) WHERE loggedin = 1 and value = '".$SCRIPT_NAME."' and modulename = 'whoshere' and setting = 'playerloc' and userid <> '".$session['user']['acctid']."'";
$result1 = db_query($sql1);
if ($SCRIPT_NAME == "shades.php"){
	$sql2 = "SELECT acctid FROM ".db_prefix("accounts")." WHERE location = '".addslashes($session['user']['location'])."' and alive = 0 and loggedin = 1 and name <> '".$session['user']['name']."' and laston > '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",300)." seconds"))."'";
}elseif ($SCRIPT_NAME == "superuser.php"){
	$sql2 = "SELECT acctid FROM ".db_prefix("accounts")." WHERE alive = 1 and loggedin = 1 and name <> '".$session['user']['name']."' and laston > '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",300)." seconds"))."'";
}else{
	$sql2 = "SELECT acctid FROM ".db_prefix("accounts")." WHERE location = '".addslashes($session['user']['location'])."' and alive = 1 and loggedin = 1 and name <> '".$session['user']['name']."' and laston > '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",300)." seconds"))."'";
}
$k=0;
$result2 = db_query($sql2);
for ($i=0;$i<db_num_rows($result2);$i++){
	$row2 = db_fetch_assoc($result2);
	if (get_module_pref('playerloc','whoshere',$row2['acctid']) == get_module_pref('playerloc')) $k++;
}
for ($i=0;$i<db_num_rows($result1);$i++){
    $row1 = db_fetch_assoc($result1);
    //multiple village/forest/inn compatability
	if ($SCRIPT_NAME == "superuser.php"){
    	$sql = "SELECT name,acctid,location,login FROM ".db_prefix("accounts")." WHERE loggedin = 1 and acctid = '".$row1['userid']."' and name <> \"".$session['user']['name']."\" and laston > '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",300)." seconds"))."' LIMIT 1";
	}else{
    	$sql = "SELECT name,acctid,location,login FROM ".db_prefix("accounts")." WHERE location = '".addslashes($session['user']['location'])."' and loggedin = 1 and acctid = '".$row1['userid']."' and name <> \"".$session['user']['name']."\" and laston > '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",300)." seconds"))."' LIMIT 1";
	}
    $result = db_query($sql);
	$row = db_fetch_assoc($result);
	if ($SCRIPT_NAME == "forest.php" and $row['name'] <> $session['user']['name'] and $row['location'] == $session['user']['location']){
        if(is_module_active("hiddenplayers")){
            $j+=1;
            if($session['user']['superuser']>0){
                if (get_module_pref("hidden", "hiddenplayers", $row['acctid']) == true) {
                    $inchat = "<a><span>".$row['name']."<i> -hidden</i> </span></a>";
                }else{
                    $inchat = "<a><span>".$row['name']."</span></a>";               
                }
            output("%s`7",$inchat,true);
            if ($j < $k - 1){
                output("`0, ");
            }elseif ($j < $k){
                output("`0 & ");
            }    
            }else{
                if (get_module_pref("hidden", "hiddenplayers", $row['acctid']) == false) {
                    $inchat = "<a><span>".$row['name']."</span></a>";                
                    output("%s`7",$inchat,true);
                    if ($j < $k - 1){
                        output("`0, ");
                    }elseif ($j < $k){
                        output("`0 & ");
                    }
                }
            }
        }else{
            $inchat = "<a><span>".$row['name']."</span></a>";
            //Not sure why we need an addnav, when there is no link to the bio. - sixf00t4           
            //addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));            
            $j+=1;
            output("%s`7",$inchat,true);
            if ($j < $k - 1){
                output("`0, ");
            }elseif ($j < $k){
                output("`0 & ");
            }
        }
	}elseif ($SCRIPT_NAME == "superuser.php" and $row['name'] <> $session['user']['name']){
		$inchat = "<a href=\"bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI'])."\"><span>".$row['name']."</span></a>";
		addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));
		$j+=1;
		output("%s`7",$inchat,true);
		if ($j < $k - 1){
			output("`0, ");
		}elseif ($j < $k){
			output("`0 & ");
		}	
	}elseif ($row['name'] <> $session['user']['name'] and $row['location'] == $session['user']['location']){
        if(is_module_active("hiddenplayers")){
            if($session['user']['superuser']>0){
                if (get_module_pref("hidden", "hiddenplayers", $row['acctid']) == true) {
                    $inchat = "<a href=\"bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI'])."\"><span>".$row['name']."<i>-hidden</i></span></a>";
                }else{    
                    $inchat = "<a href=\"bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI'])."\"><span>".$row['name']."</span></a>";
                }    
                addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));
                $j+=1;
                output("%s`7",$inchat,true);
                if ($j < $k - 1){
                    output("`0, ");
                }elseif ($j < $k){
                    output("`0 & ");
                }
            }else{
                if (get_module_pref("hidden", "hiddenplayers", $row['acctid']) == false) {
                   	$inchat = "<a href=\"bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI'])."\"><span>".$row['name']."</span></a>";
                    addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));
                    $j+=1;
                    output("%s`7",$inchat,true);
                    if ($j < $k - 1){
                        output("`0, ");
                    }elseif ($j < $k){
                        output("`0 & ");
                    }
                }
        }
        }else{
            $inchat = "<a href=\"bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI'])."\"><span>".$row['name']."</span></a>";
            addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));
            $j+=1;
            output("%s`7",$inchat,true);
            if ($j < $k - 1){
                output("`0, ");
            }elseif ($j < $k){
                output("`0 & ");
            }	
        }
    }
}
if ($j==0){
	output("`2No one`7..`6");
}else{
output(".");
}
output("`n`2-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-`n");
}
return $args;
}
?>