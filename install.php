<script>
function goBack() {
    window.history.back();
}
</script>
<?php

#@info:	settings
$modulname	 	= 	"servers"; 								// name to uninstall
$plugin_table 	= 	"servers"; 								// name of the mysql table
$str			=	"Servers"; 								// name of the plugin
$navi_name		=	"{[de]}Server{[en]}Servers{[it]}Server";			// name of the Navi
$description	=	"Mit diesem Plugin könnt ihr eure Server anzeigen lassen."; 	// description of the plugin
$admin_file 	=	"admin_servers";						// administration file
$activate 		=	"1";									// plugin activate 1 yes | 0 no
$author			=	"T-Seven";								// author
$website		= 	"https://webspell-rm.de";				// authors website
$index_link		=	"servers,sc_servers,admin_servers";		// index file (without extension, also no .php)
$sc_link 		=	"widget_servers";  						// sc_ file (visible as module/box)
$hiddenfiles 	=	"";										// hiddenfiles (background working, no display anywhere)
$version		=	"1.2";									// current version, visit authors website for updates, fixes, ..
$path			=	"includes/plugins/servers/";			// plugin files location
$navi_link		=	"servers";					 			// navi link file (index.php?site=...)
$dashnavi_link	=	"admin_servers"; 						// dashboard_navigation link file

