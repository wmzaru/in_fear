<?php
/* Tale Teller 22Sep2005
   Author: Robert of Maddrio dot com
   Something to do in the garden
*/

function taleteller_getmoduleinfo(){
	$info = array(
	"name"=>"Tale Teller",
	"version"=>"1.0",
	"author"=>"`2Robert",
	"category"=>"Gardens",
	"download"=>"http://dragonprime.net/users/robert/taleteller098.zip",
	);
	return $info;
}

function taleteller_install(){
	module_addhook("gardens");
	return true;
}
function taleteller_uninstall(){
	return true;
}

function taleteller_dohook($hookname,$args){
	switch($hookname){
	case "gardens":
	addnav("View");
	addnav("Tale Teller","runmodule.php?module=taleteller");
	break;  
	}
return $args;
}

function taleteller_run(){
	global $session;
	page_header("The Gardens - Tale Teller");
	addnav(" leave ");
	addnav("Return to Gardens","gardens.php");
	output("`c`b`2The Gardens`b`c");
	output("`n`n You stop by the Teller of Tales. ");
	output("`n`n Every day the children come to listen to her fables. ");
	output("`n`n You are just in time, as she begins to tell the tale of `n`n");
	switch (e_rand(1,14)){
	case 1:	output("`6 Hansel and Gretal and how they were arrested for murdering the Old Witch. ");break;
	case 2: output("`6 Snow White and the Six Dwarves she killed with her bare hands and the Seventh Dwarf she let get away as a warning to the other dwarfs. "); break;
	case 3: output("`6 Goldilocks who dies with honor after encountering the Three Bears "); break;
	case 4: output("`6 A promiscuous Old Woman who lived in a shoe who couldnt keep her legs closed "); break;
	case 5: output("`6 How Three Little Pigs starved a wolf to death "); break;
	case 6: output("`6 The idiot Jack who sold his cow for a handful of beans "); break;
	case 7: output("`6 Old Mother Hubbard, who survived a long cold winter by eating her dog "); break;
	case 8: output("`6 Mary who had a Little Lamb. She said it was delicious "); break;
	case 9: output("`6 The Hare who lost a race the Tortoise, who became so angry by his defeat, he now terrorizes the realm we live in. "); break;
	case 10: output("`6 A boy named Jack becomes a loser for the rest of his life, while his sister Jill became a mighty WarLord  "); break;
	case 11: output("`6 How all the Kings men and all the Kings horses enjoyed scrambled eggs for breakfast one day long ago.  "); break;
	case 12: output("`6 Little Miss Muffet who was cursed by an evil spider  "); break;
	case 13: output("`6 Cinderella and how she lured a handsome prince, married, then killed him, to become a powerful Queen  "); break;
	case 14: output("`6 A horrible little liar named Pinocchio  "); break;
	}
page_footer();
}
?>