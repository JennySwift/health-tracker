var app = angular.module('tracker');

(function () {
    app.controller('ExerciseTagsController', function ($scope, ExerciseTagsFactory) {

        $scope.exercise_tags = exercise_tags;

        $scope.insertExerciseTag = function ($keypress) {
            if ($keypress !== 13) {
                return;
            }
            ExerciseTagsFactory.insertExerciseTag().then(function (response) {
                $scope.exercise_tags = response.data;
            });
        };

        $scope.deleteExerciseTag = function ($id) {
            ExerciseTagsFactory.deleteExerciseTag($id).then(function (response) {
                $scope.exercise_tags = response.data;
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