<?php

// load Settings
require_once ('../config/Settings.php');

$class = array(
    'Database',
    'Response',
    'Menu',
    'Apps',
    'TelegramMessage',
    'Command',
    'Routes',
);

foreach ($class as $sc) {
      // load class
      $app = require_once ('../system/'.$sc.'.php');
}

// load semua class dari src
foreach (scandir('../src', 1) as $sc) {
    if (substr($sc, -3) == 'php') {
        require_once ('../src/'.$sc);
    }
}

$app->run();