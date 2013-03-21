<?php
	if ($session['user']['superuser'] & SU_EDIT_USERS){
		$id=httpget('userid');
		addnav("Village Modules");
		addnav("Jobs Even More","runmodule.php?module=jobs&op=superuser&subop=edit&userid=$id");
	}
?>