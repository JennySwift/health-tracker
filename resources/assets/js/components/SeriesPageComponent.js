var SeriesPage = Vue.component('series-page', {
    template: '#series-page-template',
    data: function () {
        return {
            exerciseSeries: series,
            exerciseSeriesHistory: [],
            workouts: workouts,
            seriesPriorityFilter: 1,
            showNewSeriesFields: false,
            showExerciseSeriesHistoryPopup: false,
            showExercisePopup: false,
            newSeries: {},
            newExercise: {},
            selectedSeries: {
                exercises: {
                    data: []
                }

            },
            selectedExercise: {}
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        getExerciseSeriesHistory: function (series) {
            $.event.trigger('show-loading');

            this.$http.get('api/seriesEntries/' + series.id, function (response) {
                this.showExerciseSeriesHistoryPopup = true;
                //For displaying the name of the series in the popup
                this.selectedSeries = $series;
                this.exerciseSeriesHistory = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        insertSeries: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newSeries.name
            };

            this.$http.post('/api/exerciseSeries', data, function (response) {
                this.exerciseSeries.push(response.data);
                $.event.trigger('provide-feedback', ['Series created', 'success']);
                this.showLoading = false;
                this.newSeries.name = '';
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
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
                $.event.trigger('provide-feedback', ['Series created', 'success']);
                $.event.trigger('get-exercise-entries', [response.data]);
                this.showLoading = false;
                this.newSeries.name = '';
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        updateSeries: function (series) {
            $.event.trigger('show-loading');

            var data = {
                name: $series.name,
                priority: $series.priority,
                workout_ids: $series.workout_ids
            };

            this.$http.put('/api/exerciseSeries/' + series.id, data, function (response) {
                var index = _.indexOf(this.exercise_series, _.findWhere(this.exerciseSeries, {id: this.selectedSeries.id}));
                this.exerciseSeries[index] = response.data;
                this.showExerciseSeriesPopup = false;
                $.event.trigger('provide-feedback', ['Series updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        deleteSeries: function (series) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/exerciseSeries/' + series.id, function (response) {
                    this.exerciseSeries = _.without(this.exerciseSeries, series);
                    $.event.trigger('provide-feedback', ['Series deleted', 'success']);
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
        getExercisesInSeries: function (series) {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseSeries/' + series.id, function (response) {
            this.selectedSeries = response;
            $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        insertExercise: function () {
            $.event.trigger('show-loading');
            var data = ExercisesRepository.setData(this.newExercise);

            this.$http.post('/api/exercises', data, function (response) {
                this.exercises.push(response);
                $.event.trigger('provide-feedback', ['Exercise created', 'success']);
                this.showLoading = false;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        showExerciseSeriesPopup: function (series) {
            this.selectedSeries = series;

            $.event.trigger('show-loading');
            ExerciseSeriesFactory.getExerciseSeriesInfo(series)
                .then(function (response) {
                    this.selectedSeries = response;
                    this.showExerciseSeriesPopup = true;
                    $.event.trigger('hide-loading');
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        },

        closePopup: function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                this.show.popups[$popup] = false;
            }
        },

        /**
         * Duplicate from exercises controller
         * @param $exercise
         */
        showExercisePopup: function ($exercise) {
            this.selected.exercise = $exercise;

            $rootScope.showLoading();
            ExercisesFactory.show($exercise)
                .then(function (response) {
                    this.exercise_popup = response.data;
                    this.show.popups.exercise = true;
                    //$rootScope.$broadcast('provideFeedback', '');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        },

        /**
        *
        */
        updateExercise: function (exercise) {
            $.event.trigger('show-loading');

            var data = ExercisesRepository.setData(exercise);

            this.$http.put('/api/exercises/' + exercise.id, data, function (response) {
                this.selectedExercise = response.data;
                var index = _.indexOf(this.selectedSeries.exercises.data, _.findWhere(this.selectedSeries.exercises.data, {id: this.selectedExercise.id}));
                this.selectedSeries.exercises.data[index] = response.data;
                this.showExercisePopup = false;
                $.event.trigger('provide-feedback', ['Exercise updated', 'success']);
                $.event.trigger('hide-loading');
                $("#exercise-step-number").val("");
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
        getUnits: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseUnits', function (response) {
                this.units = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
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
        //data to be received from parent
    ],
    ready: function () {
        this.getPrograms();
        this.getUnits();
    }
});

