<?php
	if ($session['user']['superuser'] & SU_EDIT_USERS){
		$id=httpget('userid');
		addnav("Signet Series");
		addnav("`3S`Qi`!g`\$n`3e`Qt `^Sale","runmodule.php?module=signetsale&op=superuser&subop=edit&userid=$id");
	}
?>