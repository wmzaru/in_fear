<?php
	for ($i = 13; $i < 16; $i++){
		if ($session['user']['acctid']==get_module_setting("prankon".$i)){
			$vitresult=get_module_setting("result".$i);
			$info=get_module_setting($vitresult."prank".$i);
			if ($i==13) $type=translate_inline("`QBowel `@Movements");
			elseif ($i==14) $type=translate_inline("`^Back `!Hair");
			else $type=translate_inline("`%Potency");
			setcharstat("Vital Info", "$type", "`\$`b$info`b");
		}
	}
?>