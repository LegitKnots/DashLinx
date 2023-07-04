<?php

if (file_exists('setup.php')) {
    include 'setup.php';
} elseif (file_exists('../setup.php')) {
    include '../setup.php';
} elseif (file_exists('../../setup.php')) {
    include '../../setup.php';
} else {
    // Handle the case when the setup file is not found
    echo "<br>Setup file not found.";
}


if (!isset($tab_title)) {
	$tab_title = $page_title;
}

define('PAGE_TITLE', $page_title);
define('TAB_TITLE', $tab_title);

define('DB_HOST', $db_host);
define('DB_USER', $db_user);
define('DB_PASS', $db_pass);
define('DB_NAME', $db_name);

define('ROOT_DIR', $root_dir);
