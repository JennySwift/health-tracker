angular.module('tracker')
    .controller('ExerciseEntriesController', function ($scope, ExerciseEntriesFactory) {

        $scope.exerciseEntries = exerciseEntries;

        /**
         * Get all the the user's entries for a particular exercise
         * with a particular unit on a particular date.
         * @param $exercise_id
         * @param $exercise_unit_id
         */
        $scope.getSpecificExerciseEntries = function ($exercise_id, $exercise_unit_id) {
            ExerciseEntriesFactory.getSpecificExerciseEntries($scope.date.sql, $exercise_id, $exercise_unit_id).then(function (response) {
                $scope.show.popups.exercise_entries = true;
                $scope.exercise_entries_popup = response.data;
            });
        };

        $scope.insertExerciseEntry = function () {
            $scope.new_entry.exercise.unit_id = $("#exercise-unit").val();
            ExerciseEntriesFactory.insertExerciseEntry($scope.date.sql, $scope.new_entry.exercise).then(function (response) {
                $scope.entries.exercise = response.data;
            });
        };

        $scope.insertExerciseSet = function ($exercise_id) {
            ExerciseEntriesFactory.insertExerciseSet($scope.date.sql, $exercise_id).then(function (response) {
                $scope.entries.exercise = response.data;
            });
        };

        //Todo: get the entries for the day and for the popup after deleting
        //the entry
        $scope.deleteExerciseEntry = function ($id) {
            ExerciseEntriesFactory.deleteExerciseEntry($id)
                .then(function (response) {
                    //$scope.entries.exercise = response.data.entries_for_day;
                    //$scope.exercise_entries_popup = response.data.entries_for_popup;
                });
        };

        /**
         * Autocomplete
         */

        $scope.autocompleteExercise = function ($keycode) {
            if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
                //not enter, up arrow or down arrow
                AutocompleteFactory.exercise().then(function (response) {
                    //fill the dropdown
                    $scope.autocomplete_options.exercises = response.data;
                    //show the dropdown
                    $scope.show.autocomplete_options.exercises = true;
                    //select the first item
                    $scope.autocomplete_options.exercises[0].selected = true;
                });
            }
            else if ($keycode === 38) {
                //up arrow pressed
                AutocompleteFactory.autocompleteUpArrow($scope.autocomplete_options.exercises);

            }
            else if ($keycode === 40) {
                //down arrow pressed
                AutocompleteFactory.autocompleteDownArrow($scope.autocomplete_options.exercises);
            }
        };

        $scope.finishExerciseAutocomplete = function ($array, $selected) {
            //array, input_to_focus, autocomplete_to_hide, input_to_fill, selected_property_to_define
            $selected = $selected || _.findWhere($array, {selected: true});
            $scope.selected.exercise = $selected;
            $scope.selected.exercise_unit.id = $scope.selected.exercise.default_exercise_unit_id;
            $scope.new_entry.exercise = $selected;
            $scope.new_entry.exercise.quantity = $scope.selected.exercise.default_quantity;
            $scope.selected.exercise = $selected;
            $scope.show.autocomplete_options.exercises = false;
            setTimeout(function () {
                $("#exercise-quantity").focus().select();
            }, 500);
        };

        $scope.insertOrAutocompleteExerciseEntry = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }
            //enter is pressed
            if ($scope.show.autocomplete_options.exercises) {
                //if enter is for the autocomplete
                $scope.finishExerciseAutocomplete($scope.autocomplete_options.exercises);
            }
            else {
                // if enter is to add the entry
                $scope.insertExerciseEntry();
                console.log('something');
            }
        };

    });