<?php
$clan=$u['clanid'];
if ($clan==$owned1 && $clan==$owned2 && $clan==$owned3 && $clan<>0){
	output_notl("`n`b");
	output("`^Your clan owns all three pyramids, you receive a experience bonus for forest fights");
	output_notl("`n`b");
}else{
	output_notl("`n`b");
	output("`^As your clan doesn't control all the pyramids, you don't receive a experience bonus");
	output_notl("`n`b");
}
?>