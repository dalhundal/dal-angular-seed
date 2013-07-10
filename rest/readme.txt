SlimRest
========

CREATE NEW OBJECT

   $app->post('/people', function() use ($app) {
      $person = $app->populateWithInput('people',array('name','age'));   // FIELDS TO READ FROM INPUT AND INSERT INTO RECORD
      R::store($person);
      return $person->export();
   });


LIST OBJECTS

   $app->get('/people', function() use ($app) {
      $people = R::find('people');
      return R::exportAll($people);
   });


RETREIVE AN OBJECT

   $app->get('/people/:id', function($id) use ($app) {
      $person = R::load('people',$id);
      if (!$person) throw new ResourceNotFoundException();
      return $person->export();
   });


UPDATE AN OBJECT

   $app->put('/people/:id', function($id) use ($app) {
      $person = R::load('people',$id)
      if (!$person) throw new ResourceNotFoundException();
      $app->populateWithInput($person,array('name','age'));   // FIELDS TO READ FROM INPUT AND INSERT INTO RECORD
      R::store($person);
      return $person->export();
   });


DELETE AN OBJECT

   $app->delete('/people/:id', function($id) use ($app) {
      $person = R::load('people',$id);
      if (!$person) throw new ResourceNotFoundException();
      R::trash($person);
      return true;
   });