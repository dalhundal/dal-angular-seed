angular.module('applicationFilters',[])

   .filter('time', function() {
      return function(input) {
         return "TIME";
      };
   })