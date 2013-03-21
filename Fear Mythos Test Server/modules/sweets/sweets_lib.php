<?php

function sweets_boon(){
	global $session;
	$int = e_rand(1,15);
	switch ($int){
		case 1: case 2: case 3: case 4: case 5:
			// Gold Rewards
			$gold_gain = floor(1000/$int);
			output("`3Mystie grins and pulls a ripcord.");
			output("\"`%Congratulations! You have won `^%s `%gold for being our random choice customer!`3\"",$gold_gain);
			$session['user']['gold']+=$gold_gain;
			break;
		case 6: case 7: case 8: case 9: case 10:
			// HP Loss
			$hp_loss = $int*3;
			output("`3You begin to convulse and fall out of your chair.");
			output("You hit the ground and lose `\$%s `3hitpoints.",$hp_loss);
			$session['user']['hitpoints']-=$hp_loss;
			if ($session['user']['hitpoints'] < 1) $session['user']['hitpoints'] = 1;
			break;
		case 11: case 12: case 13: 
			// Turns
			$turn_gain = e_rand(1,3);
			output("`3After you are finished with your snack, you feel energetic!");
			output("You feel as if you could face `@%s `3forest %s!",$turn_gain, translate_inline($turn_gain==1?"creature":"creatures"));
			$session['user']['turns']+=$turn_gain;
			break;
		case 14: case 15:
			// Gem Reward
			$gem_gain = ($int==14?1:2);
			output("`3After you are done, you feel the need to sneeze.");
			output("You find yourself sneezing quite vigorously and see %s %s underneath your chair!",$gem_gain, translate_inline($gem_gain==1?"gem":"gems"));
			$session['user']['gems']+=$gem_gain;
			break;
	}
}

?>