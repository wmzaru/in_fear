<?php
	if ($session['user']['superuser'] & SU_EDIT_USERS){
		$id=httpget('userid');
		addnav("Village Modules");
		addnav("Secret Order of Masons","runmodule.php?module=masons&op=superuser&subop=edit&userid=$id");
	}
?>