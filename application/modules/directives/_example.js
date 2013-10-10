angular.module('_exampleDirectives',[])
   .directive('example',function($timeout) {
      return {
         restrict: 'E',
         templateUrl: 'application/modules/directives/_example.html',
         scope: {

         },
         transclude: false,
         replace: true,
         link: function($scope, element, attr, ctrl) {
            
         }
      };
   });