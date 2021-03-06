<?php
$info = array
(
	"name" => "Gem's Eternal Mysteries",
	"author" => "RPGee.com",
	"version" => "2.0",
	"category" => "Village Specials",
	"download" => "http://www.rpgee.com/lotgd/mysterygems.zip",
	"vertxtloc" => "http://www.rpgee.com/lotgd/",		
	"settings" => array(
		"G.E.M. General, title",
		"times" => "Maximum times allowed to buy per day, int|6",
		"runonceused" => "Reset daily buying allowance only on system game days?, bool|0",
		"runoncemount" => "Reduce number of days until mount returns only on system game days?, bool|0",
		"blockstables" => "Block stables when mount is lost?, bool|1",
		"chancemod"	=> "Increase chance of good result by this percentage, range,0,30,1|0",
		"Set above to 5+ to disable horrible outcomes.,note",
		"Set above to 30 to disable all bad outcomes.,note",
		"----------,note",
		"fancy" => "Use cycling rainbow text?, bool|1",
		"The rainbow text is displayed when entering the shop and cycles through the colors of the rainbow.,note",
		"Pricing in Gold, title",
		"turquoisecost" => "Turquoise, int|20",
		"malachitecost" => "Malachite, int|29",
		"moonstonecost" => "Moonstone, int|38",
		"hematitecost" => "Hematite, int|47",
		"starsapphirecost" => "Star Sapphire, int|56",
		"diamondcost" => "Diamond, int|65",
		"levelmultiply" => "Increase cost by 50% per level above 1?, bool|1",
		"Location, title",
		"move" => "Does the shop move from village to village?, bool|1",
		"mgloc" => "Location if shop does not move, location|".getsetting("villagename", LOCATION_FIELDS),
		"----------,note",
		"runoncemove" => "Move only on system new days?, bool|0",
		"Set above to NO to enable random shop location on a per user basis.,note",
		"You can set each user's G.E.M. shop location by editing the user if needed.,note",
		"----------,note",
		"place" => "Location today if it moves on system new days, location|".getsetting ("villagename", LOCATION_FIELDS),
		"Diamond Outcomes, title",
		"These settings are used when users die from choosing the diamond in G.E.M. and get the horrible result.,note",
		"gemslost" => "Percentage of gems in hand that users lose when they die, range,0,100|50",
		"goldlost" => "Percentage of gold in hand that users lose when they die, range,0,100|50",
	),
	"prefs" => array(
		"G.E.M. Preferences,title",
		"used" => "Gems bought today, int|0",
		"lostmount" => "Lost mount?, bool|0",
		"lostmountdays" => "Days until mount returns, int|0",
		"mountid" => "ID of mount, int|0",
		"userplace" => "Shop location, location|".getsetting ("villagename", LOCATION_FIELDS),
	)
);
?>