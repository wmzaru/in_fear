<?php

require_once("lib/villagenav.php");
require_once("lib/http.php");

function streetpham_getmoduleinfo(){
    $info = array(
        "name"=>"Street Pharmacist",
        "version"=>"1.01",
        "author"=>"Mr Zone",
        "download"=>"http://9zr.net/downloads/logd/streetpham.zip",
        "category"=>"Village",
        "settings"=>array(
            "Street Pharmacist Settings,title",
			"costroots"=>"Cost of Ginseng Roots * Current Level|25",
			"costweed"=>"Cost of Dragon Weed * Current Level|50",
			"costherbale"=>"Cost of Herbal Ale * Current Level|125",
			"costmushale"=>"Cost of Mushroom Elixer * Current Level|155",
			"costcure"=>"Base Cost of Flu Cure * Current Level|420",
			"plyInfect"=>"Allow players to infect each other?,bool|1",
			"poison1"=>"Cost to cast the Flu upon another player|10000",
			"plyfluchance"=>"Chance the player will be infected,range,20,100,5|70",
			
			"streetphamloc"=>"Where does the Street street Pharmacist setup shop,location|".getsetting("villagename", LOCATION_FIELDS)
        ),
        "requires"=>array(
			"theflu"=>"The Flu |By Mr Zone",
		),	
        "prefs"=>array(
            "Street Pharmacist preferences,title",
			"poisinedtoday"=>"Has user poisined today?,bool|0",
        )
    );
    return $info;
}

function streetpham_install(){
	module_addhook("changesetting");
	module_addhook("newday");
	module_addhook("village");
	module_addhook("everyfooter");
    return true;
}

function streetpham_uninstall(){
    return true;
}

function streetpham_dohook($hookname,$args){
	
    global $session;
    switch($hookname){
	   	case "newday":
			set_module_pref("scaretoday",0);
			break;
	   	case "changesetting":
			if ($args['setting'] == "villagename") {
				if ($args['old'] == get_module_setting("streetphamloc")) {
					set_module_setting("streetphamloc", $args['new']);
				}
			}
			break;
		case "village":
			if ($session['user']['location'] == get_module_setting("streetphamloc")) {
	            tlschema($args['schemas']['tavernnav']);
				addnav($args['tavernnav']);
	            tlschema();
				addnav("The Street Pharmacist","runmodule.php?module=streetpham");
			}
			break;
	}
    return $args;
}

