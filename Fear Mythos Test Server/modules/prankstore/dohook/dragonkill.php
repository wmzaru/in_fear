<?php
	for ($i = 10; $i < 13; $i++){
		if (get_module_setting("prankon".$i)==$session['user']['acctid']){
			set_module_setting("prankon".$i,"");
			set_module_setting("weapon".$i,"");
		}
	}
	for ($i = 16; $i < 19; $i++){
		if (get_module_setting("prankon".$i)==$session['user']['acctid']){
			set_module_setting("prankon".$i,"");
			set_module_setting("title".$i,"");
		}
	}
?>