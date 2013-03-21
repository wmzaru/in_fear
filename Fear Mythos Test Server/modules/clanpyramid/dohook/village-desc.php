<?php
if (e_rand(1, 100) <= get_module_setting("villagepercent")) {
	output_notl("`n");
	output("`^The Entrance to a large Pyramid has been discovered.");
	$args['dopyramid'] = 1;
}
$lm=get_module_setting("lastwinner");
output_notl("`n");
if ($lm==""){
	output("Who will be the first monthly Winner?");
}else{
	output("`^Last months Clan Wars winner was %s",$lm);
}
if ($owned1==0){
	output_notl("`n`n");
	output("`$ Tzeltalchs Pyramid has just been discovered.  Will your clan be first?");
}elseif ($owned1>0){
	$sql = "SELECT * FROM " .db_prefix("clans"). " WHERE clanid = '$owned1'";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	$clanname1 = $row['clanname'];
	output_notl("`n`n");
	output("`$ Tzeltalchs Pyramid is currently under the control of %s",$clanname1);
	output_notl("");
}
if ($owned2==0){
	output_notl("`n");
	output("`%Chexralmins Pyramid has just been discovered.  Will your clan be first?");
	output_notl("`n");
}elseif ($owned2>0){
	$sql = "SELECT * FROM " .db_prefix("clans"). " WHERE clanid = '$owned2'";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	$clanname2 = $row['clanname'];
	output_notl("`n");
	output("`%Chexralmins Pyramid is currently under the control of %s",$clanname2);
	output_notl("`n");
}
if ($owned3==0){
	output("`QPrazlynx Pyramid has just been discovered.  Will your clan be first?");
	output_notl("`n`n");
}elseif($owned3>0){
	$sql = "SELECT * FROM " .db_prefix("clans"). " WHERE clanid = '$owned3'";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	$clanname3 = $row['clanname'];
	output("`QPrazlynx Pyramid is currently under the control of %s",$clanname3);
	output_notl("`n`n");
}
?>
			