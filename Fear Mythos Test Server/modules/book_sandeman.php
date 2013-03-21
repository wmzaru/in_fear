<?php

function book_sandeman_getmoduleinfo(){
	$info = array(
		"name"=>"Sandemans: A brief look at their race and culture (Book)",
		"author"=>"Script by WebPixie.<br>Coding by Silverfox<br>Sandeman race created by Ann Wilson",
		"version"=>"1.0",
		"category"=>"Library",
		"download"=>"http://dragonprime.net/users/ladymari/sandemanbook.zip",
		"vertxtloc"=>"http://dragonprime.net/users/ladymari/",
	);
	return $info;
}

function book_sandeman_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_sandeman_uninstall(){
	return true;
}

function book_sandeman_dohook($hookname, $args){
	global $session;
	switch($hookname){
		case "library":
			addnav("Book Shelf");
			addnav("Sandemans: A brief look at their race and culture", "runmodule.php?module=book_sandeman");
			break;
		}
	return $args;
}

function book_sandeman_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`c`bSandemans: A brief looks at their race and culture`b`c`n");
	output("`cby Ann Wilson, empire1(at)ecauldron(dot)com`c`n`n");

	switch ($op){
		case "1":
		output("`bSandemans`b`n`n");
		output("A genetically engineered race considered human-variant due to the number and extent of changes made.");
		output("In dealings with Sandemans, especially the warrior caste, it is essential to keep those changes, and the fact of a culture designed in large part to enhance them, in mind.");
		output("Sandemans are motivated primarily by honor, are intensely proud and ethnocentric, and have an extreme privacy drive.");
		output("Courtesy is deeply ingrained and they do not expect non-Sandemans to live up to Sandeman standards even when visiting Sandeman worlds.");
		output("`n`n");
		output("In most cases, that means they will ignore even deliberate insults from non-Sandemans -- but not in all cases.");
		output("Touching one without permission will get at least a verbal rebuke; attacking one will in all probability result in the attacker's death.");
		output("In Subsector Sandeman, the offender is at fault; elsewhere in the Empire, that is less automatic but still the presumption.");
		output("To paraphrase then-Baron Klaes of Mjolnir, the first Imperial world to have Sandemans as guests and protectors instead of conquerors: These people are used to protecting themselves, rather than relying on police.");
		output("Their reflexes are exceptionally fast and strong, particularly if they are under attack; anyone assaulting a Sandeman may expect to have à's cause of death listed as suicide.");
		output("`n`n");
		output("Sandemans are short and slender, the men averaging 160-165 cm and 62- 67 kilos, the women 2-3 cm and kilos less.");
		output("Skin tones range from dark tan to dark olive, hair from almost white to not quite light brown, mostly straight though a few have wavy hair, even fewer curly.");
		output("Eyes are pale-to-medium blue, green, or hazel.");
		output("Children are born covered in fur resembling a kitten's; it is usually gone by three months of age.");
		output("`n`n");
		output("Despite their small stature, Sandemans are stronger and faster than their creators, with greater endurance and higher average intelligence.");
		output("Between twenty and fifty percent (up from about ten percent under the Shapers) of the males, depending on the clan, are further modified warriors, whose speed, strength and endurance are increased still further.");
		output("`n`n");
		output("The basic Sandeman social structure is a two-caste clan, with the upper warrior caste composed of the warriors and warriors'-women, the Other caste being everyone else.");
		output("Caste may be determined by the clan prefix and often by clothing; the warrior caste usually wears subdued coveralls, the Others brighter clothing.");
		output("`n`n");
		output("As a rule, Others marry; warriors and w'women do not, fostering their children with Other families to provide a stable home life.");
		output("Children mingle freely regardless of caste, until at puberty the young student warriors and w'women move into their rooms in the main clanhome, while Other children remain with their parents until they marry or establish their own homes.");
		output("Warriors and w'women maintain ties, often close ones, with the families who raised them and will, in many cases, raise their children.");
		output("`n`n");
		output("Most traditional Sandeman names are Gaelic, Gaelic-flavored, or Norse-Germanic.");
		output("There is no naming distinction between the castes other than the clan-prefixes, or between the sexes except that names ending in -a are female; anything else can be either.");
		output("Nicknames may be proper names shortened to first or last syllable, or an occasional positive- attributive descriptive nickname.");
		output("Full first names are always proper usage, with 'the warrior' or 'the lady' added when speaking about a warrior or w'woman, and 'warrior' or 'lady' may be used instead of one's name when addressing à.");
		break;
	case "2":
		output("`bWARRIORS, Sandeman`b");
		output("`n`n");
		output("Males genetically modified even beyond the Sandeman norm by addition of a gland which produces the hormone egerin.");
		output("Under the Shapers, warriors were about ten percent of the male population.");
		output("This changed after Overthrow, when more warriors were seen as vital to the defense of the race.");
		output("After Annexation, when the benefits of warrior-police were seen on the worlds they had conquered, other worlds began to hire them and warriors became an economic asset to their clans.");
		output("Up to half of a modern clan's men may be warriors, with many of them away from the clan in the Marines or on contract as security forces.");
		output("`n`n");
		output("Egerin is the primary part of a sex-linked recessive gene complex, and is responsible for giving them speed, strength, and stamina well above the Sandeman norm.");
		output("Although the psychological segment of the gene complex is smaller, it is no less important: Sandeman warriors enjoy fighting.");
		output("`n`n");
		output("These physical and psychological traits are developed by training which starts at age five.");
		output("The major emphasis, of course, is on combat and related subjects, such as weapons, martial arts, High War Speech, military and Sandeman history, and perhaps some spacecraft handling.");
		output("Other important subjects are customs and courtesies, need management, the clan's specialty, and one or more personal interests, usually including some form of handiwork.");
		output("`n`n");
		output("When the young warriors move into the main clanhome at puberty, they begin doing their own chores (laundry, cleaning, etc.), including a share in cooking.");
		output("`n`n");
		output("At approximately 18 years old, there is a graduation ceremony for the new warriors and w'women, indicating that they now take their place in the clan as adults.");
		output("There is no sharp division other than that an adult will not fight or have sex (except for the instructors) with a student; part of the post-graduation party is an adult inviting one of the new graduates to one or the other.");
		output("`n`n");
		output("Warriors have the typical Sandeman ethnocentrism, but to an even greater degree.");
		output("The race was created to be better than its creators, and the warriors are the best of the Sandemans.");
		output("Fortunately, they are also more concerned with honor, propriety, and courtesy, and they are thoroughly trained in \"warrior restraint\"; it is dishonorable to fight Others who regard themselves as non-combatants, however great the provocation, except in self-defense.");
		output("To a Sandeman warrior, the Marine slogan \"Death before dishonor\" is a truism -- and a probable reason most warriors spend some time in that service.");
		output("`n`n");
		output("Their physiology and training, however, make it unlikely that a warrior will ever become a Ranger or Sovereign; they are temperamentally unsuited to administering a civilian group larger than a clan.");
		output("`nThat, at least, is the conventional wisdom.");
		break;
	case "3":
		output("`bWARRIORS'-WOMEN, Sandeman:`b`n");
		output("abbreviated w'women`n`n");
		output("The female members of the warrior caste, women trained in combat, medicine, and need management.");
		output("They have no genetic modifications beyond those shared by all Sandemans, so any girl-child who wants to take the training may do so, and most warriors' daughters, particularly the eldest, at least begin training; those who complete it successfully wear a gold-gemmed ring (e.g., golden topaz or citrine) to mark their status.");
		output("Since they have no special genetic alterations, however, any who decide they are not suited to the life may quit at any time, during or after training.");
		output("Because of this, there are fewer w'women than warriors, though ideally their numbers would be equal.");
		output("About the best ratio most clans can expect is one w'woman for two to three warriors.");
		break;
	case "4":
		output("`bCLAN PREFIXES:`b`n`n");
		output("Sandemans do not have surnames in the Standard sense; instead, they are identified by given name, clan prefix, and clan name.");
		output("There are three prefixes: \"Dar\" for warriors, \"Dru\" for warriors'-women, and \"Den\" for Others, of either sex.");
		break;
	case "5":
		output("`bCLANS, SANDEMAN:`b`n`n");
		output("The basic unit of Sandeman society, the extended \"family\".");
		output("While the clan-chief is in charge and has the last word, he is not -- cannot be -- a dictator.");
		output("Until Overthrow, clans were established by the Shapers and restricted in size to 200-500 members; since then, some have gotten larger, and a new clan is formed by agreement between two or more \"parent\" clans who contribute members and resources.");
		break;
	case "6":
		output("`bWAR DOGS, Sandeman:`b`n`n");
		output("One of the two types of livestock modified by the Shapers, of the wide variety they took with them.");
		output("These are Doberman pinschers modified for size (averaging about 45 kilos), intelligence, and speech.");
		output("Their intellectual ability is about that of a ten-year-old human.");
		output("The speech modification was less successful; while they can be understood, it takes either concentration or a practiced ear.");
		output("Clan Shona is the primary breeder and trainer of these dogs, and tends to consider them as members of the clan.");
		break;
	case "7":
		output("`bUNICORN, Sandeman: (usually abbreviated as 'corn)`b`n`n");
		output("One of the two types of livestock genetically modified by the Shapers, of the wide variety they took with them.");
		output("The base stock was Arabian horses; the Shapers added a unicorn horn, beard, and feathering at the hooves.");
		output("They come in all standard horse colors, plus ermine (white with black mane, tail, beard, and feathering) and counter-ermine (black, with white \"points\").");
		output("Although not designed specifically as war animals, unlike the modified Doberman pinschers, they were trained and used as such in the clan wars instigated by the Shapers, and played an important part in the Overthrow of those oppressors.");
		output("Some are still trained as such, to maintain the tradition of war 'corns, but the most popular sporting use of them is for racing (a tough rubber guard is fitted over the horn tip to prevent accidents).");
		break;
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library&op=shelves");
	    addnav("Chapters");
	    addnav("Sandemans","runmodule.php?module=book_sandeman&op=1");
	    addnav("WARRIORS, Sandeman","runmodule.php?module=book_sandeman&op=2");
	    addnav("WARRIORS'-WOMEN, Sandeman","runmodule.php?module=book_sandeman&op=3");
	    addnav("CLAN PREFIXES","runmodule.php?module=book_sandeman&op=4");	    
	    addnav("CLANS, SANDEMAN","runmodule.php?module=book_sandeman&op=5");
	    addnav("WAR DOGS, Sandeman","runmodule.php?module=book_sandeman&op=6");
	    addnav("UNICORN, Sandeman","runmodule.php?module=book_sandeman&op=7");
	page_footer();
}
?>