function streetpham_run() {
    global $session;
	page_header("Your Local Street Pharmacist");
	output("`c`bYour Local Street Pharmacist `b`c`n`n");
	
	//addnav("Refresh","runmodule.php?module=streetpham");
	
	$op = httpget('op');
	if (is_module_active("theflu"))
		$fluactive = true;
	
	$userlev=$session['user']['level'];
	$costroots = get_module_setting("costroots")*$userlev;
	$costweed = get_module_setting("costweed")*$userlev;
	$costherbale = get_module_setting("costherbale")*$userlev;
	$costmushale = get_module_setting("costmushale")*$userlev;
	$costcure1 = get_module_setting("costcure")*$userlev;
	$costcure2 = (get_module_setting("costcure")*1.2)*$userlev;
	$costcure3 = (get_module_setting("costcure")*1.5)*$userlev;
	$poison1 = get_module_setting("poison1");
		
	if ($op == "") {
		output("Shop Owners: \"Welcome to my shop\" `n");
		output("Shop Owners: \"Nature holds the key to most ailments. How may I help you today?\"`n`n");
		output("`$ Ginseng Roots `^(%s Gold)`0 helps you bodies natural attacking ability`n",$costroots);
		output("`@Dragon Weed `^(%s Gold)`0 Counters the effect of the flu`n",$costweed);
		output("`2Herbal Ale `^(%s Gold)`0 good for what ales you.`n",$costherbale);
		output("`2Mushroom Ale `^(%s Gold)`0 good for what ales you, Plus a little extra boast`n",$costmushale);
		output("`3Grade C Immune Boast `^(%s Gold)`0 Has been know to cure the flu`0`n",$costcure1);
		output("`3Grade B Immune Boast `^(%s Gold)`0 Should cure the flu`0`n",$costcure2);
		output("`3Grade A Immune Boast `^(%s Gold)`0 Cures the flu right up`0`n`n",$costcure3);
		
		output("`)Care to cast illness upon a foe, That will cost ye `^%s Gold `0`n",$poison1);
		
		
		
		/*if (get_module_pref("gotflu", "getflu") && $fluactive){
			output("YOU ARE SICK.");	
		}*/
		addnav("Heabral Elixirs");
		addnav("Ginseng Roots","runmodule.php?module=streetpham&op=confirm&xop=buy&item=root&cost=". $costroots);
		addnav("Dragon Weed","runmodule.php?module=streetpham&op=confirm&xop=buy&item=weed&cost=". $costweed);
		addnav("Herbal Ale","runmodule.php?module=streetpham&op=confirm&xop=buy&item=herbale&cost=". $costherbale);
		addnav("Mushroom Ale","runmodule.php?module=streetpham&op=confirm&xop=buy&item=mushale&cost=". $costmushale);
		if ($fluactive){
			addnav("Heabral Cures");
			addnav("Immune Boast Grade C","runmodule.php?module=streetpham&op=confirm&xop=buy&item=cure&type=1&cost=". $costcure1);
			addnav("Immune Boast Grade B","runmodule.php?module=streetpham&op=confirm&xop=buy&item=cure&type=2&cost=". $costcure2);
			addnav("Immune Boast Grade A","runmodule.php?module=streetpham&op=confirm&xop=buy&item=cure&type=3&cost=". $costcure3);
		}
		addnav("Poisons");
		//addnav("Check Foe's Health","runmodule.php?module=streetpham&op=confirm&xop=poison&item=check&cost=100");
		addnav("Poison a Foe","runmodule.php?module=streetpham&op=poison&item=poison1&cost=". $poison1);
		addnav("Street");
		addnav("Move On","village.php");
		
	}elseif($op=="confirm"){
		$cost = httpget('cost');
		addnav("Yes, Give it to me!","runmodule.php?module=streetpham&op=". httpget('xop') ."&item=". httpget('item') ."&cost=". $cost ."&type=". httpget('type'));
		addnav("No, I changed my mind","runmodule.php?module=streetpham");
		output("Are you sure you want to spend `^%s Gold?`0",$cost);
	}elseif($op=="buy"){
		addnav("Now What?");
		addnav("Look around some more","runmodule.php?module=streetpham");
		addnav("Move On","village.php");
		streetpham_buy();
	}elseif($op=="poison"){
		addnav("Now What?");
		addnav("Look around some more","runmodule.php?module=streetpham");
		addnav("Move On","village.php");
		streetpham_poison();
	}	
	
		//villagenav();
	page_footer();
}
function streetpham_poison(){ //Used a bit of code from voodoo priestess By Lonny to make this poison part
	global $session;
	$acctid=$session["user"]["acctid"];
	$poison1 = get_module_setting("poison1");
	
	switch(httpget('item')){
		case poison1:
			$txtsearch = $_POST["txtsearch"];
			output("So, you feeling evil today... Well you have come to the right place.");
			addnav("","runmodule.php?module=streetpham&op=poison&item=poison1");
			rawoutput("<form action='runmodule.php?module=streetpham&op=poison&item=poison1' method='POST' onsubmit='return validateFields(this);'>",true);
			rawoutput("<br>Search for Victims Names <input type='Text' name='txtsearch' value='bil' size='10'>");
			rawoutput("<input type='Submit' name='Submit' value='Search'>");
			rawoutput("</form>");
			
			$searchoutput= "";
			if ($txtsearch != ""){
	
				$sql = "SELECT acctid,name FROM ". db_prefix("accounts") ." where (name like '%$txtsearch%') and acctid<>$acctid order by acctid";  //
				$res = db_query($sql);
				$testvalue = "";
				

				for ($i = 0; $i < db_num_rows($res); $i++){
					$row = db_fetch_assoc($res);
					$acctid = $row['acctid'];
					$name = $row['name'];
					addnav("","runmodule.php?module=streetpham&op=buy&item=poison1&cost=". $poison1 ."&vid=$acctid");
					$searchoutput = $searchoutput ."<tr class='".($i%2==1?"trlight":"trdark")."'><td>
					<a href='runmodule.php?module=streetpham&op=buy&item=poison1&cost=". $poison1 ."&vid=$acctid'>$name</a></td></tr>";
				}
				if ($searchoutput != ""){
					rawoutput("<br><b><span class='colLtRed'> Warning</span> There is <span class='colLtRed'> NO</span> Guarantee your foe will be infected<br>and will cost you <span class='colLtYellow'>". $poison1 ." gold</span><br><br>{Click on your foe to confirm the poisoning}</b>");
					rawoutput("<table border='0' cellspacing=1 cellpadding=2><tr class='trhead'><td>");
					rawoutput("<b>Victim list...</b>");
					rawoutput("</td></tr>");
					rawoutput("$searchoutput");
					rawoutput("</table><br><br>");
				}else{
					rawoutput("No matchs found!");
				}
			
			}
			break;
	}
}

