<?php
if (!db_table_exists(db_prefix("chess"))) {
	$sql = "CREATE TABLE " . db_prefix("chess") . " (
			gameid smallint(6) NOT NULL auto_increment,
			user1 int(11) NOT NULL default 0,
			user2 int(11) NOT NULL default 0,
			starttime varchar(255) NOT NULL default '',
			lastmoves varchar(255) NOT NULL default '',
			gameinfo varchar(1024) NOT NULL default '',
			turn int(11) NOT NULL default 0,
			PRIMARY KEY (gameid)) TYPE=MyISAM";
	db_query($sql);
}
if (!db_table_exists(db_prefix("chess_saved"))) {
	$sql = "CREATE TABLE " . db_prefix("chess_saved") . " (
			gameid int(11) NOT NULL default 0,
			user1 int(11) NOT NULL default 0,
			user2 int(11) NOT NULL default 0,
			starttime varchar(255) NOT NULL default '',
			endtime varchar(255) NOT NULL default '',
			winner int(11) NOT NULL default 0,
			PRIMARY KEY (gameid)) TYPE=MyISAM";
	db_query($sql);
}

module_addhook("biostat");
module_addhook("gardens");
module_addhook("footer-hof");
module_addhook("insertcomment");
?>