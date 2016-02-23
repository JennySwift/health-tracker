var SeriesExercises = Vue.component('series-exercises', {
    template: '#series-exercises-template',
    data: function () {
        return {
            selectedExercise: ExercisesRepository.selectedExercise,
        };
    },
    components: {},
    filters: {
        filterExercises: function (exercises) {
            var that = this;

            //Sort
            exercises = _.chain(exercises).sortBy('priority').sortBy('stepNumber').value();

            //Filter
            return exercises.filter(function (exercise) {
                var filteredIn = true;

                //Priority filter
                if (that.priorityFilter && exercise.priority != that.priorityFilter) {
                    filteredIn = false;
                }

                return filteredIn;
            });
        }
    },
    methods: {

        /**
         *
         */
        showExercisePopup: function (exercise) {
            $.event.trigger('show-loading');
            this.$http.get('/api/exercises/' + exercise.id, function (response) {
                    this.selectedExercise = response;
                    $.event.trigger('show-exercise-popup');
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         */
        insertExerciseSet: function (exercise) {
            $.event.trigger('show-loading');
            var data = {
                date: moment().format('YYYY-MM-DD'),
                exercise_id: exercise.id,
                exerciseSet: true
            };

            this.$http.post('/api/exerciseEntries', data, function (response) {
                    $.event.trigger('provide-feedback', ['Set added', 'success']);
                    $.event.trigger('get-exercise-entries-for-the-day');
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
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
        'selectedSeries',
        'priorityFilter',
        'programs',
        'units',
        'exerciseSeries'
    ],
    ready: function () {

    }
});
