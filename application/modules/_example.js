'use strict';

define([], function() {

   var services = angular.module('_exampleServices', []);

   services.provider('exampleService', function() {

      this.$get = function() {
         return this;
      };

   });

});