angular.module('createRecord',['restangular'])
   .directive('createRecord',function($timeout,Restangular,$location) {

      return {
         restrict: 'E',
         templateUrl: 'application/partials/createRecord.html',
         scope: {
         },
         transclude: false,
         replace: true,
         link: function($scope, element, attr) {
            open = false;
            $scope.open = function(state) {
               if (state != undefined) {
                  open = state;
                  if (state) {
                     setTimeout(function() { 
                        $('.modal input:text').focus();
                     }, 200);
                  };
                  $scope.recordTitle = '';
               };
               return open;
            };
            //
            $scope.create = function(title) {
               Restangular.all('records').post({
                  title: title
               }).then(function(record) {
                  $location.path('/records/'+record.id);
                  $scope.open(false);
               });
            }
         }
      }

   });