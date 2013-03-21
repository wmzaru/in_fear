<?php
function deckofmanythings_exitstageleft(){
	global $session;
	$penavt=get_module_setting("penavt");
	$penavg=get_module_setting("penavg");
	$penavge=get_module_setting("penavge");
	output("You look at the `\$Deck `^of `\$Cards`#, then at the `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`#.");
	output("You pretend to look at your wrist watch `3(which, since wrist watches haven't been invented yet, makes you look a tad foolish)`# and try to stammer some excuse as to why you need to leave.`n`n");
	output("After mumbling something about going to feed a parking meter `3(which once again doesn't make any sense for the same reason that looking at a non-existent wrist watch doesn't)`# the old `%g`^y`%p`^s`%y`# woman rolls her eyes and pokes you with her `qstick`# one last time before you go.`n`n");
	if ($penavt>0 && $session['user']['turns']>0){
		if ($session['user']['turns']>$penavt) {
			output("You `@lose %s %s`#",$penavt,translate_inline($penavt>1?"turns":"turn"));
			$session['user']['turns']-=$penavt;
		}else{
			output("You `@lose all your remaining turns`#");	
			$session['user']['turns']=0;
		}
		output("for wasting her time and for being so boring and not doing anything.`n`n");	
	}
	if ($penavg>0 && $session['user']['gold']>0){
		if ($penavg==1) $gold=100;
		if ($penavg==2) $gold=500;
		if ($penavg==3) $gold=1000;
		if ($penavg<=3){
			output("As you slink off to the forest, you notice that the darn gypsy stole ");
			if ($session['user']['gold']>$gold) {
				output("`^%s gold`# from you!`n`n",$gold);
				$session['user']['gold']-=$gold;
			}else{
				output("`^all your gold`# from you!`n`n");
				$session['user']['gold']=0;
			}
		}else{
			if ($penavg==4) $mult=.1;
			if ($penavg==5) $mult=.25;
			if ($penavg==6) $mult=.5;
			output("As you slink off to the forest, you notice that the darn gypsy stole");
			if ($penavg<7){
				output("`^%s gold`# from you!`n`n",round($session['user']['gold']*$mult));
				$session['user']['gold']-=round($session['user']['gold']*$mult);
			}else{
				output("`^all your gold`# from you!`n`n");
				$session['user']['gold']=0;
			}
		}
	}
	if ($penavge>0 && $session['user']['gems']>0){
		output("Happily being away from that whole fiasco, you check your `%gem`# sack and notice that she robbed you of your `%gems`#!!!`n`n");
		if ($session['user']['gems']>$penavge) {
			output("You `%lose %s gem%s`#.",$penavge,translate_inline($penavge>1?"s":""));
			$session['user']['gems']-=$penavge;
		}else{
			output("You `%lose all your remaining gems`#.");	
			$session['user']['gems']=0;
		}
	}
	$session['user']['specialinc']="";
	addnav("Back to the Forest","forest.php");
}
?>