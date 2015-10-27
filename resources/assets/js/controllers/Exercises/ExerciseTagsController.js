var app = angular.module('tracker');

(function () {
    app.controller('ExerciseTagsController', function ($rootScope, $scope, ExerciseTagsFactory) {

        $scope.tags = exercise_tags;

        $scope.insertExerciseTag = function ($keypress) {
            if ($keypress !== 13) {
                return;
            }
            $rootScope.showLoading();
            ExerciseTagsFactory.insert()
                .then(function (response) {
                    $scope.tags.push(response.data.data);
                    $rootScope.$broadcast('provideFeedback', 'Tag created');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.deleteExerciseTag = function ($tag) {
            $rootScope.showLoading();
            ExerciseTagsFactory.destroy($tag)
                .then(function (response) {
                    $scope.tags = _.without($scope.tags, $tag);
                    $rootScope.$broadcast('provideFeedback', 'Tag deleted');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        /**
         * popups
         */

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
        };

    });

})();