<?php

if (file_exists('src/php/define.php')) {
    include 'src/php/define.php';
} elseif (file_exists('../src/php/define.php')) {
    include '../src/php/define.php';
} elseif (file_exists('../src/php/define.php')) {
    include '../src/php/define.php';
}else {
    // Handle the case when the setup file is not found
    echo "<br>Define file not found.";
}


