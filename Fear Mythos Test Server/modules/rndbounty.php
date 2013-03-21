<?php
//random bounty for 0.9.8 by Lonny Luberts of http://www.pqcomp.com
//converted from 0.9.7 module
//drop in modules folder and install from module manager

function rndbounty_getmoduleinfo(){
	$info = array(
		"name"=>"Random Bounty",
		"version"=>"1.23",
		"author"=>"`#Lonny Luberts",
		"category"=>"Forest Specials",
		"download"=>"http://www.pqcomp.com/modules/mydownloads/visit.php?cid=3&lid=27",
		"vertxtloc"=>"http://www.pqcomp.com/",
		"settings"=>array(
			"Random Bounty Settings,title",
			"max"=>"Maximum Bounty (Multiplied by user level),int|1000",
		),
	);
	return $info;
}

function rndbounty_install(){
	module_addeventhook("forest", "return 100;");
	return true;
}

function rndbounty_uninstall(){
	return true;
}

function rndbounty_dohook($hookname,$args){
	return $args;
}

function rndbounty_runevent($type){
	global $session;
    $bount = e_rand(100,(get_module_setting('max')*$session['user']['level']));
	output("`4The authorities have placed a bounty of %s gold on your head!`n",$bount);
	$user = $session['user']['name'];
	addnews("`4The authorities have placed a bounty of %s gold on %s .`n",$bount,$user);
	// modified code from dag.php By Andrew Senger
	$setdate = time();
	//took out the set time into the future.... the person was just told about the bounty
	$sql = "INSERT INTO ". db_prefix("bounty") . " (amount, target, setter, setdate) VALUES (".$bount.",".$session['user']['acctid'].",0,'".date("Y-m-d H:i:s",$setdate)."')";
	db_query($sql);
	$sql="SELECT amount FROM bounty WHERE status=0 and target='".$session['user']['acctid']."'";
	$result = db_query($sql) or die(sql_error($sql));
	for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    $total+=$row[amount];
	}
	output("`2You currently have a bounty of %s on your head.",$total);
	
	
}
function rndbounty_run(){
}
?>