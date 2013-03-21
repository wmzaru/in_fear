<?php
$clan=$u['clanid'];
if ($clan==$owned1 && $clan==$owned2 && $clan==$owned3 && $clan <> 0 && $u['alive']==true && ($args['type']=="forest")){
	$level = $u['level'];
	$bonus = $level*10;
	output_notl("`n");
	output("`QYou receive a bonus of %s  experience, as your clan owns all 3 pyramids",$bonus);
	output_notl("`n");
	$u['experience']+=$bonus;
}
?>