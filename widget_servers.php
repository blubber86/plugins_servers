<?php
/*¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\
| _    _  ___  ___  ___  ___  ___  __    __      ___   __  __       |
|( \/\/ )(  _)(  ,)/ __)(  ,\(  _)(  )  (  )    (  ,) (  \/  )      |
| \    /  ) _) ) ,\\__ \ ) _/ ) _) )(__  )(__    )  \  )    (       |
|  \/\/  (___)(___/(___/(_)  (___)(____)(____)  (_)\_)(_/\/\_)      |
|                       ___          ___                            |
|                      |__ \        / _ \                           |
|                         ) |      | | | |                          |
|                        / /       | | | |                          |
|                       / /_   _   | |_| |                          |
|                      |____| (_)   \___/                           |
\___________________________________________________________________/
/                                                                   \
|        Copyright 2005-2018 by webspell.org / webspell.info        |
|        Copyright 2018-2019 by webspell-rm.de                      |
|                                                                   |
|        - Script runs under the GNU GENERAL PUBLIC LICENCE         |
|        - It's NOT allowed to remove this copyright-tag            |
|        - http://www.fsf.org/licensing/licenses/gpl.html           |
|                                                                   |
|               Code based on WebSPELL Clanpackage                  |
|                 (Michael Gruber - webspell.at)                    |
\___________________________________________________________________/
/                                                                   \
|                     WEBSPELL RM Version 2.0                       |
|           For Support, Mods and the Full Script visit             |
|                       webspell-rm.de                              |
\__________________________________________________________________*/
# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("server", $plugin_path);


$data_array=array();
$data_array['$title'] = $plugin_language[ 'server' ];

$headtemp = $GLOBALS["_template"]->loadTemplate("sc_servers","head", $data_array, $plugin_path);
echo $headtemp;

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_servers ORDER BY sort");

if (mysqli_num_rows($ergebnis)) {
    $template = $GLOBALS["_template"]->loadTemplate("sc_servers","head_content", $data_array, $plugin_path);
    echo $template;
    echo '<ul class="list-group">';
    $n = 1;
    while ($ds = mysqli_fetch_array($ergebnis)) {

    $data_array= array();
    $servername = $ds[ 'name' ];
    $serverip = $ds[ 'ip' ];
    $filepath = "../images/games/";
    $servergame = ''.$filepath.'' . $ds[ 'game' ] . '.gif';

    $data_array = array();
    $data_array['$servergame'] = $servergame;
    $data_array['$serverip'] = $serverip;
    $data_array['$servername'] = $servername;
    
    $template = $GLOBALS["_template"]->loadTemplate("sc_servers","content", $data_array, $plugin_path);
    echo $template;
    $n++;
}
    echo '</ul>';
    $template = $GLOBALS["_template"]->loadTemplate("sc_servers","foot_content", $data_array, $plugin_path);
    echo $template;

  
    
} else {
    
    echo $plugin_language['no_server'];
}