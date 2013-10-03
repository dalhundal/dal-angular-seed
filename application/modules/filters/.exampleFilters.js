angular.module('xyzFilters',[])

   .filter('someFilterName', function() {
      return function(input) {
         return "SOMETHING";
      };
   })