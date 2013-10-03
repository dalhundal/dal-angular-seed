'use strict';

define(['core/application'], function(app) {

   app.register.controller('_exampleController', function ($scope, Restangular) {
      $scope.example = new Date();
      Restangular.one('shit',1).get();
   });

});