<?php
function gardensounds_getmoduleinfo(){
	$info = array(
		"name"=>"Garden Sounds",
		"version"=>"1.0",
		"author"=>"WebPixie",
		"category"=>"Garden",
		"download"=>"http://dragonprime.net/users/WebPixie/gardensounds.zip",
		"settings"=>array(
			"Garden Sounds Settings,title",
			"howoften"=>"Whats the chance to see the garden actions?,int|30"

		),
		
	);
	return $info;
}

function gardensounds_install(){
	module_addhook("gardens");
	return true;
}

function gardensounds_uninstall(){
	debug("Uninstalling module.");
	return true;
}

function gardensounds_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "gardens":
		$howoften = get_module_setting("howoften");
		if (e_rand(1, 100) <= $howoften) {
	
	switch (e_rand(1,10)){
		
		case 1:
     		output("`n`6A small blue bird flutters down out of the sky and rests on a nearby bench.`n`n");

		break;

		case 2:
     		output("`6A gust of wind blows through the garden creating a whirlwind of dried leaves.`n`n");

		break;
		case 3:
     		output("`6A gentle breeze blows softly across your face.`n`n");

		break;
		case 4:
     		output("`6You notice a faint smell of wildflowers waff through the garden.`n`n");

		break;
		case 5:
     		output("`6A rustle in the trees reveals a small brown squirrel playing high above.`n`n");

		break;
		case 6:
     		output("`6A Monarch butterfly flies through the air landing on a nearby flower bud.`n`n");

		break;
		case 7:
		output("`6You hear the faint sounds of children playing in the nearby park.`n`n");

		break;
		case 8:
		output("`6A small boy runs past you nearly knocking you down.`n`n");

		break;
		case 9:
		output("`6You notice a small patch of clouds moving across the sky. Looks like it might rain.`n`n");

		break;
		case 10:

     		output("`6You notice a strange silence is all around you. Then the wind picks up to a roar.`n`n");
	

		break;
	}
		}


			
	
	break;

	}
	return $args;
}


?>
