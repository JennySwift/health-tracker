var ExerciseEntries = Vue.component('exercise-entries', {
    template: '#exercise-entries-template',
    data: function () {
        return {
            exerciseEntries: exerciseEntries,
            showExerciseEntryInputs: false,
            selectedExercise: {
                unit: {}
            },
            showSpecificExerciseEntriesPopup: false,
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
        getEntriesForSpecificExerciseAndDateAndUnit: function (entry) {
            $.event.trigger('show-loading');

            var data = {
                date: this.date.sql,
                exercise_id: entry.exercise.data.id,
                exercise_unit_id: entry.unit.id
            };

            this.$http.get('api/exerciseEntries/specificExerciseAndDateAndUnit', data, function (response) {
                this.showSpecificExerciseEntriesPopup = true;
                this.selectedExercise = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         * Similar method to this in SeriesExercisesComponent
         */
        insertExerciseSet: function (exercise) {
            $.event.trigger('show-loading');
            var data = {
                date: this.date.sql,
                exercise_id: exercise.data.id,
                exerciseSet: true
            };

            this.$http.post('/api/exerciseEntries', data, function (response) {
                $.event.trigger('provide-feedback', ['Set added', 'success']);
                this.getEntriesForTheDay();
                //$.event.trigger('get-exercise-entries', [response.data]);
                this.newSeries.name = '';
                this.exerciseEntries = response.data;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        deleteExerciseEntry: function (entry) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/exerciseEntries/' + entry.id, function (response) {
                    this.selectedExercise.entries = _.without(this.selectedExercise.entries, entry);
                    $.event.trigger('provide-feedback', ['Entry deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            /**
             * For updating the exercise entries from the
             * series controller on the series page
             */
            $(document).on('get-exercise-entries-for-the-day', function (event) {
                that.getEntriesForTheDay();
            });
            $(document).on('date-changed', function (event) {
                that.getEntriesForTheDay();
            });
            $(document).on('exercise-entry-added', function (event, data) {
                //Todo: all the entries I think are actually in the data (unnecessarily)
                that.getEntriesForTheDay();
            });
            /**
             * For updating the exercise entries from the
             * series controller on the series page
             */
            //$(document).on('getExerciseEntries', function (event, data) {
            //    that.exerciseEntries = data;
            //});
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