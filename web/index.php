<?php

require __DIR__."/../vendor/autoload.php";

setlocale(LC_ALL, 'fr_FR');
define("COM_PER_PAGE", 5);

$app = new App\App();

$app->run();