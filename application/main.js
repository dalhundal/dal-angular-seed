'use strict';

require.config({
   baseUrl: 'application',
   urlArgs: 'v=1.0'
});

require(
   [
      'application',
      'services/routeResolver',
      'filters/applicationFilters'
   ],
   function() {
      angular.bootstrap(document, ['MyApplication'])
   }
)