<?php

require('lib/SlimRest/SlimRest.php');

R::setup('pgsql:host=localhost;dbname=dname','dbuser');
R::freeze(true);

$app = new \SlimRest();

\Slim\Route::setDefaultConditions(array(
   'id' => '\d+'
));

/* ===== */

$app->get('/people', function() use ($app) {
   return array(
      array('name'=>'John'),
      array('name'=>'Henry'),
      array('name'=>'David'),
      array('name'=>'Steven'),
   );
});

$app->post('/records', function() use ($app) {
   $record = $app->populateWithInput('record',array('title','body'));
   R::store($record);
   return $record->export();
});

$app->get('/records/:id', function($id) use ($app) {
   $record = R::load('record_vw',$id);
   if (!$record) throw new ResourceNotFoundException();
   return $record->export();
});

$app->put('/records/:id', function($id) use ($app) {
   $record = R::findOne('record','id=?',array($id));
   if (!$record) throw new ResourceNotFoundException();
   $app->populateWithInput($record,array('title','body','level'));
   R::store($record);
   return $record->export();
});

$app->delete('/records/:id', function($id) use ($app) {
   $record = R::findOne('record','id=?',array($id));
   if (!$record) throw new ResourceNotFoundException();
   R::trash($record);
   return true;
});

/* ===== */

$app->get('/records/:id/dependants',function($id) use ($app) {
   $record = R::load('record',$id);
   if (!$record) throw new ResourceNotFoundException();
   $dependants = R::find('dependency','record_a_id = ?',array($id));
   $results = array();
   foreach ($dependants AS $dependant) {
      $results[] = $dependant->fetchAs('record_vw')->record_b;
   }
   return R::exportAll($results);
});

$app->get('/records/:id/dependencies',function($id) use ($app) {
   $record = R::load('record',$id);
   if (!$record) throw new ResourceNotFoundException();
   $dependencies = R::find('dependency','record_b_id = ?',array($id));
   $results = array();
   foreach ($dependencies AS $dependency) {
      $results[] = $dependency->fetchAs('record_vw')->record_a;
   }
   return R::exportAll($results);
});

$app->post('/records/:id/dependants',function($id) use ($app) {
   $input = $app->getInput();
   //
   $record_a = R::load('record',$id);
   $record_b = R::load('record',$input->id);
   if (!$record_a) throw new Exception("No record found for id {$input->id}");
   if (!$record_b) throw new ResourceNotFoundException();
   $dependency = R::dispense('dependency');
   $dependency->record_a = $record_a;
   $dependency->record_b = $record_b;
   R::store($dependency);
   return true;
});

$app->post('/records/:id/dependencies',function($id) use ($app) {
   $input = $app->getInput();
   //
   $record_a = R::load('record',$input->id);
   $record_b = R::load('record',$id);
   if (!$record_a) throw new ResourceNotFoundException();
   if (!$record_b) throw new Exception("No record found for id {$input->id}");
   $dependency = R::dispense('dependency');
   $dependency->record_a = $record_a;
   $dependency->record_b = $record_b;
   R::store($dependency);
   return true;
});

$app->delete('/records/:idA/dependants/:idB',function($idA,$idB) use ($app) {
   $dependency = R::findOne('dependency','record_a_id=? AND record_b_id=?',array($idA,$idB));
   if (!$dependency) throw new ResourceNotFoundException();
   R::trash($dependency);
   return true;
});

$app->delete('/records/:idB/dependencies/:idA',function($idB,$idA) use ($app) {
   $dependency = R::findOne('dependency','record_a_id=? AND record_b_id=?',array($idA,$idB));
   if (!$dependency) throw new ResourceNotFoundException();
   R::trash($dependency);
   return true;
});

/* ===== */

$app->get('/records/autocomplete',function() use ($app) {
   $list = R::getAll("SELECT id, title, level, inherited_level FROM record_vw");
   return $list;
});

/* ===== */

$app->get('/records/:id/events', function($id) use ($app) {
   $record = R::load('record',$id);
   if (!$record) throw new ResourceNotFoundException();
   $events = $record->ownEvent;
   return R::exportAll($events);
});

$app->post('/records/:id/events', function($id) use ($app) {
   $record = R::load('record',$id);
   if (!$record) throw new ResourceNotFoundException();
   $event = $app->populateWithInput('event',array('title','body','start','end'));
   $record->ownEvent[] = $event;
   R::store($record);
   return true;
});

/* ===== */

$app->get('/records/:id/metadata',function($id) use ($app) {
   $record = R::load('record',$id);
   if (!$record) throw new ResourceNotFoundException();
   $metadata = $record->ownMetadata;
   return R::exportAll($metadata);
});

$app->get('/records/:id/metadata/:type', function($id,$type) use ($app) {
   $record = R::load('record',$id);
   if (!$record) throw new ResourceNotFoundException();
   $metadata = $record->withCondition('type = ?',array($type))->ownMetadata;
   return R::exportAll($metadata);
});

$app->post('/records/:id/metadata/:type', function($id, $type) use ($app) {
   $record = R::load('record',$id);
   if (!$record) throw new ResourceNotFoundException();
   $metadata = $app->populateWithInput('metadata',array('value'));
   $metadata->type = $type;
   $record->ownMetadata[] = $metadata;
   R::store($record);
   return true;
});

$app->run();