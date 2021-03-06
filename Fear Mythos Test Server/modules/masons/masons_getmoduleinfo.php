<?php
	$info = array(
		"name"=>"Secret Order of Masons",
		"author"=>"DaveS",
		"version"=>"5.0",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=179",
		"settings"=>array(
			"Secret Order of Masons,title",
			"masonsloc"=>"Where is the Masons Order located?,location|".getsetting("villagename", LOCATION_FIELDS),
			"healnumber"=>"Number of days required to heal a tattoo (plus random extra):,range,4,50,1|12",
			"dues"=>"How much gold are the dues?,int|5000",
			"duetime"=>"How many newdays for each Due cycle?,int|30",
			"dismisstime"=>"Number of Times Dismissed to cause ban:,int|3",
			"Set Times Dismissed to 0 to disable Ban,note",
			"dkban"=>"Number of DKs players get Banned from Order:,int|3",
			"Set Number of DKs to 0 to make Ban for Lifetime,note",
			"kickedout"=>"Kicked out of Order if not visited in number of days (does not count towards banning):,int|10",
			"madrikonatk"=>"Rare Sword (Madrikon) Attack:,int|20",
			"madrikongold"=>"Rare Sword (Madrikon) Value:,int|20000",
			"blackarmordef"=>"Rare Armor (Black Armor) Defense:,int|20",
			"blackarmorgold"=>"Rare Sword (Black Armor) Value:,int|20000",
			"perpage"=>"How many players per page in Membership List and Donor List?,int|25",
			"disciplinename"=>"Name of the last person disciplined by Superuser:,text|none",
			"disciplineid"=>"ID of the last person disciplined by Superuser:,int|0",
			"Masons Numbers and Recruiting,title",
			"newestmember"=>"Who is the newest Member of the Order:,text|Nobody",
			"newestid"=>"ID of the last Mason admitted to the Order:,int|0",
			"masonnum"=>"Number of the last Mason admitted to the Order:,int|0",
			"recruiting"=>"Allow Auto Recruiting in The Quarry based on number of Masons?,bool|1",
			"Auto Recruiting must be turned off to Close the Order from new members joining,note",
			"autozero"=>"Auto Set Very Common: less than this number of Masons,int|10",
			"autoone"=>"Auto Set Common: less than this number of Masons,int|25",
			"autotwo"=>"Auto Set Rare: less than this number of Masons (Extremely Rare if above),int|100",
			"The above settings are unnecessary if Auto Recruiting is Off,note",
			"Masons Benefits,title",
			"dkstart"=>"Give benefits based on Total Number of DKs or Number of DKs since joining the Order?,enum,0,Total DKs,1,DKs Since Joining|0",
			"resetnd"=>"Allow new set of benefits with any newday or with system newday?,enum,0,Any Newday,1,System Newday|1",
			"extraben"=>"Get 2 Benefits at 'Give to Other Masons' and 3 benefits at 'Give to Anyone'?,bool|1",
			"If Yes: Giving to Other Masons costs 2 benefits and Giving to Anyone costs 3 benefits,note",
			"mildexcede"=>"Percent Chance to Ban if Mildly Exceeds Benefits:,int|35",
			"Set Mildly Exceeds Benefits to 100 to instantly dismiss any player for exceeding benefit limits,note",
			"moderateexcede"=>"Percent Chance to Ban if Moderately Exceeds Benefits:,int|65",
			"severeexcede"=>"Percent Chance to Ban if Severly Exceeds Benefits:,int|95",
			"mbstarts"=>"What dk number do benefits start at?,int|0",
			"Set any benefit to -1 to disable,note",
			"mturns"=>"2 Turns available at dk:,int|0",
			"mgold"=>"Gold available at dk:,int|2",
			"mhps"=>"Temp HPs available at dk:,int|4",
			"mgems"=>"Gems available at dk:,int|6",
			"mfavor"=>"Favor available at dk:,int|8",
			"mcharm"=>"Charm available at dk:,int|10",
			"mtrav"=>"Travel available at dk:,int|12",
			"mhealpot"=>"Healing Potion available at dk:,int|14",
			"malign"=>"Alignment Improvement available at dk:,int|16",
			"mtorment"=>"Torments available at dk:,int|18",
			"mimpdef"=>"Improved Defense (only once per dk) available at dk:,int|20",
			"mimpatk"=>"Improved Attack (only once per dk) available at dk:,int|22",
			"mexp"=>"Experience (only once per day) available at dk:,int|24",
			"mspecialty"=>"Specialty (only once per dk) available at dk:,int|26",
			"mnweapon"=>"New Weapon available at dk:,int|28",
			"mnarmor"=>"New Armor available at dk:,int|30",
			"Gifts to Other Masons,title",
			"masongivemason"=>"Number of DKs needed before able to give benefits to Other Masons?,int|32",
			"Set any benefit to -1 to disable,note",
			"mgturns"=>"Give 2 Turns to Other Masons at dk:,int|32",
			"mggold"=>"Give Gold to Other Masons at at dk:,int|34",
			"mghps"=>"Give Temp HPs to Other Masons at dk:,int|36",
			"mggems"=>"Give Gems to Other Masons at dk:,int|38",
			"mgfavor"=>"Give Favor to Other Masons at dk:,int|40",
			"mgcharm"=>"Give Charm to Other Masons at dk:,int|42",
			"mgtrav"=>"Give Travel to Other Masons at dk:,int|44",
			"mghealpot"=>"Give Healing Potion to Other Masons at dk:,int|46",
			"mgtorment"=>"Give Torments to Other Masons at dk:,int|48",
			"mgimpatk"=>"Give Improved Defense to Other Masons (only once per dk) at dk:,int|53",
			"mgimpdef"=>"Give Improved Attack to Other Masons (only once per dk) at dk:,int|55",
			"Gifts to Anyone,title",
			"masongivenonmason"=>"Number of DKs needed before able to give benefits to Non-Masons?,int|57",
			"Set any benefit to -1 to disable,note",
			"gturns"=>"Give 2 Turns to Non-Masons at dk:,int|57",
			"ggold"=>"Give Gold to Non-Masons at dk:,int|60",
			"ghps"=>"Give Temp HPs to Non-Masons at dk:,int|63",
			"ggems"=>"Give Gems to Non-Masons at dk:,int|66",
			"gfavor"=>"Give Favor to Non-Masons at dk:,int|69",
			"gcharm"=>"Give Charm to Non-Masons at dk:,int|72",
			"gtrav"=>"Give Travel to Non-Masons at dk:,int|75",
			"ghealpot"=>"Give Healing Potion to Non-Masons at dk:,int|77",
			"gtorment"=>"Give Torments to Non-Masons at dk:,int|80",
			"gimpatk"=>"Give Improved Defense to Non-Masons (only once per dk) at dk:,int|83",
			"gimpdef"=>"Give Improved Attack to Non-Masons (only once per dk) at dk:,int|86",
		),
		"prefs"=>array(
			"Secret Order of Masons,title",
			"supermember"=>"Was Superuser Access granted?,bool|0",
			"masonnumber"=>"What number mason to join the order?,int|0",
			"allprefs"=>"Preferences for Secret Order of Masons,viewonly|",
		),
		"requires"=>array(
			"quarry" => "The Quarry by DaveS",
			),
		);
?>