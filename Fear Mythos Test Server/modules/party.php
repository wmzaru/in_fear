<?php
require_once("lib/commentary.php");
//recipes for bar and bartender from http://cocktails.about.com
function party_getmoduleinfo(){
	$info = array(
		"name" => "House Party",
		"author" => "`b`&Ka`6laza`&ar`b",
		"version" => "1.0",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;catd=11",
		"category" => "Village",
		"description" => "Party Room and Shop for houses",
		"requires"=>array(
            "houses" => "1.0|`b`&Ka`6laza`&ar`b",
            ),
		"settings" => array(
			"name" => "What is the Party Shops name, int|Party Supplies",
			"owner" => "Shop owners name, int|`8T`7a`5hl`7i`8a",
			"barc"=> "Cost in gold to hire bar, int|10000000",
			"barcg"=>"Cost in gems to hire bar, int|50",
			),
		"prefs" => array(
			"House Party User Preferences,title",
      "canedit"=>"Has access to the Party Supplies editor,bool|0",
			"room" => "Does this user have a party room, bool|0",
			"build" => "Is this user building a party room, bool|0",
			"builds" => "How many steps has this user gone through to build a room, int|0",
			"supplies1" => "Purchased from Party Shop, int|0",
			"supplies2" => "Purchased from Party Shop, int|0",
			"supplies3" => "Purchased from Party Shop, int|0",
			"hireb" => "Has this user hired a bar and bartender, bool|0",
			"active" => "Is there a party in progress, bool|0",
			"houseid" => "Houseid, int|0",
			"current" =>"house user is currently in, int|0",
			"punch" => "type of punch bartender makes, int|0",
			),
			);
		return $info;
	}

function party_install(){
	 require_once("lib/tabledescriptor.php");
	  $populate = false;
        if (!db_table_exists(db_prefix("partysupplies"))) {
                $populate = true;
            }
	   $partykeys = array(
		'keyid'=>array('name'=>'keyid', 'type'=>'int unsigned',	'extra'=>'not null auto_increment'),
		'keyholder'=>array('name'=>'keyholder', 'type'=>'int unsigned',	'default'=>'0', 'extra'=>'not null'),
		'houseid'=>array('name'=>'houseid', 'type'=>'int unsigned',	'extra'=>'not null'),
		'ownerid'=>array('name'=>'ownerid', 'type'=>'int unsigned',	'default'=>'0', 'extra'=>'not null'),
		'key-PRIMARY'=>array('name'=>'PRIMARY', 'type'=>'primary key',	'unique'=>'1', 'columns'=>'keyid'),
		'index-keyholder'=>array('name'=>'keyholder', 'type'=>'index', 'columns'=>'keyholder'),
		'index-houseid'=>array('name'=>'houseid', 'type'=>'index', 'columns'=>'houseid'),
		'index-ownerid'=>array('name'=>'ownerid', 'type'=>'index', 'columns'=>'ownerid'));
		$partysupplies = array(
		'supplyid'=>array('name'=>'supplyid', 'type'=>'int unsigned',	'extra'=>'not null auto_increment'),
		'name'=>array('name'=>'name', 'type'=>'text'),
		'gold'=>array('name'=>'gold', 'type'=>'int unsigned',	'extra'=>'not null'),
		'gems'=>array('name'=>'gems', 'type'=>'int unsigned',	'default'=>'0', 'extra'=>'not null'),
		'key-PRIMARY'=>array('name'=>'PRIMARY', 'type'=>'primary key',	'unique'=>'1', 'columns'=>'supplyid'));
		synctable(db_prefix('partykeys'), $partykeys, true);
		synctable(db_prefix('partysupplies'), $partysupplies, true);
		 //code copied and modified from Gift Shoppe by JT Traub
		if ($populate) {
      // Create some basic party supplies
			$basesupplies = array(
      "INSERT INTO " . db_prefix("partysupplies") . " VALUES (0, '`QBa`@ll`^oo`#ns`0', 50, 0)",
      "INSERT INTO " . db_prefix("partysupplies") . " VALUES (0, '`@St`3re`Qam`!er`4s`0', 100, 0)",
      "INSERT INTO " . db_prefix("partysupplies") . " VALUES (0, '`&Pa`^rt`@y P`6op`8pe`2rs`0', 150, 0)",
      "INSERT INTO " . db_prefix("partysupplies") . " VALUES (0, '`2Pa`&rt`@y H`^at`7s`0', 200, 0)",
      "INSERT INTO " . db_prefix("partysupplies") . " VALUES (0, '`!Ma`5sk`&s`0', 0, 1)"
			);
    	while (list($key,$sql)=each($basesupplies)){
    	db_query($sql);
		}
	}
	//end of copied code
	module_addhook("village");
	module_addhook("superuser");
	module_addhook("newday");
	module_addhook("moderate");
	module_addhook("houses");
	module_addhook("estate");
	return true;
}
function party_uninstall(){
	debug("Dropping partykeys table");
        $sql = "DROP TABLE IF EXISTS " . db_prefix("partykeys");
        db_query($sql);
        debug("Dropping partysupplies table");
        $sql = "DROP TABLE IF EXISTS " . db_prefix("partysupplies");
        db_query($sql);
	return true;
}
function party_dohook($hookname, $args){
	global $session;
	$name = get_module_setting("name");
	$id=$session['user']['acctid'];
	switch ($hookname){
		case "houses":
			if (get_module_pref("room")==1){
				addnav("Rooms");
				addnav("Party Room","runmodule.php?module=party&op=partyroom&houseid=$id");
			}
			break;
		case "estate":
		if (get_module_pref("room")==0 && get_module_pref("own","houses",$id)>2){
			addnav("Upgrades");
			addnav("Party Room","runmodule.php?module=party&op=bparty");
			output("You may build a room in which to throw parties and talk to your friends");
		}
		addnav("Other");
		addnav("View Parties", "runmodule.php?module=party&op=invites","party");
		output("`n`nYou may view parties you have invites to`n`n");
		break;
  	case "village":
  		if (get_module_pref("room")==1){
	  		if ($session['user']['location'] == get_module_setting("houseloc","houses"))
				tlschema($args['schemas']['gatenav']);
		    addnav($args['gatenav']);
				tlschema();
				addnav(array("%s `0",$name),"runmodule.php?module=party&op=partyshop");
		}
	    break;
	//code copied from giftshoppe by JT Traub
	case "superuser":
         if (get_module_pref("canedit")==1) {
          addnav("Module Configurations");
          // Stick the admin=true on so that when we call runmodule it'll
          // work to let us edit supplys even when the module is deactivated.
          addnav("Party Supplies Editor", "runmodule.php?module=party&op=editor&admin=true");
           }
           break;
    //end of copied code
    case "newday":
    if (get_module_pref("room")==1){
    if (get_module_pref("active")==1){
    	set_module_pref("punch",0);
	    set_module_pref("hireb",0);
	    set_module_pref("supplies1",0);
	    set_module_pref("supplies2",0);
	    set_module_pref("supplies3",0);
    	set_module_pref("active",0);
    	output("`#Your Party has ended");
	}
	}else{
		set_module_pref("active",0);
	}
	break;
	case "moderate":
		 $sql = "SELECT * FROM " . db_prefix("partykeys");
			$result = db_query($sql);
			if (db_num_rows($result)>0){
				for ($i=0;$i<db_num_rows($result);$i++){
					$row = db_fetch_assoc($result);
					$x = "Party".$row['houseid'];
					$y = "<".$row['houseid']." - "."PartyRoom";
					$args[$x] = $y;
				}
			}
       			break;
    }
        return $args;
    }
