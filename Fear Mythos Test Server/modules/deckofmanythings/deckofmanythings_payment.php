<?php
function deckofmanythings_payment(){
	global $session;
	output("You decide you're more of a lover than a fighter and choose to spend quality time with the %s.  You give %s `b`^",($session['user']['sex']?"Incubus":"Succubus"),($session['user']['sex']?"him":"her"));
	if ($session['user']['gold']>=500) {
		output("500");
		$session['user']['gold']-=500;
	}else{
		output("All your");
		$session['user']['gold']=0;
	}
	output("gold`b`# and have a great time!`n`n`%");
	switch(e_rand(1,15)){
		case 1:
			output("You feel drained of energy!`#  You `\$Lose 1/2 of your Hitpoints`#!`n`n");
			output("That was a little disappointing.  You wander back into the `@forest`# trying to figure out what you did wrong.");
			$session['user']['hitpoints']*=0.5;
		break;
		case 2:
			output("Your senses are dulled.  `#You `&Lose One Defense Point`#!`n`n");
			output("That was a little disappointing.  You wander back into the `@forest`# trying to figure out what you did wrong.");
			$session['user']['defense']--;
		break;
		case 3:
			$expbonus=$session['user']['dragonkills']*3;
			$exploss =($session['user']['level']*e_rand(10,15)+$expbonus);
			if ($session['user']['experience']>=$exploss) {
				$session['user']['experience']-=$exploss;
				output("You feel out of shape. You `3Lose %s Experience`#!",$exploss);
			}else{
				$session['user']['experience']=0;
				output("You feel out of shape. `#You `3have Zero Experience`#!");
			}
			output("`n`nThat was a little disappointing.  You wander back into the `@forest`# trying to figure out what you did wrong.");		
		break;
		case 4:
			output("You are down in the dumps.`#`n`n");
			output("That was a little disappointing.  You wander back into the `@forest`# trying to figure out what you did wrong.");		
			apply_buff('depression',array(
				"name"=>"Depression",
				"rounds"=>10,
				"wearoff"=>"Your don't feel so sad anymore!",
				"atkmod"=>.96,
				"roundmsg"=>"You feel sad.",
			));
		break;
		case 5:
			output("You feel exhausted `#and `@Lose");
			if ($session['user']['turns']>1) {
				output("Two Turns.");
				$session['user']['turns']-=2;
			}else{
				output("One Turn.");
				$session['user']['turns']--;	
			}
			output("`#`n`nThat was a little disappointing.  You wander back into the `@forest`# trying to figure out what you did wrong.");
		break;
		case 6: case 7:
			output("You feel restored to health!`#");
			output("`n`nYou wander back to `@the forest`# with a smile on your face.");
			$session['user']['hitpoints']=$session['user']['maxhitpoints'];
		break;
		case 8: case 9:
			output("You feel raised to your full potential!`#  You `&Gain 1 Attack`# and`& 1 Defense`#!");
			$session['user']['defense']++;
			$session['user']['attack']++;
			output("`n`nYou wander back to `@the forest`# with a smile on your face.");
		break;
		case 10: case 11:
			output("You feel good enough to do it again! `# The `\$%s`# shakes %s head and grins. `n`n`\$'Oh no, you only get one time!'`n`n",($session['user']['sex']?"Incubus":"Succubus"),($session['user']['sex']?"him":"her"));
			output("`#You `@Gain 3 Extra Turns`# and get your `^500 gold`# back!!");
			$session['user']['turns']+=3;
			$session['user']['gold']+=500;
			output("`n`nYou wander back to `@the forest`# with a smile on your face.");
		break;
		case 12: case 13:
			output ("That was a very educational experience.`#`n`n");
			$expbonus=$session['user']['dragonkills']*4;
			$expgain =($session['user']['level']*e_rand(20,30)+$expbonus);
			$session['user']['experience']+=$expgain;
			output("You gain `b%s experience`b.",$expgain);
			output("`n`nYou wander back to `@the forest`# with a smile on your face.");
		break;
		case 14: case 15:
			output("You will always remember the `\$%s`%...",($session['user']['sex']?"Incubus":"Succubus"));
			output("`n`n`#You wander back to `@the forest`# with a smile on your face, with a new `&Buff`# to help you along.");
			apply_buff('fondmemory',array(
				"name"=>"Fond Memories",
				"rounds"=>20,
				"wearoff"=>"`#Your happy memory fades a little.",
				"atkmod"=>1.1,
			));
		break;
	}	
	output("`n`n`n");
	$session['user']['specialinc']="";	
	addnav("Back to the Forest","forest.php");	
}
?>