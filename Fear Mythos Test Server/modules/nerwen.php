<?php

function nerwen_getmoduleinfo(){
	$info = array(
		"name"=>"Nerwen's House",
		"author"=>"Spider",
		"version"=>"1.1",
		"category"=>"Alley",
		"download"=>"http://dragonprime.net/users/Spider/darkalley.zip"
	);
	return $info;
}

function nerwen_install(){
	if (!is_module_installed("darkalley")) {
    output("This module requires the Dark Alley module to be installed.");
    return false;
	}
	else {
		module_addhook("darkalley");
		return true;
	}
}

function nerwen_uninstall(){
	return true;
}

function nerwen_dohook($hookname, $args){
	global $session;
	switch($hookname){
	case "darkalley":
		addnav("Shady Houses");
		addnav("Nerwen's House", "runmodule.php?module=nerwen");
		break;
	}
	return $args;
}

function nerwen_run(){
	global $session;
	require_once("lib/http.php");
	$op = httpget('op');
	page_header("Nerwen's House");
	output("`c`bNerwen's House`b`c");
	
	if ($op==""){
		output("Nerwen Lossëhelin looks up as you enter, elves are known for their beauty but even by elvish standards Nerwen is beautiful, her deep blue eyes look straight into your own and you feel as though she is looking straight into your heart.`n`n");
		output("`3\"Ah, dear %s, I trust you are well today.",$session['user']['name']);
		output("I suppose you would like to know a little more about yourself?  I can reveal a little of your future to you if you part with a little gold for me.\"");
		addnav("Learn of your Future","runmodule.php?module=nerwen&op=future");
	}
	else if($op=="future"){
		output("Eager to hear about your future you ask Nerwen how much a little gold is.`n`n");
		output("`3\"My child, a little is as much as you feel you can part with, the question is, how generous do you feel today?\"`0`n`n");
		output("How much gold will you offer Nerwen?`n");
		output("<form action='runmodule.php?module=nerwen&op=future2' method='POST'><input id='input' name='amount' width=5 accesskey='h'> <input type='submit' class='button' value='Offer'></form>",true);
		output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
		addnav("","runmodule.php?module=nerwen&op=future2");
	}
	else if($op=="future2"){
		$offer=abs((int)$_POST['amount']);
		if ($offer==0){
			output("Nerwen looks at you sternly`n`n");
			output("`3\"Are you trying to make a fool of me, or perhaps you think this is some kind of joke, get out of my house now and don't come back until you learn some manners!\"");
		}
		else if ($offer<50){
			output("Nerwen looks at your meager offering and shakes her head sadly.`n`n");
			output("`3\"Sorry my child, but that simply is not enough, this is how I earn my living, and I cannot do it for so little.\"");
		}
		if ($offer>$session['user']['gold']){
			output("Nerwen looks at you sternly`n`n");
			output("`3\"I think maybe you need to take some basic arithmetic lessons rather than have your fortune read, how can you give me what you do not have?\"");
			addnav("Learn of your Future","runmodule.php?module=nerwen&op=future");
		}
		else{
			$min=1;
			$max=round($offer/50);
			if ($max>15){
				$min=$max-15;
				if ($min>=15) $min=14;
				$max=15;
			}
			$session['user']['gold']-=$offer;
			output("Nerwen takes your gold, smiling and looks up into your eyes again.`n`n");
			$fortune = e_rand($min,$max);
			debuglog("offered $offer gold to Nerwen");
			switch ($fortune){
				case 1:
					output("`3\"Today does not look good for you, I'm terribly sorry.\"");
					$session['user']['hitpoints']=1;
					$session['user']['gold']-=100;
					$session['user']['charm']-=1;
					$session['user']['gems']-=1;
					if ($session['user']['gold'] < 0){
						$session['user']['gold'] = 0;
					}
					if ($session['user']['gems'] < 0){
						$session['user']['gems'] = 0;
					}
					break;
				case 2:
					output("`3\"My child, you seem unusually tired today.\"");
					$session['user']['turns']-=2;
					if ($session['user']['turns'] < 0){
						$session['user']['turns'] = 0;
					}
					break;
				case 3: case 11:
					output("`3\"Today your confidence will grow greatly, perhaps you should go and say hi to %s\"",$session['user']['sex']?"Seth":"Violet");
					$session['user']['charm']+=1;
						break;
				case 4:
					output("`3\"I'm afraid you seem to have lost something today, although I cannot tell quite what it is.\"");
					$session['user']['goldinbank']-=500;
					break;
				case 5:
					if ($session['user']['gems'] == 0){
						output("`3\"I'm afraid your future is a little cloudy today, there is nothing more I can tell you.\"");
					}
					else{
						output("`3\"I see that you are going to misplace a gem today.\"");
						$session['user']['gems']-=1;
					}
					break;
				case 7:
					output("`3\"You will be pleasantly surprised later on today.\"");
					$session['user']['goldinbank']+=1000;
					break;
				case 8: case 20:
					output("`3\"The strength flowing through you is great today, your day will be long and productive.\"");
					$session['user']['turns']+=2;
					break;
				case 9:
					output("`3\"It looks like you're going to find a gem today.\"");
					$session['user']['gems']+=1;
					break;
				case 10:
					output("`3\"I see that today you will feel a new strength in your body.\"");
					$session['user']['hitpoints']+=200;
					break;
				case 12:
					output("`3\"I see that you aren't feeling at all well today, I hope you feel better soon.\"");
					$session['user']['hitpoints']-=($session['user']['maxhitpoints']*0.5);
					if ($session['user']['hitpoints'] < 0){
						$session['user']['hitpoints'] = 1;
					}
					break;
				case 13:
					output("`3\"I suggest you stay clear of the inn today, you will not be very welcome there in your present state.\"");
					$session['user']['drunkeness']=80;
					break;
				case 14:
					output("`3\"Today you are blessed, enjoy it while it lasts.\"");
					$session['user']['hitpoints']+=50;
					break;
				case 15:
					output("`3\"Today is looking most promising for you, you should go out and make the most of it.\"");
					$session['user']['hitpoints']+=10;
					$session['user']['gold']+=100;
					$session['user']['charm']+=1;
					$session['user']['gems']+=1;
					break;
				default:
					output("`3\"I'm afraid your future is a little cloudy today, there is nothing more I can tell you.\"");
			}
		}
	}
	addnav("Return to the Alley","runmodule.php?module=darkalley");
	page_footer();
}

?>