function party_run(){
	global $session;

	$party = get_module_pref("room");
	$build = get_module_pref("build");
	$builds = get_module_pref("builds");
	$squares = get_module_pref("squares","lumberyard");
	$blocks = get_module_pref("blocks", "quarry");
	$drunkeness=get_module_pref("drunkeness","drinks");
	$maxdrunk=get_module_setting("maxdrunk","drinks");
	$op = httpget("op");
	$name = get_module_setting("name");
	$id=$session['user']['acctid'];
if ($op =="editor"){
    partysupplies_editor();
}
if ($op=="partyroom"){
	page_header("Party Room");
	output("`b`c`2Party Room`0`c`b");

	$id = $session['user']['acctid'];
    $houseid = httpget('houseid');
    $match = $houseid;
     if ($match==$id){
	     $punch = get_module_pref("punch");
	     set_module_pref("housein",$houseid);
	    addnav("Party Room Guide", "runmodule.php?module=party&op=guide");
		addnav("Your Invites","runmodule.php?module=party&op=partyk");
		output("`n`nYou walk into your Party Room .`n");
		if (get_module_pref("active")==0){
			output("sorry there is no party being held at the moment, perhaps you should start one");
			addnav("Start a Party", "runmodule.php?module=party&op=startp");
		}else{
		$id = get_module_pref("supplies1");
		$sql = "SELECT * FROM " . db_prefix("partysupplies") . " WHERE supplyid='$id'";
    	$row = db_fetch_assoc(db_query($sql));
    	$supply1 = $row['name'];

		$id = get_module_pref("supplies2");
		$sql = "SELECT * FROM " . db_prefix("partysupplies") . " WHERE supplyid='$id'";
    	$row = db_fetch_assoc(db_query($sql));
    	$supply2 = $row['name'];
		$id = get_module_pref("supplies3");
		$sql = "SELECT * FROM " . db_prefix("partysupplies") . " WHERE supplyid='$id'";
    	$row = db_fetch_assoc(db_query($sql));
    	$supply3 = $row['name'];
	   	output("`^There is a Party going in full swing, you see %s, %s, %s `^decorating the place",$supply1, $supply2, $supply3);
	    if (get_module_pref("hireb")==1){
	    	output("`n`n`^On the end of one of the tables, is a bowl of %s",$punch);
	    	addnav("Bar","runmodule.php?module=party&op=bar");

    	}
    	output("`n`n");
	addcommentary();
	viewcommentary("Party".$houseid,"Party with Friends",30,"Parties");
	    }
    }elseif ($match<>$id){
		output("`n`nYou walk into Your Friends Party room .`n");
		$sql = "SELECT * FROM " .db_prefix("module_userprefs"). " WHERE modulename = 'party' AND setting = 'active' AND userid = '$match'";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$active=$row['value'];
		if ($active==0){
			output("sorry there is no party being held at the moment");
		}
		if ($active==1){
			set_module_pref("housein",$houseid);
			$sql = "SELECT * FROM " .db_prefix("module_userprefs"). " WHERE modulename = 'party' AND setting = 'supplies1' AND userid = '$match'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$id = $row['value'];
			$sql = "SELECT * FROM " . db_prefix("partysupplies") . " WHERE supplyid='$id'";
    		$row = db_fetch_assoc(db_query($sql));
    		$supply1 = $row['name'];
			$sqla = "SELECT * FROM " .db_prefix("module_userprefs"). " WHERE modulename = 'party' AND setting = 'supplies2' AND userid = '$match'";
			$resa = db_query($sqla);
			$rowa = db_fetch_assoc($resa);
			$id = $rowa['value'];
			$sql = "SELECT * FROM " . db_prefix("partysupplies") . " WHERE supplyid='$id'";
    		$row = db_fetch_assoc(db_query($sql));
    		$supply2 = $row['name'];
			$sqlb = "SELECT * FROM " .db_prefix("module_userprefs"). " WHERE modulename = 'party' AND setting = 'supplies3' AND userid = '$match'";
			$resb = db_query($sqlb);
			$rowb = db_fetch_assoc($resb);
			$id = $rowb['value'];
			$sql = "SELECT * FROM " . db_prefix("partysupplies") . " WHERE supplyid='$id'";
    		$row = db_fetch_assoc(db_query($sql));
    		$supply3 = $row['name'];
			output("`^There is a Party going in full swing, you see %s, %s, %s `^decorating the place", $supply1, $supply2, $supply3);
			$sql = "SELECT * FROM " .db_prefix("module_userprefs"). " WHERE modulename = 'party' AND setting = 'hireb' AND userid = '$match'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$hireb=$row['value'];
			if ($hireb==1){
			$sql = "SELECT * FROM " .db_prefix("module_userprefs"). " WHERE modulename = 'party' AND setting = 'punch' AND userid = '$match'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$punch=$row['value'];
				output("`n`n`^On the end of one of the tables, is a bowl of %s",$punch);
	    		addnav("Bar","runmodule.php?module=party&op=bar");
    		}
    		output("`n`n");
addcommentary();
viewcommentary("Party".$houseid,"Party with Friends",30,"Parties");
		}
	}
	$id1=$session['user']['acctid'];
if (get_module_pref("own","houses",$id1)<>0){
	addnav("Return to House", "runmodule.php?module=houses&op=house","houses");
}else{
addnav("Return to Village", "village.php?");
}
if ($op == "partyroom"){
	$acctid = $session['user']['acctid'];
	$sqlo = "SELECT * FROM " .db_prefix("partykeys") . " WHERE ownerid = '$acctid'";
	$reso = db_query($sqlo);
	$rowo = db_fetch_assoc($reso);
	$houseid = $rowo['houseid'];
	if ($houseid==0){
		$houseid=$acctid;
	}
	set_module_pref("current",$houseid);
	}else{

	set_module_pref("current",$current);
	}
	page_footer();
}

if ($op=="guide"){
	$houseid = get_module_pref("houseid");
	page_header("Party Room Guide");
	addnav("Return to PartyRoom", "runmodule.php?module=party&op=partyroom&houseid=$houseid");
	output("`c`b`^GUIDELINES`b`c");
	output("`2`cThese are a few basic guidelines to using your party room.`n`n  Do NOT complain if you have not read them.`c`n`n");
	output("`@1.  Parties must be started, you can start a party by clicking on the link in your Party Room.`n`n");
	output("2.  A party will only last until the next new day, on new day it will be ended.`n`n");
	output("3.  There is a Party Supplies shop, where you may purchase items for your party.`n`n");
	page_footer();
}
if ($op=="startp"){
	page_header("Start a Party");

	set_module_pref("active",1);
	output("Your Party has been started");
	if (get_module_pref("hireb")==1){
		punch_outcome();
	}
	$houseid = get_module_pref("housein");
	addnav("Return to Party Room", "runmodule.php?module=party&op=partyroom&houseid=$houseid");
	page_footer();
}
if ($op=="bar"){
	page_header("Private Bar");
	$houseid = get_module_pref("housein");
	if($drunkeness>$maxdrunk){
		output("`6The Bartender looks at you, \"`^I'm sorry, but I cannot serve you anymore\" `6 and you realise you've reached the bars limit.");
		addnav("Return to Party", "runmodule.php?module=party&op=partyroom&houseid=$houseid");
	}else{
	output("You approach the bar, the bartender looks up at you, \"What can I get you\" he says, gesturing at the current drinks list.");
	addnav("Absolut Quaalude","runmodule.php?module=party&op=qaalude");
	addnav("Aggravation","runmodule.php?module=party&op=aggravation");
	addnav("Bad Habit","runmodule.php?module=party&op=habit");
	addnav("Werewolf","runmodule.php?module=party&op=werewolf");
	addnav("Vampire","runmodule.php?module=party&op=vampire");
	addnav("Black Devil Cocktail", "runmodule.php?module=party&op=devil");
	addnav("Nightmare", "runmodule.php?module=party&op=nightmare");
	addnav("Purple Rain", "runmodule.php?module=party&op=rain");
	addnav("Rattlesnake", "runmodule.php?module=party&op=rattlesnake");
	addnav("Pink Almond", "runmodule.php?module=party&op=almond");
	addnav("Moon Beam", "runmodule.php?module=party&op=beam");
	addnav("Return to Party", "runmodule.php?module=party&op=partyroom&houseid=$houseid");
}
	page_footer();
}
if ($op=="qaalude"){
	page_header("Absolut Qaalude");
	output("The Bartender, pours 1/3 Absolut Vodka, 1/3 Baileys and 1/3 Frangelico over ice and mixes it with a long spoon.`n`n");
	output("With a flourish of his hand, he strains the mixture into a large shot glass and places it on the bar counter in front of you.");
	$drunkeness+=10;
	$session['user']['hitpoints']*=1.07;
	set_module_pref("drunkeness",$drunkeness,"drinks");
	apply_buff('buzz',array("name"=>"Qallude Buzz","rounds"=>10,"atkmod"=>1.06, "schema"=>"module-party"));
	addnav("Return to the Bar", "runmodule.php?module=party&op=bar");
	page_footer();
}
if ($op=="aggravation"){
	page_header("Aggravation");
	output("The Bartender, pours 1 1/4 oz. Scotch, 3/4 oz. Coffee liqueur, 3 oz. Cream into a highball glass filled with ice.`n`n");
	output("He places the drink in down in front of you.");
	$drunkeness+=10;
	$session['user']['hitpoints']*=1.07;
	set_module_pref("drunkeness",$drunkeness,"drinks");
	apply_buff('buzz',array("name"=>"Aggravation Buzz","rounds"=>10,"atkmod"=>1.06, "schema"=>"module-party"));
	addnav("Return to the Bar", "runmodule.php?module=party&op=bar");
	page_footer();
}
if ($op=="habit"){
	page_header("Bad Habit");
	output("The Bartender, pours  equal parts of Vodka and Peach schnapps into a shaker, adds some ice and fastens the lid in place.  He shakes your drink.`n`n");
	output("Turning he strains the mix into a chilled cocktail glass on the countertop in front of you.");
	$drunkeness+=10;
	$session['user']['hitpoints']*=1.07;
	set_module_pref("drunkeness",$drunkeness,"drinks");
	apply_buff('buzz',array("name"=>"Bad Habit Buzz","rounds"=>10,"atkmod"=>1.06, "schema"=>"module-party"));
	addnav("Return to the Bar", "runmodule.php?module=party&op=bar");
	page_footer();
}
if ($op=="werewolf"){
	page_header("Werewolf");
	output("The Bartender, adds 1 oz. each of Drambuie and Bourbon to a rock glass containing ice.`n`n");
	output("He places the drink in front of you.");
	$drunkeness+=10;
	$session['user']['hitpoints']*=1.07;
	set_module_pref("drunkeness",$drunkeness,"drinks");
	apply_buff('buzz',array("name"=>"Werewolf Buzz","rounds"=>10,"atkmod"=>1.06, "schema"=>"module-party"));
	addnav("Return to the Bar", "runmodule.php?module=party&op=bar");
	page_footer();
}
if ($op=="vampire"){
	page_header("Vampire");
	output("The Bartender, pours 1 1/4 oz. Vodka, 3/4 oz. Chambord and a splash of Cranberry juice into a shaker, adds some ice and fastens the lid in place.  He shakes your drink.`n`n");
	output("Turning he strains the mix into a chilled cocktail glass on the countertop in front of you.");
	$drunkeness+=10;
	$session['user']['hitpoints']*=1.07;
	set_module_pref("drunkeness",$drunkeness,"drinks");
	apply_buff('buzz',array("name"=>"Vampire Buzz","rounds"=>10,"atkmod"=>1.06, "schema"=>"module-party"));
	addnav("Return to the Bar", "runmodule.php?module=party&op=bar");
	page_footer();
}
if ($op=="devil"){
	page_header("Black Devil Cocktail");
	output("The Bartender, pours 2 oz. Rum and 1/2 oz. Dry Vermouth into a shaker, adds some ice and fastens the lid in place.  He shakes your drink.`n`n");
	output("Turning he strains the mix into a chilled cocktail glass, adds a black olive to it and places it on the countertop in front of you.");
	$drunkeness+=10;
	$session['user']['hitpoints']*=1.07;
	set_module_pref("drunkeness",$drunkeness,"drinks");
	apply_buff('buzz',array("name"=>"Black Devil Buzz","rounds"=>10,"atkmod"=>1.06, "schema"=>"module-party"));
	addnav("Return to the Bar", "runmodule.php?module=party&op=bar");
	page_footer();
}
if ($op=="nightmare"){
	page_header("Nightmare");
	output("The Bartender, adds 1/3 Jagermeister, 1/3 Bacardi 151 and 1/3 Goldschlager to a shooter glass.`n`n");
	output("He carefully places it on the countertop in front of you with the admonishment \"`4Be Careful\".");
	$drunkeness+=10;
	$session['user']['hitpoints']*=1.07;
	set_module_pref("drunkeness",$drunkeness,"drinks");
	apply_buff('buzz',array("name"=>"Nightmare Buzz","rounds"=>10,"atkmod"=>1.06, "schema"=>"module-party"));
	addnav("Return to the Bar", "runmodule.php?module=party&op=bar");
	page_footer();
}
if ($op=="rain"){
	page_header("Purple Rain");
	output("The Bartender, pours 1 1/2 oz. Vodka, a splash of Blue curacao and a splash of Cranberry juice into a shaker, adds some ice and fastens the lid in place.  He swirls the shaker around mixing your drink.`n`n");
	output("Turning he strains the mix into a chilled cocktail glass and places it on the countertop in front of you.");
	$drunkeness+=10;
	$session['user']['hitpoints']*=1.07;
	set_module_pref("drunkeness",$drunkeness,"drinks");
	apply_buff('buzz',array("name"=>"Purple Rain Buzz","rounds"=>10,"atkmod"=>1.06, "schema"=>"module-party"));
	addnav("Return to the Bar", "runmodule.php?module=party&op=bar");
	page_footer();
}
if ($op=="rattlesnake"){
	page_header("Rattlesnake");
	output("The Bartender, pours in a layer of 1 oz. Kahlua, a second layer of1 1/2 oz. Amaretto and a final layer of 1/2 oz. Cream into a shot glass`n`n");
	output("He slides the shotglass across the countertop to you.");
	$drunkeness+=10;
	$session['user']['hitpoints']*=1.07;
	set_module_pref("drunkeness",$drunkeness,"drinks");
	apply_buff('buzz',array("name"=>"Rattlesnake Buzz","rounds"=>10,"atkmod"=>1.06, "schema"=>"module-party"));
	addnav("Return to the Bar", "runmodule.php?module=party&op=bar");
	page_footer();
}
if ($op=="almond"){
	page_header("Pink Almond");
	output("The Bartender, pours 1 oz. Whiskey, 3/4 oz. Creme de Noyaux, 3/4 oz. Amaretto and a splash of Sour Mix into a shaker, adds some ice and fastens the lid in place.  He swirls the shaker around mixing your drink.`n`n");
	output("Turning he strains the mix into a chilled cocktail glass and places it on the countertop in front of you.");
	$drunkeness+=10;
	$session['user']['hitpoints']*=1.07;
	set_module_pref("drunkeness",$drunkeness,"drinks");
	apply_buff('buzz',array("name"=>"Pink Almond Buzz","rounds"=>10,"atkmod"=>1.06, "schema"=>"module-party"));
	addnav("Return to the Bar", "runmodule.php?module=party&op=bar");
	page_footer();
}
if ($op=="beam"){
	page_header("Moon Beam");
	output("The Bartender, pours 3/4 oz. Amaretto and 3/4 oz. White Creme de Cacoa into a shaker, adds some ice and fastens the lid in place.  He swirls the shaker around mixing your drink.`n`n");
	output("Turning he strains the mix into a chilled cocktail glass and places it on the countertop in front of you.");
	$drunkeness+=10;
	$session['user']['hitpoints']*=1.07;
	set_module_pref("drunkeness",$drunkeness,"drinks");
	apply_buff('buzz',array("name"=>"Moon Beam Buzz","rounds"=>10,"atkmod"=>1.06, "schema"=>"module-party"));
	addnav("Return to the Bar", "runmodule.php?module=party&op=bar");
	page_footer();
}

//add drinks and what they do
if($op=="invites"){
	//to see who has given you a key and to go to a party
	page_header("Your Invites");
	$partykeys = db_prefix("partykeys");
	$sql = "SELECT $partykeys.houseid AS houseid,
		$partykeys.ownerid AS ownerid,
		$partykeys.keyholder FROM $partykeys
		WHERE $partykeys.ownerid != ".$session['user']['acctid']."
		AND $partykeys.keyholder = ".$session['user']['acctid']."
		ORDER BY $partykeys.keyid DESC";
	$result = db_query($sql);
    $owner = translate_inline("Owner");
    $visit = translate_inline("Parties");
	rawoutput("<table border='0' cellpadding='3' cellspacing='0' align='center'><tr class='trhead'><td style='width:250px' align=center>$owner</td><td align=center>$visit</td></tr>");
	if(!db_num_rows($result)){
		$none = translate_inline("None");
        rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td align=center  colspan=4><i>$none</i></td></tr>");
    }else{
		for ($i = 0; $i < db_num_rows($result); $i++){
        $row = db_fetch_assoc($result);
        $ownerid = $row['ownerid'];
		$sql2 = "SELECT * FROM ".db_prefix("accounts")." WHERE acctid = '$ownerid'";
        $result2 = db_query($sql2);
        $row2 = db_fetch_assoc($result2);
        $owner = $row2['name'];
        $houseid = $row['houseid'];
        $num = $i+1;
		rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>");
		output_notl($owner);
		rawoutput("</td><td>");
        rawoutput("<a href=runmodule.php?module=party&op=partyroom&houseid=$houseid>");
        addnav("","runmodule.php?module=party&op=partyroom&houseid=".$houseid);
        output_notl("`#[`&$visit`#]`0");
    	}
    }
    rawoutput("</table>");
	if (get_module_pref("own","houses",$id)<>0){
	if (is_module_active("houses")) addnav("Return to House", "runmodule.php?module=houses&op=house","houses");
	}else{
	addnav("Return to Village", "village.php?");
	}

	page_footer();
}
if ($op=="partyk"){
	page_header("Your Party Invites");
	output("Give or Take Invites to your current Party");
	addnav("Give or Take a Invite", "runmodule.php?module=party&op=gtkeys");
	if (is_module_active("houses")) addnav("Return to House", "runmodule.php?module=houses&op=house","houses");
	page_footer();
}
if($op=="gtkeys"){
	//giving and taking keys to your party
		page_header("Your Party Invites");
		$houseid = get_module_pref("houseid");
		$sql="SELECT * FROM " .db_prefix("partykeys"). " WHERE houseid = '$houseid'";
		$res=db_query($sql);
		$row=db_fetch_assoc($res);
		$ownerid=$row['ownerid'];
		$sql = "SELECT keyholder, keyid FROM ".db_prefix("partykeys")."
			WHERE houseid = $houseid
			AND keyholder != ".$session['user']['acctid']."
			ORDER BY keyid ASC";
		$result = db_query($sql);
        $number = translate_inline("Number");
        $owner = translate_inline("Owner");
        $take = translate_inline("Take back");
        $give = translate_inline("Give away");
      	rawoutput("<table border='0' cellpadding='3' cellspacing='0' align='center'><tr class='trhead'><td style=\"width:35px\">$number</td><td style='width:250px' align=center>$owner</td></tr>");
        for ($i=0;$i<15;$i++){
        	$row = db_fetch_assoc($result);
		if($row['keyid']>0 && $row['keyholder']!=0){
            $sql2 = "SELECT * FROM ".db_prefix("accounts")." WHERE acctid=".$row['keyholder'];
            $result2 = db_query($sql2);
            $row2 = db_fetch_assoc($result2);
        	$name = $row2['name'];
		if($name == "") $name = translate_inline("No one");
            $num = $i+1;
            $keyid = $row['keyid'];
            rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>");
			output_notl($num);
            rawoutput("</td><td align=center>");
			output_notl($name);
			rawoutput("</td><td>");
            addnav("","runmodule.php?module=party&op=takek&keyid=$keyid&houseid=$houseid");
            rawoutput("<a href=runmodule.php?module=party&op=takek&keyid=$keyid&houseid=$houseid>");
            output_notl("`#[`&$take`#]`0");
			rawoutput("</a></td></tr>");
		}else{
            $name = translate_inline("No one");
            $num = $i+1;
            rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>");
            addnav("","runmodule.php?module=party&op=givek&houseid=$houseid");
            output_notl($num);
            rawoutput("</td><td align=center>");
			output_notl($name);
			rawoutput("</td><td>");
            rawoutput("<a href=runmodule.php?module=party&op=givek&houseid=$houseid>");
            output_notl("`#[`&$give`#]`0");
			rawoutput("</a></td></tr>");
    	}
	}
	rawoutput("</table>");

	if (is_module_active("houses")) addnav("Return to House", "runmodule.php?module=houses&op=house","houses");

	page_footer();
}
if ($op=="givek"){
	//giving a key
	page_header("Give Invite");
	output("`2Who do you want to give this Invite to?`n`n");
    	$submit = translate_inline("Submit");
        rawoutput("<form action='runmodule.php?module=party&op=givek2&keyid=$keyid&houseid=$houseid' method='POST'>");
        addnav("","runmodule.php?module=party&op=givek2&keyid=$keyid&houseid=$houseid");
        rawoutput("<input name='name' id='name'> <input type='submit' class='button' value='$submit'>");
        rawoutput("</form>");
        rawoutput("<script language='JavaScript'>document.getElementById('name').focus()</script>");
        if (is_module_active("houses")) addnav("Return to House", "runmodule.php?module=houses&op=house","houses");
        page_footer();
}
if ($op=="givek2"){
	page_header("Give Invite");
	$sql = "SELECT login,name,level,acctid FROM accounts WHERE name = '".addslashes($_POST['name'])."' and acctid != ".$session['user']['acctid']." AND locked=0 ORDER BY level,login";
    $result = db_query($sql);
    if (db_num_rows($result) <> 1) {
    	$string="%";
        for ($x=0;$x<strlen(httppost('name'));$x++){
        $string .= substr(httppost('name'),$x,1)."%";
    }
    $sql = "SELECT login,name,level,acctid FROM accounts WHERE name LIKE '".addslashes($string)."' and acctid != ".$session['user']['acctid']." AND locked=0 ORDER BY level,login";
    $result = db_query($sql);
    }
    if (db_num_rows($result)<=0){
    	output("`2There is no one with that name.");
    }elseif(db_num_rows($result)>100){
        output("There are too many matches, please try to narrow the search down a bit.`n`n");
        rawoutput("<form action='runmodule.php?module=party&op=givek2&keyid=$keyid&houseid=$houseid' method='POST'>");
        addnav("","runmodule.php?module=party&op=givek2&keyid=$keyid&houseid=$houseid");
        output("`2Who do you want to give the invite to?`n`n");
        rawoutput("<input name='name' id='name'> <input type='submit' class='button' value='Suchen'>",true);
        rawoutput("</form>");
        rawoutput("<script language='JavaScript'>document.getElementById('name').focus()</script>");
	}else{
    	output("Which player did you mean?`n`n");
        rawoutput("<table cellpadding='3' cellspacing='0' border='0'>");
        rawoutput("<tr class='trhead'><td>Name</td><td>Level</td></tr>");
        for ($i=0;$i<db_num_rows($result);$i++){
        	$row = db_fetch_assoc($result);
            rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>");
            rawoutput("<a href='runmodule.php?module=party&op=givek3&keyid=$keyid&houseid=$houseid&keyholder=".HTMLEntities($row['acctid'])."'>");
            output_notl($row['name']);
            rawoutput("</a></td><td>");
            output($row['level']);
            rawoutput("</td></tr>");
            addnav("","runmodule.php?module=party&op=givek3&keyid=$keyid&houseid=$houseid&keyholder=".HTMLEntities($row['acctid']));

            }
        rawoutput("</table>",true);
	}
	if (is_module_active("houses")) addnav("Return to House", "runmodule.php?module=houses&op=house","houses");
page_footer();
}

if ($op=="givek3"){
	page_header("Give Invite");
	$houseid = get_module_pref("houseid");
	$keyholder = httpget('keyholder');
	if($keyid == ""){
    	$sql = "SELECT keyid FROM ".db_prefix("partykeys")." WHERE keyholder = 0 AND houseid = '$houseid' LIMIT 1";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $keyid = $row['keyid'];
    }
  	if($keyid==""){
		$sql = "INSERT INTO ".db_prefix("partykeys")." (houseid, keyholder) VALUES ('$houseid', '$keyholder')";
    }else{
		$sql = "UPDATE ".db_prefix("partykeys")." SET keyholder = '$keyholder' WHERE keyid = '$keyid'";
	}
    db_query($sql);
    require_once("lib/systemmail.php");
    $sqlk = "SELECT * FROM ".db_prefix("accounts")." WHERE acctid = '$keyholder'";
    $resk = db_query($sqlk);
    $rowk = db_fetch_assoc($resk);
    $kname = $rowk['name'];
    output("A mail has been sent to %s informing them you have given then an Invite to your Party!", $kname);
    systemmail($keyholder,"`^You Have a Invitation!`0",array("`&%s`& has Invited you to their Party, you can access this from either Dragonscale Realty in Blades Boulevarde, or the Parties link in your house.",$session['user']['name']));
    if (is_module_active("houses")) addnav("Return to House", "runmodule.php?module=houses&op=house","houses");

    page_footer();
}
if ($op=="takek"){
	//taking a invite back
	page_header("Take Invite Back");
	$keyid = httpget('keyid');
	$houseid = get_module_pref("houseid");
	$keyholder = httpget ('keyholder');
	$sql = "UPDATE ".db_prefix("partykeys")." SET keyholder = 0 WHERE keyid = $keyid AND houseid = " . $houseid;
    db_query($sql);
    output("Invite taken back.");
    if (is_module_active("houses")) addnav("Return to House", "runmodule.php?module=houses&op=house","houses");
    page_footer();
    }
if ($op=="bparty"){
	page_header("Build Party Room");

	if ($party==1){
		output("`^You cannot build another Party Room as you already have one.");
		if (is_module_active("houses")) addnav("Back to Office", "runmodule.php?module=houses&op=office","houses");
	}
	if ($party==0 && $build==0){
		output("`^A Party Room, is a very nice option, you may host a party for your friends.  There is also a party supplies shop, from which you may purchase up to 3 items to add to your party");
		output_notl("`n`n");
		output("`6To build a party room entirely takes 350 blocks of stone from the Quarry, 200 blocks of wood from the lumberyard, and 50 turns");
		output_notl("`n`n");
		output("`^To make it a little easier, you may build it all at once, or slowly, (in 5 sections, of 70 stone, 40 wood, 10 turns).  Would you like to build a Party Room now?");
		addnav("Build complete", "runmodule.php?module=party&op=buildc");
		addnav("Build in Sections", "runmodule.php?module=party&op=buildpart");
		if (is_module_active("houses")) addnav("Back to Office", "runmodule.php?module=houses&op=office","houses");
	}
	if ($party == 0 && $build == 1){
		output("`^You are currently building a Party Room, you have completed %s sections, would you like to continue?", $builds);
		output_notl("`n`n");
		output("`6Remember it takes 70 blocks of stone, 40 squares of wood and 10 turns per section");
		addnav("Continue Building", "runmodule.php?module=party&op=buildpart");
		if (is_module_active("houses")) addnav("Back to Office", "runmodule.php?module=houses&op=office","houses");
	}
	page_footer();
}
if ($op=="buildc"){
	page_header("Building Your Party Room");
	if ($blocks<350 || $squares<200 || $session['user']['turns']<50){
		output("You do not have the needed supplies to build a complete party room");
		if (is_module_active("houses")) addnav("Back to Office", "runmodule.php?module=houses&op=office","houses");
	}
	if ($blocks>=350 && $squares>=200 && $session['user']['turns']>=50){
		$houseid = $session['user']['acctid'];
		$ownerid = $session['user']['acctid'];
		output("You've spent the time and effort, and now have a fully functioning Party Room, Don't forget to visit the Party Supplies Shop");
		set_module_pref("room",1);
		$blocksleft = $blocks-=350;
		$squaresleft = $squares-=200;
		set_module_pref("blocks", $blocksleft, "quarry");
		set_module_pref("squares", $squaresleft, "lumberyard");
		$session['user']['turns']-=50;
		$sql="INSERT into " .db_prefix ("partykeys") . "(houseid, ownerid) VALUES ($houseid, $ownerid)";
		db_query($sql);
		$sql = "SELECT houseid FROM ".db_prefix("partykeys")." WHERE ownerid=".$session['user']['acctid']." ORDER BY houseid DESC LIMIT 1";
		db_query($sql);
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$houseid = $row['houseid'];
		set_module_pref("houseid",$houseid);
		addnav("Your Party Room", "runmodule.php?module=party&op=partyroom&houseid=$houseid");
		if (is_module_active("houses")) addnav("Back to Office", "runmodule.php?module=houses&op=office","houses");
	}
	page_footer();
}
if ($op=="buildpart"){
	page_header("Build Your Party Room by Section");
	if ($builds == 0){
		if ($blocks<70 || $squares<40 || $session['user']['turns']<10){
			output("`^You do not have the supplies to complete this section of your party room");
			addnav("Return to Office", "runmodule.php?module=houses&op=office","houses");
		}else{
		output("`^You have completed the first section of building your Party Room");
		$blocksleft=$blocks-=70;
		$squaresleft = $squares-=40;
		set_module_pref("blocks", $blocksleft, "quarry");
		set_module_pref("squares", $squaresleft, "lumberyard");
		$session['user']['turns']-=10;
		set_module_pref("build", 1);
		set_module_pref("builds", 1);
		if (is_module_active("houses")) addnav("Back to Office", "runmodule.php?module=houses&op=office","houses");
	}
}
	if ($builds == 1){
		if ($blocks<70 || $squares<40 || $session['user']['turns']<10){
			output("`^You do not have the supplies to complete this section of your party room");
			addnav("Return to Office", "runmodule.php?module=houses&op=office","houses");
		}else{
		output("`^You have completed the second section of building your Party Room");
		$blocksleft=$blocks-=70;
		$squaresleft = $squares-=40;
		set_module_pref("blocks", $blocksleft, "quarry");
		set_module_pref("squares", $squaresleft, "lumberyard");
		$session['user']['turns']-=10;
		set_module_pref("build", 1);
		set_module_pref("builds", 2);
		if (is_module_active("houses")) addnav("Back to Office", "runmodule.php?module=houses&op=office","houses");
		}
	}
	if ($builds == 2){
		if ($blocks<70 || $squares<40 || $session['user']['turns']<10){
			output("`^You do not have the supplies to complete this section of your party room");
			addnav("Return to Office", "runmodule.php?module=houses&op=office","houses");
		}else{
		output("`^You have completed the third section of building your Party Room");
		$blocksleft=$blocks-=70;
		$squaresleft = $squares-=40;
		set_module_pref("blocks", $blocksleft, "quarry");
		set_module_pref("squares", $squaresleft, "lumberyard");
		$session['user']['turns']-=10;
		set_module_pref("build", 1);
		set_module_pref("builds", 3);
		if (is_module_active("houses")) addnav("Back to Office", "runmodule.php?module=houses&op=office","houses");
		}
	}
	if ($builds == 3){
		if ($blocks<70 || $squares<40 || $session['user']['turns']<10){
			output("`^You do not have the supplies to complete this section of your party room");
			addnav("Return to Office", "runmodule.php?module=houses&op=office","houses");
		}else{
		output("`^You have completed the fourth section of building your Party Room");
		$blocksleft=$blocks-=70;
		$squaresleft = $squares-=40;
		set_module_pref("blocks", $blocksleft, "quarry");
		set_module_pref("squares", $squaresleft, "lumberyard");
		$session['user']['turns']-=10;
		set_module_pref("build", 1);
		set_module_pref("builds", 4);
		if (is_module_active("houses")) addnav("Back to Office", "runmodule.php?module=houses&op=office","houses");
		}
	}
	if ($builds == 4){
		$houseid = $session['user']['acctid'];
		$ownerid = $session['user']['acctid'];
		if ($blocks<70 || $squares<40 || $session['user']['turns']<10){
			output("`^You do not have the supplies to complete this section of your party room");
			if (is_module_active("houses")) addnav("Back to Office", "runmodule.php?module=houses&op=office","houses");
		}else{
		output("`^You have completed the final section of your Party Room, it is now ready.");
		$blocksleft=$blocks-=70;
		$squaresleft = $squares-=40;
		set_module_pref("blocks", $blocksleft, "quarry");
		set_module_pref("squares", $squaresleft, "lumberyard");
		$session['user']['turns']-=10;
		set_module_pref("build", 0);
		set_module_pref("builds", 0);
		set_module_pref("room",1);
		$sql="INSERT into " .db_prefix ("partykeys") . "(houseid, ownerid) VALUES ($houseid, $ownerid)";
		db_query($sql);
		$sql = "SELECT houseid FROM ".db_prefix("partykeys")." WHERE ownerid=".$session['user']['acctid']." ORDER BY houseid DESC LIMIT 1";
		db_query($sql);
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$houseid = $row['houseid'];
		set_module_pref("houseid",$houseid);
		addnav("Your Party Room", "runmodule.php?module=party&op=partyroom&houseid=$houseid");
		if (is_module_active("houses")) addnav("Back to Office", "runmodule.php?module=houses&op=office","houses");
		}
	}
	page_footer();
}
//now for the party supplies shop
if ($op=="partyshop"){
	page_header("The Party Supply Shop");
	$owner = get_module_setting("owner");
	$g=get_module_setting("barc");
	$ge=get_module_setting("barcg");
    output("`^You step through the doors and are immediately surrounded by a `4R`!a`8i`2n`%b`Qo`6w `^of colors that almost blind you!`n`n");
    output("Lining the walls, are Party Supplies of every size, shape and description.`n`n");
    output("`&As you gaze around trying to decide what your party needs, a small child pops up from behind the counter.`n");
    output("`&\"`\$Hi! My name is %s, and I know what you're thinking, a child, in charge of a shop.  Well who better to know what a party needs!!`&\"`n`n",$owner);
    output("`^Here you may also hire a bar and bartender for your party, the bar is fully stocked and costs $g gold and $ge gems to hire.");
    addnav("Supplies");
    addnav("Hire a Bar and Bartender","runmodule.php?module=party&op=hireb");
    $sql = "SELECT * FROM " . db_prefix("partysupplies");
    $res = db_query($sql);
    	while($row = db_fetch_assoc($res)) {
        if ($row['gems'] == 1) $str = "gem";
        else $str = "gems";
        $item = $row['supplyid'];
        $buy1 = translate_inline("");
        $sid = translate_inline("Id");
        $nm = translate_inline("Name");
        $gold = translate_inline("Gold Cost");
        $gem = translate_inline("Gem Cost");
        $buy = translate_inline("Buy");
        rawoutput("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>");
        rawoutput("<tr class='trhead'>");
        rawoutput("<td>$buy1</td><td>$sid</td><td>$nm</td><td>$gold</td><td>$gem</td>");
        rawoutput("</tr>");
                $sql = "SELECT * FROM " . db_prefix("partysupplies") . " ORDER BY supplyid";
                $res= db_query($sql);
                for ($i=0;$i<db_num_rows($res);$i++){
                        $row = db_fetch_assoc($res);
                        $item = $row['supplyid'];
                        $id = $row['supplyid'];
                        rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
                        rawoutput("<td nowrap>[ <a href='runmodule.php?module=party&op=buy&item=$item'>$buy</a>");
                        addnav("","runmodule.php?module=party&op=buy&item=$item");
                        output_notl("<td>`^%s</td>`0", $id, true);
                        output_notl("<td>`&%s`0</td>", $row['name'], true);
                        output_notl("<td>`^%s`0</td>", $row['gold'], true);
                        output_notl("<td>`%%s`0</td>", $row['gems'], true);
                        rawoutput("</tr>");
                }
                rawoutput("</table>");
        }
    addnav("Return to Village", "village.php?");
	page_footer();
}
if ($op == "buy") {
    page_header("Party Supplies Shop");
    addnav("Return to Shop", "runmodule.php?module=party&op=partyshop");
    addnav("Return to Village", "village.php?");
    $id = httpget("item");
       	$sql = "SELECT * FROM " . db_prefix("partysupplies") . " WHERE supplyid='$id'";
    	$row = db_fetch_assoc(db_query($sql));
    	$name = $row['name'];
        $owner = get_module_setting("owner");
        if (!$row['supplyid']) {
        output("`&%s looks sadly at you.  \"`\$That is ONE of our most popular items! We seem to have run out of stock!  Is there something else you would like?`&\"",$owner);
        } elseif ($row['gold'] > $session['user']['gold'] || $row['gems'] > $session['user']['gems']) {
        output("`^You realize you don't have the necessary gold or gems to purchase %s`^.`n`n", $row['name']);
        output("`&%s suggests that there might be something a little cheaper you would like?",$owner);
        }else{
      if (get_module_pref("supplies1")==0 && get_module_pref("supplies2")==0 && get_module_pref("supplies3")==0){
	        output("%s, `^Smiles impishly at you,\" `\$Oh what a wonderful choice, I am sure your guests will enjoy your party immensely with %s `\$included.\".`n`n",$owner,$name);
	    output("%s, `^Takes your gold and gems, \"`\$Your purchases will be delivered straight to your Party Room.\"",$owner);
	        $gold = $row['gold'];
        $gems = $row['gems'];
        $session['user']['gems'] -= $gems;
        $session['user']['gold'] -= $gold;
        debuglog("spent $gold gold and $gem gems on party supplies");
	        set_module_pref("supplies1",$id);
    	}elseif (get_module_pref("supplies1")>0 && get_module_pref("supplies2")==0 && get_module_pref("supplies3")==0){
	    	output("%s, `^Smiles impishly at you,\" `\$Oh what a wonderful choice, I am sure your guests will enjoy your party immensely with %s `\$included.\".`n`n",$owner,$name);
	    output("%s, `^Takes your gold and gems, \"`\$Your purchases will be delivered straight to your Party Room.\"", $owner);
	    	$gold = $row['gold'];
        $gems = $row['gems'];
        $session['user']['gems'] -= $gems;
        $session['user']['gold'] -= $gold;
        debuglog("spent $gold gold and $gem gems on party supplies");
	    	set_module_pref("supplies2",$id);
    	}elseif (get_module_pref("supplies1")>0 && get_module_pref("supplies2")>0 && get_module_pref("supplies3")==0){
	    	output("%s, `^Smiles impishly at you,\" `\$Oh what a wonderful choice, I am sure your guests will enjoy your party immensely with %s `\$included.\".`n`n",$owner,$name);
	    output("%s, `^Takes your gold and gems, \"`\$Your purchases will be delivered straight to your Party Room.\"",$owner);
	    	$gold = $row['gold'];
        $gems = $row['gems'];
        $session['user']['gems'] -= $gems;
        $session['user']['gold'] -= $gold;
        debuglog("spent $gold gold and $gem gems on party supplies");
	    	set_module_pref("supplies3",$id);
    	}elseif (get_module_pref("supplies3")>0){
	    	output("You have purchased 3 items and can purchase no more");
    	}

    }
        page_footer();
}
if ($op=="hireb"){
	page_header("Party Supplies Shop");
	$g=get_module_setting("barc");
	$ge=get_module_setting("barcg");
	if (get_module_pref("hireb")==1){
		output("\"`\$I am so sorry, you already have a bartender hired, there is a limited number of bartenders available, you can only have one.\"`n`n");
	}
	if ($session['user']['gold']<$g || $session['user']['gems']<$ge){
		output("`^You do not have enough gold or gems for this");
	}else{
		output("`^You have successfully hired a bar and bartender, when you start your party, he will be there.");
		$session['user']['gold']-=$g;
		$session['user']['gems']-=$ge;
		set_module_pref("hireb",1);
	}
	addnav("Return to Shop", "runmodule.php?module=party&op=partyshop");
	addnav("Return to Village", "village.php?");
	page_footer();
}
}
//copied and modified from JT Traubs Gift Shoppe
function partysupplies_editor(){
	global $mostrecentmodule;

        page_header("Party Supplies Shop Editor");
        require_once("lib/superusernav.php");
        superusernav();
        addnav("Party Supplies Editor");
        addnav("Add a Supply","runmodule.php?module=party&op=editor&subop=add&admin=true");
        $subop = httpget('subop');
        $supplyid = httpget('supplyid');
        $header = "";
        if ($subop != "") {
                addnav("Supplies Editor Main","runmodule.php?module=party&op=editor&admin=true");
                if ($subop == 'add') {
                        $header = translate_inline("Adding a new supply");
                } else if ($subop == 'edit') {
                        $header = translate_inline("Editing a supply");
                }
        } else {
                $header = translate_inline("Current supplies");
        }
        output_notl("`&<h3>$header`0</h3>", true);
        $supplyarray=array(
                "supply,title",
                "supplyid"=>"supply ID,hidden",
                "name"=>"supply Name",
                "gold"=>"Gold cost,int",
                "gems"=>"Gem cost,int",
        );
        if($subop=="del"){
                $sql = "DELETE FROM " . db_prefix("partysupplies") . " WHERE supplyid='$supplyid'";
                db_query($sql);
                $subop = "";
                httpset('subop', "");
        }
        if($subop=="save"){
                $supplyid = httppost("supplyid");
                list($sql, $keys, $vals) = postparse($supplyarray);
                if ($supplyid > 0) {
                        $sql = "UPDATE " . db_prefix("partysupplies") . " SET $sql WHERE supplyid='$supplyid'";
                } else {
                        $sql = "INSERT INTO " . db_prefix("partysupplies") . " ($keys) VALUES ($vals)";
                }
                db_query($sql);
                if (db_affected_rows()> 0) {
                        output("`^supply saved!");
                } else {
                        output("`^supply not saved: `\$%s`0", $sql);
                }
                if ($supplyid) {
                        $subop = "edit";
                        httpset("supplyid", $supplyid, true);
                } else {
                        $subop = "";
                }
                httpset('subop', $subop);
        }
        if ($subop==""){
                $ops = translate_inline("Ops");
                $id = translate_inline("Id");
                $nm = translate_inline("Name");
                $gold = translate_inline("Gold Cost");
                $gem = translate_inline("Gem Cost");
                $edit = translate_inline("Edit");
                $conf = translate_inline("Are you sure you wish to delete this supply?");
                $del = translate_inline("Del");
                rawoutput("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>");
                rawoutput("<tr class='trhead'>");
                rawoutput("<td>$ops</td><td>$id</td><td>$nm</td><td>$gold</td><td>$gem</td>");
                rawoutput("</tr>");
                $sql = "SELECT * FROM " . db_prefix("partysupplies") . " ORDER BY supplyid";
                $result= db_query($sql);
                for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        $id = $row['supplyid'];
                        rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
                        rawoutput("<td nowrap>[ <a href='runmodule.php?module=party&op=editor&subop=edit&supplyid=$id&admin=true'>$edit</a>");
                        addnav("","runmodule.php?module=party&op=editor&subop=edit&supplyid=$id&admin=true");
                        rawoutput(" | <a href='runmodule.php?module=party&op=editor&subop=del&supplyid=$id&admin=true' onClick='return confirm(\"$conf\");'>$del</a> ]</td>");
                        addnav("","runmodule.php?module=party&op=editor&subop=del&supplyid=$id&admin=true");
                        output_notl("<td>`^%s</td>`0", $id, true);
                        output_notl("<td>`&%s`0</td>", $row['name'], true);
                        output_notl("<td>`^%s`0</td>", $row['gold'], true);
                        output_notl("<td>`%%s`0</td>", $row['gems'], true);
                        rawoutput("</tr>");
                }
                rawoutput("</table>");
        }
        if($subop=="edit"){
                $sql="SELECT * FROM " . db_prefix("partysupplies") . " WHERE supplyid='$supplyid'";
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
        }elseif ($subop=="add"){

                $row = array();
                $row['supplyid'] = 0;
        }

        if ($subop == "edit" || $subop == "add") {
                require_once("lib/showform.php");
                rawoutput("<form action='runmodule.php?module=party&op=editor&subop=save&admin=true' method='POST'>");
                addnav("","runmodule.php?module=party&op=editor&subop=save&admin=true");
                showform($supplyarray,$row);
                rawoutput("</form>");
        }
        page_footer();

}
//end of copied code
function punch_outcome(){
	global $session;
	switch (e_rand(1,3)){
	case 1:
	output("Your bartender places a small punch bowl on the end of one of the tables of food in your Party Room, into it he places a molded ice ring before pouring in:  2 Bottles Champagne, 1/2 Cup Chambord, 1/2 Cup Orange juice, 1/4 Cup Lemon and Lime juice.`n`n");
	output("Adding a handful of fresh raspberries to the punch he turns to you and says \"Diamond Punch for your Guests\".");
	set_module_pref("punch","`7Diamond `6Punch");
	break;
	case 2:
	output("Your bartender places a large punch bowl filled with ice onto the end of one of the tables of food in your Party Room, into it he places a smaller punch bowl.  He pours, 1 Quart Brandy, 1 Quart Sherry, 4 oz. Maraschino Liqueur, 1 Cup Orange Curacao, 2 Quarts Club Soda, 4 Bottles Champagne into the bowl, and adds a few orange and lemon slices.`n`n");
	output("Turning to you he says \"Bombay Punch for your Guests\".");
	set_module_pref("punch","`QBombay `qPunch");
	break;
	case 3:
	output("Your bartender places a small punch bowl half filled with ice.  He pours 2 Cups Kahlua, 1 12 oz. can Frozen Apple Juice,1/2 Cup Lemon Juice over the ice.  He then fills the bowl with 1 Bottle of Sparkling Apple Juice, 1 Quart of Lemon Lime Soda, 750 ml Dry Champagne and adds a few orange and lemon slices.`n`n");
	output("Turning to you he says \"Kahlua Punch for your Guests\".");
	set_module_pref("punch","`qKahlua `^Punch");
	break;
	}
	}
?>