#@info: database
$install = "CREATE TABLE `" . PREFIX . "plugins_servers` (
  `serverID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `game` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`serverID`)
) AUTO_INCREMENT=1
  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci";
        

 

# 	= = =		/!\ DO NOT EDIT THE LINES BELOW !!!		= = =
# 	= = =		/!\ DO NOT EDIT THE LINES BELOW !!!		= = =
# 	= = =		/!\ DO NOT EDIT THE LINES BELOW !!!		= = =

# 	= = =		/!\ Ab hier nichts mehr ändern !!!		= = =

  
$add_plugin = "INSERT INTO `".PREFIX."plugins` (`name`, `modulname`, `description`, `admin_file`, `activate`, `author`, `website`, `index_link`, `sc_link`, `hiddenfiles`, `version`, `path`) 
				VALUES ('$str', '$modulname', '$description', '$admin_file', '$activate', '$author', '$website', '$index_link', '$sc_link', '$hiddenfiles', '$version', '$path');";

$add_navigation = "INSERT INTO `".PREFIX."navigation_website_sub` (`mnavID`, `name`, `modulname`, `url`, `sort`, `indropdown`) 
					VALUES ('3','$navi_name', '$modulname', 'index.php?site=$navi_link', '1', '1');";

$add_dashboard_navigation = "INSERT INTO `".PREFIX."navigation_dashboard_links` (`catID`, `name`, `modulname`, `url`, `accesslevel`, `sort`) 
					VALUES ('7','$navi_name', '$modulname', 'admincenter.php?site=$dashnavi_link', 'page', '1');";

$add_module = "INSERT INTO `".PREFIX."settings_moduls` (`module`, `modulname`, `activated`, `le_activated`, `re_activated`, `deactivated`, `head_activated`, `content_head_activated`, `content_foot_activated`, `sort`) VALUES ('$plugin_table', '$modulname', '1', '0', '0', '0', '0', '0', '0', '1');";	

if(!ispageadmin($userID)) { echo ("Access denied!"); return false; }		
			
		echo "<div class='card'>
			<div class='card-header'>
				<h3>$str Database Installation</h3>
			</div>
			<div class='card-body'>";
	
		# if table exists
		try {
			if(mysqli_query($_database, $install)) { 
				echo "<div class='alert alert-success'>$str installation successful <br />";
				echo "$str installation erfolgreich <br /></div>";
			} else {
					echo "<div class='alert alert-warning'>$str entry already exists <br />";
					echo "$str Eintrag schon vorhanden <br /></div>";
					echo "<hr>";
			}	
		} CATCH (EXCEPTION $x) {
				echo "<div class='alert alert-danger'>$str installation failed <br />";
				echo "Send the following line to the support team:<br /><br />";
				echo "<pre>".$x->message()."</pre>		
					  </div>";
		}

		
		# Add to Plugin-Manager
		if(mysqli_num_rows(safe_query("SELECT name FROM `".PREFIX."plugins` WHERE name ='".$str."'"))>0) {
					echo "<div class='alert alert-warning'>$str Plugin Manager entry already exists <br />";
					echo "$str Plugin Manager Eintrag schon vorhanden <br /></div>";
					echo "<hr>";
		} else {
			try {
				if(safe_query($add_plugin)) { 
					echo "<div class='alert alert-success'>$str added to the plugin manager <br />";
					echo "$str wurde dem Plugin Manager hinzugef&uuml;gt <br />";
					echo "<a href = '/admin/admincenter.php?site=plugin-manager' target='_blank'><b>LINK => Plugin Manager</b></a></div>";
				} else {
					echo "<div class='alert alert-danger'>Add to plugin manager failed <br />";
					echo "Zum Plugin Manager hinzuf&uuml;gen fehlgeschlagen <br /></div>";
				}	
			} CATCH (EXCEPTION $x) {
					echo "<div class='alert alert-danger'>$str installation failed <br />";
					echo "Send the following line to the support team:<br /><br />";
					echo "<pre>".$x->message()."</pre>		
						  </div>";
			}
		}


		# Add to navigation
		if(mysqli_num_rows(safe_query("SELECT * FROM `".PREFIX."navigation_website_sub` WHERE `name`='$str' AND `url`='index.php?site=$navi_link'"))>0) {
					echo "<div class='alert alert-warning'>$str Navigation entry already exists <br />";
					echo "$str Navigationseintrag schon vorhanden <br /></div>";
					
		} else {
			try {
				if(safe_query($add_navigation)) { 
					echo "<div class='alert alert-success'>$str added to the Website Navigation <br />";
					echo "$str wurde der Website Navigation hinzugef&uuml;gt <br />";
					echo "<a href = '/admin/admincenter.php?site=webside_navigation' target='_blank'><b>LINK => Website Navigation</b></a></div>";
				} else {
					echo "<div class='alert alert-danger'>Add to Website Navigation failed <br />";
					echo "Zur Website Navigation hinzuf&uuml;gen fehlgeschlagen<br /></div>";
				}	
			} CATCH (EXCEPTION $x) {
					echo "<div class='alert alert-danger'>$str installation failed <br />";
					echo "Send the following line to the support team:<br /><br />";
					echo "<pre>".$x->message()."</pre>		
						  </div>";
			}
		}

		# Add to dashboard navigation
		if(mysqli_num_rows(safe_query("SELECT * FROM `".PREFIX."navigation_dashboard_links` WHERE `name`='$str' AND `url`='$dashnavi_link'"))>0) {
					echo "<div class='alert alert-warning'>$str Dashboard Navigation entry already exists <br />";
					echo "$str Dashboard Navigationseintrag schon vorhanden <br /></div>";
					
		} else {
			try {
				if(safe_query($add_dashboard_navigation)) { 
					echo "<div class='alert alert-success'>$str added to the Dashboard Navigation <br />";
					echo "$str wurde der Dashboard Navigation hinzugef&uuml;gt <br />";
					echo "<a href = '/admin/admincenter.php?site=dashnavi' target='_blank'><b>LINK => Dashboard Navigation</b></a></div>";
				} else {
					echo "<div class='alert alert-danger'>Add to Dashboard Navigation failed <br />";
					echo "Zur Dashboard Navigation hinzuf&uuml;gen fehlgeschlagen<br /></div>";
				}	
			} CATCH (EXCEPTION $x) {
					echo "<div class='alert alert-danger'>$str installation failed <br />";
					echo "Send the following line to the support team:<br /><br />";
					echo "<pre>".$x->message()."</pre>		
						  </div>";
			}
		}
		
		# Add to module
		if(mysqli_num_rows(safe_query("SELECT * FROM `".PREFIX."settings_moduls` WHERE `module`='".$plugin_table."'"))>0) {
					echo "<div class='alert alert-warning'>$str Entry already exists <br />";
					echo "$str Eintrag schon vorhanden <br /></div>";
					
		} else {
			try {
				if(safe_query($add_module)) { 
					echo "<div class='alert alert-success'>$str added to the Module <br />";
					echo "$str wurde in Module hinzugef&uuml;gt <br /></div>";
				} else {
					echo "<div class='alert alert-danger'>Add to Module failed <br />";
					echo "Zur Module hinzuf&uuml;gen fehlgeschlagen<br /></div>";
				}	
			} CATCH (EXCEPTION $x) {
					echo "<div class='alert alert-danger'>$str installation failed <br />";
					echo "Send the following line to the support team:<br /><br />";
					echo "<pre>".$x->message()."</pre>		
						  </div>";
			}
		}
		echo "</div></div>";

		
	
	echo "<button class='btn btn-default btn-sm' onclick='goBack()'>Go Back</button>
	
		</div></div>";
	
 ?>