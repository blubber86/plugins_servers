<?php
$name = "servers";
// Name Tabelle | Where Klause | ID name
DeleteData("plugins","modulname",$name);
DeleteData("settings_moduls","modulname",$name);
DeleteData("navigation_dashboard_links","modulname",$name);
DeleteData("navigation_website_sub","modulname",$name);
DeleteData("plugins_widgets","modulname",$name);
safe_query("DROP TABLE IF EXISTS " . PREFIX . "plugins_".$name."");
?>