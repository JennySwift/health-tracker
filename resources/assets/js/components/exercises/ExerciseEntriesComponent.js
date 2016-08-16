module.exports = {
    template: '#exercise-entries-template',
    data: function () {
        return {
            exerciseEntries: [],
            showExerciseEntryInputs: false,
            selectedExercise: {
                unit: {}
            },
        };
    },
    components: {},
    methods: {

        /**
         *
         * @param entry
         */
        showEntriesForSpecificExerciseAndDateAndUnitPopup: function (entry) {
            $.event.trigger('show-entries-for-specific-exercise-and-date-and-unit-popup', [entry]);
        },

        /**
         *
         */
        getEntriesForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseEntries/' + this.date.sql).then(function (response) {
                this.exerciseEntries = response.data;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
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

            this.$http.post('/api/exerciseEntries', data).then(function (response) {
                $.event.trigger('provide-feedback', ['Set added', 'success']);
                this.getEntriesForTheDay();
                this.exerciseEntries = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
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
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'date'
    ],
    ready: function () {
        this.listen();
        this.getEntriesForTheDay();
    }
};