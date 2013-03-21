<?php
function masons_goodevil(){
	output("`7'This is a little tricky, but let me try to describe it to you.'`n`n");
	//evil people
	$alignment=get_module_pref("alignment","alignment");
	if ($alignment<=get_module_setting("evilalign","alignment")) {
		output("`&Since you are `\$Evil`&, T`)he `&O`)rder`& can help you out:`n`n");
		output("`\$1. To become `bEven More Evil`b, we have little kittens that you can poke with sticks.`n`n");
		output("`@2. To become `bBetter`b, we have little kittens that you can cuddle with and take care of.");
		output("Somehow, they've been poked with sticks.`n`n");
		output("`^3. If you're happy just the way you are, you `bDon't Have to Do Anything`b at all.`n`n");
		addnav("`\$More Evil","runmodule.php?module=masons&op=alignevil");
		addnav("`@Less Evil","runmodule.php?module=masons&op=aligngood");
		addnav("`^Do Nothing","runmodule.php?module=masons&op=alignnothing");
	}
	//neutral people
	if (($alignment>get_module_setting("evilalign","alignment"))&&($alignment<get_module_setting("goodalign","alignment"))) {
		output("`&Since you are `^Neutral`&, T`)he `&O`)rder`& can help you out:`n`n");
		output("`\$1. To become `bEvil`b, we have orphans that you can steal candy from.`n`n");
		output("`@2. To become `bBetter`b, there is extra candy that someone found; you can deliver it to some orphans.`n`n");
		output("`^3. If you're happy just the way you are, you `bDon't Have to Do Anything`b at all.`n`n");
		addnav("`\$Evil","runmodule.php?module=masons&op=alignevil");
		addnav("`@Good","runmodule.php?module=masons&op=aligngood");
		addnav("`^Do Nothing","runmodule.php?module=masons&op=alignnothing");
	}
	//good people
	if ($alignment>=get_module_setting("goodalign","alignment")) {
		output("`&Since you are `@Good`&, T`)he `&O`)rder`& can help you out:`n`n");
		output("`\$1. To become `bEvil`b, we arrange for you to steal pencils from the blind homeless man in front of the `&S`)ociety`\$.`n`n");
		output("`@2.To become `bEven Better`b, we send you to volunteer in the `&P`)encil `&F`)actory`@.`n`n");
		output("`^3. If you're happy just the way you are, you `bDon't Have to Do Anything`b at all.`n`n");
		addnav("`\$Less Good","runmodule.php?module=masons&op=alignevil");
		addnav("`@Even Better","runmodule.php?module=masons&op=aligngood");
		addnav("`^Do Nothing","runmodule.php?module=masons&op=alignnothing");
	}
}
?>