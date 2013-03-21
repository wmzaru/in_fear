<?php
	if ($session['user']['superuser'] & SU_AUDIT_MODERATION){
			$sql="SELECT count(u.objid) AS counter FROM ".db_prefix("module_objprefs")." AS u INNER JOIN ".db_prefix('module_objprefs')." AS t ON u.objid=t.objid WHERE u.modulename='clan_avatars' AND u.objtype='clans' AND u.setting='validate' AND u.value!='1' AND t.setting='filename' AND t.value !='';";
			$result=db_query($sql);
			$num=db_fetch_assoc($result);
			output_notl("`n`n");
			if ($num['counter']>0) output("`\$`b`cCurrently there are `v%s`\$ clanavatars waiting for validation.`c`b`0`n`n",$num['counter']);
		}

?>