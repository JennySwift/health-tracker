angular.module('tracker')
    .controller('ExerciseEntriesController', function ($rootScope, $scope, ExerciseEntriesFactory, AutocompleteFactory) {

        $scope.exerciseEntries = exerciseEntries;
        $scope.exerciseUnits = exerciseUnits;

        $scope.selectedExercise = {};

        $rootScope.$on('getEntries', function () {
            $rootScope.showLoading();
            ExerciseEntriesFactory.getEntriesForTheDay($scope.date.sql)
                .then(function (response) {
                    $scope.exerciseEntries = response.data;
                    //$rootScope.$broadcast('provideFeedback', '');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        });

        /**
         * Get all the the user's entries for a particular exercise
         * with a particular unit on a particular date.
         * @param $entry
         */
        $scope.getSpecificExerciseEntries = function ($entry) {
            $rootScope.showLoading();
            ExerciseEntriesFactory.getSpecificExerciseEntries($scope.date.sql, $entry)
                .then(function (response) {
                    $scope.show.popups.exercise_entries = true;
                    $scope.exercise_entries_popup = response.data;
                    //$rootScope.$broadcast('provideFeedback', '');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.insertExerciseEntry = function () {
            $scope.new_entry.exercise.unit_id = $("#exercise-unit").val();
            $rootScope.showLoading();
            ExerciseEntriesFactory.insert($scope.date.sql, $scope.newEntry)
                .then(function (response) {
                    $scope.exerciseEntries = response.data;
                    //$rootScope.$broadcast('provideFeedback', '');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.insertExerciseSet = function ($exercise) {
            $rootScope.showLoading();
            ExerciseEntriesFactory.insertExerciseSet($scope.date.sql, $exercise)
                .then(function (response) {
                    $scope.exerciseEntries = response.data;
                    //$rootScope.$broadcast('provideFeedback', '');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        //Todo: get the entries for the day after deleting the entry
        $scope.deleteExerciseEntry = function ($entry) {
            ExerciseEntriesFactory.deleteExerciseEntry($entry)
                .then(function (response) {
                    //$scope.entries.exercise = response.data.entries_for_day;
                    $scope.exercise_entries_popup.entries = _.without($scope.exercise_entries_popup.entries, $entry);
                    $rootScope.$broadcast('provideFeedback', 'Entry deleted');
                });
        };

        /**
         * Autocomplete
         */

        $scope.autocompleteExercise = function ($keycode) {
            if ($keycode !== 13 && $keycode !== 38 && $keycode !== 40) {
                //not enter, up arrow or down arrow
                AutocompleteFactory.exercise().then(function (response) {
                    if (response.data.length > 0) {
                        //Results were found
                        //fill the dropdown
                        $scope.autocomplete_options.exercises = response.data;
                        //select the first item
                        $scope.autocomplete_options.exercises[0].selected = true;
                    }
                    else {
                        //No results found
                        $scope.autocomplete_options.exercises = [];
                    }
                    //show the dropdown
                    $scope.showAutocompleteOptions.exercises = true;
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
            $scope.selectedExercise = $selected;
            $scope.selectedExercise.unit_id = $scope.selectedExercise.default_unit_id;
            $scope.newEntry = $selected;
            $scope.newEntry.quantity = $scope.selectedExercise.default_quantity;
            $scope.selectedExercise = $selected;
            $scope.showAutocompleteOptions.exercises = false;
            setTimeout(function () {
                $("#exercise-quantity").focus().select();
            }, 500);
        };

        $scope.insertOrAutocompleteExerciseEntry = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }
            //enter is pressed
            if ($scope.showAutocompleteOptions.exercises) {
                //if enter is for the autocomplete
                $scope.finishExerciseAutocomplete($scope.autocomplete_options.exercises);
            }
            else {
                // if enter is to add the entry
                $scope.insertExerciseEntry();
            }
        };

    });