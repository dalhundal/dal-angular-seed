angular.module('selectObject',[])
   .directive('selectObject',function($timeout) {
      return {
         restrict: 'E',
         templateUrl: 'application/directives/selectObject.html',
         scope: { list:'=', excludeList:'=', field:'=', format:'=', placeholder:'='},
         transclude: false,
         link: function($scope, element, attr, ctrl) {
            var selectElement = $(element.children()[0]);
            var select2 = selectElement.select2({
               placeholder: $scope.placeholder
            });
            $timeout(function() {
               element.bind('change',function() {
                  $scope.$apply(function() {
                     $scope.field = _.findWhere($scope.list,{id:select2.val()});
                  });
               });
            });
            //
            var lastVal = null;
            $scope.$watch(function(a) {
               if (lastVal && a.field == null) {
                  select2.select2('val','');
               };
               lastVal = a.field;
            });
         }
      };
   })
   .filter('selectObjectExcludeFilter',function() {
      return function(input,other) {
         var diff = [];
         for (var i=0; i<input.length; i++) {
            var isFound = false;
            for (var j=0; j<other.length; j++) {
               if (input[i].id == other[j].id) {
                  isFound = true;
                  break;
               };
            };
            if (!isFound) diff.push(input[i]);
         };
         return diff;
      };
   });