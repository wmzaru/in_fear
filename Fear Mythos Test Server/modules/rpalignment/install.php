<?php
// database table creation
if (!db_table_exists(db_prefix("rpalignment"))){
	$sql = "CREATE TABLE `".db_prefix("rpalignment")."` (
	  `name` varchar(255) NOT NULL default 'General',
	  `id` int(11) NOT NULL auto_increment,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM COMMENT='This holds the rp alighment info for the Roleplay Alignment module.' AUTO_INCREMENT=1 ;";
	db_query($sql);
}

// module hooks
	module_addhook("biostat");
	module_addhook("village");
	module_addhook("superuser");
	module_addhook("charstats");
	module_addhook("header-village");
	module_addhook("newday");
	module_addhook("shades");