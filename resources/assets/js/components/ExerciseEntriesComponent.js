var ExerciseEntries = Vue.component('exercise-entries', {
    template: '#exercise-entries-template',
    data: function () {
        return {
            exerciseEntries: exerciseEntries,
            exerciseUnits: exerciseUnits,
            showExerciseEntryInputs: false,
            selectedExercise: {
                unit: {}
            },
            showSpecificExerciseEntriesPopup: false
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        closeSpecificExerciseEntriesPopup: function () {
            this.showSpecificExerciseEntriesPopup = false;
        },

        /**
        *
        */
        getEntriesForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseEntries/' + this.date.sql, function (response) {
                this.exerciseEntries = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         * Get all the the user's entries for a particular exercise
         * with a particular unit on a particular date.
         * @param entry
         */
        getSpecificExerciseEntries: function (entry) {
            $.event.trigger('show-loading');

            var data = {
                date: this.date.sql,
                exercise_id: entry.exercise.id,
                exercise_unit_id: entry.unit.id
            };

            this.$http.get('api/select/specificExerciseEntries', function (response) {
                $scope.show.popups.exercise_entries = true;
                this.selectedExercise = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        insertExerciseEntry: function () {
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
        },

        insertExerciseSet: function ($exercise) {
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
        },

        //Todo: get the entries for the day after deleting the entry
        deleteExerciseEntry: function ($entry) {
            ExerciseEntriesFactory.deleteExerciseEntry($entry)
                .then(function (response) {
                    //$scope.entries.exercise = response.data.entries_for_day;
                    $scope.exercise_entries_popup.entries = _.without($scope.exercise_entries_popup.entries, $entry);
                    $rootScope.$broadcast('provideFeedback', 'Entry deleted');
                });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            //$(document).on('get-exercise-entries', function (event) {
            //    that.getEntriesForTheDay();
            //});
            $(document).on('date-changed', function (event) {
                that.getEntriesForTheDay();
            });
            /**
             * For updating the exercise entries from the
             * series controller on the series page
             */
            $(document).on('getExerciseEntries', function (event, data) {
                that.exerciseEntries = data;
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            this.$broadcast('response-error', response);
            this.showLoading = false;
        }
    },
    props: [
        'date'
    ],
    ready: function () {
        this.listen();
    }
});