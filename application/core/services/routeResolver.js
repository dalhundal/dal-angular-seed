'use strict';

define([], function() {

   var services = angular.module('core/routeResolverServices', []);

   services.provider('routeResolver', function() {

      this.$get = function() {
         return this;
      };

      this.routeConfig = function() {
         var _viewsDirectory = 'application/views/';
         var _controllersDirectory = 'application/controllers/';

         var viewsDirectory = function(directory) {
            if (directory != undefined) _viewsDirectory = directory;
            return _viewsDirectory;
         };

         var controllersDirectory = function(directory) {
            if (directory != undefined) _controllersDirectory = directory;
            return _controllersDirectory;
         };

         return {
            viewsDirectory: viewsDirectory,
            controllersDirectory: controllersDirectory
         };
      }();

      this.route = function(routeConfig) {
         var resolve = function(baseName) {
            var routeDef = {};
            routeDef.templateUrl = routeConfig.viewsDirectory() + baseName + '.html';
            routeDef.controller = baseName + 'Controller';
            routeDef.resolve = {
               load: ['$q', '$rootScope', function($q, $rootScope) {
                  var dependencies = [routeConfig.controllersDirectory() + baseName + '.js'];
                  return resolveDependencies($q, $rootScope, dependencies);
               }]
            };
            return routeDef;
         };

         var resolveDependencies = function($q, $rootScope, dependencies) {
            var defer = $q.defer();
            require(dependencies, function() {
               defer.resolve();
               $rootScope.$apply();
            });
            return defer.promise;
         };

         return {
            resolve: resolve
         };
      }(this.routeConfig);


   });

});