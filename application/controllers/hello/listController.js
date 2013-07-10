'use strict';

define(['application'], function(app) {

   app.register.controller('hello/listController', function ($scope, Restangular) {
      $scope.list = Restangular.all('people').getList();
   });

});