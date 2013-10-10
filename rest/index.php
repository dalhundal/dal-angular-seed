<?php

require('lib/SlimRest/SlimRest.php');

R::setup('pgsql:host=localhost;dbname=dname','dbuser');
R::freeze(true);

$app = new \SlimRest();

\Slim\Route::setDefaultConditions(array(
   'id' => '\d+'
));

/* ===== */

include_once('endpoints.php');

$app->run();