<?php


$app->get('/people', function() use ($app) {
   return array(
      array('name'=>'John'),
      array('name'=>'Henry'),
      array('name'=>'David'),
      array('name'=>'Steven'),
   );
});