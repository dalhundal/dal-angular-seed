'use strict';

define(['json!config.json'], function(config) {
   var app = angular.module('MyApplication',[
      'core/routeResolverServices'
   ].concat(_.keys(config.loadModules)));
   app.config(function ($routeProvider, routeResolverProvider, $controllerProvider, $compileProvider, $filterProvider, $provide) {

      app.register = {
         controller: $controllerProvider.register,
         directive: $compileProvider.directive,
         filter: $filterProvider.register,
         factory: $provide.factory,
         service: $provide.service
      };

      for (var i in config.routes) {
         var action;
         var actionKey = _.keys(config.routes[i])[0];
         var actionValue = config.routes[i][actionKey];
         switch (actionKey) {
            case 'resolve':
               action = routeResolverProvider.route.resolve(actionValue);
               break;
            case 'redirect':
               action = {redirectTo:actionValue};
               break;
         };
         $routeProvider.when(i,action);
         if (config.routes[i].default === true) $routeProvider.otherwise(action);
      };

   });
   if (config.less && config.less.watch===true) less.watch();
   return app;
});