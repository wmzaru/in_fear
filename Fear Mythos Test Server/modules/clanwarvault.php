<?php
function clanwarvault_getmoduleinfo(){
        $info = array(
                "name"=>"Clan War Vault",
                "author"=>"`)ShadowRaven",
                "version"=>"1.0",
                "category"=>"Clan",
                "download"=>"",
                "description"=>"Allows clan members to donate gold for the leader or officer to buy clan attack and defence points for the clan.",
                "requires"=>array(
                        "clanpyramid"=>"1.1+|Kalazaar"
),
                "settings"=>array(
                        "Clan vault Settings,title",
                        "catt"=>"how much gold needed per attack point?,int|500000000",
                        "cdef"=>"How much gold needed per defense point?,ont|500000000",
),
                "prefs"=>array(
                        "Clan vault Prefs,title",
                        "don"=>"how much has this user donated?,int|0",
),
                "prefs-clans"=>array(
                        "allowed"=>"are officers allowed to make purchases?,bool|0",
                        "att"=>"How much attack does this clan have?,viewonly|100",
                        "def"=>"How much Defense does this clan have?,viewonly|100",
                        "gatt"=>"How much gold spent on attack?,viewonly|0",
                        "gdef"=>"How much gold spent on defense?,viewonly|0",
                        "total"=>"how much gold is in the vault?,viewonly|0",
),
);
        return $info;
}
function clanwarvault_install(){
        module_addhook("footer-clan");
        module_addhook("clan-enter");
        module_addhook("clanoptions-fountain");
        return true;
}
function clanwarvault_uninstall(){
        return true;
}
function clanwarvault_dohook($hookname,$args){
           global $session;
           $u=&$session['user'];
        switch($hookname){
                case "footer-clan":
                        if ($u['clanrank'] >= CLAN_MEMBER){
                                addnav("~");
                                addnav("`^Clan War Vault","runmodule.php?module=clanwarvault");
                                addnav("`#Clan Top Donators","runmodule.php?module=clanwarvault&op=hof");
                        }
                        break;
                case "clan-enter":
                       if ($u['clanid']==0 || $u['clanrank']==0){
                        $id = $u['acctid'];
                        set_module_pref("don",0,"clanwarvault",$id);
}
                        break;
                case "clanoptions-fountain":
                       $clanid = $u['clanid'];
                        if ($u['clanrank'] >= CLAN_LEADER){
                                addnav("War Room","runmodule.php?module=clanwarvault&op=warroom");
                        }elseif ($u['clanrank'] >= CLAN_OFFICER && get_module_objpref("clans", $clanid, "allowed")==1){
                                addnav("War Room","runmodule.php?module=clanwarvault&op=warroom");
                        }
                }
                return $args;
}
function clanwarvault_run(){
      require_once("./modules/clanwarvault/clanwarvault_run.php");
}
?>
