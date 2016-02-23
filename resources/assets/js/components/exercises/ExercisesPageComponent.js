var ExercisesPage = Vue.component('exercises-page', {
    template: '#exercises-page-template',
    data: function () {
        return {
            selectedExercise: ExercisesRepository.selectedExercise,
            exercises: [],
            showNewExerciseFields: false,
            series: [],
            exerciseSeries: [],
            programs: [],
            units: [],
            filterByName: '',
            filterByDescription: '',
            filterByPriority: '',
            filterBySeries: ''
        };
    },
    components: {},
    filters: {
        exercisesFilter: function (exercises) {
            var that = this;
            return exercises.filter(function (exercise) {
                //Name filter
                var show = exercise.name.indexOf(that.filterByName) !== -1;

                //Description filter
                if (exercise.description && exercise.description.indexOf(that.filterByDescription) === -1) {
                    show = false;
                }

                else if (!exercise.description && that.filterByDescription !== '') {
                    show = false;
                }

                //Priority filter
                if (that.filterByPriority && exercise.priority != that.filterByPriority) {
                    show = false;
                }

                //Series filter
                if (that.filterBySeries && exercise.series.name != that.filterBySeries) {
                    show = false;
                }

                return show;
            });
        }
    },
    methods: {

        /**
        *
        */
        getSeries: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseSeries', function (response) {
                this.series = _.pluck(response, 'name');
                this.exerciseSeries = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        getExercises: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exercises', function (response) {
                this.exercises = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        getUnits: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseUnits', function (response) {
                this.units = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
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
                HelpersRepository.handleResponseError(response);
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
                    HelpersRepository.handleResponseError(response);
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
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getSeries();
        this.getPrograms();
        this.getUnits();
        this.getExercises();
    }
});