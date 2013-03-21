<?php
require_once("lib/commentary.php");
require_once("lib/villagenav.php");
require_once("lib/systemmail.php");
require_once("lib/sanitize.php");
require_once("lib/http.php");

//V1.01: Improved "New Day in" for translation readyness, added admin values for exp-randomizer-multiplicator
//V1.10: Added 2 new training hardness (factor 2 and factor 3 from forest fights/normal training)
//V1.11: output message now displays even if you didn't have any cash on you
//V1.12: new message if money is picked from your bank
//V1.13: Disabled "a new day in" display, wasn't working properly and there's a better module for it
//V1.20: Added an "if" in case people don't have enough cash for 1 round 
//V1.21: Added a cash check for the training costs for >1 rounds 
//V1.22: Added admin-values for gold- and exp-increase in the new functions
//V1.23: Added new outputs to display max and min values for each training
//V1.30: Added exaustion, admin-values
//V1.31: Added energy drink, admin-values
//V1.32: Corrected drink-price-display-bug
//V1.33: Added 10 exaustion messages
//V1.40: Added Events and Accidents
//V1.50: Added the "Kill the Player" code, so he can't heal himself from death 
//V2.00: Added death news anouncements, polished some code

function clantrain_getmoduleinfo() {
    $info = array(
        "name"=>"Clan Turn Training (enhanced)",
        "author"=>"Tristan Lueking (original concept) `nmodifications by Lightbringer, Akuma, Kody Sumter, Sloth `n`2enhanced by SexyCook",
        "version"=>"2.00",
        "category"=>"Clan",
        "download"=>"",
			"settings"=>array(
				"Training Options,title",
				"display"=>"How many training levels should be displayed,enum,1,only normal,2,up to harder,3,up to hardest|3",
				"(three possible levels: normal - harder - hardest),note",
				"ctminexp"=>"What is the lowest multiplicator for the exp gain randomizer,int|5",
				"ctmaxexp"=>"What is the highest multiplicator for the exp gain randomizer,int|12",
				"expexchange"=>"How much gold is needed to pay for 1 experience point (normal),int|1",
				"expexchange2"=>"How much gold is needed to pay for 1 experience point (hard),int|5",
				"expexchange3"=>"How much gold is needed to pay for 1 experience point (hardest),int|20",
				"multip2"=>"By how much (multiplicator) should exp be increased for hard training,int|2",
				"multip3"=>"By how much (multiplicator) should exp be increased for hardest training,int|3",
 				"The last two values will have have a direct effect on exaustion,note",
				"Exaustion Options,title",
        "exaustturns"=>"How much turns until players die 100% sure of exaustion?,int|20",
        "exaustexp"=>"How many exp until players die 100% sure of exaustion?,int|2000",
 				"Choose apropriate average values for both variables,note",
        "pricedrink"=>"How much gold should energie drinks cost?,int|200",
				"Training Events and Accidents,title",
        "events"=>"Make good events happen?,bool|1",
        "chanceeventg"=>"Chances for good events to happen?,enum,1,10%,11,20%,21,30%,31,40%,41,50%,51,60%,61,70%,71,80%,81,90%,91,100%|1",
        "accidents"=>"Make bad accidents happen?,bool|1",
        "chanceaccidb"=>"Chances for bad accidents to happen?,enum,1,10%,11,20%,21,30%,31,40%,41,50%,51,60%,61,70%,71,80%,81,90%,91,100%|1",
        

			),
			);
       return $info;
}
function clantrain_install() {
    module_addhook("footer-clan");
    return true;
}

