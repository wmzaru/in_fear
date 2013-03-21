<?php
$nav = "modules/mysterygems/func/villagenav.php";
$move = get_module_setting('move');
$rum = get_module_setting('runoncemove');
$sul = $session['user']['location'];
if (!$move)
	if ($sul == get_module_setting('mgloc')) require_once($nav);
if ($move)
	if ($rum && get_module_setting('place') == $sul || !$rum && get_module_pref('userplace') == $sul) require_once($nav);
if (get_module_pref('lostmount') && get_module_setting('blockstables')){
	blocknav('stables.php');
	blocknav('runmodule.php?module=garlandstable');
}
?>