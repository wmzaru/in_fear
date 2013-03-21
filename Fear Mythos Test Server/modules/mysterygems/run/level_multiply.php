<?php
$ul = ($session['user']['level'] - 1) / 2 + .5;
if ($ul < 1) $ul = 1;
if ($ul == 1 && $session['user']['level'] == 2) $ul = 1.5;
$tc = round(get_module_setting('turquoisecost') * $ul);
$mac = round(get_module_setting('malachitecost') * $ul);
$moc = round(get_module_setting('moonstonecost') * $ul);
$hc = round(get_module_setting('hematitecost') * $ul);
$sc = round(get_module_setting('starsapphirecost') * $ul);
$dc = round(get_module_setting('diamondcost') * $ul);
?>