function clantrain_uninstall() {
    return true;
}
function clantrain_dohook($hookname,$args) {
	global $session;
	switch ($hookname){
		case "footer-clan":
			if ($session['user']['clanrank'] != CLAN_APPLICANT){
				addnav("~");
				addnav("Clan Training","runmodule.php?module=clantrain&op=enter");
		}
		break;
	}
	return $args;
}
function clantrain_run(){
    global $session;
    $display = get_module_setting("display");
    $goldturns = get_module_setting("expexchange");
    $goldturns2 = get_module_setting("expexchange2");
    $goldturns3 = get_module_setting("expexchange3");
    $ctminexp = get_module_setting("ctminexp");
    $ctmaxexp = get_module_setting("ctmaxexp");
    $multi2 = get_module_setting("multip2");
    $multi3 = get_module_setting("multip3");
    $exaustturns = get_module_setting("exaustturns");
    $exaustexp = get_module_setting("exaustexp");
    $didtrain = 0;
    $pricedrink = get_module_setting("pricedrink");
    $events = get_module_setting("events");
    $chanceeventg = get_module_setting("chanceeventg");
    $accidents = get_module_setting("accidents");
    $chanceaccidb = get_module_setting("chanceaccidb");
    $playername = $session['user']['name'];
    
        
	page_header("Clan Training");
    
    switch (httpget("op")) {
        case "enter":
			output("`#You enter the training room and look around.");
			output(" Inside are various swords, dummies, and trainers.");
			output(" You can spend forest fights in here gaining experience points.");
			output(" `nAny gold used in the training room will be added to the guild fund!");
			output("The clock on the wall reads `^%s`#.`n`n`n",getgametime());

    if ($session['user']['turns'] < 1){
        output("`n`n`%You however do not have any Forest Fights left to train in!");
          }elseif($session['user']['gold'] + $session['user']['goldinbank'] < ($session['user']['level']*12+9)*$goldturns){
              output("`n`n`%You however may not have enough gold left to train");
    }else{
        output("`%How many turns do you want to spend training? `#(%s gold per experience point)`%?`n",$goldturns);
        output("`7This should cost you %s-%s gold and raise your experience by %s-%s for each forest fight.`n",$session['user']['level']*$ctminexp*$goldturns,($session['user']['level']*$ctmaxexp+9)*$goldturns,$session['user']['level']*$ctminexp,$session['user']['level']*$ctmaxexp+9);
        if(($session['user']['level']*$ctmaxexp+9)*$goldturns > $session['user']['gold']+$session['user']['goldinbank']){
        output("`%You don't have enough money for this training.`n`n`n"); 
        } else { output("<form action='runmodule.php?module=clantrain&op=train2' method='POST'><input name='train' id='train'><input type='submit' class='button' value='".translate_inline("Train")."'></form>",true);
        output("<script language='JavaScript'>document.getElementById('train').focus();</script>",true); // Bravebrain
        addnav("","runmodule.php?module=clantrain&op=train2"); 
        }
        if($display>1){
          output("`n`n`%How many turns do you want to spend training harder `#(%s gold per experience point)`%?`n",$goldturns2);
          output("`7This should cost you %s-%s gold and raise your experience by %s-%s for each forest fight.`n",$session['user']['level']*$ctminexp*$multi2*$goldturns2,($session['user']['level']*$ctmaxexp+9)*$multi2*$goldturns2,$session['user']['level']*$ctminexp*$multi2,($session['user']['level']*$ctmaxexp+9)*$multi2);
          if(($session['user']['level']*$ctmaxexp+9)*$multi2*$goldturns2 > $session['user']['gold']+$session['user']['goldinbank']){
          output("`%You don't have enough money for this training.`n`n`n"); 
          } else { output("<form action='runmodule.php?module=clantrain&op=train3' method='POST'><input name='train' id='train'><input type='submit' class='button' value='".translate_inline("Train")."'></form>",true);
          addnav("","runmodule.php?module=clantrain&op=train3"); 
          }
        }

        if($display>2){
          output("`n`n`%How many turns do you want to spend training hardest `#(%s gold per experience point)`%?`n",$goldturns3);
          output("`7This should cost you %s-%s gold and raise your experience by %s-%s for each forest fight.`n",$session['user']['level']*$ctminexp*$multi3*$goldturns3,($session['user']['level']*$ctmaxexp+9)*$multi3*$goldturns3,$session['user']['level']*$ctminexp*$multi3,($session['user']['level']*$ctmaxexp+9)*$multi3);
          if(($session['user']['level']*$ctmaxexp+9)*$multi3*$goldturns3 > $session['user']['gold']+$session['user']['goldinbank']){
          output("`%You don't have enough money for this training.`n`n`n"); 
          } else { output("<form action='runmodule.php?module=clantrain&op=train4' method='POST'><input name='train' id='train'><input type='submit' class='button' value='".translate_inline("Train")."'></form>",true);
          addnav("","runmodule.php?module=clantrain&op=train4"); 
          }
        }
        }
        break;

//Start calculations for exp and gold cost
case "train2":
    page_header("Clan Training");
	  $train = abs($_POST['train']);
    if ($session['user']['turns'] <= $train) $train = $session['user']['turns'];
    $exp = $session['user']['level']*e_rand($ctminexp,$ctmaxexp)+e_rand(0,9);
    $totalexp = $exp*$train;
    $goldfee = $totalexp;
    if ($goldfee > ($session['user']['gold']+$session['user']['goldinbank'])) {
      output("You don't have enough money to train");
    }else{
        if ($goldfee > $session['user']['gold']) {
           $session['user']['goldinbank']+=$session['user']['gold'];
           $session['user']['gold']=0;
           $session['user']['goldinbank']-=$goldfee;
            output("The amount was, at least partially, debited from your bank account`n");
        }else{
			    $session['user']['gold'] -= $goldfee;
		        $newgold=(floor($goldfee*0.5)+$row['goldfund']);
			  }  output("`^You train for %s turns, for a cost of %s gold and gain %s experience!`n",$train,$goldfee,$totalexp);
     $session['user']['turns']-=$train;
     $session['user']['experience']+=$totalexp;
     $didtrain=1;
    }
    break;

case "train3":
    page_header("Clan Training");
	  $train = abs($_POST['train']);
    if ($session['user']['turns'] <= $train) $train = $session['user']['turns'];
    $exp = $session['user']['level']*e_rand($ctminexp,$ctmaxexp)+e_rand(0,9);
    $totalexp = ($exp*$train)*$multi2;
    $goldfee =($totalexp * $goldturns2);
    if ($goldfee > ($session['user']['gold']+$session['user']['goldinbank'])) {
      output("You don't have enough money to train");
    }else{
        if ($goldfee > $session['user']['gold']) {
           $session['user']['goldinbank']+=$session['user']['gold'];
           $session['user']['gold']=0;
           $session['user']['goldinbank']-=$goldfee;
            output("The amount was, at least partially, debited from your bank account`n");
        }else{
			    $session['user']['gold'] -= $goldfee;
		        $newgold=(floor($goldfee*0.5)+$row['goldfund']);
			  }  output("`^You train for %s turns, for a cost of %s gold and gain %s experience!`n",$train,$goldfee,$totalexp);
     $session['user']['turns']-=$train;
     $session['user']['experience']+=$totalexp;
     $didtrain = $multi2;
    }
    break;

case "train4":
    page_header("Clan Training");
	  $train = abs($_POST['train']);
    if ($session['user']['turns'] <= $train) $train = $session['user']['turns'];
    $exp = $session['user']['level']*e_rand($ctminexp,$ctmaxexp)+e_rand(0,9);
    $totalexp = ($exp*$train)*$multi3;
    $goldfee =($totalexp * $goldturns3);
    if ($goldfee > ($session['user']['gold']+$session['user']['goldinbank'])) {
      output("You don't have enough money to train");
    }else{
        if ($goldfee > $session['user']['gold']) {
           $session['user']['goldinbank']+=$session['user']['gold'];
           $session['user']['gold']=0;
           $session['user']['goldinbank']-=$goldfee;
            output("The amount was, at least partially, debited from your bank account`n");
        }else{
			    $session['user']['gold'] -= $goldfee;
		        $newgold=(floor($goldfee*0.5)+$row['goldfund']);
			  }  output("`^You train for %s turns, for a cost of %s gold and gain %s experience!`n",$train,$goldfee,$totalexp);
     $session['user']['turns']-=$train;
     $session['user']['experience']+=$totalexp;
     $didtrain = $multi2;
    }
		break;
//End calculations for exp and gold cost

case "drink":
    page_header("Clan Training");
    $goldfee = $pricedrink;
    if ($goldfee > ($session['user']['gold']+$session['user']['goldinbank'])) {
      output("You don't have enough money for an energy drink.");
    }else{
        if ($goldfee > $session['user']['gold']) {
           $session['user']['goldinbank']+=$session['user']['gold'];
           $session['user']['gold']=0;
           $session['user']['goldinbank']-=$goldfee;
            output("The amount was, at least partially, debited from your bank account`n");
        }else{
			    $session['user']['gold'] -= $goldfee;
		        $newgold=(floor($goldfee*0.5)+$row['goldfund']);
			  }  output("`^You buy an energy drink and feel much healthier!`n");
     $session['user']['hitpoints']+=100;
     if ($session['user']['hitpoints']>$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];

      addnav(array("Buy another energy drink (%s gold)",$pricedrink),"runmodule.php?module=clantrain&op=drink");
      addnav("Clan Training","runmodule.php?module=clantrain&op=enter");
    }
		break;

            }//end switch

if($didtrain>0){
addnav("Clan Training","runmodule.php?module=clantrain&op=enter");
addnav(array("Buy an energy drink (%s gold)",$pricedrink),"runmodule.php?module=clantrain&op=drink");

//Start Exaustion calculation

$exausthp = $session['user']['maxhitpoints'];
$exaust1 = round(($exausthp/$exaustturns)*$train*$didtrain);
$exaust2 = round(($exausthp/$exaustexp)*$totalexp*$didtrain);
$exaust3 = e_rand(-5,5);
$exausttotal = $exaust1+$exaust2+$exaust3;
$exausthp = $session['user']['hitpoints'];

if($exausttotal >= $exausthp) $exausttotal=$session['user']['hitpoints']-1;
if($exausttotal == 0) $exausttotal=1;

$session['user']['hitpoints']-=$exausttotal;


$exaustion = round(($session['user']['hitpoints']/$session['user']['maxhitpoints'])*10);

switch($exaustion){
    case 10: output("`2`nThis was just warm up for you, the training cost you %s health points!`n",$exausttotal); break;
    case 9: output("`2`nThe exercise makes you feel hot, you take off the extra jacket, the training cost you %s health points!`n",$exausttotal); break;
    case 8: output("`2`nYou broke a sweat, the training cost you %s health points!`n",$exausttotal); break;
    case 7: output("`2`nYou feel your heart pumping faster, the training cost you %s health points!`n",$exausttotal); break;
    case 6: output("`^`nYou wonder if all this exercise will make you look thinner, the training cost you %s health points!`n",$exausttotal); break;
    case 5: output("`^`nThe blood is really flowing through your veins now, the training cost you %s health points!`n",$exausttotal); break;
    case 4: output("`^`nThis *huff* exercise *puff* has all *huff* your attention *puff* now, the training cost you %s health points!`n",$exausttotal); break;
    case 3: output("`4`nYou're starting to feel the pain on your bad knee again, the training cost you %s health points!`n",$exausttotal); break;
    case 2: output("`4`nOooooh, this is gonna hurt in the morning, the training cost you %s health points!`n",$exausttotal); break;
    case 1: output("`4`nYou're pretty sure the trainer wants to kill you, the training cost you %s health points!`n",$exausttotal); break;
    case 0: output("`4`nYou leave the training hall barely alive, the training cost you %s health points!`n",$exausttotal); break;
}

//End Exaustion calculation

//Start good events

if($events){
    $eventnumber=e_rand($chanceeventg,100);
    switch($eventnumber){
        case 91:
            $eventdam = e_rand(1,10);
            output("`n`2You find a candy bar someone forgot during the last training session. You gain %s hitpoints.",$eventdam);
            $session['user']['hitpoints'] += $eventdam;
            if($session['user']['hitpoints']>$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];
            break;
        case 92:
            $eventdam = e_rand(1,10);
            output("`n`2After the training you go to the bar to buy a fruit juice. The bartender tells you the person across the bar paid you a drink. You gain %s hitpoints and 1 charm.",$eventdam);
            $session['user']['hitpoints'] += $eventdam;
            if($session['user']['hitpoints']>$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];
            $session['user']['charm'] += 1;
            break;
        case 93:
            output("`n`2It's the training hall's anniversary. Free energy drinks for everyone! You're fully healed.");
            $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
            break;
        case 94:
            output("`n`2You were really in the mood for training, you did it in record time. You gain 1 turn.");
            $session['user']['turns'] += 1;
            break;
        case 95:
            $eventdam = e_rand(10,20);
            output("`n`2A training healer uses you as his victim... eh, subject. You gain %s hitpoints.",$eventdam);
            $session['user']['hitpoints'] += $eventdam;
            if($session['user']['hitpoints']>$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];
            break;
        case 96:
            $eventdam = e_rand(25,50);
            output("`n`2A throwing axe flys in your direction and chops your head off! Piffany, the cleric, puts you back together with some duct tape.");
            $session['user']['hitpoints'] += $eventdam;
            apply_buff('Duct Tape',
        			array(
          				"name"=>"`%Duct Tape",
      		    		"rounds"=>20,
      				    "defmod"=>0.9,
           				"atkmod"=>0.9,
          				"roundmsg"=>"The duct tape around your neck hinders your movements",
        	   			"schema"=>"module-clantrain",
      				)
			     );
            break;
        case 97:
            output("`n`2The training works, you increase your maximum hitpoints by 1.");
            $session['user']['maxhitpoints'] += 1;
            break;
        case 98:
            output("`n`2You trip and fall face down on the stony ground. On the plus side, you find a gem.");
            $session['user']['gems'] += 1;
            break;
        case 99:
            $eventgold = e_rand(1,1000);
            output("`n`2In the empty changing room you find a money purse with %s gold. Without remorse you add it to your own pack.",$eventgold);
            $session['user']['gold'] += $eventgold;
            break;
        case 100:
            $eventexp = e_rand(50,100);
            output("`n`2You paid perfect attention during this training session and executed every movement magnificently. You gain an extra %s experience.",$eventexp);
            $session['user']['experience'] += $eventexp;
            break;
    }
}


//Start accidents

if($accidents){
    $accidnumber=e_rand($chanceaccidb,100);
    switch($accidnumber){
        case 91:
            $acciddam = e_rand(1,10);
            output("`n`4You nick your finger on a training sword. This costs you %s hitpoints.",$acciddam);
            $session['user']['hitpoints'] -= $acciddam;
            if($session['user']['hitpoints']<1) addnews("`%%s`5 died in the clan training hall from a tiny nick in a finger!",$playername);
            break;
        case 92:
            $acciddam = e_rand(1,5);
            output("`n`4You get a splinter from a wooden shield. This costs you %s hitpoints, you crybaby.",$acciddam);
            $session['user']['hitpoints'] -= $acciddam;
            if($session['user']['hitpoints']<1) addnews("`%%s`5 died in the clan training hall from an infested splinter caught in the hand!",$playername);
            break;
        case 93:
            $acciddam = e_rand(10,50);
            output("`n`4An overeager sparring partner chopps your hand off! This costs you %s hitpoints.",$acciddam);
            $session['user']['hitpoints'] -= $acciddam;
            if($session['user']['hitpoints']<1) addnews("`%%s`5 bled to death in the clan training hall by having his hand chopped off by an overeager sparring partner!",$playername);
            break;
        case 94:
            $acciddam = e_rand(1,20);
            output("`n`4While you are lifting weights your hand slips. The bar falls on your chests and cracks a rib. This costs you %s hitpoints.",$acciddam);
            $session['user']['hitpoints'] -= $acciddam;
            if($session['user']['hitpoints']<1) addnews("`%%s`5 died crushed in the clan training hall under the handlebars of 200 kg weights!",$playername);
            break;
        case 95:
            output("`n`4Your sparring partner looks REALLY nice in full plated metal armor. You try to make some googly eyes, but it seems to have no effect. Demoralized, you lose 1 charm");
            $session['user']['charm'] -= 1;
            break;
        case 96:
            output("`n`4You slip on a wet towel, fall on the floor and hit your head. It makes you no damage, but it takes one turn to get back to your senses.");
            $session['user']['turns'] -= 1;
            break;
        case 97:
            output("`n`4You leave the training area badly scarred. This permantly decreases your hitpoints by 1");
            $session['user']['maxhitpoints'] -= 1;
            break;
        case 98:
            output("`n`4You come out of the training area and discover a nick in your purse. After counting your fortune your discover, you've lost a gem.");
            $session['user']['gems'] -= 1;
            break;
        case 99:
            $accidgold = e_rand(1,1000);
            output("`n`4For damages to the training hall and medical supplies to the other trainees you have to pay %s gold.",$accidgold);
            if($session['user']['gold'] > $accidgold) $session['user']['gold'] -= $acciddam;
            else $session['user']['bankedgold'] -= $acciddam;
            break;
        case 100:
            $accidexp = e_rand(50,100);
            output("`n`4Instead of staring at other people's behinds you should have paid more attention to the trainig. You lose %s experience.",$accidexp);
            $session['user']['experience'] -= $accidexp;
            break;
        
    }

}

//End Events and Accidents

//Kill players

if($session['user']['hitpoints']<1){
    $tempexp = $session['user']['experience'];
    $tempexp = round($tempexp / 10);
    $session['user']['experience'] -= $tempexp;
		output("`%`n`nYou die from exaustion and training accidents! You lose %s experience.`n",$tempexp);
    blocknav("clan.php",true);
    blocknav("runmodule.php?module=clantrain&op=enter",true);
    blocknav("runmodule.php?module=clantrain&op=drink",true);
    addnav("You're dead","shades.php");
}

} //End if($didtrain>0)

		addnav("Back to Clan Halls","clan.php");
		page_footer();
}
?>
