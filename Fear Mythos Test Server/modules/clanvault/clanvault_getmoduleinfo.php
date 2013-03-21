<?php
function clanvault_private_moduleinfo(){
	$info = array(
		"name"=>"Clan Vault for gems and gold",
		"author"=>"`^Damien`&, and `@CortalUX`&; with modifications by `3Lonnyl`&, `#RPGSL`& and RPGee.com",
		"version"=>"2.00",
		"vertxtloc"=>"http://www.rpgee.com/lotgd/",
		"category"=>"Clan",
		"download"=>"http://www.rpgee.com/lotgd/clanvault.zip",
		"settings"=>array(
			"Clan Vault - General Settings,title",
			"allowgoldinvault"=>"The Vault can store gold,bool|1",
			"allowgemsinvault"=>"The Vault can store gems,bool|1",
			"maxgoldinvault"=>"Max gold in the vault,int|20000",
			"maxgemsinvault"=>"Max gems in the vault,int|100",
			"vaulttime"=>"Number of realtime hours as member before vault is accessible?,int|24",		
			"vaultbonusgold"=>"Enable vault size increase for gold?,bool|1",
			"minmembersgold"=>"What is the minimum number of members needed for vault gold size increase?,int|10",
			"vaultpermembergold"=>"How much more gold can be stored in vault per member?,int|500",
			"vaultbonusgems"=>"Enable vault size increase for gems?,bool|1",
			"minmembersgems"=>"What is the minimum number of members needed for vault gem size increase?,int|10",
			"vaultpermembergems"=>"How many more gems can be stored in vault per member?,int|1",
			"smartid"=>"Enable Smart Vault which will check for matching IDs?,bool|1",
			"smartip"=>"Enable Smart Vault which will check for similar IPs?,bool|1",
			"minmem"=>"Minimum number of unique members needed to use the vault when Smart Vault is enabled?,int|0",
			"Clan Vault - Stipend Settings, title",
			"maxstipends"=>"Max amount of stipends per player per day(0=disable stipends),int|5",
			"maxrequests"=>"Max amount of request per player per day,int|5",
			"resetreceiverunonce"=>"Only reset number of stipends received on server game days?,bool|1",
			"maxreceive"=>"Max number of stipends a character may receive per day?,int|5",
			"requesttime"=>"Number of realtime hours as member before requests can be made?,int|24",
			"goldtransfer"=>"Gold stipends/requests into player's bank instead of into players hand,bool|0",
			"stipendtime"=>"Number of realtime hours as member before stipends can be given?,int|24",
			"stipendtimemember"=>"Number of realtime hours as member before stipends can be received?,int|24",
			"stipendgoldlevel"=>"Max gold allowed to stipend/request per receiver's level?,int|500",
			"stipendgemlevel"=>"Max gems allowed to stipend/request per receiver's level?,floatrange,.5,10,.5|.5",
			"stipendrunonce"=>"Only reset sent and received stipends on server generated new days?,bool|1",
			"Clan Vault - Withdrawal Settings,title",
			"allowwithdraw"=>"Allow leaders to withdraw funds?,bool|0",
			"withdrawtime"=>"Number of realtime hours as member before withdraws are possible?,int|24",
			"leaderwithdrawlevel"=>"The level which is required from the leader to make a withdraw,range,1,15,1|10",
			"goldperlevel"=>"How much gold per level can be withdrawn?,int|2000",
			"gemsperlevel"=>"How many gems per level can be withdrawn?,int|3",
			"resetlimitrunonce"=>"Reset the total amount of gold and gems withdrawn each server generated game day?,bool|1",
			"leaderlimitgold"=>"Max amount of gold allowed to withdraw each day?,int|30000",
			"leaderlimitgems"=>"Max amount of gems allowed to withdraw each day?,int|45",
			"Clan Vault - Donation/Deposit Settings,title",
			"deposittime"=>"Number of realtime hours as member before deposits are possible?,int|24",
			"donatetime"=>"Number of realtime hours as member before donations are possible?,int|24",
			"Clan Vault - Taxation Settings,title",
			"dktax"=>"Tax clan if player kills the dragon,bool|1",
			"oneplayertax"=>"Amount of dragon kill tax if only one member in clan(%),int|90",
			"taxannouncement"=>"Taxation announcement in the clan hall,bool|0",
		),
		"prefs-clans"=>array(
			"Clan Vault - Clan Preferences,title",
			"vaultgold"=>"Gold in the Vault,int|0",
			"vaultgems"=>"Gems in the Vault,int|0",
			"request1"=>"Request 1,text|empty",
			"request2"=>"Request 2,text|empty",
			"request3"=>"Request 3,text|empty",
			"request4"=>"Request 4,text|empty",
			"request5"=>"Request 5,text|empty",
			"request6"=>"Request 6,text|empty",
			"request7"=>"Request 7,text|empty",
			"request8"=>"Request 8,text|empty",
			"request9"=>"Request 9,text|empty",
			"request10"=>"Request 10,text|empty",
			"memberlow"=>"What is the lowest member count when using Smart Vault?,int|99999",

//RPGee.com - added in new clan pref to make sure the member low is updated when new members join
			"currentmem"=>"What is the current total member count?,int|0",
//END RPGee.com

		),
		"prefs"=>array(
			"Clan Vault - Preferences,title",
			"hasrequested"=>"Has player requested gold/gems,bool|0",
			"stipends"=>"How many stipends left,int|5",
			"requests"=>"How many requests left,int|5",
			"showNot"=>"Show notification?,bool|1",
			"withdrawgoldtoday"=>"How much gold has this character withdrawn today?,int|0",
			"withdrawgemstoday"=>"How many gems has this character withdrawn today?,int|0",
			"stipendreceive"=>"How many stipends has this person received?,int|0",
		),
	);
	return $info;
}
?>