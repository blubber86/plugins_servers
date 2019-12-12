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

$ergebnis = safe_query("SELECT * FROM ".PREFIX."navigation_dashboard_links WHERE modulname='servers'");
    while ($db=mysqli_fetch_array($ergebnis)) {
      $accesslevel = 'is'.$db['accesslevel'].'admin';

if (!$accesslevel($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($plugin_language[ 'access_denied' ]);
}
}

if (isset($_POST[ 'save' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "INSERT INTO
                `" . PREFIX . "plugins_servers` (
                    `name`,
                    `ip`,
                    `game`,
                    `info`
                )
                VALUES(
                    '" . $_POST[ 'name' ] . "',
                    '" . $_POST[ 'serverip' ] . "',
                    '" . $_POST[ 'game' ] . "',
                    '" . $_POST[ 'message' ] . "'
                    )"
        );
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'saveedit' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        safe_query(
            "UPDATE " . PREFIX . "plugins_servers SET name='" . $_POST[ 'name' ] . "', ip='" . $_POST[ 'serverip' ] .
            "', game='" . $_POST[ 'game' ] . "', info='" . $_POST[ 'message' ] . "' WHERE serverID='" .
            $_POST[ 'serverID' ] . "'"
        );
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'sort' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        if (is_array($_POST[ 'sortlist' ])) {
            foreach ($_POST[ 'sortlist' ] as $sortstring) {
                $sorter = explode("-", $sortstring);
                safe_query("UPDATE " . PREFIX . "plugins_servers SET sort='$sorter[1]' WHERE serverID='$sorter[0]' ");
            }
        }
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
} elseif (isset($_GET[ 'delete' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        safe_query("DELETE FROM " . PREFIX . "plugins_servers WHERE serverID='" . $_GET[ 'serverID' ] . "'");
    } else {
        echo $plugin_language[ 'transaction_invalid' ];
    }
}

$games = '';
$gamesa = safe_query("SELECT tag, name FROM " . PREFIX . "settings_games ORDER BY name");
while ($dv = mysqli_fetch_array($gamesa)) {
    $games .= '<option value="' . $dv[ 'tag' ] . '">' . getinput($dv[ 'name' ]) . '</option>';
}

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if ($action == "add") {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

echo'<div class="card">
            <div class="card-header">
                            <i class="fas fa-gamepad"></i> ' . $plugin_language[ 'servers' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_servers">' . $plugin_language[ 'servers' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'add_server' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';

echo '<script>
        function chkFormular() {
            if(!validbbcode(document.getElementById(\'message\').value, \'admin\')){
                return false;
            }
        }
    </script>';
  
	echo '<form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_servers" onsubmit="return chkFormular();">
	 <div class="row">

<div class="col-md-6">

<div class="form-group">
    <label class="col-sm-3 control-label">'.$plugin_language['server_name'].':</label>
    <div class="col-sm-9"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="name" size="60" /></em></span>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">'.$plugin_language['ip_port'].':</label>
    <div class="col-sm-9"><span class="text-muted small"><em>
    <input class="form-control" type="text" name="serverip" size="60" /></em></span>
    </div>
  </div>

  </div>
  

<div class="col-md-6">
  <div class="form-group">
    <label class="col-sm-2 control-label">'.$plugin_language['game'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
		<select class="form-control" name="game">'.$games.'</select></em></span>
    </div>
  </div>
</div>
</div>

  <div class="form-group">
   <div class="col-md-12">
    <textarea class="ckeditor" id="ckeditor" name="message" rows="10" cols="" ></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-12">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" />
		<button class="btn btn-success" type="submit" name="save"  />'.$plugin_language['add_server'].'</button>
    </div>
  </div>

  </div>
  </div>
  </form></div>
  </div>';
} elseif($action=="edit") {

echo'<div class="card">
            <div class="card-header">
                            <i class="fas fa-gamepad"></i> ' . $plugin_language[ 'servers' ] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_servers">' . $plugin_language[ 'servers' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $plugin_language[ 'edit_server' ] . '</li>
                </ol>
            </nav> 
                        <div class="card-body">';

      $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $serverID = $_GET[ 'serverID' ];
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_servers WHERE serverID='" . $serverID . "'");
    $ds = mysqli_fetch_array($ergebnis);

    $games = str_replace(' selected="selected"', '', $games);
    $games = str_replace('value="' . $ds[ 'game' ] . '"', 'value="' . $ds[ 'game' ] . '" selected="selected"', $games);

    echo '<script>
        function chkFormular() {
            if(!validbbcode(document.getElementById(\'message\').value, \'admin\')){
                return false;
            }
        }
    </script>';

    
  echo '<form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=admin_servers" onsubmit="return chkFormular();">
<div class="row">

<div class="col-md-6">

   <div class="form-group">
    <label class="col-sm-3 control-label">'.$plugin_language['server_name'].':</label>
    <div class="col-sm-9"><span class="text-muted small"><em>
      <input class="form-control" type="text" name="name" value="'.getinput($ds['name']).'" /></em></span>
    </div>
  </div>
  
  <div class="form-group">
    <label class="col-sm-3 control-label">'.$plugin_language['ip_port'].':</label>
    <div class="col-sm-9"><span class="text-muted small"><em>
		<input class="form-control" type="text" name="serverip" value="'.getinput($ds['ip']).'" /></em></span>
    </div>
  </div>

  </div>
  

<div class="col-md-6">

<div class="form-group">
    <label class="col-sm-2 control-label">'.$plugin_language['game'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
    <select class="form-control" name="game">'.$games.'</select></em></span>
    </div>
  </div>
</div>
</div>

  <div class="row">
  <div class="col-md-12">
   <div class="form-group">
   <div class="col-md-12">
      <textarea class="ckeditor" id="ckeditor" name="message" rows="10" cols="" >'.getinput($ds['info']).'</textarea>
    </div>
  </div>

  <div class="form-group">
    <div class="col-md-12">
		<input type="hidden" name="serverID" value="'.$serverID.'" /><input type="hidden" name="captcha_hash" value="'.$hash.'" />
		<button class="btn btn-warning" type="submit" name="saveedit"  />'.$plugin_language['edit_server'].'</button>
    </div>
  </div>

  </div>
  </div>
  </form></div>
  </div>';
}

else {

  echo'<div class="card">
            <div class="card-header">
                            <i class="fas fa-gamepad"></i> ' . $plugin_language[ 'servers' ] . '</div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=admin_servers">' . $plugin_language[ 'servers' ] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
                </ol>
            </nav>  
                        <div class="card-body">

<div class="form-group row">
    <label class="col-md-1 control-label">' . $plugin_language['options'] . ':</label>
    <div class="col-md-8">
      <a href="admincenter.php?site=admin_servers&amp;action=add" class="btn btn-primary" type="button">' . $plugin_language[ 'new_server' ] . '</a>
    </div>
  </div>';

	$ergebnis = safe_query("SELECT * FROM " . PREFIX . "plugins_servers ORDER BY sort");
    $anz = mysqli_num_rows($ergebnis);
    if ($anz) {
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
  
  echo'<form method="post" name="ws_servers" action="admincenter.php?site=admin_servers">
    <table class="table table-striped">
      <thead>
        <th><b>'.$plugin_language['servers'].'</b></th>
        <th><b>'.$plugin_language['actions'].'</b></th>
        <th><b>'.$plugin_language['sort'].'</b></th>
      </thead>';

		$i = 1;
        while ($ds = mysqli_fetch_array($ergebnis)) {
            if ($i % 2) {
                $td = 'td1';
            } else {
                $td = 'td2';
            }

            $list = '<select name="sortlist[]">';
            $counter = mysqli_num_rows($ergebnis);
            for ($n = 1; $n <= $counter; $n++) {
                $list .= '<option value="' . $ds[ 'serverID' ] . '-' . $n . '">' . $n . '</option>';
            }
            $list .= '</select>';
            $list = str_replace(
                'value="' . $ds[ 'serverID' ] . '-' . $ds[ 'sort' ] . '"',
                'value="' . $ds[ 'serverID' ] . '-' . $ds[ 'sort' ] . '" selected="selected"',
                $list
            );

            $name = $ds[ 'name' ];
            $info = $ds[ 'info' ];

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($name);
            $name = $translate->getTextByLanguage($name);
            $translate->detectLanguages($info);
            $info = $translate->getTextByLanguage($info);
    
            
       echo '<tr>
        <td> <a href="hlsw://'.$ds['ip'].'"><b>'.$ds['ip'].'</b></a><br /><b>'.$name.'</b><br />

        <span class="text-muted small"><em>'.$info.'</em></span>
        </td>
        <td><a href="admincenter.php?site=admin_servers&amp;action=edit&amp;serverID='.$ds['serverID'].'" class="btn btn-warning" type="button">' . $plugin_language[ 'edit' ] . '</a>

        <input class="btn btn-danger" type="button" onclick="MM_confirm(\'' . $plugin_language['really_delete'] . '\', \'admincenter.php?site=admin_servers&amp;delete=true&amp;serverID='.$ds['serverID'].'&amp;captcha_hash='.$hash.'\')" value="' . $plugin_language['delete'] . '" />

    </td>
        <td>'.$list.'</td>
      </tr>';
        
        $i++;
		}
		echo'<tr>
        <td colspan="3" class="td_head" align="right"><input type="hidden" name="captcha_hash" value="'.$hash.'" /><button class="btn btn-primary" type="submit" name="sort" />'.$plugin_language['to_sort'].'</button></td>
      </tr>
    </table>
    </form>';
	}
	else echo $plugin_language['admin_no_server'];
}
echo '</div></div>';
?>