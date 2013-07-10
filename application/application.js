'use strict';

define(['services/routeResolver'], function() {
   var app = angular.module('MyApplication',[
      'routeResolverServices',
      'restangular',
   ]);
   app.config(function ($routeProvider, routeResolverProvider, $controllerProvider, $compileProvider, $filterProvider, $provide, RestangularProvider) {

         app.register = {
            controller: $controllerProvider.register,
            directive: $compileProvider.directive,
            filter: $filterProvider.register,
            factory: $provide.factory,
            service: $provide.service
         };

         var route = routeResolverProvider.route;
         RestangularProvider.setBaseUrl('rest');

         // ROUTING

         $routeProvider
            .when('/', { redirectTo:'/hello/world' })

            .when('/hello/all', route.resolve('hello/list'))
            .when('/hello/:name', route.resolve('hello/world'))
            
            .otherwise({ redirectTo:'/' });

         // END / ROUTING

      });
      return app;
});