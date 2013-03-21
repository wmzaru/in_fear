<?php
tlschema($args['schemas']['marketnav']);
addnav($args['marketnav']);
tlschema();
addnav("Gem's Eternal Mysteries", "runmodule.php?module=mysterygems&op=enter");
?>