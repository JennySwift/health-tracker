var app = angular.module('tracker');

(function () {
    app.controller('workouts', function ($rootScope, $scope, $http, ExercisesFactory, WorkoutsFactory) {

        $scope.workouts = workouts;

        $scope.insertWorkout = function ($keypress) {
            if ($keypress !== 13) {
                return;
            }
            $rootScope.showLoading();
            WorkoutsFactory.insertWorkout()
                .then(function (response) {
                    $scope.workouts.push(response.data.data);
                    $rootScope.$broadcast('provideFeedback', 'Workout created');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.insertSeriesIntoWorkout = function () {
            WorkoutsFactory.insertSeriesIntoWorkout($workout_id, $series_id).then(function (response) {
                $scope.exercise_series = response.data;
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