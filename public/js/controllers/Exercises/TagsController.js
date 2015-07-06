var app = angular.module('tracker');

(function () {
    app.controller('exerciseTags', function ($scope, $http, ExercisesFactory, TagsFactory) {

        /**
         * scope properties
         */

        $scope.exercise_tags = exercise_tags;

        /**
         * select
         */

        /**
         * insert
         */

        $scope.insertExerciseTag = function ($keypress) {
            if ($keypress !== 13) {
                return;
            }
            TagsFactory.insertExerciseTag().then(function (response) {
                $scope.exercise_tags = response.data;
            });
        };

        /**
         * update
         */

        /**
         * delete
         */

        $scope.deleteExerciseTag = function ($id) {
            TagsFactory.deleteExerciseTag($id).then(function (response) {
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

    }); //end controller

})();