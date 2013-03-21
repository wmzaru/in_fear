<?php


/* TravelAgent module */
/* by Andrea Britt => WebPixie */
/* 1 Nov 2004 */
/* Based on Hitchhike module */
/* by Shannon Brown => SaucyWench -at- gmail -dot- com */
/* 24 Aug 2004 */

require_once("lib/http.php");
require_once("lib/villagenav.php");

function travelagent_getmoduleinfo(){
	$info = array(
		"name"=>"TravelAgent Module",
		"version"=>"1.1",
		"author"=>"WebPixie, based on Shannon Brown's Hitch mod",
		"category"=>"Travel",
		"download"=>"http://dragonprime.net/users/WebPixie/travelagent_98.zip",
		"settings"=>array(
			"danger"=>"Chance of being hurt or killed,range,1,25,1|5",
			"basecost"=>"Base cost of ticket,int|500",
			"cost"=>"Additonal Cost per level over base cost to travel,int|50",
		),
	
	);
	return $info;
}

function travelagent_install(){
	module_addhook("pre-travel");
	module_addhook("charstats");
	return true;
}

function travelagent_uninstall(){
	return true;
}

function travelagent_dohook($hookname,$args){
	global $session;
	switch($hookname){

	case "pre-travel":
			$args = modulehook("count-travels", array('available'=>0,'used'=>0));
			$free = max(0, $args['available'] - $args['used']);
			$canpay = 0;
			if ($free==0 && $session['user']['turns']==0)
				$canpay = 1;
			if ($canpay) {
				output("`n`7Noticing the Travel office, you ponder paying for a ride to your destination.`n");
				addnav("Travel Agent");
				addnav("Pay for Ride","runmodule.php?module=travelagent");
		}
		break;
	
	}

	return $args;
}


function travelagent_run(){
	global $session;
	$session['user']['specialinc'] = "module:travelagent";
	$op = httpget("op");
	$chance = (e_rand(1,100));
	$danger = get_module_setting("danger");
	$death = ($danger/3);
	$thisvillage = $session['user']['location'];
	$vloc = array();
	$vname = getsetting("villagename", LOCATION_FIELDS);
	$vloc[$vname] = "village";
	$vloc = modulehook("validlocation", $vloc);
	ksort($vloc);
	reset($vloc);

	$cost = (get_module_setting("cost")*$session['user']['level']+get_module_setting("basecost"));

	page_header("Travel Agent");

	if ($op=="") {
	$session['user']['specialinc'] = "";

		addnav("Buy a Ticket");

		foreach($vloc as $loc=>$val) {
			if ($loc == $session['user']['location']) continue;
			addnav(array("Go to %s", $loc), "runmodule.php?module=travelagent&op=go&cname=".htmlentities($loc));
		}

		addnav("Leave");
		addnav("Forget it, I'll walk","runmodule.php?module=cities&op=travel");
		output("`n`6You wander into the travel agency and ask the rough looking gentleman behind the counter about a paid transport. `n`n");
		output("\"`0We make stops in all major cities. We'll take ye there... for a price. For you it will cost just `2`b%s`b `0gold for a ticket.`6\"`n`n",$cost);
		output("\"`0Now we have had a bit of trouble with a small band of boys trying to rob the transport, but the chance is very slight. Even so, we still are the safest way to travel in these parts.`6\"`n`n \"`0Interested?`6\" `n`n");

	}



	if ($op=="go") {

        if ($session['user']['gold'] < ($cost)) {
		$session['user']['specialinc'] = "";
		output("`c`3`bYou're Broke!`0`b`c`n");
		output("Sorry, looks like you can't afford a ticket anywhere. Maybe you can hitch a ride to your destination.");
		addnav("Leave");
		addnav("Forget it, I'll walk","runmodule.php?module=cities&op=travel");

	}else {
	$session['user']['gold']-=$cost;
	if ($chance<=$danger) {

		$session['user']['specialinc'] = "";

		output("`c`3`bYou've been Waylaid!.`0`b`c`n");

		output("Your transport finally departs. You casually sit back and start to enjoy the view.");
		output("Suddenly, deep in the forest surrounding %s, a band of robbers attacks demanding gold and gems.", $thisvillage);
		output("For one breif moment you feel the urge to be heroic, they quickly change your mind. Jumping from the transport you run for your life!`n`n");
		output("Suddenly you realize you have left your %s and %s inside the transport! Running is your last resort!", $session['user']['weapon'], $session['user']['armor']);
		output("The masked man is no match for you you feel him grab the back of your collar and drag you to the ground.");
		output("Without a thought he lunges his blade into your side.`n`n");
		if ($chance<=$death) {
			output("With a slight grin the man pulls the blade from your side, and with one final blow plunges it into your heart.");
			output("Your last thought is to wonder if you will be getting a refund from the travel agent.`n`n");
			output("`^You have died!`n");
			output("`^You `\$lose`^ 5% of your experience.`n");
			output("You may continue playing again tomorrow.");
			$session['user']['alive']=false;
			$session['user']['hitpoints']=0;
			$session['user']['experience']*=0.95;
			addnav("Daily News","news.php");
			addnews("`7Out side of town a band of masked men attacked the paid transport and`&%s `7was killed in the process.", $session['user']['name']);
		} else {

			$session['user']['specialinc'] = "";
			villagenav();
			output("In a finally burst of energy you make a dash for the thick trees hoping to lose him.`n`n");
			output("Blood driping down your side, you hide for what seems like hours before realizing you are finally alone. The men have gone.`n`n");
			output("With much caution you go back to the scene of the crime. One dead driver and blood everywhere. You search for your %s and %s and make your way back to %s unable to reach your final destination.", $session['user']['weapon'], $session['user']['armor'], $thisvillage);
			$session['user']['hitpoints'] = $session['user']['hitpoints']*0.1;
			if ($session['user']['hitpoints'] < 1)
				$session['user']['hitpoints'] = 1;
		}


	} else {

	$cname = httpget("cname");
	$session['user']['specialinc'] = "";
		$session['user']['location']=$cname;
		addnav(array("Explore %s",$cname),"village.php");
		output("`c`4`bYou've Arrived in %s.`0`b`c`n", $cname);
		output("`7Your transport finally departs. You secure your belongings, and climb aboard.");
		output("In what seems like no time, you have arrived in %s.`n`n", $cname);
		output("You are dropped off at the town gates and the transport starts it's way on to it's next stop.");


	}
}
}
	page_footer();
}

?>
