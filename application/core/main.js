'use strict';

require.config({
   baseUrl: 'application',
   urlArgs: 'v=1.0',
   paths: {
      'json': '../lib/bower/requirejs-plugins/src/json',
      'text': '../lib/bower/requirejs-plugins/lib/text'
   }
});

require(
   [
      'core/application',
      'core/services/routeResolver',
      'json!config.json'
   ],
   function(application,services_routeResolver,config) {
      require(_.values(config.loadModules),function() {
         angular.bootstrap(document, ['MyApplication'])
      });
      return false;
   }
)