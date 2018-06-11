<?php

require __DIR__."/../vendor/autoload.php";

setlocale(LC_ALL, 'fr_FR');

$app = new App\App();

$app->run();