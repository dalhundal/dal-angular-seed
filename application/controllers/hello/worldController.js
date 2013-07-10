'use strict';

define(['application'], function(app) {

   app.register.controller('hello/worldController', function ($scope, $routeParams, Restangular) {
      $scope.name = $routeParams.name;
   });

});