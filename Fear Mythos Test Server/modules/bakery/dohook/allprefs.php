<?php
	if ($session['user']['superuser'] & SU_EDIT_USERS){
		$id=httpget('userid');
		addnav("Village Modules");
		addnav("Bakery","runmodule.php?module=bakery&op=superuser&subop=edit&userid=$id");
	}
?>