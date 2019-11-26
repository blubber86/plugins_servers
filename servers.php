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

$_language->readModule('server');

$plugin_data= array();
$plugin_data['$title']=$plugin_language['server'];

$template = $GLOBALS["_template"]->loadTemplate("servers","head", $plugin_data, $plugin_path);
echo $template;

$template = $GLOBALS["_template"]->loadTemplate("servers","content_head", $plugin_data, $plugin_path);
echo $template;

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_servers ORDER BY sort");

if (mysqli_num_rows($ergebnis)) {
    while ($ds = mysqli_fetch_array($ergebnis)) {
        if ($ds[ 'game' ] == "CS") {
            $game = "HL";
        } else {
            $game = $ds[ 'game' ];
        }

        $showgame = getgamename($ds[ 'game' ]);
        $filepath = "../images/games/";
        $gameicon = ''.$filepath.'' . $ds[ 'game' ] . '';

        $serverdata = explode(":", $ds[ 'ip' ]);
        $ip = $serverdata[ 0 ];
        if (isset($serverdata[ 1 ])) {
            $port = $serverdata[ 1 ];
        } else {
            $port = '';
        }

        if (!checkenv('disable_functions', 'fsockopen')) {
            if (!fsockopen("udp://" . $ip, $port, $strErrNo, $strErrStr, 30)) {
                $status = "<em style='color: #da0c0c'>" . $plugin_language[ 'timeout' ] . "</em>";
            } else {
                $status = "<strong style='color: #5cb85c'>" . $plugin_language[ 'online' ] . "</strong>";
            }
        } else {
            $status = "<em style='color: #da0c0c'>" . $plugin_language[ 'not_supported' ] . "</em>";
        }

        $servername = $ds[ 'name' ];
        $info = $ds[ 'info' ];

        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($servername);
        $servername = $translate->getTextByLanguage($servername);
        $translate->detectLanguages($info);
        $info = $translate->getTextByLanguage($info);
    
        $data_array = array();
        $data_array['$game'] = $ds[ 'game' ];
        $data_array['$gameicon'] = $gameicon;
        $data_array['$ip'] = $ds[ 'ip' ];
        $data_array['$servername'] = $servername;
        $data_array['$status'] = $status;
        $data_array['$showgame'] = $showgame;
        $data_array['$info'] = $info;

        $data_array['$server_ip']=$plugin_language['ip'];
        $data_array['$server_status']=$plugin_language['status'];
        $data_array['$server_game']=$plugin_language['game'];
        $data_array['$server_information']=$plugin_language['information'];
        $data_array['$server_link']=$plugin_language['link'];

        $template = $GLOBALS["_template"]->loadTemplate("servers","content", $data_array, $plugin_path);
        echo $template;
        
    }
} else {
    
    echo $plugin_language['no_server'];
}

     $template = $GLOBALS["_template"]->loadTemplate("servers","foot", array(), $plugin_path);
     echo $template;  

?>