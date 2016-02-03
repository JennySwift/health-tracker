var ExercisesPage = Vue.component('exercises-page', {
    template: '#exercises-page-template',
    data: function () {
        return {
            selectedExercise: ExercisesRepository.selectedExercise,
            exercises: all_exercises,
            workouts: workouts,
            showNewExerciseFields: false,
            series: [],
            exerciseSeries: series,
            programs: [],
            units: units
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        getSeries: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseSeries', function (response) {
                this.series = _.pluck(response.data, 'name');
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        getPrograms: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exercisePrograms', function (response) {
                this.programs = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

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
                    this.handleResponseError(response);
                });
        },

        closePopup: function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
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
        //data to be received from parent
    ],
    ready: function () {
        this.getSeries();
        this.getPrograms();
    }
});