function streetpham_buy(){
	global $session;
	$cost = httpget('cost');
	$enoughmoney = false;
	if ($cost <= $session['user']['gold']){
		$session['user']['gold']-=$cost;
		
		switch(httpget('item')){
		case root:		
			output("You hand the shop owner `^ %s gold. `0He gives you the`$ Ginseng Roots`0 and you eat it right away. You feel a boost in your attacking `n`n",$cost);
			apply_buff('streetpharm',array("name"=>"Root `$ Boost","rounds"=>20,"atkmod"=>1.03,));
			break;
		case weed:		
			output("You hand the shop owner `^ %s gold. `0He gives you the`@ Dragon Weed`0 and a pipe so you can smoke it right away. `&You feel you strenght regaining. `n`n",$cost);
			apply_buff('streetpharm',array("name"=>"Weed `7Haze","rounds"=>25,"defmod"=>1.05,"atkmod"=>1.02,));
			break;
		case herbale:
			output("You hand the shop owner `^ %s gold. `0He gives you the`2 Herbal Ale`0 and you drink it right away. The Ale gives you an energy boost. `n`n",$cost);
			$drunkeness+=15; 
			$session['user']['maxhitpoints']*=1.15;
			if (e_rand(1,100)<=79){
				$session['user']['turns']+=1;
				rawoutput("`<span class='colDkYellow'>This ale seemed to give you an extra energy boost, you gain a </span><span class='colLtGreen'>forest fight</span><br>");
			}
			apply_buff('streetpharm',array("name"=>"Herbal `2Buzz","rounds"=>25,"defmod"=>1.1,"atkmod"=>1.09,));

			break;
		case mushale:
			output("You hand the shop owner `^ %s gold. `0He gives you the`2 Herbal Ale`0 and you drink it right away. The Ale gives you an energy boost. `n`n",$cost);
			$drunkeness+=15;
			if ($session['user']['hitpoints'] < $session['user']['maxhitpoints'])
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
			if (e_rand(1,100)<=60){
				$session['user']['turns']+=1;
				rawoutput("`<span class='colDkYellow'>This ale seemed to give you an extra energy boost, you gain a </span><span class='colLtGreen'>forest fight</span><br>");
			}
			apply_buff('streetpharm',array("name"=>"Mushroom `2Buzz","rounds"=>30,"defmod"=>1.09,"atkmod"=>1.11,));
			break;
	
		case cure:
		
			if (get_module_pref("hasflu","theflu")!=1){
				output("What are you trying to waste your `@money...`0 `^This cure will not help you at this time! But look around some more, other of my items give a good boost.");
				$session['user']['gold']+=$cost;
				break;
				}
			
			$type = httpget('type');
			if ($type==1){    $chance=58;
			}elseif($type==2){$chance=75;
			}elseif($type==3){$chance=100;}
			if (e_rand(1,100)<=$chance){
				rawoutput("Looking for any type of relief,...<br><br>. . . You take the <span class='colDkCyan'>immune boost</span> right away. <br><br><span class='colLtBlue'>You feel it running through veins, your body feels much better!!</span><br><br>");
				include("modules/lib/flu.php");
				call_flucure("yes","cure");
			}else{
				rawoutput("Looking for any type of relief...<br><br>. . . You take the<span class='colDkCyan'> immune boost</span> right away.<span class='colLtBlue'> You feel a bit better today</span><br>");
				apply_buff('streetpharm',array("name"=>"`5Immune Boost","rounds"=>-1,
				"defmod"=>(1-get_module_setting("defeffect","theflu"))+1,
				"atkmod"=>(1-get_module_setting("atkeffect","theflu"))+1,
				"allowinpvp"=>get_module_setting("pvpapply","theflu"),
				"allowintrain"=>get_module_setting("trainapply","theflu"),
				));
			}
			if (e_rand(1,100)<=$chance){
				$session['user']['turns']+=1;
				rawoutput("<br><span class='colDkYellow'>This seemed to give you an extra energy boost, you gain a </span><span class='colLtGreen'>forest fight</span><br>");
			}
			break;
		case poison1:
			$victimid=httpget('vid');
			$sql = "SELECT acctid,name FROM ". db_prefix("accounts") ." where acctid=$victimid";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$name  = $row['name'];
			$erand= e_rand(0, 100);
			if ($erand <=get_module_setting("plyfluchance") && ($name !=""))
				$inffectuser = true;
			if ((get_module_pref("safedaysleft","theflu")>0) || (get_module_pref("hasflu","theflu")==1)) 
				$inffectuser = false;
				
			if ($inffectuser){
				//set_module_pref("hasflu",1,"theflu",$victimid);
				set_module_pref("fludays",get_module_setting("flulast","theflu"),"theflu",$victimid);
				include("modules/lib/flu.php");
				theflu_poison("","",$victimid);
				output($name);
				rawoutput(" was successfully infected by my henchmen. Good doing business with, come back soon.");
			}else{
				output($name);
				rawoutput("was not successfully infected by the poison my henchmen gave them. Sorry, but that is just how business works out. We could try again if you would like.");
			}
			break;
		}
	}else{
		output("O.K. here you go, here is your.... `n`n... `n`n Wait are you trying to pull a fast one me? You do not enough gold.`n`n");	
	}
	
}
?>