<?php
	$allprefs=unserialize(get_module_pref('allprefs','masons',$args[acctid]));
	if ($allprefs['masonmember']==1){
		output("`n`&%s `# has a beautiful tattoo on %s hand that says `&S`)o`&M`#.`n", $args['name'],translate_inline(($args['sex']?"her":"his")));
	}
?>