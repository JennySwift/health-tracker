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
            showSpecificExerciseEntriesPopup: false,
            //newEntry: {}
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
                this.showSpecificExerciseEntriesPopup = true;
                this.selectedExercise = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        insertEntry: function () {
            $.event.trigger('show-loading');

            //this.newEntry.exercise.unit_id = $("#exercise-unit").val();
            //$("#exercise").val("").focus();

            var data = {
                date: this.date.sql,
                exercise_id: this.newEntry.id,
                quantity: this.newEntry.quantity,
                unit_id: this.newEntry.unit_id
            };

            this.$http.post('/api/exerciseEntries', data, function (response) {
                this.exerciseEntries = response;
                $.event.trigger('provide-feedback', ['Entry created', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         * Similar method to this in SeriesPageComponent
         */
        insertExerciseSet: function (exercise) {
            $.event.trigger('show-loading');
            var data = {
                date: this.date.sql,
                exercise_id: exercise.id,
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