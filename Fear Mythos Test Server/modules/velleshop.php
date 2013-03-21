<?php

/*
changes in Version 1.1 by seekey
- added a function for making a flower as a present to other players
- added some settings and prefs
- changed some cases for making the present function
- changed some navigations for a better look
- if present function is available the player can choose after choosing a flower
- the gifted player get a message that someone has bought a present
- a new link appears when a present was bought for a player so the player can take his gift
- changed the set_module_pref("stemstaken") into module_pref("stemstaken")

Greetings and I hope you will have fun with that.
Seekey
*/

function velleshop_getmoduleinfo(){
	$info = array(
	  "name" => "Velle's Flower Shop",
	  "version"=> "1.1",
	  "author"=> "Velle,based on code by Chris Vorndran with additions by seekey",
	  "download"=> "http://will put addy here once I have one",
	  "description"=>"Flowers apply random effects, including neg and pos buffs. Users can also buy the flowers as gifts that will apply the outcome to the recipient.",
	  "category"=>"Village",
	  "settings"=>array(
			"Velle's Flower Shop Settings,title",
			"shoploc"=>"Where does this shop appear,location|".getsetting("villagename",LOCATION_FIELDS),
			"mindk"=>"What is the minimum DK before this shop will appear to user?,int|0",
			"mult"=>"Multiplier of Levels to stem cost,int|20",
			"stemsallowed"=>"Times players are allowed to buy per day,int|3",
			"allowgifts"=>"Are players allowed to buy flowers as a present?,bool|1",
			"giftsallowed"=>"If allowed how many times players can become a gift per day,int|3",
			"giftstems"=>"Should a gift decrease the total amount of stems per day for both players?,bool|1",
			"expgain"=>"Experience Multiplier,floatrange,0,1,.05|.1",
		),
		"prefs"=>array(
			"Velle's Flower Shop Preferences,title",
			 "stemstaken"=>"Has the user bought a stem today,int|0",
			 "giftedtotal"=>"How many times has this player gotten a gift today?,int|0",
			 "flower"=>"Which flower was bought as a gift?,text|",
			 "gifttaken"=>"Has the player gotten their gift?,bool|1",
		)
	  );
	return $info;
}
function velleshop_install(){
  module_addhook("changesetting");
  module_addhook("newday");
  module_addhook("village");
  return true;
}
function velleshop_uninstall(){
  return true;
}
function velleshop_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "changesetting":
			if($args['setting'] == "villagename"){
				if ($args['old'] == get_module_setting("shoploc")){
					set_module_setting("shoploc",$arg['new']);
				}
			}
		break;
		case "newday":
			set_module_pref("stemstaken",0);
			set_module_pref("giftedtotal",0);
		break;
		case "village":
			if ($session['user']['location'] == get_module_setting("shoploc") && $session['user']['dragonkills'] >=get_module_setting("mindk")){
				tlschema($args['scemas']['marketnav']);
				addnav($args['marketnav']);
				tlschema();
				addnav("Velle's Flower Shop","runmodule.php?module=velleshop&op=enter");
			}
		break;
	}
	return $args;
}
function velleshop_run(){
	global $session;
	$op = httpget('op');
	$flower = httpget('flower');

	// get the settings
	$ag = get_module_setting("allowgifts");
	$sa = get_module_setting("stemsallowed");
	$ga = get_module_setting("giftsallowed");	
	$gs = get_module_setting("giftstems");
	$c = ($session['user']['level']*get_module_setting("mult"));

	// get the prefs
	$st = get_module_pref("stemstaken");
	$ta = get_module_pref("gifttaken");
	$gt = get_module_pref("giftedtotal");	
	page_header("Velle's Flower Shop");

	switch ($op){
		case "enter":
			output("`7The heavy scent of flowers of every type and hue greet you as you step into the flower shop.`n`n");
			output("A tall blonde with deep green eyes walks toward you with a smile dancing on her ruby lips.");
			output("You can't help but notice the tempting curves she carries on her delicate, half elven frame.");
			output("She watches you for a moment and then points to the many vases of flowers the line the shop.");
			output("`3\"Would you like to buy a flower?\"`n`n");
			output("`7She pulls back a stray strand of hair and wraps it behind a tall pointed ear.");
           	output("Her eyes never seem to leave you, and you realize that those eyes are older than they look.");
			output("Part of you wonders at what all she has seen.");
           	output("You turn away from that gaze to look once more at the flowers in the shop.");
			output("You also glance at the door, wondering if you should even be here.");
			addnav("Look about the Shop","runmodule.php?module=velleshop&op=flower");
		break;
		
		case "flower":
			if ($flower == "" && $st < $sa){
				output("The shop is full of many delightful and elegant choices.");
				output("You walk about the shop, taking time to breathe in the aroma of each bloom.");
				output("You can sense that each exquisite ethereal bloom has some sort of magic stored in the petals.");
				output("You reach out to examine one of the flowers closely.");
				output("`5\"Careful\"`7, she flashes a wicked grin, \"`5These are not ordinary blooms\"`7");
                output("You look back at Velle, and suppress the urge to snap a retort.");
                output("Sheesh, anyone with half a brain would realize that.`n`n");
				
				addnav("The Stand Arrangement");
				addnav("Rose","runmodule.php?module=velleshop&op=flower&flower=rose");
				addnav("Lily","runmodule.php?module=velleshop&op=flower&flower=lilly");
				addnav("Amaryllis","runmodule.php?module=velleshop&op=flower&flower=amaryllis");
				addnav("Sweet Pea","runmodule.php?module=velleshop&op=flower&flower=sweetpea");
				addnav("Bird of Paradise","runmodule.php?module=velleshop&op=flower&flower=Bird of Paradise");
				addnav("Iris","runmodule.php?module=velleshop&op=flower&flower=Iris");
				addnav("Black Nasturtium","runmodule.php?module=velleshop&op=flower&flower=Black Nasturtium");
				if($ta==0){
				$gift=get_module_pref("flower");
				addnav("Gifts");
				addnav("Get your Gift","runmodule.php?module=velleshop&op=gifted");
				output("You know that someone has bought you a gift, so you reach into your pocket to pull out your message as proof that a present is here for you. You show it to her and notice she has a rather amused twinkle in her eye.  Velle takes the sheet and reads it over before she nods.");
				output("`5\"Do you wish to see your present now?\",`7 she asks you.");
				}
			}elseif($st >= $sa){
				output("The stench is too much for your delicate nose.  You place your hand over your mouth to keep from gagging before running out the shop door.");
			}else{
				if ($session['user']['gold'] >= $c){
					$session['user']['gold']-=$c;
				
				if($ag==1){	
				  output("Velle slips up next to you so quietly that her sudden words startle you.");
				  output(" `4\"Is it posible that you want to give this to anyone as a gift, or do you want it for your own? If it is a gift, I will send the person a message, and they can come to pick it up later. \"`5");
					addnav("For your own","runmodule.php?module=velleshop&op=own&flower=$flower");
					addnav("Gifts");
					addnav("As a gift","runmodule.php?module=velleshop&op=gift&flower=$flower");
					addnav("Shop");
					addnav("Return to Menu","runmodule.php?module=velleshop&op=flower");					
				}else{
					redirect("runmodule.php?module=velleshop&op=own&flower=$flower");
					}
						
				}else{
					output("`@Silly Billy! You don't have enough cash for even a single leaf, let alone a full stem.");
				}
			}
			break;
		
		case "own":
			increment_module_pref("stemstaken",+1);	
			switch ($flower){
			case "rose":
					output("`4The soft delicate scent of the blood red rose fill your nose, as the color captures your eye.");
					output("You reach out to grasp the stem only to feel the sudden pain of a thorn pierce the soft flesh of your thumb.");
					output("You quickly pull your hand back and suck the red  drop of blood from your digit as Velle stoops to pick up the dropped flower.");
					output("She hands it back to you, her eyes searching your face for some sign of change.");
					output("`5\"Careful\" `4she says with an odd smile,`5\"The prick of the rose can have odd effects\"`n");
					velleshop_flowers();
			break;
			case"lilly":
					output("`7Looking over your shoulder you see a tall black vase with bright white flowers that bring to mind thoughts of death and funerals");
					output("Stepping forward, you dip your head down to see if these beauties have a scent.");
					output("Velle simply laughs as you feel the strange scent invade your mind.");
					output("You can feel that something has changed with in you, but what?!?`n");
					velleshop_flowers();
			break;
			case "amaryllis":
				  	output("`%You spy the oddest flower and cross the shop to get a closer look.");
					output("It has such a bewitching shape that you just can't take your eyes off of it.");
					output("Too bad your eyes were focused on the flower and not the floor.");
					output("You trip over a cat that was sleeping in the shop and fall on your nose.");
					output("Well at least you can say you had some excitement today.`n");
					velleshop_flowers();
			break;
			case "sweetpea":
					output("`^What flower is this that floats like a tiny balloon on an impossibly delicate stem?");
					output("You think to yourself that there couldn't be a sweeter scent than that of this flower.");
					output("The joy the flower gives you makes you feel like nothing in the world could go wrong.");
					output("Too bad that feeling has proven to be wrong in the past.");
					output("Who knows, maybe it will be right this time.");
					velleshop_flowers();
			break;
			case "Bird of Paradise":
			  output("In a crystal vase by the front window is a tall stem that holds the oddest flower.");
			  output("The Bird of Paradise is so exotic even in its shape that you can't help but wonder if those found in the wild have their own magik even without Velle's enhancements.");
			  output("There is a small paper sign at the base of the crystal vase that tells you that the Bird of Paradise symbolizes joy.");
			  output("You begin to wonder if this whimsical bloom can provide you with a little of that.");
			  velleshop_flowers();
			  break;
			case "Iris":
			  output("Here stands the showy bloom of the flower which takes its name from the Greek word for a rainbow");
			  output("This particular strain is of a rich, royal purple hue seems to speak of storms and dark nights.");
			  output("A subdued bouquet reaches your nose and you find that it makes you feel as if you could sit here in this spot all day, just to keep from losing the pleasure of this aroma.");
			  velleshop_flowers();

			  break;
			case "Black Nasturtium":
			  output("Ah, the deep crimson to almost true black of the Nasturtium.");
			  output("Long a flower used in rites of magik, for the blossoms, leaves, and buds are considered Fire herbs.");
			  output("You once heard it said that to eat of this plant while burning Dragon's Blood Resin would give a warrior unimagined power and gifts");
			  output("Well, it would, that is if you did it correctly.");
			 output("Are you even sure what Dragon's Blood Resin is?");
			 velleshop_flowers();
			  break;
			}
		break;	
		
		case "gifted":
			if($taken==0){
					$flower=get_module_pref("flower");
				if($gs==1){
					increment_module_pref("stemstaken",+1);
				}			
					increment_module_pref("giftedtotal",+1);
					set_module_pref("flower","");
					set_module_pref("gifttaken",1);
				switch ($flower){
				case "rose":
						output("`4The soft delicate scent of the blood red rose fill your nose, as the color captures your eye.");
						output("You reach out to grasp the stem to feel the sudden pain of a thorn pierce the soft flesh of your thumb.");
						output("You quickly pull your hand back and suck the red  drop of blood from your didgit as Velle stoops to pick up the dropped flower.");
						output("She hands it back to you, her eyes searching your face for some sign of change.");
						output("`5\"Careful\" `4she says with an odd smile,`5\"The prick of the rose can have odd effects\"`n");
						velleshop_flowers();
				break;
				case"lilly":
						output("`7Looking over your shoulder you see a tall black vase with bright white flowers that bring to mind thoughts of death and funerals");
						output("Stepping forward you dip your head down to see if these beauties have a scent.");
						output("Velle simply laughs as you feel the strange scent invade your mind.");
						output("You can feel that something has changed with in you, but what?!?`n");
						velleshop_flowers();
				break;
				case "amaryllis":
					  	output("`%You spy the oddest flower and cross the shop to get a closer look.");
						output("It has such a bewitching shape that you just can't take your eyes off of it.");
						output("Too bad your eyes were focused on the flower and not the floor.");
						output("You trip over a cat that was sleeping in the shop and fall on your nose.");
						output("Well at least you can say you had some excitement today.`n");
						velleshop_flowers();
				break;
				case "sweetpea":
						output("`^What flower is this that floats like a tiny ballon on an impossibly delicate stem?");
						output("You think to yourself that there couldn't be a sweeter scent than that of this flower.");
						output("The joy the flower gives you makes you feel like nothing in the world could go wrong.");
						output("Too bad that feeling has proven to be wrong in the past.");
						output("Who knows, maybe it will be right this time.");
						velleshop_flowers();
				break;
                        	case "Bird of Paradise":
			  output("In a crystal vase by the front window is a tall stem that holds the oddest flower.");
			  output("The Bird of Paradise is so exotic even in it's shape that you can't help but wonder if those found in the wild have thier own magik even without Velle's enhancements.");
			  output("There is a small paper sign at the base of the crystal vase that tells you that the Bird of Paradise symbolises joy.");
			  output("You begin to wonder if this whimsical bloom can provide you with a little of that.");
			  velleshop_flowers();
			  break;
			case"Iris":
			  output("Here stands the showy bloom of the flower which takes its name from the Greek word for a rainbow");
			  output("This particular strain is of a rich royal purple hue that seems to speak of storms and dark nights.");
			  output("A subdued bouquet reaches your nose and ou find that it makes you feel as if you sit here in this spot all day, just to keep from losing the pleasure of this aroma.");
			  velleshop_flowers();

			  break;
			case"Black Nasturtium":
			  output("Ah, the deep crimson to almost true black of the Nasturtium.");
			  output("Long a flower used in rites of magik for the blossoms, leaves and buds are considered Fire herbs.");
			  output("You once heard it said that to eat of this plant while burning Dragon's blood resin would give a warrior unimagined power and gifts");
			  output("Well, it would, that is if you did it correctly.");
			 output("Are you even sure what Dragon's Blood Resin is?");
			 velleshop_flowers();
			 break;
				}
			}else{
				output("Sorry, but you have received your present, so you'll have to wait until someone will buy you a new present.");
			}
			break;
		
		case "gift":	
				output("`5\"I need the name of the person who will receive the gift,\" `4she says with an odd smile,`5\"Do you want to tell me, who the lucky one is?\"`n");
					$text=translate_inline("Choose");
					rawoutput("<form action='runmodule.php?module=velleshop&op=present&flower=$flower&name=".HTMLEntities($row['login'])."' method='POST'>");
					rawoutput("<input id='input' name='whom' width='25' maxlength='25' value='".htmlentities($name)."'>");
					rawoutput("<input type='submit' class='button' value='$text'>");
					rawoutput("</form>");
					addnav("","runmodule.php?module=velleshop&op=present&flower=$flower&name=".HTMLEntities($row['login'])."");
					addnav("Shop");
					addnav("Return to Menu","runmodule.php?module=velleshop&op=flower");
		break;
		
		case "present":
				$string="%";
		
				for ($x=0;$x<strlen($_POST['name']);$x++){
				$string .= substr($_POST['name'],$x,1)."%";
				}
				
				$whom=$_POST['whom'];
				$whom = stripslashes(rawurldecode($whom));
  			    $name="%";
       
			    for ($x=0;$x<strlen($whom);$x++){
  		        $name.=substr($whom,$x,1)."%";
   			    }
       
			   $whom = addslashes($name);
			   $sql = "SELECT login,name,level FROM ".db_prefix("accounts")." WHERE name LIKE '%".$whom."%' ORDER BY name";
			   $result = db_query($sql);
			if (db_num_rows($result)>0){
 
			   output("You would send a present to the following person?`n");
			   rawoutput("<table cellpadding='3' cellspacing='0' border='0'>");
			   rawoutput("<tr class='trhead'><td>Name</td><td>Level</td></tr>");
		          
			   for ($i=0;$i<db_num_rows($result);$i++){
			   $row = db_fetch_assoc($result);
			   rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='runmodule.php?module=velleshop&op=present2&flower=$flower&name=".HTMLEntities($row['login'])."'>");
			   output_notl($row['name']);
			   rawoutput("</a></td></tr>");
			   addnav("","runmodule.php?module=velleshop&op=present2&flower=$flower&name=".HTMLEntities($row['login']));
		       }
		   	   rawoutput("</table>");
			 }else{
				output("`4There is no person with this name. Please choose an other person.");
			 }	
			addnav("Choose again","runmodule.php?module=velleshop&op=gift");
			addnav("Shop");
			addnav("Return to Menu","runmodule.php?module=velleshop&op=flower");

		break;
	
		case "present2":
				$sql = "SELECT name,level,acctid FROM ".db_prefix("accounts")." WHERE login='".$_GET['name']."'";
				$result = db_query($sql);
	
				if (db_num_rows($result)>0){
					$row = db_fetch_assoc($result);
				if (get_module_pref("giftedtotal","velleshop",$row['acctid'])>=get_module_setting("giftsallowed","velleshop",$row['acctid'])){
					output("`4This person has too many presents already.  Try Back again tomorrow.");
				
				}elseif(get_module_pref("gifttaken","velleshop",$row['acctid'])==0){
					output("`4This person has already recieved a gift, and has not bothered to pick the present up. Maybe you could come later and try again or choose an other person for giving a gift to.");
					addnav("Choose again","runmodule.php?module=velleshop&op=present");
				}else{	
					increment_module_pref("giftedtotal",1,"velleshop",$row['acctid']);
					set_module_pref("flower",$flower,"velleshop",$row['acctid']);
					set_module_pref("gifttaken",0,"velleshop",$row['acctid']);
					$msg=array("%s`Q has bought a flower for  %s `Qat Velles Flowershop. You are welcome to get your present, whenever you want.",$session['user']['name'],$flower);
					require_once("lib/systemmail.php");
					systemmail($row['acctid'],array("`QSomeone has bought you a present!"),$msg);
					output("`4A message was sent to the person, so that they may pick up their gift here.");	
					}
				}else{
					output("`4There is no person with this name. Please choose another person.");
					addnav("Choose again","runmodule.php?module=velleshop&op=gift");
				}
			addnav("Shop");
			addnav("Return to Menu","runmodule.php?module=velleshop&op=flower");

		break;
	}
	addnav("Leave");
	villagenav();
   	page_footer();
}
function velleshop_flowers(){
	global $session;
	$flower= httpget('flower');
			addnav("Shop");
			addnav("Return to Menu","runmodule.php?module=velleshop&op=flower");


	switch (e_rand(1,9)){
		case 1:
			apply_buff('str',array(
				"name"=>"Bloated Sense Of Self",
				"rounds"=>5,
				"wearoff"=>"Maybe you're not that big and tough after all.",
				"roundmsg"=>"You are so much more powerful than a flower.",
				"atkmod"=>1.04,
				"schema"=>"module-velleshop"
			));
			output("`n`n`6Such a small thing in your hand.");
			output("You could easily crush it, if you wanted to.");
			output("The flower's soft scent and delicate shape makes you feel big and strong.");
			output("Would it be so hard to crush the world, just as you could crush this insignificant thing?");
			output("You take closer look, but have a sudden feeling you should be looking elsewhere.");
			output("`n`n`^You might wanna look at your buffs!");
			debuglog("gained 1 strength buff  from a $flower flower");
		break;
		case 2:
			$charm=$session['user']['charm'];
			apply_buff('ch',array(
				"name"=>"Wheezing",
				"rounds"=>13,
				"startmsg"=>"Darn that seasonal Allergy.",
				"wearoff"=>"You feel your meds kicking in for that hayfever",
				"roundmsg"=>"When will this sneezing end?",
				"defmod"=>($charm>8000?0.8:1.2),
				"schema"=>"module-velleshop"
			));
			output("`n`n`6You breathe in deeply the fresh scent and suddenly feel your throat tickle.");
			output(" Oh, No!`4 Allergies!. `n`n");
			output("`6You check your pocket for your meds.");
			output("`7You might wanna also check your buffs!.");
			debuglog("gained charmbased defmod from a $flower flower");
		break;
		case 3:
			$ch = e_rand(1,9);
			output("`n`n`6The beauty of the flowers reminds you of how beautiful you are.`n`n");
            output("You can just sense that everyone in the shop is staring at your alluring physique.");
			output(" Now, aren't you just the charming specimen?.");
			output(" Bet your a wiz with the opposite sex");
			output("`^You gain `%%s `^Charm!", $ch);
			debuglog("gained $ch charm from a $flower flower");
			$session['user']['charm']+=$ch;
		break;
		case 4:
		  $regen =e_rand(1,9);
			apply_buff('re',array(
				"name"=>"Hippie Health",
				"rounds"=>10,
				"startmsg"=>"Fight `iThe Man`i with Flowery Goodness",
				"wearoff"=>"But junk food and SUV's looks so good.",
				"roundmsg"=>"Down with `iThe Man`i, Man!",
				"regen"=>$regen,
				"schema"=>"module-velleshop"
			));
			output("`n`n`6You notice all that nature holds in this small flower.");
			output("You realize that you don't need anything but what the simple life has to offer.");
			output("Holding this flower you feel you can face the tyranny of `iThe Man`i.`n`n");
			output("By opposing the oppressor, you will make the world safe for flowers everywhere!");
			debuglog("gained regen buff from a $flower flower");
		break;
		case 5:
			$expgain = round($session['user']['experience']*get_module_setting("expgain"));
			output("`n`n`6You breathe in deep the perfume.");
            output("The fragrance reminds you of something.");
			output("Memories of home, family, and loved ones come back in waves.`n`n");
			output("`^It was like living your life all over agin in a single scent. You gain `#%s `^Experience!",$expgain);
			$session['user']['experience']+=$expgain;
			debuglog("gained $expgain experience from $flower flower");
		break;
		case 6:
			apply_buff('tms',array(
				"name"=>"Good Feeling",
				"rounds"=>15,
				"startmsg"=>"That happy place feeling coming from the memory of your flower helps you to stand tall.",
				"wearoff"=>"Good Feeling Gone",
				"roundmsg"=>"Happy, Happy, Joy, Joy",
				"damageshield"=>0.5,
				"schema"=>"module-velleshop"
			));
			output("`n`n`2Looking at the bloom you are overcome by a sense of peace and calm`n`n");
			output(" You feel a surge of well being and know it's a wonderful world after all.");
			output("A feeling that you can take on more powerful challenges is so strong, you run out the shop door.");
			output("The forest is calling your name.");
			debuglog("gained tempstat from $flower flower");
		break;
	case 7:
	  apply_buff('lif',array(
				   "name"=>"Roots and Shoots",
				   "rounds"=>5,
				   "startmsg"=>"Velle's flower magic allows you to force your attacker to hit themself.",
				   "wearoff"=>"The flower withers from over use.",
				   "roundmsg"=>"Flower of Power, Protect Me!",
                   "lifetap"=>1,
					"schema"=>"module-velleshop"
				   ));
	  output("`n`n`6You place the flower at your breast and feel the power it holds radiating out.");
	  output("It is odd, but you feel as if nothing could hurt you, or at least not as well as they could have.");
	  output("I bet you could even face the in-laws with this flower by your side.");
	  output("Then again, maybe that's asking too much from something so small.");
	  break;
	case 8:
	$gems = e_rand(1,20);
			$session['user']['gems']+=$gems;
			output("`n`n`6You look closely at the flower to find that it seems something is wedged within the petals.");
			output(" You pull the petals off one at a time, and with each layer you find something sparkly.");
			output(" Looking over your shoulder, you slip the small sparkles into your pocket.");
			output(" `^You wait till you have stepped outside to look at what all is in your pocket.");
			output("You must be extremely lucky no one else found that flower first, for you found `5%s `^%s.",$gems,translate_inline($gems>1?"Gems":"Gem"));
			debuglog("gained $gems gem from a $flower flower");
			break;
	case 9:	
	  apply_buff('fol',array(
				    "name"=>"Fragrance OverLoad",
				    "rounds"=>10,
				    "startmsg"=>"The Smell!!! The Smell!!!",
				    "roundmsg"=>"Oh, the stench of that horrible flower!",
				    "wearoff"=>"Finally you can breathe right again.",
                    "atkmod"=>-3,
					"schema"=>"module-velleshop"
					));
	  output("`n`n`6You breathe deep the flower's...stench.");
	  output("Wait, this smells horrid.");
	  output("It's like walking nose first into the perfume department from hell.");
	  output("Not only that...but now you can't smell anything but that funk.");
      output("Wonder if this will affect how well you fight?");
	}
    page_footer();
}
?>
